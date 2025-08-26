<template>
    <Head title="Assets Management" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-coins me-2"></i>
                            Assets Management
                        </h4>
                        <button @click="showAddModal = true" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add Asset
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Manage your company assets like cash accounts, equipment, inventory, and receivables.
                            These items represent what your company owns.
                        </p>
                    </div>
                </div>

                <!-- Solo Entrepreneur Assets Management Guide -->
                <div class="card mb-4 entrepreneur-guide">
                    <div class="card-header bg-success-light">
                        <h6 class="text-success mb-0">
                            <button 
                                class="btn btn-link text-success p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#assetsGuide" 
                                aria-expanded="false" 
                                aria-controls="assetsGuide"
                            >
                                <i class="fas fa-lightbulb me-2"></i>
                                Solo Entrepreneur Assets Management Guide
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h6>
                    </div>
                    <div id="assetsGuide" class="collapse">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-question-circle me-2"></i>
                                    What are Business Assets?
                                </h6>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Current Assets:</strong> Cash, bank accounts, receivables (money clients owe you)
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Fixed Assets:</strong> Equipment, computers, software licenses, furniture
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Digital Assets:</strong> Domain names, websites, templates, apps you've built
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Investments:</strong> Stocks, crypto, other business investments
                                    </li>
                                </ul>

                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Why Track Assets?
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Know your true business net worth
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Plan for equipment upgrades and replacements
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Track depreciation for tax purposes
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Monitor cash flow and liquidity
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Solo Entrepreneur Asset Examples
                                </h6>
                                <div class="asset-examples">
                                    <div class="example-category mb-3">
                                        <strong class="text-success d-block mb-2">
                                            <i class="fas fa-laptop me-2"></i>Technology & Equipment
                                        </strong>
                                        <small class="text-muted d-block">
                                            MacBook Pro, monitors, cameras, microphones, software subscriptions (Adobe, Figma, hosting)
                                        </small>
                                    </div>
                                    <div class="example-category mb-3">
                                        <strong class="text-info d-block mb-2">
                                            <i class="fas fa-money-bill-wave me-2"></i>Cash & Receivables
                                        </strong>
                                        <small class="text-muted d-block">
                                            Business bank accounts, PayPal, Stripe balance, pending client payments
                                        </small>
                                    </div>
                                    <div class="example-category mb-3">
                                        <strong class="text-warning d-block mb-2">
                                            <i class="fas fa-globe me-2"></i>Digital Assets
                                        </strong>
                                        <small class="text-muted d-block">
                                            Domain portfolio, Webflow templates, Shopify apps, website themes
                                        </small>
                                    </div>
                                </div>

                                <div class="alert alert-success mt-3">
                                    <strong>
                                        <i class="fas fa-star me-2"></i>Pro Tips:
                                    </strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Update asset values quarterly for accurate financial picture</li>
                                        <li>Separate personal and business assets clearly</li>
                                        <li>Track purchase dates for depreciation calculations</li>
                                        <li>Consider insurance coverage for valuable equipment</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Assets List -->
                <div class="row">
                    <div v-for="asset in assets" :key="asset.id" class="col-lg-4 col-md-6 mb-4">
                        <div class="asset-card">
                            <div class="asset-header">
                                <div class="asset-icon">
                                    <i :class="asset.icon || 'fas fa-coins'"></i>
                                </div>
                                <div class="asset-actions">
                                    <button @click="editAsset(asset)" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteAsset(asset.id)" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="asset-content">
                                <h5>{{ asset.name }}</h5>
                                <p class="asset-description">{{ asset.description || 'No description' }}</p>
                                <div class="asset-amount">
                                    <small class="text-muted">Default Value</small>
                                    <div class="amount">
                                        {{ asset.currency === 'USD' ? '$' : '৳' }}{{ formatNumber(asset.default_amount) }}
                                        <small v-if="asset.currency === 'USD'" class="d-block mt-1 text-muted" style="font-size: 0.75rem;">
                                            (৳{{ formatNumber(asset.default_amount * 120) }} BDT)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="assets.length === 0" class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-coins text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Assets Found</h5>
                            <p class="text-muted">Click "Add Asset" to create your first asset category.</p>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Modal -->
                <div v-if="showAddModal || editingAsset" class="modal-overlay" @click="closeModal">
                    <div class="modal-content" @click.stop>
                        <div class="modal-header">
                            <h5>{{ editingAsset ? 'Edit' : 'Add' }} Asset</h5>
                            <button @click="closeModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveAsset">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Asset Name *</label>
                                        <input 
                                            v-model="form.name" 
                                            type="text" 
                                            class="form-control" 
                                            required 
                                            placeholder="e.g., Cash, Equipment, Inventory"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Default Amount</label>
                                        <div class="input-group">
                                            <select v-model="form.currency" class="input-group-text" style="border-right: 0; background: #e9ecef;">
                                                <option value="BDT">৳</option>
                                                <option value="USD">$</option>
                                            </select>
                                            <input 
                                                v-model.number="form.default_amount" 
                                                type="number" 
                                                class="form-control" 
                                                step="0.01"
                                                min="0"
                                                placeholder="0.00"
                                            >
                                        </div>
                                        <div v-if="form.currency === 'USD'" class="form-text text-info">
                                            <small>
                                                <i class="fas fa-exchange-alt me-1"></i>
                                                Converted: ৳{{ formatNumber(form.default_amount * 120) }} BDT (1 USD = 120 BDT)
                                            </small>
                                        </div>
                                        <div v-else class="form-text text-muted">
                                            <small>Initial value for new balance sheets</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon</label>
                                    <select v-model="form.icon" class="form-select">
                                        <option value="fas fa-coins">Coins (Default)</option>
                                        <option value="fas fa-money-bill-wave">Cash/Money</option>
                                        <option value="fas fa-hand-holding-usd">Accounts Receivable</option>
                                        <option value="fas fa-laptop">Equipment/Technology</option>
                                        <option value="fas fa-boxes">Inventory/Stock</option>
                                        <option value="fas fa-building">Real Estate</option>
                                        <option value="fas fa-car">Vehicles</option>
                                        <option value="fas fa-chart-line">Investments</option>
                                        <option value="fas fa-university">Bank Accounts</option>
                                        <option value="fas fa-warehouse">Warehouse/Storage</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea 
                                        v-model="form.description" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Describe this asset category"
                                    ></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tooltip (Help Text)</label>
                                    <input 
                                        v-model="form.tooltip" 
                                        type="text" 
                                        class="form-control" 
                                        placeholder="Help text shown to users"
                                    >
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success" :disabled="saving">
                                        <i class="fas fa-save"></i>
                                        {{ saving ? 'Saving...' : 'Save' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import { Head } from '@inertiajs/inertia-vue3';
import { ref, reactive } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    layout: AdminMaster,
    name: "Assets",
    components: {
        Head
    },
    props: {
        assets: Array
    },
    setup(props) {
        const showAddModal = ref(false);
        const editingAsset = ref(null);
        const saving = ref(false);
        const assets = ref([...props.assets]);

        const form = reactive({
            name: '',
            description: '',
            default_amount: 0,
            icon: 'fas fa-coins',
            tooltip: '',
            currency: 'BDT'
        });

        const formatNumber = (num) => {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num || 0);
        };

        const resetForm = () => {
            form.name = '';
            form.description = '';
            form.default_amount = 0;
            form.icon = 'fas fa-coins';
            form.tooltip = '';
            form.currency = 'BDT';
        };

        const editAsset = (asset) => {
            editingAsset.value = asset;
            form.name = asset.name;
            form.description = asset.description;
            form.default_amount = asset.default_amount;
            form.icon = asset.icon;
            form.tooltip = asset.tooltip;
            form.currency = asset.currency || 'BDT';
        };

        const closeModal = () => {
            showAddModal.value = false;
            editingAsset.value = null;
            resetForm();
        };

        const saveAsset = async () => {
            saving.value = true;
            try {
                const url = editingAsset.value 
                    ? route('admin.finance.assets.update', editingAsset.value.id)
                    : route('admin.finance.assets.store');
                
                const method = editingAsset.value ? 'put' : 'post';
                
                const response = await axios[method](url, form);

                if (response.data.success) {
                    if (editingAsset.value) {
                        const index = assets.value.findIndex(a => a.id === editingAsset.value.id);
                        assets.value[index] = response.data.asset;
                    } else {
                        assets.value.push(response.data.asset);
                    }
                    
                    closeModal();
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Save failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to save asset',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                saving.value = false;
            }
        };

        const deleteAsset = async (id) => {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this asset? This will remove it from all balance sheets.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await axios.delete(route('admin.finance.assets.delete', id));
                
                if (response.data.success) {
                    assets.value = assets.value.filter(a => a.id !== id);
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Delete failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to delete asset',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        return {
            assets,
            showAddModal,
            editingAsset,
            saving,
            form,
            formatNumber,
            editAsset,
            closeModal,
            saveAsset,
            deleteAsset
        };
    }
}
</script>

<style scoped>
.asset-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
    border-left: 4px solid #28a745;
}

.asset-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.asset-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.asset-icon {
    width: 50px;
    height: 50px;
    background: #28a745;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.asset-actions {
    display: flex;
    gap: 0.5rem;
}

.asset-content h5 {
    color: #5a5c69;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.asset-description {
    color: #858796;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    min-height: 2.7rem;
}

.asset-amount {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e3e6f0;
}

.asset-amount .amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: #28a745;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-content {
    background: white;
    border-radius: 0.5rem;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h5 {
    margin: 0;
    flex: 1;
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.modal-body {
    padding: 1.5rem;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.entrepreneur-guide .bg-success-light {
    background-color: #d4edda !important;
    border-bottom: 1px solid #c3e6cb;
}

.entrepreneur-guide .example-category {
    padding: 0.75rem;
    border-left: 3px solid #28a745;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

@media (max-width: 768px) {
    .asset-actions {
        flex-direction: column;
    }
}
</style>