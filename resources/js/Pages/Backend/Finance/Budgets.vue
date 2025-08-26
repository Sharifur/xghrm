<template>
    <Head title="Budget Management & Forecasting" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Budget Management & Forecasting
                        </h4>
                        <div class="d-flex gap-2">
                            <select v-model="selectedPeriod" @change="updatePeriod" class="form-select form-select-sm" style="width: 180px;">
                                <option value="current">Current Quarter</option>
                                <option value="next">Next Quarter</option>
                                <option value="annual">Annual View</option>
                            </select>
                            <button @click="showCreateBudget = true" class="btn btn-primary me-2">
                                <i class="fas fa-plus"></i> Create Budget
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-trash-alt"></i> Manage
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item text-danger" @click="clearAllBudgets" :disabled="budgets.length === 0">
                                        <i class="fas fa-trash-alt me-2"></i>Clear All Budgets
                                    </button></li>
                                    <li><button class="dropdown-item" @click="exportBudgets" :disabled="budgets.length === 0">
                                        <i class="fas fa-download me-2"></i>Export CSV
                                    </button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item" @click="resetBudgetPeriod">
                                        <i class="fas fa-sync-alt me-2"></i>Reset Current Period
                                    </button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted mb-0">
                                    Plan and track your business expenses across different categories with quarterly forecasting.
                                    Monitor spending patterns and predict future financial needs based on historical data.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="budget-summary">
                                    <div class="total-budget">
                                        <small class="text-muted">Total Budget</small>
                                        <div class="h5 mb-0 text-primary">৳{{ formatNumber(totalBudget) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solo Entrepreneur Budget Management Guide -->
                <div class="card mb-4 entrepreneur-guide">
                    <div class="card-header bg-warning-light">
                        <button class="btn btn-link text-warning p-0 w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#budgetGuide" aria-expanded="false">
                            <h6 class="mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                Solo Entrepreneur Budget & Forecasting Guide
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </h6>
                        </button>
                    </div>
                    <div class="collapse" id="budgetGuide">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-question-circle me-2"></i>
                                    What is Budget Management?
                                </h6>
                                <p class="mb-3 text-muted">
                                    Budget management helps you <strong>plan and control spending</strong> by setting limits for different 
                                    business categories and tracking actual expenses against those limits.
                                </p>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Plan Ahead:</strong> Set spending limits for each category
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Track Progress:</strong> Monitor actual vs budgeted amounts
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Forecast Future:</strong> Predict next quarter's needs
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Stay on Track:</strong> Get alerts when nearing limits
                                    </li>
                                </ul>

                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-crystal-ball me-2"></i>
                                    Quarterly Forecasting Benefits
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Plan for seasonal business changes
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Prepare for equipment upgrades
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Budget for marketing campaigns
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Ensure adequate cash flow
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Solo Business Budget Categories
                                </h6>
                                <div class="budget-categories">
                                    <div class="category-example mb-3">
                                        <strong class="text-success d-block mb-2">
                                            <i class="fas fa-laptop me-2"></i>Technology & Software
                                        </strong>
                                        <small class="text-muted d-block">
                                            Hosting, domains, Adobe CC, Figma Pro, development tools, equipment repairs
                                        </small>
                                        <div class="example-amounts mt-1">
                                            <span class="badge bg-light text-dark">Q1: ৳15,000</span>
                                            <span class="badge bg-light text-dark">Q2: ৳8,000</span>
                                        </div>
                                    </div>
                                    <div class="category-example mb-3">
                                        <strong class="text-info d-block mb-2">
                                            <i class="fas fa-bullhorn me-2"></i>Marketing & Growth
                                        </strong>
                                        <small class="text-muted d-block">
                                            Google Ads, Facebook Ads, content creation, networking events, conferences
                                        </small>
                                        <div class="example-amounts mt-1">
                                            <span class="badge bg-light text-dark">Q1: ৳20,000</span>
                                            <span class="badge bg-light text-dark">Q2: ৳25,000</span>
                                        </div>
                                    </div>
                                    <div class="category-example mb-3">
                                        <strong class="text-warning d-block mb-2">
                                            <i class="fas fa-building me-2"></i>Operations & Office
                                        </strong>
                                        <small class="text-muted d-block">
                                            Electricity, internet, phone, office supplies, co-working space
                                        </small>
                                        <div class="example-amounts mt-1">
                                            <span class="badge bg-light text-dark">Q1: ৳12,000</span>
                                            <span class="badge bg-light text-dark">Q2: ৳12,000</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-3">
                                    <strong>
                                        <i class="fas fa-star me-2"></i>Pro Forecasting Tips:
                                    </strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Review last 6 months to predict next quarter</li>
                                        <li>Add 10-20% buffer for unexpected expenses</li>
                                        <li>Plan for seasonal changes in your business</li>
                                        <li>Set aside money for annual software renewals</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Overview Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-primary">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">৳{{ formatNumber(currentQuarterSpent) }}</div>
                                <div class="stat-label">Current Quarter Spent</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-success">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">৳{{ formatNumber(forecastNextQuarter) }}</div>
                                <div class="stat-label">Next Quarter Forecast</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ nearLimitCount }}</div>
                                <div class="stat-label">Budgets Near Limit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-info">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ daysLeftInQuarter }}</div>
                                <div class="stat-label">Days Left in Quarter</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Categories List -->
                <div class="row">
                    <div v-for="budget in sortedBudgets" :key="budget.id" class="col-lg-6 col-xl-4 mb-4">
                        <div class="budget-card" :class="getBudgetCardClass(budget)">
                            <div class="budget-header">
                                <div class="budget-icon">
                                    <i :class="budget.icon || 'fas fa-chart-pie'"></i>
                                </div>
                                <div class="budget-actions">
                                    <button @click="editBudget(budget)" class="btn btn-sm btn-outline-primary" title="Edit Budget">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="viewBudgetDetails(budget)" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-chart-bar"></i>
                                    </button>
                                    <button @click="deleteBudget(budget)" class="btn btn-sm btn-outline-danger" title="Delete Budget">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="budget-content">
                                <h6>{{ budget.name }}</h6>
                                <p class="budget-description">{{ budget.description || 'No description' }}</p>
                                
                                <!-- Progress Bar -->
                                <div class="budget-progress mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>Used: ৳{{ formatNumber(budget.used_amount) }}</small>
                                        <small>{{ Math.round((budget.used_amount / budget.allocated_amount) * 100) }}%</small>
                                    </div>
                                    <div class="progress">
                                        <div 
                                            class="progress-bar" 
                                            :class="getProgressBarClass(budget)"
                                            :style="`width: ${Math.min((budget.used_amount / budget.allocated_amount) * 100, 100)}%`"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Budget Details -->
                                <div class="budget-details">
                                    <div class="budget-amounts">
                                        <div class="amount-row">
                                            <span class="label">Allocated:</span>
                                            <span class="value">৳{{ formatNumber(budget.allocated_amount) }}</span>
                                        </div>
                                        <div class="amount-row">
                                            <span class="label">Remaining:</span>
                                            <span class="value" :class="budget.remaining_amount < 0 ? 'text-danger' : 'text-success'">
                                                ৳{{ formatNumber(budget.remaining_amount) }}
                                            </span>
                                        </div>
                                        <div v-if="budget.forecast_next_quarter" class="amount-row">
                                            <span class="label">Next Quarter Forecast:</span>
                                            <span class="value text-info">৳{{ formatNumber(budget.forecast_next_quarter) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="budget-status mt-2">
                                    <span class="badge" :class="getStatusBadgeClass(budget)">
                                        {{ getBudgetStatus(budget) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="budgets.length === 0" class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-chart-pie text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Budgets Created</h5>
                            <p class="text-muted">Click "Create Budget" to set up your first budget category with forecasting.</p>
                        </div>
                    </div>
                </div>

                <!-- Create/Edit Budget Modal -->
                <div v-if="showCreateBudget || editingBudget" class="modal-overlay" @click="closeModal">
                    <div class="modal-content large-modal" @click.stop>
                        <div class="modal-header">
                            <h5>{{ editingBudget ? 'Edit' : 'Create' }} Budget</h5>
                            <button @click="closeModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveBudget">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Budget Name *</label>
                                        <input 
                                            v-model="budgetForm.name" 
                                            type="text" 
                                            class="form-control" 
                                            required 
                                            placeholder="e.g., Technology & Software"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quarterly Amount (BDT) *</label>
                                        <input 
                                            v-model.number="budgetForm.allocated_amount" 
                                            type="number" 
                                            class="form-control" 
                                            step="0.01"
                                            min="0"
                                            required
                                            placeholder="15000.00"
                                        >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category Icon</label>
                                        <select v-model="budgetForm.icon" class="form-select">
                                            <option value="fas fa-chart-pie">Chart Pie (Default)</option>
                                            <option value="fas fa-laptop">Technology & Software</option>
                                            <option value="fas fa-bullhorn">Marketing & Advertising</option>
                                            <option value="fas fa-building">Office & Operations</option>
                                            <option value="fas fa-car">Travel & Transportation</option>
                                            <option value="fas fa-graduation-cap">Education & Training</option>
                                            <option value="fas fa-handshake">Professional Services</option>
                                            <option value="fas fa-shield-alt">Insurance & Legal</option>
                                            <option value="fas fa-cogs">Equipment & Maintenance</option>
                                            <option value="fas fa-users">Outsourcing & Contractors</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Auto-Forecast Next Quarter</label>
                                        <select v-model="budgetForm.auto_forecast" class="form-select">
                                            <option value="1">Yes - Based on current usage</option>
                                            <option value="0">No - Manual planning only</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea 
                                        v-model="budgetForm.description" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="What expenses will this budget cover?"
                                    ></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Budget Notes</label>
                                    <textarea 
                                        v-model="budgetForm.notes" 
                                        class="form-control" 
                                        rows="2" 
                                        placeholder="Internal notes about this budget category"
                                    ></textarea>
                                </div>
                                
                                <!-- Forecasting Section -->
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-crystal-ball me-2"></i>Forecasting Information</h6>
                                    <p class="mb-0">The system will analyze your historical spending in this category and automatically suggest budget amounts for next quarter. You can always adjust these suggestions manually.</p>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" :disabled="saving">
                                        <i class="fas fa-save"></i>
                                        {{ saving ? 'Saving...' : 'Save Budget' }}
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
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    layout: AdminMaster,
    name: "Budgets",
    components: {
        Head
    },
    props: {
        budgets: Array
    },
    setup(props) {
        const selectedPeriod = ref('current');
        const showCreateBudget = ref(false);
        const editingBudget = ref(null);
        const saving = ref(false);
        const budgets = ref([...props.budgets || []]);

        // Sort budgets by ID descending (newest first)
        const sortedBudgets = computed(() => {
            return budgets.value.sort((a, b) => b.id - a.id);
        });

        const budgetForm = reactive({
            name: '',
            description: '',
            allocated_amount: 0,
            icon: 'fas fa-chart-pie',
            notes: '',
            auto_forecast: 1
        });

        // Computed values
        const totalBudget = computed(() => {
            return budgets.value.reduce((sum, budget) => sum + budget.allocated_amount, 0);
        });

        const currentQuarterSpent = computed(() => {
            return budgets.value.reduce((sum, budget) => sum + budget.used_amount, 0);
        });

        const forecastNextQuarter = computed(() => {
            return budgets.value.reduce((sum, budget) => sum + (budget.forecast_next_quarter || 0), 0);
        });

        const nearLimitCount = computed(() => {
            return budgets.value.filter(budget => {
                const percentage = (budget.used_amount / budget.allocated_amount) * 100;
                return percentage >= 80;
            }).length;
        });

        const daysLeftInQuarter = computed(() => {
            const today = new Date();
            const currentQuarter = Math.floor(today.getMonth() / 3) + 1;
            const quarterEndMonth = currentQuarter * 3;
            const quarterEnd = new Date(today.getFullYear(), quarterEndMonth, 0);
            const diffTime = quarterEnd - today;
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        });

        const formatNumber = (num) => {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(num || 0);
        };

        const getBudgetCardClass = (budget) => {
            const percentage = (budget.used_amount / budget.allocated_amount) * 100;
            if (percentage >= 100) return 'budget-over-limit';
            if (percentage >= 80) return 'budget-near-limit';
            return '';
        };

        const getProgressBarClass = (budget) => {
            const percentage = (budget.used_amount / budget.allocated_amount) * 100;
            if (percentage >= 100) return 'bg-danger';
            if (percentage >= 80) return 'bg-warning';
            if (percentage >= 60) return 'bg-info';
            return 'bg-success';
        };

        const getStatusBadgeClass = (budget) => {
            const percentage = (budget.used_amount / budget.allocated_amount) * 100;
            if (percentage >= 100) return 'bg-danger';
            if (percentage >= 80) return 'bg-warning';
            return 'bg-success';
        };

        const getBudgetStatus = (budget) => {
            const percentage = (budget.used_amount / budget.allocated_amount) * 100;
            if (percentage >= 100) return 'Over Budget';
            if (percentage >= 80) return 'Near Limit';
            return 'On Track';
        };

        const resetForm = () => {
            budgetForm.name = '';
            budgetForm.description = '';
            budgetForm.allocated_amount = 0;
            budgetForm.icon = 'fas fa-chart-pie';
            budgetForm.notes = '';
            budgetForm.auto_forecast = 1;
        };

        const editBudget = (budget) => {
            editingBudget.value = budget;
            budgetForm.name = budget.name;
            budgetForm.description = budget.description;
            budgetForm.allocated_amount = budget.allocated_amount;
            budgetForm.icon = budget.icon;
            budgetForm.notes = budget.notes;
            budgetForm.auto_forecast = budget.auto_forecast;
        };

        const closeModal = () => {
            showCreateBudget.value = false;
            editingBudget.value = null;
            resetForm();
        };

        const saveBudget = async () => {
            saving.value = true;
            try {
                // Mock save - in real implementation, this would call API
                const budgetData = {
                    ...budgetForm,
                    id: editingBudget.value ? editingBudget.value.id : Date.now(),
                    used_amount: editingBudget.value ? editingBudget.value.used_amount : 0,
                    remaining_amount: budgetForm.allocated_amount - (editingBudget.value ? editingBudget.value.used_amount : 0),
                    forecast_next_quarter: budgetForm.allocated_amount * 1.1 // Simple forecast
                };

                if (editingBudget.value) {
                    const index = budgets.value.findIndex(b => b.id === editingBudget.value.id);
                    budgets.value[index] = budgetData;
                } else {
                    budgets.value.push(budgetData);
                }

                closeModal();
                Swal.fire({
                    title: 'Success!',
                    text: `Budget ${editingBudget.value ? 'updated' : 'created'} successfully`,
                    icon: 'success',
                    confirmButtonColor: '#007bff',
                    timer: 3000,
                    timerProgressBar: true
                });
            } catch (error) {
                console.error('Save failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to save budget',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                saving.value = false;
            }
        };

        const viewBudgetDetails = (budget) => {
            Swal.fire({
                title: budget.name + ' Details',
                html: `
                    <div class="text-left">
                        <p><strong>Allocated:</strong> ৳${formatNumber(budget.allocated_amount)}</p>
                        <p><strong>Used:</strong> ৳${formatNumber(budget.used_amount)}</p>
                        <p><strong>Remaining:</strong> ৳${formatNumber(budget.remaining_amount)}</p>
                        ${budget.forecast_next_quarter ? `<p><strong>Next Quarter Forecast:</strong> ৳${formatNumber(budget.forecast_next_quarter)}</p>` : ''}
                        <p><strong>Status:</strong> ${getBudgetStatus(budget)}</p>
                        ${budget.description ? `<p><strong>Description:</strong> ${budget.description}</p>` : ''}
                    </div>
                `,
                confirmButtonColor: '#007bff'
            });
        };

        const updatePeriod = () => {
            // In real implementation, this would refetch data for the selected period
            console.log('Period changed to:', selectedPeriod.value);
        };

        const deleteBudget = async (budget) => {
            const result = await Swal.fire({
                title: 'Delete Budget?',
                html: `
                    <div class="text-left">
                        <p>Are you sure you want to delete the budget for <strong>${budget.name}</strong>?</p>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Budget Details:</strong><br>
                            Allocated: ৳${formatNumber(budget.allocated_amount)}<br>
                            Used: ৳${formatNumber(budget.used_amount)}<br>
                            Remaining: ৳${formatNumber(budget.remaining_amount)}
                        </div>
                        <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                const index = budgets.value.findIndex(b => b.id === budget.id);
                if (index !== -1) {
                    budgets.value.splice(index, 1);
                    
                    Swal.fire({
                        title: 'Budget Deleted!',
                        text: `${budget.name} budget has been removed`,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }
        };

        const clearAllBudgets = async () => {
            if (budgets.value.length === 0) {
                Swal.fire({
                    title: 'No Budgets',
                    text: 'There are no budgets to clear',
                    icon: 'info',
                    confirmButtonColor: '#007bff'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Clear All Budgets?',
                html: `
                    <div class="text-left">
                        <p>This will permanently delete <strong>${budgets.value.length}</strong> budget categories totaling <strong>৳${formatNumber(totalBudget.value)}</strong></p>
                        <div class="mt-3">
                            <h6>Budgets to be deleted:</h6>
                            <ul class="list-unstyled">
                                ${budgets.value.map(b => `
                                    <li class="mb-1">
                                        <i class="${b.icon || 'fas fa-chart-pie'} me-1"></i> ${b.name} - 
                                        <strong>৳${formatNumber(b.allocated_amount)}</strong>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> All budget data and forecasting information will be permanently lost. This action cannot be undone!
                        </div>
                    </div>
                `,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear all budgets!',
                cancelButtonText: 'Cancel',
                width: '600px'
            });

            if (result.isConfirmed) {
                const deletedCount = budgets.value.length;
                const deletedAmount = totalBudget.value;
                
                budgets.value.splice(0);
                
                Swal.fire({
                    title: 'All Budgets Cleared!',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                            <p><strong>${deletedCount}</strong> budgets deleted</p>
                            <p><strong>৳${formatNumber(deletedAmount)}</strong> total budget cleared</p>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                You can now create fresh budgets for your business planning!
                            </div>
                        </div>
                    `,
                    icon: null,
                    confirmButtonColor: '#28a745',
                    timer: 5000,
                    timerProgressBar: true,
                    width: '500px'
                });
            }
        };

        const exportBudgets = () => {
            if (budgets.value.length === 0) {
                Swal.fire({
                    title: 'No Data to Export',
                    text: 'There are no budgets to export',
                    icon: 'info',
                    confirmButtonColor: '#007bff'
                });
                return;
            }

            let csvContent = "Budget Name,Description,Allocated Amount (BDT),Used Amount (BDT),Remaining Amount (BDT),Usage %,Status,Next Quarter Forecast (BDT),Auto Forecast\n";
            
            budgets.value.forEach(budget => {
                const usagePercent = ((budget.used_amount / budget.allocated_amount) * 100).toFixed(1);
                csvContent += `"${budget.name}","${budget.description || ''}",${budget.allocated_amount},${budget.used_amount},${budget.remaining_amount},${usagePercent}%,"${getBudgetStatus(budget)}",${budget.forecast_next_quarter || ''},"${budget.auto_forecast ? 'Yes' : 'No'}"\n`;
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `Budget-Summary-${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            Swal.fire({
                title: 'Export Complete!',
                text: `Budget data exported to CSV file with ${budgets.value.length} categories`,
                icon: 'success',
                confirmButtonColor: '#007bff',
                timer: 3000,
                timerProgressBar: true
            });
        };

        const resetBudgetPeriod = async () => {
            const result = await Swal.fire({
                title: 'Reset Budget Period?',
                html: `
                    <div class="text-left">
                        <p>This will reset the "used amount" for all budget categories to zero, keeping the allocated amounts intact.</p>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>What happens:</strong><br>
                            • All "used amount" values reset to ৳0<br>
                            • Allocated amounts remain unchanged<br>
                            • Progress bars restart at 0%<br>
                            • Perfect for starting a new quarter
                        </div>
                        <p>Use this when starting a new budget period.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reset period!'
            });

            if (result.isConfirmed) {
                budgets.value.forEach(budget => {
                    budget.used_amount = 0;
                    budget.remaining_amount = budget.allocated_amount;
                });
                
                Swal.fire({
                    title: 'Budget Period Reset!',
                    text: 'All budget categories have been reset for the new period',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        };

        // Initialize with sample data if no budgets provided
        onMounted(() => {
            if (budgets.value.length === 0) {
                budgets.value = [
                    {
                        id: 1,
                        name: 'Technology & Software',
                        description: 'Hosting, domains, software subscriptions, development tools',
                        allocated_amount: 15000,
                        used_amount: 8500,
                        remaining_amount: 6500,
                        icon: 'fas fa-laptop',
                        forecast_next_quarter: 16500,
                        auto_forecast: 1
                    },
                    {
                        id: 2,
                        name: 'Marketing & Advertising',
                        description: 'Google Ads, Facebook Ads, content creation, networking',
                        allocated_amount: 20000,
                        used_amount: 16800,
                        remaining_amount: 3200,
                        icon: 'fas fa-bullhorn',
                        forecast_next_quarter: 25000,
                        auto_forecast: 1
                    },
                    {
                        id: 3,
                        name: 'Office & Operations',
                        description: 'Electricity, internet, phone, office supplies',
                        allocated_amount: 12000,
                        used_amount: 5400,
                        remaining_amount: 6600,
                        icon: 'fas fa-building',
                        forecast_next_quarter: 13200,
                        auto_forecast: 1
                    }
                ];
            }
        });

        return {
            selectedPeriod,
            showCreateBudget,
            editingBudget,
            saving,
            budgets,
            sortedBudgets,
            budgetForm,
            totalBudget,
            currentQuarterSpent,
            forecastNextQuarter,
            nearLimitCount,
            daysLeftInQuarter,
            formatNumber,
            getBudgetCardClass,
            getProgressBarClass,
            getStatusBadgeClass,
            getBudgetStatus,
            editBudget,
            closeModal,
            saveBudget,
            viewBudgetDetails,
            updatePeriod,
            deleteBudget,
            clearAllBudgets,
            exportBudgets,
            resetBudgetPeriod
        };
    }
}
</script>

<style scoped>
.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.entrepreneur-guide .bg-warning-light {
    background-color: #fff3cd !important;
    border-bottom: 1px solid #ffeaa7;
}

.entrepreneur-guide .category-example {
    padding: 1rem;
    border-left: 3px solid #ffc107;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

.entrepreneur-guide .example-amounts {
    margin-top: 0.5rem;
}

.stat-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    height: 100%;
    transition: all 0.3s ease;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.stat-content .stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #5a5c69;
}

.stat-content .stat-label {
    color: #858796;
    font-size: 0.9rem;
}

.budget-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
}

.budget-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.budget-card.budget-near-limit {
    border-left: 4px solid #f6c23e;
}

.budget-card.budget-over-limit {
    border-left: 4px solid #e74a3b;
}

.budget-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.budget-icon {
    width: 50px;
    height: 50px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.budget-actions {
    display: flex;
    gap: 0.5rem;
}

.budget-content h6 {
    color: #5a5c69;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.budget-description {
    color: #858796;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    min-height: 2.2rem;
    white-space: pre-line;
    word-wrap: break-word;
    line-height: 1.5;
}

.progress {
    height: 0.7rem;
    background-color: #e3e6f0;
}

.budget-details .amount-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.budget-details .label {
    color: #858796;
}

.budget-details .value {
    font-weight: 600;
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

.modal-content.large-modal {
    max-width: 800px;
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

@media (max-width: 768px) {
    .budget-actions {
        flex-direction: column;
    }
    
    .stat-card {
        text-align: center;
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>