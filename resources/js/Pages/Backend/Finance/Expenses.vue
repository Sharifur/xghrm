<template>
    <Head title="One-time Expenses" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            One-time Expenses
                        </h4>
                        <button @click="showAddModal = true" class="btn btn-warning">
                            <i class="fas fa-plus"></i> Add Expense
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="entrepreneur-guide mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-lightbulb me-2"></i>
                                Solo Entrepreneur Expense Management Guide
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="guide-section">
                                        <h6 class="text-success">What are One-time Expenses?</h6>
                                        <ul class="small text-muted">
                                            <li><strong>Equipment:</strong> Laptop, monitor, desk, chair</li>
                                            <li><strong>Software:</strong> Adobe CC, design tools, development licenses</li>
                                            <li><strong>Training:</strong> Courses, certifications, conferences</li>
                                            <li><strong>Marketing:</strong> Ad campaigns, branding materials</li>
                                            <li><strong>Legal/Professional:</strong> Lawyer fees, accountant consultations</li>
                                            <li><strong>Emergency:</strong> Urgent repairs, unexpected costs</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="guide-section">
                                        <h6 class="text-warning">Why Track One-time Expenses?</h6>
                                        <ul class="small text-muted">
                                            <li>📊 <strong>Tax Deductions:</strong> Most business expenses are tax-deductible</li>
                                            <li>💡 <strong>Cash Flow:</strong> Know exactly where your money goes</li>
                                            <li>📈 <strong>Business Growth:</strong> Track investments in your business</li>
                                            <li>🎯 <strong>Budget Planning:</strong> Plan for similar expenses next year</li>
                                            <li>💰 <strong>Profit Analysis:</strong> Understand true business profitability</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Pro Tip:</strong> Set aside 20-30% of your revenue for business expenses. Track everything - even small purchases add up!
                            </div>
                        </div>
                        <p class="text-muted">
                            Record all your one-time business expenses here. Use different currencies (USD for software, BDT for local expenses) 
                            and categorize properly for better financial insights.
                        </p>
                    </div>
                </div>

                <!-- Expenses List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Expense Records</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Amount & Currency</th>
                                        <th>Description</th>
                                        <th>Icon</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="expense in expenses" :key="expense.id">
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ formatDate(expense.expense_date) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ expense.name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ expense.category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                {{ expense.currency === 'USD' ? '$' : '৳' }}{{ formatNumber(expense.amount) }}
                                                <small v-if="expense.currency === 'USD'" class="d-block mt-1 text-muted">
                                                    (৳{{ formatNumber(expense.amount * 120) }} BDT)
                                                </small>
                                            </span>
                                        </td>
                                        <td>{{ expense.description || '-' }}</td>
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
                                        <td colspan="7" class="text-center text-muted">
                                            No expenses found. Click "Add Expense" to record your first expense.
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
                            <h5>{{ editingExpense ? 'Edit' : 'Add' }} Expense</h5>
                            <button @click="closeModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveExpense">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expense Name *</label>
                                        <input 
                                            v-model="form.name" 
                                            type="text" 
                                            class="form-control" 
                                            required 
                                            placeholder="e.g., Office Equipment, Training Course"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expense Date *</label>
                                        <input 
                                            v-model="form.expense_date" 
                                            type="date" 
                                            class="form-control" 
                                            required
                                        >
                                        <small class="form-text text-muted">Date when the expense occurred</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Amount *</label>
                                        <div class="input-group">
                                            <select v-model="form.currency" class="input-group-text" style="border-right: 0; background: #e9ecef;">
                                                <option value="BDT">৳</option>
                                                <option value="USD">$</option>
                                            </select>
                                            <input 
                                                v-model.number="form.amount" 
                                                type="number" 
                                                class="form-control" 
                                                step="0.01"
                                                min="0"
                                                required 
                                                placeholder="0.00"
                                            >
                                        </div>
                                        <div v-if="form.currency === 'USD'" class="form-text text-info">
                                            <small>
                                                <i class="fas fa-exchange-alt me-1"></i>
                                                Converted: ৳{{ formatNumber(form.amount * 120) }} BDT (1 USD = 120 BDT)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category *</label>
                                        <select v-model="form.category" class="form-select" required>
                                            <option value="">Select Category</option>
                                            <option value="Equipment">Equipment</option>
                                            <option value="Repairs">Repairs & Maintenance</option>
                                            <option value="Training">Training & Development</option>
                                            <option value="Travel">Travel & Transportation</option>
                                            <option value="Marketing">Marketing & Advertising</option>
                                            <option value="Legal">Legal & Professional</option>
                                            <option value="Supplies">Office Supplies</option>
                                            <option value="Technology">Technology & Software</option>
                                            <option value="Miscellaneous">Miscellaneous</option>
                                            <option value="Emergency">Emergency Expenses</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Icon</label>
                                        <select v-model="form.icon" class="form-select">
                                            <option value="fas fa-receipt">Receipt (Default)</option>
                                            <option value="fas fa-laptop">Equipment/Technology</option>
                                            <option value="fas fa-wrench">Repairs & Tools</option>
                                            <option value="fas fa-graduation-cap">Training & Education</option>
                                            <option value="fas fa-plane">Travel</option>
                                            <option value="fas fa-bullhorn">Marketing</option>
                                            <option value="fas fa-gavel">Legal</option>
                                            <option value="fas fa-boxes">Supplies</option>
                                            <option value="fas fa-exclamation-triangle">Emergency</option>
                                            <option value="fas fa-shopping-cart">Purchase</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea 
                                        v-model="form.description" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Brief description of the expense"
                                    ></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Additional Notes</label>
                                    <textarea 
                                        v-model="form.notes" 
                                        class="form-control" 
                                        rows="2" 
                                        placeholder="Any additional notes or details"
                                    ></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-warning" :disabled="saving">
                                        <i class="fas fa-save"></i>
                                        {{ saving ? 'Saving...' : 'Save Expense' }}
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
    name: "Expenses",
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
            amount: 0,
            currency: 'BDT',
            expense_date: new Date().toISOString().split('T')[0],
            category: '',
            icon: 'fas fa-receipt',
            notes: ''
        });

        const formatNumber = (num) => {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num || 0);
        };

        const formatDate = (dateString) => {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        };

        const resetForm = () => {
            form.name = '';
            form.description = '';
            form.amount = 0;
            form.currency = 'BDT';
            form.expense_date = new Date().toISOString().split('T')[0];
            form.category = '';
            form.icon = 'fas fa-receipt';
            form.notes = '';
        };

        const editExpense = (expense) => {
            editingExpense.value = expense;
            form.name = expense.name;
            form.description = expense.description;
            form.amount = expense.amount;
            form.currency = expense.currency || 'BDT';
            form.expense_date = expense.expense_date;
            form.category = expense.category;
            form.icon = expense.icon;
            form.notes = expense.notes;
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
                    ? route('admin.finance.expenses.update', editingExpense.value.id)
                    : route('admin.finance.expenses.store');
                
                const method = editingExpense.value ? 'put' : 'post';
                
                const response = await axios[method](url, form);

                if (response.data.success) {
                    if (editingExpense.value) {
                        const index = expenses.value.findIndex(e => e.id === editingExpense.value.id);
                        expenses.value[index] = response.data.expense;
                    } else {
                        expenses.value.unshift(response.data.expense);
                    }
                    
                    closeModal();
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#f6c23e',
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
                text: 'You want to delete this expense? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await axios.delete(route('admin.finance.expenses.delete', id));
                
                if (response.data.success) {
                    expenses.value = expenses.value.filter(e => e.id !== id);
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#f6c23e',
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

        return {
            expenses,
            showAddModal,
            editingExpense,
            saving,
            form,
            formatNumber,
            formatDate,
            editExpense,
            closeModal,
            saveExpense,
            deleteExpense
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
    max-width: 700px;
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

.bg-light {
    background-color: #f8f9fa !important;
}

.bg-secondary {
    background-color: #858796 !important;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>