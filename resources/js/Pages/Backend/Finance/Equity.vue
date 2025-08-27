<template>
    <Head title="Equity Management" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Equity Management
                        </h4>
                        <button @click="showAddModal = true" class="btn btn-info">
                            <i class="fas fa-plus"></i> Add Equity Item
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Manage your company equity including owner investments, retained earnings, and profit distributions.
                            These items represent the owner's stake in the company.
                        </p>
                    </div>
                </div>

                <!-- Solo Entrepreneur Equity Management Guide -->
                <div class="card mb-4 entrepreneur-guide">
                    <div class="card-header bg-info-light">
                        <h6 class="text-info mb-0">
                            <button 
                                class="btn btn-link text-info p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#equityGuide" 
                                aria-expanded="false" 
                                aria-controls="equityGuide"
                            >
                                <i class="fas fa-lightbulb me-2"></i>
                                Single Company Owner Equity Guide
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h6>
                    </div>
                    <div id="equityGuide" class="collapse">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-question-circle me-2"></i>
                                    What is Owner's Equity?
                                </h6>
                                <p class="mb-3 text-muted">
                                    As a solo entrepreneur, equity represents <strong>your ownership stake</strong> in the business. 
                                    It's essentially what you've put into the business minus what you've taken out.
                                </p>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-plus text-success me-2"></i>
                                        <strong>Owner Investment:</strong> Money you've put into the business
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-plus text-success me-2"></i>
                                        <strong>Retained Earnings:</strong> Profits kept in the business
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-danger me-2"></i>
                                        <strong>Owner Withdrawals:</strong> Money you've taken out
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-danger me-2"></i>
                                        <strong>Business Losses:</strong> Reduces your equity
                                    </li>
                                </ul>

                                <div class="alert alert-info">
                                    <strong>
                                        <i class="fas fa-calculator me-2"></i>Simple Formula:
                                    </strong>
                                    <br>
                                    <code class="text-dark">Owner's Equity = Assets - Liabilities</code>
                                    <br>
                                    <small class="mt-1 d-block">This shows your true ownership value in the business.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Solo Business Equity Examples
                                </h6>
                                <div class="equity-examples">
                                    <div class="example-category mb-3 positive">
                                        <strong class="text-success d-block mb-2">
                                            <i class="fas fa-arrow-up me-2"></i>Positive Equity (Increases Value)
                                        </strong>
                                        <ul class="list-unstyled small text-muted mb-0">
                                            <li>• Initial $10,000 investment to start business</li>
                                            <li>• Purchased laptop and equipment for business</li>
                                            <li>• Monthly profits reinvested in business growth</li>
                                            <li>• Earnings from Webflow templates & Shopify apps</li>
                                        </ul>
                                    </div>
                                    <div class="example-category mb-3 negative">
                                        <strong class="text-danger d-block mb-2">
                                            <i class="fas fa-arrow-down me-2"></i>Negative Equity (Decreases Value)
                                        </strong>
                                        <ul class="list-unstyled small text-muted mb-0">
                                            <li>• Owner draws/salary you pay yourself</li>
                                            <li>• Business losses during tough months</li>
                                            <li>• Large equipment purchases (temporary)</li>
                                            <li>• Emergency cash withdrawals</li>
                                        </ul>
                                    </div>
                                </div>

                                <h6 class="text-primary mb-3 mt-4">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Why Track Equity?
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        See how much your business is truly worth
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Track your return on investment
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Plan for future investments or withdrawals
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Prepare accurate financial statements
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <strong>
                                        <i class="fas fa-star me-2"></i>Pro Tips for Solo Entrepreneurs:
                                    </strong>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li><strong>Keep it Simple:</strong> Track major investments and withdrawals</li>
                                                <li><strong>Regular Reviews:</strong> Check equity monthly to see business growth</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li><strong>Separate Funds:</strong> Don't mix personal and business money</li>
                                                <li><strong>Plan Withdrawals:</strong> Avoid taking out too much too quickly</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Automatic Calculations -->
                <div class="row mb-4">
                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="metric-card current-profit-card">
                            <div class="metric-content">
                                <div class="metric-value" :class="currentMonthProfit >= 0 ? 'text-success' : 'text-danger'">
                                    ৳{{ formatNumber(Math.abs(currentMonthProfit)) }}
                                </div>
                                <div class="metric-label">Current Month Profit</div>
                                <div class="metric-subtitle">
                                    <span :class="currentMonthProfit >= 0 ? 'text-success' : 'text-danger'">
                                        <i :class="currentMonthProfit >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                        {{ currentMonthProfit >= 0 ? 'Profit' : 'Loss' }}
                                    </span>
                                    • ৳{{ formatNumber(Math.abs(currentMonthExpenses)) }} expenses
                                </div>
                            </div>
                            <div class="metric-icon" :class="currentMonthProfit >= 0 ? 'text-success' : 'text-danger'">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="metric-card retained-earnings-card">
                            <div class="metric-content">
                                <div class="metric-value text-primary">৳{{ formatNumber(totalRetainedEarnings) }}</div>
                                <div class="metric-label">Total Retained Earnings</div>
                                <div class="metric-subtitle text-muted">
                                    <i class="fas fa-piggy-bank me-1"></i>
                                    Accumulated business profits
                                </div>
                            </div>
                            <div class="metric-icon text-primary">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equity List -->
                <div class="row">
                    <div v-for="equityItem in sortedEquity" :key="equityItem.id" class="col-lg-4 col-md-6 mb-4">
                        <div class="equity-card">
                            <div class="equity-header">
                                <div class="equity-icon">
                                    <i :class="equityItem.icon || 'fas fa-chart-line'"></i>
                                </div>
                                <div class="equity-actions">
                                    <button @click="editEquity(equityItem)" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteEquity(equityItem.id)" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="equity-content">
                                <h5>{{ equityItem.name }}</h5>
                                <p class="equity-description">{{ equityItem.description || 'No description' }}</p>
                                <div class="equity-amount">
                                    <small class="text-muted">Default Value</small>
                                    <div class="amount">
                                        ৳{{ formatNumber(equityItem.currency === 'USD' ? equityItem.default_amount * USD_TO_BDT_RATE : equityItem.default_amount) }}
                                        <small v-if="equityItem.currency === 'USD'" class="d-block mt-1 text-muted" style="font-size: 0.75rem;">
                                            (${{ formatNumber(equityItem.default_amount) }} USD)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="equity.length === 0" class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Equity Items Found</h5>
                            <p class="text-muted">Click "Add Equity Item" to create your first equity category.</p>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Modal -->
                <div v-if="showAddModal || editingEquity" class="modal-overlay" @click="closeModal">
                    <div class="modal-content" @click.stop>
                        <div class="modal-header">
                            <h5>{{ editingEquity ? 'Edit' : 'Add' }} Equity Item</h5>
                            <button @click="closeModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveEquity">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Equity Name *</label>
                                        <input 
                                            v-model="form.name" 
                                            type="text" 
                                            class="form-control" 
                                            required 
                                            placeholder="e.g., Owner Investment, Retained Earnings"
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
                                                placeholder="0.00"
                                            >
                                        </div>
                                        <div v-if="form.currency === 'USD'" class="form-text text-info">
                                            <small>
                                                <i class="fas fa-exchange-alt me-1"></i>
                                                Converted: ৳{{ formatNumber(Math.abs(form.default_amount) * USD_TO_BDT_RATE) }} BDT (1 USD = {{ USD_TO_BDT_RATE }} BDT)
                                            </small>
                                        </div>
                                        <div v-else class="form-text text-muted">
                                            <small>Can be negative for distributions/losses</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon</label>
                                    <select v-model="form.icon" class="form-select">
                                        <option value="fas fa-chart-line">Chart Line (Default)</option>
                                        <option value="fas fa-user-tie">Owner Investment</option>
                                        <option value="fas fa-piggy-bank">Retained Earnings</option>
                                        <option value="fas fa-trophy">Profits</option>
                                        <option value="fas fa-handshake">Partnership Capital</option>
                                        <option value="fas fa-coins">Paid-in Capital</option>
                                        <option value="fas fa-chart-pie">Equity Distribution</option>
                                        <option value="fas fa-balance-scale">Common Stock</option>
                                        <option value="fas fa-star">Preferred Stock</option>
                                        <option value="fas fa-minus-circle">Treasury Stock</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea 
                                        v-model="form.description" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Describe this equity category"
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
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Equity can be positive (owner investment, profits) or negative (distributions, losses).
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-info" :disabled="saving">
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
import { ref, reactive, computed, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    layout: AdminMaster,
    name: "Equity",
    components: {
        Head
    },
    props: {
        equity: Array
    },
    setup(props) {
        const showAddModal = ref(false);
        const editingEquity = ref(null);
        const saving = ref(false);
        const equity = ref([...props.equity]);
        
        // Data for automatic calculations
        const expenses = ref([]);
        const recurringExpenses = ref([]);
        const revenues = ref([]);
        
        // Fetch financial data for calculations
        const fetchFinancialData = async () => {
            try {
                // Fetch expenses
                const expensesResponse = await axios.get('/admin/finance/expenses');
                expenses.value = expensesResponse.data.expenses || [];
                
                // Fetch recurring expenses  
                const recurringResponse = await axios.get('/admin/finance/recurring-expenses');
                recurringExpenses.value = recurringResponse.data.expenses || [];
                
                // Fetch dashboard data for revenue information
                const dashboardResponse = await axios.get('/admin/finance/dashboard/data');
                if (dashboardResponse.data.totalRevenue) {
                    // Use dashboard revenue data if available
                    revenues.value = [{
                        amount: dashboardResponse.data.totalRevenue,
                        date: new Date().toISOString().split('T')[0]
                    }];
                }
            } catch (error) {
                console.error('Failed to fetch financial data:', error);
            }
        };

        // Sort equity by ID descending (newest first)
        const sortedEquity = computed(() => {
            return equity.value.sort((a, b) => b.id - a.id);
        });

        // Automatic calculations for equity
        const currentMonthRevenue = computed(() => {
            // Use revenue data from dashboard or other sources
            if (revenues.value.length > 0) {
                return revenues.value.reduce((total, revenue) => {
                    const amount = parseFloat(revenue.amount) || 0;
                    return total + amount;
                }, 0);
            }
            
            // If no direct revenue data, estimate from typical business ratios
            // This is a simplified approach - in a real scenario you'd have actual revenue data
            const expenses = currentMonthExpenses.value;
            // Assuming a 30% profit margin, estimate revenue
            return expenses > 0 ? expenses / 0.7 : 0;
        });

        const currentMonthExpenses = computed(() => {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1;
            
            // Calculate one-time expenses for current month
            const oneTimeExpenses = expenses.value
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

            // Add monthly portion of recurring expenses
            const monthlyRecurringExpenses = recurringExpenses.value
                .reduce((total, expense) => {
                    const amount = parseFloat(expense.default_amount) || 0;
                    const frequency = expense.frequency || 'monthly';
                    
                    let monthlyAmount = amount;
                    if (frequency === 'yearly') {
                        monthlyAmount = amount / 12;
                    } else if (frequency === 'weekly') {
                        monthlyAmount = amount * 4.33; // Average weeks per month
                    }
                    
                    return total + monthlyAmount;
                }, 0);

            return oneTimeExpenses + monthlyRecurringExpenses;
        });

        const currentMonthProfit = computed(() => {
            // Since we don't have direct revenue data, we'll use a placeholder
            // In a real implementation, you'd calculate: Revenue - Expenses
            const revenue = currentMonthRevenue.value;
            const expenses = currentMonthExpenses.value;
            return revenue - expenses;
        });

        const totalRetainedEarnings = computed(() => {
            // Calculate total retained earnings from all historical profits
            // This would typically come from historical P&L data
            // For now, we'll use current month profit as a starting point
            
            // Get existing retained earnings from equity items
            const existingRetainedEarnings = equity.value
                .filter(item => 
                    item.name.toLowerCase().includes('retained') || 
                    item.name.toLowerCase().includes('earning') ||
                    item.name.toLowerCase().includes('profit')
                )
                .reduce((total, item) => {
                    const amount = parseFloat(item.default_amount) || 0;
                    return total + amount;
                }, 0);

            return existingRetainedEarnings + currentMonthProfit.value;
        });

        const form = reactive({
            name: '',
            description: '',
            default_amount: 0,
            icon: 'fas fa-chart-line',
            tooltip: '',
            currency: 'BDT'
        });

        // Currency conversion constants
        const USD_TO_BDT_RATE = 120;
        const previousCurrency = ref('BDT');

        // Watch for currency changes and convert amount automatically
        watch(() => form.currency, (newCurrency) => {
            const oldCurrency = previousCurrency.value;
            if (oldCurrency && form.default_amount !== 0 && oldCurrency !== newCurrency) {
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
            form.icon = 'fas fa-chart-line';
            form.tooltip = '';
            form.currency = 'BDT';
            previousCurrency.value = 'BDT'; // Reset previous currency tracking
        };

        const editEquity = (equityItem) => {
            editingEquity.value = equityItem;
            form.name = equityItem.name;
            form.description = equityItem.description;
            form.default_amount = equityItem.default_amount;
            form.icon = equityItem.icon;
            form.tooltip = equityItem.tooltip;
            form.currency = equityItem.currency || 'BDT';
            previousCurrency.value = equityItem.currency || 'BDT'; // Set previous currency to current equity currency
        };

        const closeModal = () => {
            showAddModal.value = false;
            editingEquity.value = null;
            resetForm();
        };

        const saveEquity = async () => {
            saving.value = true;
            try {
                const url = editingEquity.value 
                    ? route('admin.finance.equity.update', editingEquity.value.id)
                    : route('admin.finance.equity.store');
                
                const method = editingEquity.value ? 'put' : 'post';
                
                const response = await axios[method](url, form);

                if (response.data.success) {
                    if (editingEquity.value) {
                        const index = equity.value.findIndex(e => e.id === editingEquity.value.id);
                        equity.value[index] = response.data.equity;
                    } else {
                        equity.value.push(response.data.equity);
                    }
                    
                    closeModal();
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#17a2b8',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Save failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to save equity item',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                saving.value = false;
            }
        };

        const deleteEquity = async (id) => {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this equity item? This will remove it from all balance sheets.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await axios.delete(route('admin.finance.equity.delete', id));
                
                if (response.data.success) {
                    equity.value = equity.value.filter(e => e.id !== id);
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonColor: '#17a2b8',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Delete failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to delete equity item',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        // Initialize data on component mount
        const initializeComponent = async () => {
            await fetchFinancialData();
        };

        // Call initialization
        initializeComponent();

        return {
            equity,
            sortedEquity,
            showAddModal,
            editingEquity,
            saving,
            form,
            USD_TO_BDT_RATE,
            formatNumber,
            editEquity,
            closeModal,
            saveEquity,
            deleteEquity,
            // Automatic calculations
            currentMonthProfit,
            totalRetainedEarnings,
            currentMonthExpenses,
            currentMonthRevenue
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

.metric-card.current-profit-card {
    border-left: 4px solid #28a745 !important;
}

.metric-card.retained-earnings-card {
    border-left: 4px solid #007bff !important;
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

.equity-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
    border-left: 4px solid #17a2b8;
}

.equity-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.equity-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.equity-icon {
    width: 50px;
    height: 50px;
    background: #17a2b8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.equity-actions {
    display: flex;
    gap: 0.5rem;
}

.equity-content h5 {
    color: #5a5c69;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.equity-description {
    color: #858796;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    min-height: 2.7rem;
    white-space: pre-line;
    word-wrap: break-word;
    line-height: 1.5;
}

.equity-amount {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e3e6f0;
}

.equity-amount .amount {
    font-size: 1.5rem;
    font-weight: bold;
    color: #17a2b8;
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

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.entrepreneur-guide .bg-info-light {
    background-color: #d1ecf1 !important;
    border-bottom: 1px solid #bee5eb;
}

.entrepreneur-guide .example-category.positive {
    padding: 0.75rem;
    border-left: 3px solid #28a745;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

.entrepreneur-guide .example-category.negative {
    padding: 0.75rem;
    border-left: 3px solid #dc3545;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

@media (max-width: 768px) {
    .equity-actions {
        flex-direction: column;
    }
}
</style>