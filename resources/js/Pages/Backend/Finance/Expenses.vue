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
                        <div class="btn-group">
                            <button @click="openAddModal" class="btn btn-warning">
                                <i class="fas fa-plus"></i> Add Expense
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
                        <div class="entrepreneur-guide mb-4">
                            <h6 class="text-primary mb-0">
                                <button 
                                    class="btn btn-link text-primary p-0 text-decoration-none w-100 text-start" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#expenseGuide" 
                                    aria-expanded="false" 
                                    aria-controls="expenseGuide"
                                >
                                    <i class="fas fa-lightbulb me-2"></i>
                                    Solo Entrepreneur Expense Management Guide
                                    <i class="fas fa-chevron-down float-end mt-1"></i>
                                </button>
                            </h6>
                            <div id="expenseGuide" class="collapse mt-3">
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
                        </div>
                        <p class="text-muted">
                            Record all your one-time business expenses here. Use different currencies (USD for software, BDT for local expenses) 
                            and categorize properly for better financial insights.
                        </p>
                    </div>
                </div>

                <!-- Monthly Statistics -->
                <div class="row mb-4">
                    <div class="col-lg-4 col-md-4 mb-3">
                        <div class="metric-card current-month-card">
                            <div class="metric-content">
                                <div class="metric-value">৳{{ formatNumber(currentMonthTotal) }}</div>
                                <div class="metric-label">Current Month Expenses</div>
                                <div class="metric-subtitle">{{ getCurrentMonthName() }}</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 mb-3">
                        <div class="metric-card previous-month-card">
                            <div class="metric-content">
                                <div class="metric-value">৳{{ formatNumber(previousMonthTotal) }}</div>
                                <div class="metric-label">Previous Month Expenses</div>
                                <div class="metric-subtitle">{{ getPreviousMonthName() }}</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 mb-3">
                        <div class="metric-card comparison-card">
                            <div class="metric-content">
                                <div class="metric-value" :class="expenseChangePercentage >= 0 ? 'text-danger' : 'text-success'">
                                    {{ expenseChangePercentage >= 0 ? '+' : '' }}{{ expenseChangePercentage.toFixed(1) }}%
                                </div>
                                <div class="metric-label">Month-over-Month Change</div>
                                <div class="metric-subtitle" :class="expenseChangePercentage >= 0 ? 'text-danger' : 'text-success'">
                                    <i :class="expenseChangePercentage >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    {{ expenseChangePercentage >= 0 ? 'Increased' : 'Decreased' }} by ৳{{ formatNumber(Math.abs(expenseChangeAmount)) }}
                                </div>
                            </div>
                            <div class="metric-icon" :class="expenseChangePercentage >= 0 ? 'text-danger' : 'text-success'">
                                <i :class="expenseChangePercentage >= 0 ? 'fas fa-trending-up' : 'fas fa-trending-down'"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Expense Records</h5>
                        <div class="d-flex align-items-center gap-3">
                            <div v-if="expenses.length > 0" class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    :checked="isAllSelected" 
                                    @change="toggleSelectAll"
                                    id="selectAll"
                                >
                                <label class="form-check-label" for="selectAll">
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
                                    <tr v-for="expense in paginatedExpenses" :key="expense.id">
                                        <td>
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    :value="expense.id" 
                                                    v-model="selectedExpenses"
                                                    :id="`expense-${expense.id}`"
                                                >
                                            </div>
                                        </td>
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
                                                ৳{{ formatNumber(expense.amount) }}
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
                                        <td colspan="8" class="text-center text-muted">
                                            No expenses found. Click "Add Expense" to record your first expense.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="d-md-none">
                            <div v-if="expenses.length === 0" class="text-center text-muted py-5">
                                <i class="fas fa-receipt fa-3x mb-3"></i>
                                <p>No expenses found.<br>Click "Add Expense" to record your first expense.</p>
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
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <i :class="expense.icon || 'fas fa-receipt'" class="text-primary me-2 fs-5"></i>
                                                        <h6 class="mb-0 expense-name fw-bold">{{ expense.name }}</h6>
                                                    </div>
                                                    <span class="badge bg-light text-dark">
                                                        {{ formatDate(expense.expense_date) }}
                                                    </span>
                                                </div>
                                                
                                                <div class="expense-details">
                                                    <div class="mb-2">
                                                        <span class="badge bg-secondary me-2">{{ expense.category }}</span>
                                                    </div>
                                                    
                                                    <p class="text-muted mb-2 small" v-if="expense.description">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        {{ expense.description }}
                                                    </p>
                                                    
                                                    <div class="expense-amount">
                                                        <span class="badge bg-warning text-dark fs-6">
                                                            ৳{{ formatNumber(expense.amount) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div v-if="expense.notes" class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-sticky-note me-1"></i>
                                                            {{ expense.notes }}
                                                        </small>
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
                            <nav aria-label="Expenses pagination">
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
                                                Converted: ৳{{ formatNumber(form.amount * USD_TO_BDT_RATE) }} BDT (1 USD = {{ USD_TO_BDT_RATE }} BDT)
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
import { ref, reactive, computed, watch } from 'vue';
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
        
        // Pagination and selection
        const selectedExpenses = ref([]);
        const currentPage = ref(1);
        const itemsPerPage = ref(10);

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

        // Currency conversion constants
        const USD_TO_BDT_RATE = 120;
        const previousCurrency = ref('BDT');

        // Watch for currency changes and convert amount automatically
        watch(() => form.currency, (newCurrency) => {
            const oldCurrency = previousCurrency.value;
            if (oldCurrency && form.amount > 0 && oldCurrency !== newCurrency) {
                if (oldCurrency === 'BDT' && newCurrency === 'USD') {
                    // Convert from BDT to USD
                    form.amount = Number((form.amount / USD_TO_BDT_RATE).toFixed(2));
                } else if (oldCurrency === 'USD' && newCurrency === 'BDT') {
                    // Convert from USD to BDT
                    form.amount = Number((form.amount * USD_TO_BDT_RATE).toFixed(2));
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
                // Show all pages if total is 7 or less
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                // Show first page, current page range, and last page
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

        // Monthly statistics computed properties
        const currentMonthTotal = computed(() => {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1;
            
            const total = expenses.value
                .filter(expense => {
                    if (!expense.expense_date) return false;
                    const expenseDate = new Date(expense.expense_date);
                    return !isNaN(expenseDate.getTime()) && 
                           expenseDate.getFullYear() === currentYear && 
                           expenseDate.getMonth() + 1 === currentMonth;
                })
                .reduce((total, expense) => {
                    const amount = parseFloat(expense.amount) || 0;
                    return total + amount;
                }, 0);
            
            return isNaN(total) ? 0 : total;
        });

        const previousMonthTotal = computed(() => {
            const currentDate = new Date();
            const previousMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
            const previousYear = previousMonth.getFullYear();
            const previousMonthNum = previousMonth.getMonth() + 1;
            
            const total = expenses.value
                .filter(expense => {
                    if (!expense.expense_date) return false;
                    const expenseDate = new Date(expense.expense_date);
                    return !isNaN(expenseDate.getTime()) && 
                           expenseDate.getFullYear() === previousYear && 
                           expenseDate.getMonth() + 1 === previousMonthNum;
                })
                .reduce((total, expense) => {
                    const amount = parseFloat(expense.amount) || 0;
                    return total + amount;
                }, 0);
            
            return isNaN(total) ? 0 : total;
        });

        const expenseChangeAmount = computed(() => {
            const current = currentMonthTotal.value || 0;
            const previous = previousMonthTotal.value || 0;
            const change = current - previous;
            return isNaN(change) ? 0 : change;
        });

        const expenseChangePercentage = computed(() => {
            const current = currentMonthTotal.value || 0;
            const previous = previousMonthTotal.value || 0;
            
            if (previous === 0) {
                return current > 0 ? 100 : 0;
            }
            
            const percentage = ((current - previous) / previous) * 100;
            return isNaN(percentage) ? 0 : percentage;
        });

        const formatNumber = (num) => {
            // Handle NaN, null, undefined, and non-numeric values
            if (num === null || num === undefined || isNaN(num)) {
                return '0.00';
            }
            
            const numValue = parseFloat(num);
            if (isNaN(numValue)) {
                return '0.00';
            }
            
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(numValue);
        };

        const getCurrentMonthName = () => {
            const currentDate = new Date();
            return currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        };

        const getPreviousMonthName = () => {
            const currentDate = new Date();
            const previousMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
            return previousMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
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
            previousCurrency.value = 'BDT'; // Reset previous currency tracking
        };

        const openAddModal = () => {
            resetForm(); // Ensure form is properly reset
            showAddModal.value = true;
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
                    ? route('admin.finance.expenses.update', editingExpense.value.id)
                    : route('admin.finance.expenses.store');
                
                const method = editingExpense.value ? 'put' : 'post';
                
                // Convert USD to BDT before saving to database
                const saveData = { ...form };
                if (form.currency === 'USD') {
                    saveData.amount = form.amount * USD_TO_BDT_RATE;
                    saveData.currency = 'BDT';
                }
                
                const response = await axios[method](url, saveData);

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

        // Bulk delete function
        const deleteSelectedExpenses = async () => {
            if (selectedExpenses.value.length === 0) return;

            const selectedCount = selectedExpenses.value.length;
            const selectedExpensesData = expenses.value.filter(expense => 
                selectedExpenses.value.includes(expense.id)
            );
            
            const totalBDT = selectedExpensesData.reduce((sum, expense) => {
                const amount = expense.amount;
                return sum + amount;
            }, 0);

            const expenseList = selectedExpensesData.slice(0, 5).map(expense => 
                `• ${expense.name} (${expense.category}): ৳${formatNumber(expense.amount)}`
            ).join('<br>');
            
            const moreText = selectedExpensesData.length > 5 ? `<br>...and ${selectedExpensesData.length - 5} more expenses` : '';

            const result = await Swal.fire({
                title: `Delete ${selectedCount} Selected Expenses?`,
                html: `<div class="text-left">
                    <p>This will permanently remove <strong>${selectedCount}</strong> selected expenses:</p>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                        ${expenseList}
                        ${moreText}
                    </div>
                    <p><strong>Total Value:</strong> ৳${formatNumber(totalBDT)}</p>
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
                    axios.delete(route('admin.finance.expenses.delete', expenseId))
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
                    text: `Successfully removed ${selectedCount} expenses.`,
                    icon: 'success',
                    confirmButtonColor: '#f6c23e',
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
                const amount = expense.amount;
                return sum + amount;
            }, 0);

            const expenseList = expenses.value.slice(0, 5).map(expense => 
                `• ${expense.name} (${expense.category}): ৳${formatNumber(expense.amount)}`
            ).join('<br>');
            
            const moreText = expenses.value.length > 5 ? `<br>...and ${expenses.value.length - 5} more expenses` : '';

            const result = await Swal.fire({
                title: 'Clear All One-Time Expenses?',
                html: `<div class="text-left">
                    <p>This will permanently remove <strong>${totalExpenses}</strong> one-time expenses:</p>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 14px;">
                        ${expenseList}
                        ${moreText}
                    </div>
                    <p><strong>Total Value:</strong> ৳${formatNumber(totalBDT)}</p>
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
                    axios.delete(route('admin.finance.expenses.delete', expense.id))
                );
                
                await Promise.all(deletePromises);
                
                expenses.value.splice(0);
                
                Swal.fire({
                    title: 'All Expenses Cleared!',
                    text: `Successfully removed ${totalExpenses} one-time expenses.`,
                    icon: 'success',
                    confirmButtonColor: '#f6c23e',
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
            const csvHeader = 'Date,Name,Category,Amount,Currency,BDT Equivalent,Description,Notes\n';
            const csvRows = expenses.value.map(expense => {
                const bdtAmount = expense.amount;
                return [
                    expense.expense_date,
                    `"${expense.name}"`,
                    `"${expense.category}"`,
                    expense.amount,
                    expense.currency,
                    bdtAmount.toFixed(2),
                    `"${expense.description || ''}"`,
                    `"${expense.notes || ''}"`
                ].join(',');
            }).join('\n');

            const csvContent = csvHeader + csvRows;
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `One-Time-Expenses-${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                Swal.fire({
                    title: 'Export Complete!',
                    text: 'One-time expenses have been exported to CSV.',
                    icon: 'success',
                    confirmButtonColor: '#f6c23e',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        };

        return {
            expenses,
            showAddModal,
            editingExpense,
            saving,
            form,
            USD_TO_BDT_RATE,
            formatNumber,
            formatDate,
            openAddModal,
            editExpense,
            closeModal,
            saveExpense,
            deleteExpense,
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
            // Monthly Statistics
            currentMonthTotal,
            previousMonthTotal,
            expenseChangeAmount,
            expenseChangePercentage,
            getCurrentMonthName,
            getPreviousMonthName
        };
    }
}
</script>

<style scoped>
/* Metric Cards */
.metric-card {
    background: white;
    border: 1px solid #e3e6f0 !important;
    border-radius: 0.5rem;
    padding: 1rem;
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-sizing: border-box;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 120px;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.metric-card.current-month-card {
    border-left: 4px solid #007bff !important;
}

.metric-card.previous-month-card {
    border-left: 4px solid #6c757d !important;
}

.metric-card.comparison-card {
    border-left: 4px solid #28a745 !important;
}

.metric-icon {
    width: 45px;
    height: 45px;
    background: rgba(0,123,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #007bff;
    font-size: 1.2rem;
    flex-shrink: 0;
    margin-left: 1rem;
}

.metric-content {
    flex-grow: 1;
}

.metric-value {
    font-size: 1.4rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.metric-label {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.metric-subtitle {
    font-size: 0.75rem;
    color: #6c757d;
}

.metric-subtitle i {
    margin-right: 0.25rem;
}

/* Remove any debug outlines */
.metric-card,
.metric-card *,
.metric-card:before,
.metric-card:after {
    outline: none !important;
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

.bg-light {
    background-color: #f8f9fa !important;
}

.bg-secondary {
    background-color: #858796 !important;
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
    cursor: pointer;
    user-select: none;
}

.mobile-expense-card .expense-actions {
    flex-shrink: 0;
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

/* Enhanced Mobile and Tablet Responsive Styles */
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
        font-size: 0.85rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .table th, .table td {
        padding: 0.4rem 0.2rem;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.6rem;
        padding: 0.3em 0.4em;
    }
    
    .form-check-input {
        transform: scale(1.1);
    }
    
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination .page-link {
        font-size: 0.75rem;
        padding: 0.35rem 0.5rem;
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
    
    .entrepreneur-guide {
        margin-bottom: 1rem !important;
    }
    
    .entrepreneur-guide .row {
        margin: 0;
    }
    
    .entrepreneur-guide .col-md-6 {
        padding: 0.5rem;
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
        max-width: 85%;
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
        width: 25px;
        padding: 0.2rem 0.1rem;
    }
    
    .table th:nth-child(4),
    .table td:nth-child(4) {
        min-width: 90px;
    }
    
    .btn {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }
    
    .form-control, .form-select {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .pagination .page-item {
        margin: 1px;
    }
    
    .alert {
        font-size: 0.85rem;
        padding: 0.75rem;
    }
    
    .entrepreneur-guide .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>