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
                        <div class="btn-group">
                            <button @click="openAddModal" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Recurring Expense
                            </button>
                            <button v-if="selectedExpenses.length > 0" @click="deleteSelectedExpenses" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Selected ({{ selectedExpenses.length }})
                            </button>
                            <button v-if="expenses.length > 0" @click="exportExpenses" class="btn btn-outline-success">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                            <button v-if="expenses.length > 0" @click="clearAllExpenses" class="btn btn-outline-danger">
                                <i class="fas fa-trash-alt"></i> Clear All
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Manage monthly recurring expenses like server bills, office rent, subscriptions, etc.
                            These will automatically appear in your balance sheet each month.
                        </p>
                    </div>
                </div>

                <!-- Statistics Boxes -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="fas fa-calendar-day text-primary fs-3 me-2"></i>
                                    <div>
                                        <h6 class="text-muted mb-0">Monthly Total</h6>
                                        <h4 class="text-primary mb-0">৳{{ formatNumber(monthlyTotal) }}</h4>
                                    </div>
                                </div>
                                <small class="text-muted">All expenses converted to monthly basis</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="fas fa-calendar-week text-success fs-3 me-2"></i>
                                    <div>
                                        <h6 class="text-muted mb-0">Weekly Total</h6>
                                        <h4 class="text-success mb-0">৳{{ formatNumber(weeklyTotal) }}</h4>
                                    </div>
                                </div>
                                <small class="text-muted">Total weekly recurring costs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="fas fa-calendar-alt text-danger fs-3 me-2"></i>
                                    <div>
                                        <h6 class="text-muted mb-0">Yearly Total</h6>
                                        <h4 class="text-danger mb-0">৳{{ formatNumber(yearlyTotal) }}</h4>
                                    </div>
                                </div>
                                <small class="text-muted">Total yearly recurring costs</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Current Recurring Expenses</h5>
                        <div class="d-flex align-items-center gap-3">
                            <div v-if="expenses.length > 0" class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    :checked="isAllSelected" 
                                    @change="toggleSelectAll"
                                    id="selectAllRecurring"
                                >
                                <label class="form-check-label" for="selectAllRecurring">
                                    Select All
                                </label>
                            </div>
                            <span class="text-muted">{{ paginatedExpenses.length }} of {{ expenses.length }} items</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Desktop Table View -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="40">
                                            <i class="fas fa-check-square text-muted"></i>
                                        </th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Amount & Currency</th>
                                        <th>Frequency</th>
                                        <th>Payment Status</th>
                                        <th>Next Due</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="expense in paginatedExpenses" :key="expense.id">
                                        <td>
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    :value="expense.id" 
                                                    v-model="selectedExpenses"
                                                    :id="`recurring-expense-${expense.id}`"
                                                >
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ expense.name }}</strong>
                                        </td>
                                        <td>{{ expense.description || '-' }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                ৳{{ formatNumber(expense.default_amount) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" :class="getFrequencyColor(expense.frequency)">{{ formatFrequency(expense.frequency) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge" :class="getPaymentStatusColor(expense.payment_status)">
                                                <i :class="getPaymentStatusIcon(expense.payment_status)"></i>
                                                {{ formatPaymentStatus(expense.payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small v-if="expense.next_due_date" class="text-muted">
                                                {{ formatDate(expense.next_due_date) }}
                                            </small>
                                            <small v-else class="text-muted">-</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button 
                                                    v-if="expense.payment_status !== 'paid'" 
                                                    @click="markAsPaid(expense)" 
                                                    class="btn btn-sm btn-success" 
                                                    title="Mark as Paid"
                                                >
                                                    <i class="fas fa-check"></i>
                                                </button>
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
                                        <td colspan="8" class="text-center text-muted">
                                            No recurring expenses found. Click "Add Recurring Expense" to get started.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="d-md-none">
                            <div v-if="expenses.length === 0" class="text-center text-muted py-5">
                                <i class="fas fa-receipt fa-3x mb-3"></i>
                                <p>No recurring expenses found.<br>Click "Add Recurring Expense" to get started.</p>
                            </div>
                            <div v-for="expense in paginatedExpenses" :key="expense.id" class="mobile-expense-card mb-3">
                                <div class="card border-start border-warning border-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <!-- Left side: Checkbox -->
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input mobile-checkbox" 
                                                    type="checkbox" 
                                                    :value="expense.id" 
                                                    v-model="selectedExpenses"
                                                    :id="`mobile-expense-${expense.id}`"
                                                >
                                            </div>
                                            
                                            <!-- Center: Expense Info -->
                                            <div class="expense-info flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i :class="expense.icon || 'fas fa-receipt'" class="text-primary me-2 fs-5"></i>
                                                    <h6 class="mb-0 expense-name fw-bold">{{ expense.name }}</h6>
                                                </div>
                                                
                                                <div class="expense-details">
                                                    <p class="text-muted mb-2 small" v-if="expense.description">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        {{ expense.description }}
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="expense-amount">
                                                            <span class="badge bg-warning text-dark fs-6">
                                                                ৳{{ formatNumber(expense.default_amount) }}
                                                            </span>
                                                        </div>
                                                        <span class="badge" :class="getFrequencyColor(expense.frequency)">{{ formatFrequency(expense.frequency) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Right side: Action buttons -->
                                            <div class="expense-actions d-flex flex-column gap-2">
                                                <button @click="editExpense(expense)" class="btn btn-outline-primary btn-sm mobile-btn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteExpense(expense.id)" class="btn btn-outline-danger btn-sm mobile-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div v-if="expenses.length > itemsPerPage" class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ ((currentPage - 1) * itemsPerPage) + 1 }} to {{ Math.min(currentPage * itemsPerPage, expenses.length) }} of {{ expenses.length }} entries
                            </div>
                            <nav aria-label="Recurring expenses pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                        <button class="page-link" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                    </li>
                                    <li 
                                        v-for="page in visiblePages" 
                                        :key="page" 
                                        class="page-item" 
                                        :class="{ active: page === currentPage }"
                                    >
                                        <button class="page-link" @click="changePage(page)">{{ page }}</button>
                                    </li>
                                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                        <button class="page-link" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
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
                                                Converted: ৳{{ formatNumber(form.default_amount * USD_TO_BDT_RATE) }} BDT (1 USD = {{ USD_TO_BDT_RATE }} BDT)
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
import { ref, reactive, computed, watch, nextTick } from 'vue';
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
        
        // Debug: Check what frequency data we're getting
        console.log('Loaded expenses with frequencies:', 
            expenses.value.map(exp => ({ name: exp.name, frequency: exp.frequency }))
        );
        
        // Pagination and selection
        const selectedExpenses = ref([]);
        const currentPage = ref(1);
        const itemsPerPage = ref(10);

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

        // Currency conversion constants
        const USD_TO_BDT_RATE = 120;
        const previousCurrency = ref('BDT');

        // Watch for currency changes and convert amount automatically
        watch(() => form.currency, (newCurrency) => {
            const oldCurrency = previousCurrency.value;
            
            if (oldCurrency && form.default_amount > 0 && oldCurrency !== newCurrency) {
                if (oldCurrency === 'BDT' && newCurrency === 'USD') {
                    // Convert from BDT to USD
                    form.default_amount = Number((form.default_amount / USD_TO_BDT_RATE).toFixed(2));
                } else if (oldCurrency === 'USD' && newCurrency === 'BDT') {
                    // Convert from USD to BDT
                    form.default_amount = Number((form.default_amount * USD_TO_BDT_RATE).toFixed(2));
                }
            }
            previousCurrency.value = newCurrency;
        });

        // Computed properties for pagination
        const totalPages = computed(() => Math.ceil(expenses.value.length / itemsPerPage.value));
        
        const paginatedExpenses = computed(() => {
            const sortedExpenses = expenses.value.sort((a, b) => b.id - a.id); // Sort by ID descending (newest first)
            const start = (currentPage.value - 1) * itemsPerPage.value;
            const end = start + itemsPerPage.value;
            return sortedExpenses.slice(start, end);
        });
        
        const visiblePages = computed(() => {
            const total = totalPages.value;
            const current = currentPage.value;
            const pages = [];
            
            if (total <= 7) {
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                if (current <= 4) {
                    for (let i = 1; i <= 5; i++) pages.push(i);
                    pages.push('...');
                    pages.push(total);
                } else if (current >= total - 3) {
                    pages.push(1);
                    pages.push('...');
                    for (let i = total - 4; i <= total; i++) pages.push(i);
                } else {
                    pages.push(1);
                    pages.push('...');
                    for (let i = current - 1; i <= current + 1; i++) pages.push(i);
                    pages.push('...');
                    pages.push(total);
                }
            }
            
            return pages.filter(page => page !== '...' || pages.indexOf(page) !== pages.lastIndexOf(page));
        });

        // Selection computed properties
        const isAllSelected = computed(() => {
            return paginatedExpenses.value.length > 0 && 
                   paginatedExpenses.value.every(expense => selectedExpenses.value.includes(expense.id));
        });

        // Statistics computed properties
        const monthlyTotal = computed(() => {
            return expenses.value.reduce((total, expense) => {
                const amount = parseFloat(expense.default_amount) || 0;
                
                // Convert all frequencies to monthly basis
                if (expense.frequency === 'yearly') {
                    return total + (amount / 12); // Divide yearly by 12
                } else if (expense.frequency === 'weekly') {
                    return total + (amount * 4.33); // Multiply weekly by 4.33 weeks per month
                } else { // monthly or default
                    return total + amount;
                }
            }, 0);
        });

        const weeklyTotal = computed(() => {
            return expenses.value
                .filter(expense => expense.frequency === 'weekly')
                .reduce((total, expense) => {
                    const amount = parseFloat(expense.default_amount) || 0;
                    return total + amount;
                }, 0);
        });

        const yearlyTotal = computed(() => {
            return expenses.value
                .filter(expense => expense.frequency === 'yearly')
                .reduce((total, expense) => {
                    const amount = parseFloat(expense.default_amount) || 0;
                    return total + amount;
                }, 0);
        });

        const formatNumber = (num) => {
            const number = parseFloat(num) || 0;
            if (isNaN(number)) return '0.00';
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        };

        const formatFrequency = (frequency) => {
            console.log('Formatting frequency:', frequency);
            const frequencyMap = {
                'monthly': 'Monthly',
                'yearly': 'Yearly', 
                'weekly': 'Weekly',
                'daily': 'Daily'
            };
            const result = frequencyMap[frequency] || 'Monthly';
            console.log('Formatted result:', result);
            return result;
        };

        const getFrequencyColor = (frequency) => {
            if (frequency === 'monthly') return 'bg-primary';
            if (frequency === 'weekly') return 'bg-success';
            if (frequency === 'yearly') return 'bg-danger';
            return 'bg-info';
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
            previousCurrency.value = 'BDT'; // Reset previous currency tracking
        };

        const openAddModal = async () => {
            resetForm(); // Ensure form is properly reset
            showAddModal.value = true;
            // Ensure the form is fully reset before any currency changes
            await nextTick();
            previousCurrency.value = 'BDT';
        };

        const editExpense = (expense) => {
            editingExpense.value = expense;
            form.name = expense.name;
            form.description = expense.description;
            form.default_amount = expense.default_amount;
            form.frequency = expense.frequency || 'monthly';
            form.icon = expense.icon;
            form.tooltip = expense.tooltip;
            form.currency = expense.currency || 'BDT';
            previousCurrency.value = expense.currency || 'BDT'; // Set previous currency to current expense currency
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
                
                // Convert USD to BDT before saving to database
                const saveData = { ...form };
                if (form.currency === 'USD') {
                    saveData.default_amount = form.default_amount * USD_TO_BDT_RATE;
                    saveData.currency = 'BDT';
                }
                
                console.log('Saving expense with data:', {
                    original: { currency: form.currency, amount: form.default_amount, frequency: form.frequency },
                    saving: { currency: saveData.currency, amount: saveData.default_amount, frequency: saveData.frequency }
                });
                
                const response = await axios[method](url, saveData);

                if (response.data.success) {
                    console.log('Server returned expense:', response.data.expense);
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

        // Pagination functions
        const changePage = (page) => {
            if (page >= 1 && page <= totalPages.value && page !== '...') {
                currentPage.value = page;
                selectedExpenses.value = []; // Clear selection when changing pages
            }
        };

        // Selection functions
        const toggleSelectAll = () => {
            if (isAllSelected.value) {
                // Deselect all current page items
                const currentPageIds = paginatedExpenses.value.map(expense => expense.id);
                selectedExpenses.value = selectedExpenses.value.filter(id => !currentPageIds.includes(id));
            } else {
                // Select all current page items
                const currentPageIds = paginatedExpenses.value.map(expense => expense.id);
                const newSelections = currentPageIds.filter(id => !selectedExpenses.value.includes(id));
                selectedExpenses.value.push(...newSelections);
            }
        };

        // Bulk delete function for selected expenses
        const deleteSelectedExpenses = async () => {
            if (selectedExpenses.value.length === 0) return;

            const selectedCount = selectedExpenses.value.length;
            const selectedExpensesData = expenses.value.filter(expense => 
                selectedExpenses.value.includes(expense.id)
            );
            
            const totalBDT = selectedExpensesData.reduce((sum, expense) => {
                return sum + expense.default_amount; // All amounts are now in BDT
            }, 0);

            const expenseList = selectedExpensesData.slice(0, 5).map(expense => 
                `• ${expense.name}: ৳${formatNumber(expense.default_amount)}`
            ).join('<br>');
            
            const moreText = selectedExpensesData.length > 5 ? `<br>...and ${selectedExpensesData.length - 5} more expenses` : '';

            const result = await Swal.fire({
                title: `Delete ${selectedCount} Selected Expenses?`,
                html: `<div class="text-left">
                    <p>This will permanently remove <strong>${selectedCount}</strong> recurring expenses:</p>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                        ${expenseList}
                        ${moreText}
                    </div>
                    <p><strong>Total Monthly Value:</strong> ৳${formatNumber(totalBDT)}</p>
                    <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, delete ${selectedCount} expenses!`,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });

            if (!result.isConfirmed) return;

            try {
                // Delete selected expenses one by one
                const deletePromises = selectedExpenses.value.map(expenseId => 
                    axios.delete(route('admin.finance.recurring.expenses.delete', expenseId))
                );
                
                await Promise.all(deletePromises);
                
                // Remove from local array
                expenses.value = expenses.value.filter(expense => 
                    !selectedExpenses.value.includes(expense.id)
                );
                
                // Clear selection and adjust pagination if necessary
                selectedExpenses.value = [];
                if (currentPage.value > totalPages.value && totalPages.value > 0) {
                    currentPage.value = totalPages.value;
                }
                
                Swal.fire({
                    title: 'Expenses Deleted!',
                    text: `Successfully removed ${selectedCount} recurring expenses.`,
                    icon: 'success',
                    confirmButtonColor: '#007bff',
                    timer: 3000,
                    timerProgressBar: true
                });
            } catch (error) {
                console.error('Bulk delete failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete some expenses. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        const clearAllExpenses = async () => {
            const totalExpenses = expenses.value.length;
            const totalBDT = expenses.value.reduce((sum, expense) => {
                return sum + expense.default_amount; // All amounts are now in BDT
            }, 0);

            const expenseList = expenses.value.slice(0, 5).map(expense => 
                `• ${expense.name}: ৳${formatNumber(expense.default_amount)}`
            ).join('<br>');
            
            const moreText = expenses.value.length > 5 ? `<br>...and ${expenses.value.length - 5} more expenses` : '';

            const result = await Swal.fire({
                title: 'Clear All Recurring Expenses?',
                html: `<div class="text-left">
                    <p>This will permanently remove <strong>${totalExpenses}</strong> recurring expenses:</p>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                        ${expenseList}
                        ${moreText}
                    </div>
                    <p><strong>Total Monthly Value:</strong> ৳${formatNumber(totalBDT)}</p>
                    <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
                </div>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear all expenses!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });

            if (!result.isConfirmed) return;

            try {
                // Since we don't have a bulk delete endpoint, delete them one by one
                const deletePromises = expenses.value.map(expense => 
                    axios.delete(route('admin.finance.recurring.expenses.delete', expense.id))
                );
                
                await Promise.all(deletePromises);
                
                expenses.value.splice(0);
                
                Swal.fire({
                    title: 'All Expenses Cleared!',
                    text: `Successfully removed ${totalExpenses} recurring expenses.`,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
            } catch (error) {
                console.error('Bulk delete failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to clear some expenses. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        const exportExpenses = async () => {
            const csvHeader = 'Name,Description,Amount,Currency,BDT Equivalent,Frequency,Icon\n';
            const csvRows = expenses.value.map(expense => {
                return [
                    `"${expense.name}"`,
                    `"${expense.description || ''}"`,
                    expense.default_amount.toFixed(2),
                    'BDT', // All amounts are now stored in BDT
                    expense.default_amount.toFixed(2),
                    formatFrequency(expense.frequency),
                    `"${expense.icon || 'fas fa-receipt'}"`
                ].join(',');
            }).join('\n');

            const csvContent = csvHeader + csvRows;
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `Recurring-Expenses-${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                Swal.fire({
                    title: 'Export Complete!',
                    text: 'Recurring expenses have been exported to CSV.',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        };

        // Payment Status Methods
        const getPaymentStatusColor = (status) => {
            const colors = {
                'paid': 'bg-success text-white',
                'unpaid': 'bg-warning text-dark',
                'pending': 'bg-info text-white',
                'overdue': 'bg-danger text-white',
                'due': 'bg-warning text-dark'
            };
            return colors[status] || 'bg-secondary text-white';
        };

        const getPaymentStatusIcon = (status) => {
            const icons = {
                'paid': 'fas fa-check-circle',
                'unpaid': 'fas fa-exclamation-circle',
                'pending': 'fas fa-clock',
                'overdue': 'fas fa-exclamation-triangle',
                'due': 'fas fa-calendar-exclamation'
            };
            return icons[status] || 'fas fa-question-circle';
        };

        const formatPaymentStatus = (status) => {
            const labels = {
                'paid': 'Paid',
                'unpaid': 'Unpaid',
                'pending': 'Pending',
                'overdue': 'Overdue',
                'due': 'Due'
            };
            return labels[status] || status;
        };

        const formatDate = (dateString) => {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        };

        const markAsPaid = async (expense) => {
            const result = await Swal.fire({
                title: 'Mark as Paid',
                html: `
                    <div class="text-left">
                        <p>Mark <strong>${expense.name}</strong> as paid?</p>
                        <div class="form-group mt-3">
                            <label for="paid_date">Payment Date:</label>
                            <input type="date" id="paid_date" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                        </div>
                        <div class="form-group mt-3">
                            <label for="payment_notes">Payment Notes (optional):</label>
                            <textarea id="payment_notes" class="form-control" rows="3" placeholder="Add any payment notes..."></textarea>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Mark as Paid',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const paidDate = document.getElementById('paid_date').value;
                    const paymentNotes = document.getElementById('payment_notes').value;
                    
                    if (!paidDate) {
                        Swal.showValidationMessage('Please select a payment date');
                        return false;
                    }
                    
                    return { paidDate, paymentNotes };
                }
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.post(route('admin.finance.recurring.expenses.mark-paid', expense.id), {
                        paid_date: result.value.paidDate,
                        payment_notes: result.value.paymentNotes
                    });

                    if (response.data.success) {
                        // Update the expense in the list
                        const index = expenses.value.findIndex(e => e.id === expense.id);
                        if (index !== -1) {
                            expenses.value[index] = {
                                ...expenses.value[index],
                                payment_status: 'paid',
                                last_paid_date: result.value.paidDate,
                                payment_notes: result.value.paymentNotes
                            };
                        }

                        await Swal.fire({
                            title: 'Success!',
                            text: 'Expense marked as paid successfully.',
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                } catch (error) {
                    console.error('Error marking expense as paid:', error);
                    await Swal.fire({
                        title: 'Error!',
                        text: 'Failed to mark expense as paid. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#e74a3b'
                    });
                }
            }
        };

        // Load expenses with payment status on component mount
        const loadExpensesWithStatus = async () => {
            try {
                const response = await axios.get(route('admin.finance.recurring.expenses.with-status'));
                if (response.data.success) {
                    expenses.value = response.data.expenses;
                }
            } catch (error) {
                console.error('Error loading expenses with status:', error);
            }
        };

        // Load expenses with status on mount
        loadExpensesWithStatus();

        return {
            expenses,
            showAddModal,
            editingExpense,
            saving,
            form,
            USD_TO_BDT_RATE,
            formatNumber,
            formatFrequency,
            getFrequencyColor,
            openAddModal,
            editExpense,
            closeModal,
            saveExpense,
            deleteExpense,
            updateConvertedAmount,
            clearAllExpenses,
            exportExpenses,
            // Pagination
            selectedExpenses,
            currentPage,
            itemsPerPage,
            totalPages,
            paginatedExpenses,
            visiblePages,
            changePage,
            // Selection
            isAllSelected,
            toggleSelectAll,
            deleteSelectedExpenses,
            // Statistics
            monthlyTotal,
            weeklyTotal,
            yearlyTotal,
            // Payment Status
            getPaymentStatusColor,
            getPaymentStatusIcon,
            formatPaymentStatus,
            formatDate,
            markAsPaid,
            loadExpensesWithStatus
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

/* Ensure form labels are left-aligned */
.form-label,
.modal-body label {
    text-align: left !important;
    display: block;
    margin-bottom: 0.5rem;
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

/* Mobile Card Styles */
.mobile-expense-card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.15s ease-in-out;
}

.mobile-expense-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.mobile-checkbox {
    transform: scale(1.3);
    margin-top: 2px;
}

.mobile-btn {
    padding: 0.25rem 0.4rem;
    font-size: 0.75rem;
    min-width: 20px;
    min-height: 20px;
    border-radius: 0.25rem;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mobile-btn i {
    font-size: 0.7rem;
}

.expense-name {
    color: #2c3e50;
    font-size: 1rem;
}

.expense-amount .badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

/* Touch-friendly interactions */
.mobile-expense-card .card-body {
    user-select: none;
}

.mobile-expense-card .expense-actions {
    flex-shrink: 0;
    min-width: 28px; /* Accommodate button width + gap */
}

.mobile-expense-card .expense-info {
    min-width: 0; /* Prevent flex item from overflowing */
}

/* Improved mobile card spacing */
.mobile-expense-card .d-flex.gap-3 {
    align-items: flex-start;
}

.mobile-expense-card .form-check {
    padding-top: 2px; /* Align checkbox with content */
}

.mobile-expense-card .expense-actions .mobile-btn {
    margin-bottom: 0; /* Remove any default margins */
}

/* Touch and PWA Optimizations */
@media (hover: none) and (pointer: coarse) {
    .mobile-btn:hover {
        transform: none;
    }
    
    .mobile-btn:active {
        transform: scale(0.95);
        transition: transform 0.1s;
    }
    
    .mobile-expense-card:hover {
        transform: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .mobile-expense-card:active {
        transform: translateY(1px);
    }
}

/* PWA and Touch Device Optimizations */
* {
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
    -webkit-touch-callout: none;
}

.mobile-btn, .pagination .page-link, .form-check-input {
    touch-action: manipulation;
}

/* Desktop button styles */
@media (min-width: 768px) {
    .btn-sm {
        min-width: 30px;
        min-height: 30px;
        padding: 0.25rem 0.5rem;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-sm i {
        font-size: 0.8rem;
    }
}

/* Mobile button styles */
@media (max-width: 767px) {
    .btn-sm {
        min-width: 20px;
        min-height: 20px;
        padding: 0.2rem 0.3rem;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-sm i {
        font-size: 0.7rem;
    }
}

.pagination .page-link {
    min-width: 44px;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-check-input {
    min-width: 20px;
    min-height: 20px;
}

/* Mobile and Tablet Responsive Styles */
@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start !important;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
        gap: 8px;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-right: 0;
        margin-bottom: 8px;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .table th, .table td {
        padding: 0.5rem 0.25rem;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.35em 0.5em;
    }
    
    .form-check-input {
        transform: scale(1.2);
    }
    
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination .page-link {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .modal-content {
        margin: 10px;
        width: calc(100% - 20px);
        max-height: calc(100vh - 20px);
    }
    
    .modal-body {
        padding: 1rem;
    }
}

/* Tablet Responsive Styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .btn-group {
        gap: 8px;
    }
    
    .table th, .table td {
        padding: 0.6rem 0.4rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
    
    .modal-content {
        max-width: 90%;
    }
}

/* Small mobile devices */
@media (max-width: 480px) {
    .card-header h4 {
        font-size: 1rem;
        line-height: 1.3;
    }
    
    .table th:first-child,
    .table td:first-child {
        width: 30px;
        padding: 0.3rem 0.1rem;
    }
    
    .table th:nth-child(3),
    .table td:nth-child(3) {
        min-width: 100px;
    }
    
    .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
    }
    
    .form-control {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .pagination .page-item {
        margin: 1px;
    }
}
</style>