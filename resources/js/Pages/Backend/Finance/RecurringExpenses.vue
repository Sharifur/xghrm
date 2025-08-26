<template>
    <Head title="Recurring Expenses Management" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-repeat me-2"></i>
                            Recurring Expenses Management
                        </h4>
                        <button @click="showAddModal = true" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Recurring Expense
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Manage monthly recurring expenses like server bills, office rent, subscriptions, etc.
                            These will automatically appear in your balance sheet each month.
                        </p>
                    </div>
                </div>

                <!-- Expenses List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Current Recurring Expenses</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Amount & Currency</th>
                                        <th>Frequency</th>
                                        <th>Icon</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="expense in expenses" :key="expense.id">
                                        <td>
                                            <strong>{{ expense.name }}</strong>
                                        </td>
                                        <td>{{ expense.description || '-' }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                {{ expense.currency === 'USD' ? '$' : '৳' }}{{ formatNumber(expense.default_amount) }}
                                                <small v-if="expense.currency === 'USD'" class="d-block mt-1 text-muted">
                                                    (৳{{ formatNumber(expense.default_amount * 120) }} BDT)
                                                </small>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">Monthly</span>
                                        </td>
                                        <td>
                                            <i :class="expense.icon || 'fas fa-receipt'" class="text-primary"></i>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button @click="editExpense(expense)" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteExpense(expense.id)" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="expenses.length === 0">
                                        <td colspan="6" class="text-center text-muted">
                                            No recurring expenses found. Click "Add Recurring Expense" to get started.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Modal -->
                <div v-if="showAddModal || editingExpense" class="modal-overlay" @click="closeModal">
                    <div class="modal-content" @click.stop>
                        <div class="modal-header">
                            <h5>{{ editingExpense ? 'Edit' : 'Add' }} Recurring Expense</h5>
                            <button @click="closeModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveExpense">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Name *</label>
                                        <input 
                                            v-model="form.name" 
                                            type="text" 
                                            class="form-control" 
                                            required 
                                            placeholder="e.g., Office Rent, Server Bills"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Default Amount *</label>
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
                                                required 
                                                placeholder="0.00"
                                                @input="updateConvertedAmount"
                                            >
                                        </div>
                                        <div v-if="form.currency === 'USD'" class="form-text text-info">
                                            <small>
                                                <i class="fas fa-exchange-alt me-1"></i>
                                                Converted: ৳{{ formatNumber(form.default_amount * 120) }} BDT (1 USD = 120 BDT)
                                            </small>
                                        </div>
                                        <div v-else class="form-text text-muted">
                                            <small>Amount in Bangladeshi Taka (BDT)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Icon</label>
                                        <select v-model="form.icon" class="form-select">
                                            <option value="fas fa-receipt">Receipt (Default)</option>
                                            <option value="fas fa-server">Server</option>
                                            <option value="fas fa-building">Building/Rent</option>
                                            <option value="fas fa-bolt">Electricity</option>
                                            <option value="fas fa-wifi">Internet/WiFi</option>
                                            <option value="fas fa-tools">Tools/Software</option>
                                            <option value="fas fa-broom">Cleaning</option>
                                            <option value="fas fa-shopping-cart">Supplies</option>
                                            <option value="fas fa-shield-alt">Insurance</option>
                                            <option value="fas fa-bullhorn">Marketing</option>
                                            <option value="fas fa-credit-card">Payment/Subscription</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Frequency</label>
                                        <select v-model="form.frequency" class="form-select" required>
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                            <option value="weekly">Weekly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea 
                                        v-model="form.description" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Brief description of this expense"
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
                                    <button type="submit" class="btn btn-primary" :disabled="saving">
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
    name: "RecurringExpenses",
    components: {
        Head
    },
    props: {
        expenses: Array
    },
    setup(props) {
        const showAddModal = ref(false);
        const editingExpense = ref(null);
        const saving = ref(false);
        const expenses = ref([...props.expenses]);

        const form = reactive({
            name: '',
            description: '',
            default_amount: 0,
            frequency: 'monthly',
            icon: 'fas fa-receipt',
            tooltip: '',
            type: 'liability',
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
            form.frequency = 'monthly';
            form.icon = 'fas fa-receipt';
            form.tooltip = '';
            form.type = 'liability';
            form.currency = 'BDT';
        };

        const editExpense = (expense) => {
            editingExpense.value = expense;
            form.name = expense.name;
            form.description = expense.description;
            form.default_amount = expense.default_amount;
            form.frequency = 'monthly';
            form.icon = expense.icon;
            form.tooltip = expense.tooltip;
            form.currency = expense.currency || 'BDT';
        };

        const closeModal = () => {
            showAddModal.value = false;
            editingExpense.value = null;
            resetForm();
        };

        const saveExpense = async () => {
            saving.value = true;
            try {
                const url = editingExpense.value 
                    ? route('admin.finance.recurring.expenses.update', editingExpense.value.id)
                    : route('admin.finance.recurring.expenses.store');
                
                const method = editingExpense.value ? 'put' : 'post';
                
                const response = await axios[method](url, form);

                if (response.data.success) {
                    if (editingExpense.value) {
                        const index = expenses.value.findIndex(e => e.id === editingExpense.value.id);
                        expenses.value[index] = response.data.expense;
                    } else {
                        expenses.value.push(response.data.expense);
                    }
                    
                    closeModal();
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Save failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to save expense',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                saving.value = false;
            }
        };

        const deleteExpense = async (id) => {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this recurring expense?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await axios.delete(route('admin.finance.recurring.expenses.delete', id));
                
                if (response.data.success) {
                    expenses.value = expenses.value.filter(e => e.id !== id);
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Delete failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to delete expense',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        const updateConvertedAmount = () => {
            // This function can be used for real-time conversion display
            // Already handled in the template with reactive binding
        };

        return {
            expenses,
            showAddModal,
            editingExpense,
            saving,
            form,
            formatNumber,
            editExpense,
            closeModal,
            saveExpense,
            deleteExpense,
            updateConvertedAmount
        };
    }
}
</script>

<style scoped>
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
    justify-content: between;
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.badge {
    font-size: 0.75rem;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-info {
    background-color: #36b9cc !important;
}

.btn-group .btn {
    margin-right: 0.25rem;
}
</style>