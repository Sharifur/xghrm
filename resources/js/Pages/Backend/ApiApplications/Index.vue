<template>
    <Head title="AI Applications"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">AI Applications</h2>
                    <div class="btn-wrp">
                        <BsModalButton target="createAppModal" button-class="btn btn-info m-1">Create Application</BsModalButton>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Key Prefix</th>
                                <th>Status</th>
                                <th>Last Used</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="app in appList()" :key="app.id">
                                <td>{{ app.name }}</td>
                                <td>{{ app.description || '—' }}</td>
                                <td><code>{{ app.key_prefix }}</code></td>
                                <td>
                                    <span class="alert" :class="app.active ? 'alert-success' : 'alert-danger'">
                                        {{ app.active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ app.last_used_at ? new Date(app.last_used_at).toLocaleString() : 'Never' }}</td>
                                <td>{{ new Date(app.created_at).toLocaleDateString() }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm m-1" @click="toggleActive(app)" :title="app.active ? 'Deactivate' : 'Activate'">
                                        <i :class="app.active ? 'fas fa-ban' : 'fas fa-check'"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm m-1" @click="regenerate(app)" title="Regenerate secret">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm m-1" @click="deleteApp(app)" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="appList().length === 0">
                                <td colspan="7" class="text-center text-muted py-4">No AI applications yet. Create one to get started.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <BsModal modal-title="Create AI Application" modal-id="createAppModal" modal-size="modal-md">
        <form @submit.prevent="submitCreate">
            <BsInput title="Application Name" v-model="createForm.name" placeholder="e.g. Cortex OS HR Agent" />
            <div class="single-info-input margin-top-20">
                <label class="info-title">Description (optional)</label>
                <textarea class="form-control" v-model="createForm.description" rows="2" placeholder="What does this application do?"></textarea>
            </div>
            <div class="margin-top-20">
                <BsButton button-text="Create" button-type="submit" :disabled="createForm.processing"/>
            </div>
        </form>
    </BsModal>

    <!-- Secret Reveal Modal -->
    <div v-if="revealedSecret" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Secret Key — Copy Now</h5>
                </div>
                <div class="modal-body">
                    <p class="text-danger fw-bold">This secret will not be shown again. Copy it now and store it securely.</p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <code class="p-2 bg-light border rounded flex-grow-1" style="word-break: break-all; font-size: 13px;">{{ revealedSecret }}</code>
                        <button class="btn btn-sm btn-outline-secondary" @click="copySecret" :title="copied ? 'Copied!' : 'Copy'">
                            <i :class="copied ? 'fas fa-check' : 'fas fa-copy'"></i>
                        </button>
                    </div>
                    <p class="mt-2 text-muted small">Send this as the <strong>X-Signature</strong> header in every API request.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" @click="closeSecretModal">I have copied it</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import AdminMaster from '@/Layouts/AdminMaster.vue';
import BsModal from '@/Components/BsModal.vue';
import BsModalButton from '@/Components/BsModalButton.vue';
import BsInput from '@/Components/BsForm/Input.vue';
import BsButton from '@/Components/BsForm/Button.vue';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';

export default {
    name: 'AiApplications',
    layout: AdminMaster,
    components: { Head, Link, BsModal, BsModalButton, BsInput, BsButton },
    setup() {
        const page = usePage();
        const revealedSecret = ref(null);
        const copied = ref(false);

        const createForm = useForm({ name: '', description: '' });

        function appList() {
            return page.props.value.apps ?? [];
        }

        watch(() => page.props.value.flashMsg, (flash) => {
            if (flash?.secret) {
                revealedSecret.value = flash.secret;
                copied.value = false;
                // Close bootstrap modal if open
                const el = document.getElementById('createAppModal');
                if (el) {
                    el.classList.remove('show');
                    el.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            }
        }, { deep: true });

        function submitCreate() {
            createForm.post(route('admin.ai.applications.store'), {
                onSuccess: () => createForm.reset('name', 'description'),
            });
        }

        function toggleActive(app) {
            Swal.fire({
                title: app.active ? 'Deactivate this application?' : 'Activate this application?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    useForm({}).post(route('admin.ai.applications.toggle', app.id));
                }
            });
        }

        function regenerate(app) {
            Swal.fire({
                title: 'Regenerate secret key?',
                text: 'The old key will stop working immediately.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, regenerate',
            }).then((result) => {
                if (result.isConfirmed) {
                    useForm({}).post(route('admin.ai.applications.regenerate', app.id));
                }
            });
        }

        function deleteApp(app) {
            Swal.fire({
                title: 'Delete application?',
                text: 'All requests using this key will be rejected.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    useForm({ id: app.id }).post(route('admin.ai.applications.delete', app.id));
                }
            });
        }

        function copySecret() {
            if (revealedSecret.value) {
                navigator.clipboard.writeText(revealedSecret.value);
                copied.value = true;
            }
        }

        function closeSecretModal() {
            revealedSecret.value = null;
            copied.value = false;
        }

        return {
            appList,
            createForm,
            submitCreate,
            toggleActive,
            regenerate,
            deleteApp,
            revealedSecret,
            copied,
            copySecret,
            closeSecretModal,
        };
    },
};
</script>
