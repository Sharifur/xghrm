<template>
    <Head title="Finance Dashboard" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Finance Dashboard
                        </h4>
                        <div class="d-flex gap-2">
                            <select v-model="selectedMonth" @change="loadMonthData" class="form-select form-select-sm" style="width: 180px;">
                                <option v-for="month in availableMonths" :key="month.value" :value="month.value">
                                    {{ month.label }}
                                </option>
                            </select>
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-download"></i> Export Data
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" @click="generateMonthlyExpenseReport">Monthly Expense Report</button></li>
                                    <li><button class="dropdown-item" @click="generateMonthlyBalanceSheet">Monthly Balance Sheet</button></li>
                                    <li><button class="dropdown-item" @click="generateFinancialSummary">Financial Summary</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item" @click="showCustomReportModal = true">Custom Export</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted mb-0">
                                    Comprehensive financial overview for {{ getCurrentMonthName() }}. Monitor your income, expenses, 
                                    assets, and generate detailed reports to track your business performance.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="last-updated">
                                    <small class="text-muted">Last Updated:</small>
                                    <div class="small text-primary">{{ formatDate(new Date()) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Financial Metrics -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="metric-card revenue-card">
                            <div class="metric-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value">৳{{ formatNumber(financialData.totalRevenue) }}</div>
                                <div class="metric-label">Total Revenue</div>
                                <div class="metric-change" :class="financialData.revenueChange >= 0 ? 'positive' : 'negative'">
                                    <i :class="financialData.revenueChange >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    {{ Math.abs(financialData.revenueChange) }}% from last month
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="metric-card expenses-card">
                            <div class="metric-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value">৳{{ formatNumber(financialData.totalExpenses) }}</div>
                                <div class="metric-label">Total Expenses</div>
                                <div class="metric-change" :class="financialData.expenseChange >= 0 ? 'negative' : 'positive'">
                                    <i :class="financialData.expenseChange >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                    {{ Math.abs(financialData.expenseChange) }}% from last month
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="metric-card profit-card">
                            <div class="metric-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value" :class="financialData.netProfit >= 0 ? 'text-success' : 'text-danger'">
                                    ৳{{ formatNumber(financialData.netProfit) }}
                                </div>
                                <div class="metric-label">Net Profit</div>
                                <div class="profit-margin">
                                    {{ ((financialData.netProfit / financialData.totalRevenue) * 100).toFixed(1) }}% margin
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="metric-card pending-card">
                            <div class="metric-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-value">৳{{ formatNumber(financialData.pendingRevenue) }}</div>
                                <div class="metric-label">Pending Payments</div>
                                <div class="pending-count">
                                    {{ financialData.pendingCount }} outstanding invoices
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Overview Charts & Quick Stats -->
                <div class="row mb-4">
                    <!-- Monthly Trend Chart -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-area me-2"></i>
                                    Monthly Financial Trend
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <div class="trend-chart">
                                        <div v-for="(month, index) in trendData" :key="index" class="trend-month">
                                            <div class="trend-bars">
                                                <div class="trend-bar revenue-bar" :style="`height: ${(month.revenue / maxAmount) * 100}%`" :title="`Revenue: ৳${formatNumber(month.revenue)}`"></div>
                                                <div class="trend-bar expense-bar" :style="`height: ${(month.expenses / maxAmount) * 100}%`" :title="`Expenses: ৳${formatNumber(month.expenses)}`"></div>
                                            </div>
                                            <div class="trend-label">{{ month.name }}</div>
                                        </div>
                                    </div>
                                    <div class="chart-legend">
                                        <div class="legend-item">
                                            <div class="legend-color revenue-color"></div>
                                            <span>Revenue</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color expense-color"></div>
                                            <span>Expenses</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Financial Stats -->
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Quick Stats
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="quick-stats">
                                    <div class="stat-item">
                                        <div class="stat-icon bg-primary">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value">৳{{ formatNumber(financialData.totalAssets) }}</div>
                                            <div class="stat-label">Total Assets</div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon bg-warning">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value">৳{{ formatNumber(financialData.totalLiabilities) }}</div>
                                            <div class="stat-label">Total Liabilities</div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon bg-info">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value">৳{{ formatNumber(financialData.totalEquity) }}</div>
                                            <div class="stat-label">Owner's Equity</div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon bg-success">
                                            <i class="fas fa-percentage"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value">{{ calculateROI().toFixed(1) }}%</div>
                                            <div class="stat-label">Return on Investment</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expense Breakdown & Recent Transactions -->
                <div class="row mb-4">
                    <!-- Expense Categories -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-donut me-2"></i>
                                    Expense Categories (This Month)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="expense-categories">
                                    <div v-for="category in expenseCategories" :key="category.name" class="category-item">
                                        <div class="category-info">
                                            <div class="category-name">
                                                <i :class="category.icon" class="me-2"></i>
                                                {{ category.name }}
                                            </div>
                                            <div class="category-amount">৳{{ formatNumber(category.amount) }}</div>
                                        </div>
                                        <div class="category-progress">
                                            <div class="progress">
                                                <div class="progress-bar" :class="category.color" :style="`width: ${(category.amount / financialData.totalExpenses) * 100}%`"></div>
                                            </div>
                                            <small class="text-muted">{{ ((category.amount / financialData.totalExpenses) * 100).toFixed(1) }}%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Recent Transactions
                                </h6>
                                <button class="btn btn-sm btn-outline-primary" @click="$inertia.visit('/admin-home/finance/expenses')">
                                    View All
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="recent-transactions">
                                    <div v-for="transaction in recentTransactions" :key="transaction.id" class="transaction-item">
                                        <div class="transaction-icon" :class="transaction.type === 'income' ? 'income' : 'expense'">
                                            <i :class="transaction.icon"></i>
                                        </div>
                                        <div class="transaction-details">
                                            <div class="transaction-description">{{ transaction.description }}</div>
                                            <small class="text-muted">{{ formatDate(transaction.date) }}</small>
                                        </div>
                                        <div class="transaction-amount" :class="transaction.type === 'income' ? 'text-success' : 'text-danger'">
                                            {{ transaction.type === 'income' ? '+' : '-' }}৳{{ formatNumber(transaction.amount) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget Overview & Alerts -->
                <div class="row mb-4">
                    <!-- Budget Status -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-wallet me-2"></i>
                                    Budget Status ({{ getCurrentMonthName() }})
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="budget-overview">
                                    <div v-for="budget in budgetStatus" :key="budget.name" class="budget-item">
                                        <div class="budget-header">
                                            <div class="budget-name">
                                                <i :class="budget.icon" class="me-2"></i>
                                                {{ budget.name }}
                                            </div>
                                            <div class="budget-amounts">
                                                <span class="used">৳{{ formatNumber(budget.used) }}</span>
                                                <span class="divider">/</span>
                                                <span class="total">৳{{ formatNumber(budget.total) }}</span>
                                            </div>
                                        </div>
                                        <div class="budget-progress">
                                            <div class="progress">
                                                <div class="progress-bar" :class="getBudgetProgressClass(budget)" :style="`width: ${Math.min((budget.used / budget.total) * 100, 100)}%`"></div>
                                            </div>
                                            <div class="budget-status">
                                                <small class="percentage">{{ Math.round((budget.used / budget.total) * 100) }}% used</small>
                                                <small class="remaining" :class="budget.remaining < 0 ? 'text-danger' : 'text-success'">
                                                    ৳{{ formatNumber(Math.abs(budget.remaining)) }} {{ budget.remaining < 0 ? 'over' : 'remaining' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Alerts -->
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Financial Alerts
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="financial-alerts">
                                    <div v-for="alert in financialAlerts" :key="alert.id" class="alert-item" :class="alert.type">
                                        <div class="alert-icon">
                                            <i :class="alert.icon"></i>
                                        </div>
                                        <div class="alert-content">
                                            <div class="alert-title">{{ alert.title }}</div>
                                            <div class="alert-message">{{ alert.message }}</div>
                                            <small class="alert-time">{{ formatDate(alert.date) }}</small>
                                        </div>
                                    </div>
                                    <div v-if="financialAlerts.length === 0" class="no-alerts">
                                        <i class="fas fa-check-circle text-success mb-2"></i>
                                        <p class="text-muted mb-0">All good! No financial alerts at this time.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Report Modal -->
                <div v-if="showCustomReportModal" class="modal-overlay" @click="closeCustomReportModal">
                    <div class="modal-content" @click.stop>
                        <div class="modal-header">
                            <h5>Generate Custom Export</h5>
                            <button @click="closeCustomReportModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="generateCustomReport">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Export Type *</label>
                                        <select v-model="customReport.type" class="form-select" required>
                                            <option value="expense">Expense Report</option>
                                            <option value="revenue">Revenue Report</option>
                                            <option value="balance">Balance Sheet</option>
                                            <option value="profit_loss">Profit & Loss</option>
                                            <option value="budget">Budget Analysis</option>
                                            <option value="cash_flow">Cash Flow Statement</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Export Period *</label>
                                        <select v-model="customReport.period" class="form-select" required>
                                            <option value="current_month">Current Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="quarter">Current Quarter</option>
                                            <option value="year">Current Year</option>
                                            <option value="custom">Custom Date Range</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="customReport.period === 'custom'" class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input v-model="customReport.startDate" type="date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input v-model="customReport.endDate" type="date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Export Format</label>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        All exports are generated in CSV format for better data compatibility.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Include</label>
                                    <div class="form-check-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="customReport.includeCharts" id="charts">
                                            <label class="form-check-label" for="charts">Charts & Graphs</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="customReport.includeComments" id="comments">
                                            <label class="form-check-label" for="comments">Analysis & Comments</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeCustomReportModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" :disabled="generatingReport">
                                        <i class="fas fa-download"></i>
                                        {{ generatingReport ? 'Generating...' : 'Export Data' }}
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
import Swal from 'sweetalert2';

export default {
    layout: AdminMaster,
    name: "FinanceDashboard",
    components: {
        Head
    },
    props: {
        financialSummary: Object
    },
    setup(props) {
        const selectedMonth = ref(new Date().toISOString().slice(0, 7)); // YYYY-MM format
        const showCustomReportModal = ref(false);
        const generatingReport = ref(false);
        
        const financialData = ref({
            totalRevenue: 125000,
            totalExpenses: 78000,
            netProfit: 47000,
            pendingRevenue: 18500,
            pendingCount: 7,
            totalAssets: 185000,
            totalLiabilities: 35000,
            totalEquity: 150000,
            revenueChange: 12.5,
            expenseChange: -8.2
        });

        const customReport = reactive({
            type: 'expense',
            period: 'current_month',
            startDate: '',
            endDate: '',
            format: 'csv',
            includeCharts: true,
            includeComments: false
        });

        const availableMonths = computed(() => {
            const months = [];
            const today = new Date();
            for (let i = 11; i >= 0; i--) {
                const date = new Date(today.getFullYear(), today.getMonth() - i, 1);
                months.push({
                    value: date.toISOString().slice(0, 7),
                    label: date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
                });
            }
            return months;
        });

        const trendData = ref([
            { name: 'Jul', revenue: 98000, expenses: 62000 },
            { name: 'Aug', revenue: 115000, expenses: 69000 },
            { name: 'Sep', revenue: 108000, expenses: 71000 },
            { name: 'Oct', revenue: 122000, expenses: 74000 },
            { name: 'Nov', revenue: 118000, expenses: 76000 },
            { name: 'Dec', revenue: 125000, expenses: 78000 }
        ]);

        const maxAmount = computed(() => {
            return Math.max(...trendData.value.flatMap(m => [m.revenue, m.expenses]));
        });

        const expenseCategories = ref([
            { name: 'Technology & Software', amount: 25000, icon: 'fas fa-laptop', color: 'bg-primary' },
            { name: 'Marketing & Advertising', amount: 18000, icon: 'fas fa-bullhorn', color: 'bg-success' },
            { name: 'Office & Operations', amount: 15000, icon: 'fas fa-building', color: 'bg-warning' },
            { name: 'Professional Services', amount: 12000, icon: 'fas fa-handshake', color: 'bg-info' },
            { name: 'Travel & Transportation', amount: 8000, icon: 'fas fa-car', color: 'bg-secondary' }
        ]);

        const recentTransactions = ref([
            { id: 1, description: 'Client Payment - Web Development', amount: 25000, type: 'income', icon: 'fas fa-arrow-down', date: new Date('2024-12-24') },
            { id: 2, description: 'Adobe Creative Cloud Subscription', amount: 1200, type: 'expense', icon: 'fas fa-arrow-up', date: new Date('2024-12-23') },
            { id: 3, description: 'Shopify App Revenue', amount: 5000, type: 'income', icon: 'fas fa-arrow-down', date: new Date('2024-12-22') },
            { id: 4, description: 'Google Ads Campaign', amount: 3500, type: 'expense', icon: 'fas fa-arrow-up', date: new Date('2024-12-21') },
            { id: 5, description: 'Webflow Template Sale', amount: 800, type: 'income', icon: 'fas fa-arrow-down', date: new Date('2024-12-20') }
        ]);

        const budgetStatus = ref([
            { name: 'Technology & Software', used: 18500, total: 25000, remaining: 6500, icon: 'fas fa-laptop' },
            { name: 'Marketing & Advertising', used: 22000, total: 20000, remaining: -2000, icon: 'fas fa-bullhorn' },
            { name: 'Office & Operations', used: 12000, total: 15000, remaining: 3000, icon: 'fas fa-building' },
            { name: 'Professional Services', used: 8500, total: 12000, remaining: 3500, icon: 'fas fa-handshake' }
        ]);

        const financialAlerts = ref([
            {
                id: 1,
                type: 'warning',
                title: 'Budget Exceeded',
                message: 'Marketing budget is 110% used this month',
                icon: 'fas fa-exclamation-triangle',
                date: new Date()
            },
            {
                id: 2,
                type: 'info',
                title: 'Overdue Payments',
                message: '3 client payments are overdue (৳8,500)',
                icon: 'fas fa-clock',
                date: new Date()
            },
            {
                id: 3,
                type: 'success',
                title: 'Revenue Target Met',
                message: 'Monthly revenue target achieved!',
                icon: 'fas fa-check-circle',
                date: new Date()
            }
        ]);

        // Helper functions
        const formatNumber = (num) => {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(num || 0);
        };

        const formatDate = (date) => {
            return new Date(date).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        };

        const getCurrentMonthName = () => {
            return new Date(selectedMonth.value + '-01').toLocaleDateString('en-US', { 
                month: 'long', 
                year: 'numeric' 
            });
        };

        const calculateROI = () => {
            return (financialData.value.netProfit / financialData.value.totalEquity) * 100;
        };

        const getBudgetProgressClass = (budget) => {
            const percentage = (budget.used / budget.total) * 100;
            if (percentage >= 100) return 'bg-danger';
            if (percentage >= 80) return 'bg-warning';
            return 'bg-success';
        };

        // Report generation functions
        const generateMonthlyExpenseReport = async () => {
            try {
                const result = await Swal.fire({
                    title: 'Generate Monthly Expense Report',
                    html: `
                        <div class="text-left">
                            <p><strong>Report Period:</strong> ${getCurrentMonthName()}</p>
                            <p><strong>Total Expenses:</strong> ৳${formatNumber(financialData.value.totalExpenses)}</p>
                            <p><strong>Categories:</strong> ${expenseCategories.value.length} expense categories</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Generate & Download CSV',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Generating Report...',
                        text: 'Please wait while we create your expense report.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Generate actual downloadable CSV report
                    await generateExpenseReportCSV();
                    
                    Swal.fire({
                        title: 'Report Downloaded!',
                        text: `Monthly expense report for ${getCurrentMonthName()} has been downloaded to your device.`,
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Error generating report:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to generate expense report. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        const generateMonthlyBalanceSheet = async () => {
            try {
                const result = await Swal.fire({
                    title: 'Generate Monthly Balance Sheet',
                    html: `
                        <div class="text-left">
                            <p><strong>Report Period:</strong> ${getCurrentMonthName()}</p>
                            <p><strong>Total Assets:</strong> ৳${formatNumber(financialData.value.totalAssets)}</p>
                            <p><strong>Total Liabilities:</strong> ৳${formatNumber(financialData.value.totalLiabilities)}</p>
                            <p><strong>Owner's Equity:</strong> ৳${formatNumber(financialData.value.totalEquity)}</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Generate & Download CSV',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Generating Balance Sheet...',
                        text: 'Please wait while we create your balance sheet report.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Generate actual downloadable CSV report
                    await generateBalanceSheetCSV();
                    
                    Swal.fire({
                        title: 'Report Downloaded!',
                        text: `Balance sheet for ${getCurrentMonthName()} has been downloaded to your device.`,
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Error generating report:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to generate balance sheet report. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        const generateFinancialSummary = async () => {
            try {
                const result = await Swal.fire({
                    title: 'Generate Financial Summary',
                    html: `
                        <div class="text-left">
                            <p><strong>Report Period:</strong> ${getCurrentMonthName()}</p>
                            <p><strong>Revenue:</strong> ৳${formatNumber(financialData.value.totalRevenue)}</p>
                            <p><strong>Expenses:</strong> ৳${formatNumber(financialData.value.totalExpenses)}</p>
                            <p><strong>Net Profit:</strong> ৳${formatNumber(financialData.value.netProfit)}</p>
                            <p><strong>Profit Margin:</strong> ${((financialData.value.netProfit / financialData.value.totalRevenue) * 100).toFixed(1)}%</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Generate & Download CSV',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Generating Financial Summary...',
                        text: 'Please wait while we create your financial summary report.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Generate actual downloadable CSV report
                    await generateFinancialSummaryCSV();
                    
                    Swal.fire({
                        title: 'Report Downloaded!',
                        text: `Financial summary for ${getCurrentMonthName()} has been downloaded to your device.`,
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Error generating report:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to generate financial summary report. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            }
        };

        const generateCustomReport = async () => {
            generatingReport.value = true;
            try {
                // Simulate custom report generation
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                // Generate CSV report based on type (all reports are now CSV only)
                let csvContent = '';
                let filename = '';
                
                switch (customReport.type) {
                    case 'expense':
                        csvContent = createExpenseCSVContent();
                        filename = `Custom-Expense-Report-${getCurrentMonthName().replace(' ', '-')}.csv`;
                        break;
                    case 'revenue':
                        csvContent = createRevenueCSVContent();
                        filename = `Custom-Revenue-Report-${getCurrentMonthName().replace(' ', '-')}.csv`;
                        break;
                    case 'balance':
                        csvContent = createBalanceSheetCSVContent();
                        filename = `Custom-Balance-Sheet-${getCurrentMonthName().replace(' ', '-')}.csv`;
                        break;
                    case 'profit_loss':
                        csvContent = createFinancialSummaryCSVContent();
                        filename = `Custom-Profit-Loss-${getCurrentMonthName().replace(' ', '-')}.csv`;
                        break;
                    case 'budget':
                        csvContent = createBudgetCSVContent();
                        filename = `Custom-Budget-Report-${getCurrentMonthName().replace(' ', '-')}.csv`;
                        break;
                    case 'cash_flow':
                        csvContent = createCashFlowCSVContent();
                        filename = `Custom-Cash-Flow-${getCurrentMonthName().replace(' ', '-')}.csv`;
                        break;
                    default:
                        csvContent = createFinancialSummaryCSVContent();
                        filename = `Custom-Report-${getCurrentMonthName().replace(' ', '-')}.csv`;
                }
                
                // Download the CSV report
                downloadCSV(csvContent, filename);
                
                closeCustomReportModal();
                Swal.fire({
                    title: 'Custom Report Generated!',
                    text: `Your ${customReport.type} report has been downloaded as CSV.`,
                    icon: 'success',
                    confirmButtonColor: '#007bff',
                    timer: 3000,
                    timerProgressBar: true
                });
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to generate custom report. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                generatingReport.value = false;
            }
        };

        // Additional CSV content generators for custom reports
        const createRevenueCSVContent = () => {
            let csvContent = "Report,Revenue Report\n";
            csvContent += `Period,${getCurrentMonthName()}\n`;
            csvContent += `Generated,${formatDate(new Date())}\n\n`;
            
            csvContent += "Revenue Summary\n";
            csvContent += "Item,Amount (BDT),Details,Currency\n";
            csvContent += `Total Revenue,${financialData.value.totalRevenue},Revenue Growth: +${Math.abs(financialData.value.revenueChange)}%,BDT\n`;
            csvContent += `Pending Revenue,${financialData.value.pendingRevenue},${financialData.value.pendingCount} outstanding invoices,BDT\n`;
            csvContent += `Revenue Growth Rate,${Math.abs(financialData.value.revenueChange)},Compared to last month,%\n`;
            
            return csvContent;
        };

        const createBudgetCSVContent = () => {
            let csvContent = "Report,Budget Analysis Report\n";
            csvContent += `Period,${getCurrentMonthName()}\n`;
            csvContent += `Generated,${formatDate(new Date())}\n\n`;
            
            csvContent += "Budget Analysis\n";
            csvContent += "Category,Used (BDT),Budget (BDT),Remaining (BDT),Status,Currency\n";
            budgetStatus.value.forEach(budget => {
                const status = budget.remaining < 0 ? 'Over Budget' : 'Under Budget';
                csvContent += `${budget.name},${budget.used},${budget.total},${Math.abs(budget.remaining)},${status},BDT\n`;
            });
            
            return csvContent;
        };

        const createCashFlowCSVContent = () => {
            let csvContent = "Report,Cash Flow Statement\n";
            csvContent += `Period,${getCurrentMonthName()}\n`;
            csvContent += `Generated,${formatDate(new Date())}\n\n`;
            
            csvContent += "Cash Inflows\n";
            csvContent += "Source,Amount (BDT),Currency\n";
            csvContent += `Revenue Received,${financialData.value.totalRevenue},BDT\n`;
            csvContent += `Other Income,${Math.round(financialData.value.totalRevenue * 0.05)},BDT\n\n`;
            
            csvContent += "Cash Outflows\n";
            csvContent += "Category,Amount (BDT),Currency\n";
            csvContent += `Operating Expenses,${financialData.value.totalExpenses},BDT\n`;
            csvContent += `Tax Payments,${Math.round(financialData.value.totalRevenue * 0.15)},BDT\n\n`;
            
            const netCashFlow = financialData.value.netProfit - (financialData.value.totalRevenue * 0.15);
            csvContent += "Net Cash Flow\n";
            csvContent += "Item,Amount (BDT),Status,Currency\n";
            csvContent += `Net Cash Flow,${Math.round(netCashFlow)},${netCashFlow >= 0 ? 'Positive' : 'Negative'},BDT\n`;
            
            return csvContent;
        };

        // HTML report generators (legacy - keeping for reference but not used)
        const createRevenueReportHTML = () => {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Revenue Report - ${getCurrentMonthName()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                        .header { text-align: center; border-bottom: 3px solid #28a745; padding-bottom: 20px; margin-bottom: 30px; }
                        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .report-title { font-size: 18px; color: #666; }
                        .report-period { font-size: 14px; color: #999; margin-top: 10px; }
                        .summary-section { margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px; }
                        .summary-item { display: flex; justify-content: space-between; margin: 10px 0; }
                        .revenue-positive { color: #28a745; font-weight: bold; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e9ecef; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="company-name">Your Company Name</div>
                        <div class="report-title">Revenue Report</div>
                        <div class="report-period">Period: ${getCurrentMonthName()}</div>
                    </div>
                    <div class="summary-section">
                        <h3>Revenue Summary</h3>
                        <div class="summary-item">
                            <span>Total Revenue:</span>
                            <span class="revenue-positive">৳${formatNumber(financialData.value.totalRevenue)}</span>
                        </div>
                        <div class="summary-item">
                            <span>Pending Revenue:</span>
                            <span>৳${formatNumber(financialData.value.pendingRevenue)}</span>
                        </div>
                        <div class="summary-item">
                            <span>Revenue Growth:</span>
                            <span class="revenue-positive">+${Math.abs(financialData.value.revenueChange)}%</span>
                        </div>
                    </div>
                    <div class="footer">
                        <p>Generated on ${formatDate(new Date())} | Revenue Report</p>
                    </div>
                </body>
                </html>
            `;
        };

        const createBudgetReportHTML = () => {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Budget Report - ${getCurrentMonthName()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                        .header { text-align: center; border-bottom: 3px solid #ffc107; padding-bottom: 20px; margin-bottom: 30px; }
                        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .report-title { font-size: 18px; color: #666; }
                        .budget-item { padding: 15px; border: 1px solid #e9ecef; margin: 10px 0; border-radius: 5px; }
                        .budget-name { font-weight: bold; margin-bottom: 10px; }
                        .budget-numbers { display: flex; justify-content: space-between; }
                        .over-budget { color: #dc3545; }
                        .under-budget { color: #28a745; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e9ecef; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="company-name">Your Company Name</div>
                        <div class="report-title">Budget Analysis Report</div>
                        <div class="report-period">Period: ${getCurrentMonthName()}</div>
                    </div>
                    ${budgetStatus.value.map(budget => `
                        <div class="budget-item">
                            <div class="budget-name">${budget.name}</div>
                            <div class="budget-numbers">
                                <span>Used: ৳${formatNumber(budget.used)}</span>
                                <span>Budget: ৳${formatNumber(budget.total)}</span>
                                <span class="${budget.remaining < 0 ? 'over-budget' : 'under-budget'}">
                                    ${budget.remaining < 0 ? 'Over' : 'Remaining'}: ৳${formatNumber(Math.abs(budget.remaining))}
                                </span>
                            </div>
                        </div>
                    `).join('')}
                    <div class="footer">
                        <p>Generated on ${formatDate(new Date())} | Budget Report</p>
                    </div>
                </body>
                </html>
            `;
        };

        const createCashFlowReportHTML = () => {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Cash Flow Report - ${getCurrentMonthName()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                        .header { text-align: center; border-bottom: 3px solid #17a2b8; padding-bottom: 20px; margin-bottom: 30px; }
                        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .report-title { font-size: 18px; color: #666; }
                        .cash-flow-section { margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px; }
                        .cash-item { display: flex; justify-content: space-between; margin: 10px 0; padding: 5px 0; border-bottom: 1px solid #e9ecef; }
                        .cash-positive { color: #28a745; }
                        .cash-negative { color: #dc3545; }
                        .net-cash-flow { font-weight: bold; font-size: 18px; padding: 15px; background: white; border-radius: 5px; margin-top: 20px; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e9ecef; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="company-name">Your Company Name</div>
                        <div class="report-title">Cash Flow Statement</div>
                        <div class="report-period">Period: ${getCurrentMonthName()}</div>
                    </div>
                    
                    <div class="cash-flow-section">
                        <h3>Cash Inflows</h3>
                        <div class="cash-item">
                            <span>Revenue Received</span>
                            <span class="cash-positive">+৳${formatNumber(financialData.value.totalRevenue)}</span>
                        </div>
                        <div class="cash-item">
                            <span>Other Income</span>
                            <span class="cash-positive">+৳${formatNumber(financialData.value.totalRevenue * 0.05)}</span>
                        </div>
                    </div>

                    <div class="cash-flow-section">
                        <h3>Cash Outflows</h3>
                        <div class="cash-item">
                            <span>Operating Expenses</span>
                            <span class="cash-negative">-৳${formatNumber(financialData.value.totalExpenses)}</span>
                        </div>
                        <div class="cash-item">
                            <span>Tax Payments</span>
                            <span class="cash-negative">-৳${formatNumber(financialData.value.totalRevenue * 0.15)}</span>
                        </div>
                    </div>

                    <div class="net-cash-flow">
                        <div class="cash-item">
                            <span>Net Cash Flow</span>
                            <span class="${financialData.value.netProfit >= 0 ? 'cash-positive' : 'cash-negative'}">
                                ${financialData.value.netProfit >= 0 ? '+' : ''}৳${formatNumber(financialData.value.netProfit - (financialData.value.totalRevenue * 0.15))}
                            </span>
                        </div>
                    </div>

                    <div class="footer">
                        <p>Generated on ${formatDate(new Date())} | Cash Flow Report</p>
                    </div>
                </body>
                </html>
            `;
        };

        // CSV Content Creation Functions
        const createExpenseCSVContent = () => {
            let csvContent = "Report,Monthly Expense Report\n";
            csvContent += `Period,${getCurrentMonthName()}\n`;
            csvContent += `Generated,${formatDate(new Date())}\n\n`;
            
            csvContent += "Summary\n";
            csvContent += "Item,Amount (BDT),Currency\n";
            csvContent += `Total Monthly Expenses,${financialData.value.totalExpenses},BDT\n`;
            csvContent += `Number of Categories,${expenseCategories.value.length},Count\n`;
            csvContent += `Average per Category,${Math.round(financialData.value.totalExpenses / expenseCategories.value.length)},BDT\n\n`;
            
            csvContent += "Expense Categories Breakdown\n";
            csvContent += "Category,Amount (BDT),Percentage,Currency\n";
            expenseCategories.value.forEach(category => {
                const percentage = ((category.amount / financialData.value.totalExpenses) * 100).toFixed(1);
                csvContent += `${category.name},${category.amount},${percentage}%,BDT\n`;
            });
            
            return csvContent;
        };

        const createBalanceSheetCSVContent = () => {
            let csvContent = "Report,Balance Sheet\n";
            csvContent += `Period,As of ${getCurrentMonthName()}\n`;
            csvContent += `Generated,${formatDate(new Date())}\n\n`;
            
            csvContent += "ASSETS\n";
            csvContent += "Item,Amount (BDT),Currency\n";
            csvContent += `Cash and Cash Equivalents,${Math.round(financialData.value.totalAssets * 0.4)},BDT\n`;
            csvContent += `Accounts Receivable,${financialData.value.pendingRevenue},BDT\n`;
            csvContent += `Equipment and Assets,${Math.round(financialData.value.totalAssets * 0.6 - financialData.value.pendingRevenue)},BDT\n`;
            csvContent += `TOTAL ASSETS,${financialData.value.totalAssets},BDT\n\n`;
            
            csvContent += "LIABILITIES\n";
            csvContent += "Item,Amount (BDT),Currency\n";
            csvContent += `Accounts Payable,${Math.round(financialData.value.totalLiabilities * 0.6)},BDT\n`;
            csvContent += `Accrued Expenses,${Math.round(financialData.value.totalLiabilities * 0.4)},BDT\n`;
            csvContent += `TOTAL LIABILITIES,${financialData.value.totalLiabilities},BDT\n\n`;
            
            csvContent += "EQUITY\n";
            csvContent += "Item,Amount (BDT),Currency\n";
            csvContent += `Owner's Capital,${Math.round(financialData.value.totalEquity * 0.7)},BDT\n`;
            csvContent += `Retained Earnings,${Math.round(financialData.value.totalEquity * 0.3)},BDT\n`;
            csvContent += `TOTAL EQUITY,${financialData.value.totalEquity},BDT\n\n`;
            
            csvContent += "Balance Equation\n";
            csvContent += "Item,Amount (BDT),Currency\n";
            csvContent += `Assets,${financialData.value.totalAssets},BDT\n`;
            csvContent += `Liabilities + Equity,${financialData.value.totalLiabilities + financialData.value.totalEquity},BDT\n`;
            csvContent += `Balanced,${financialData.value.totalAssets === (financialData.value.totalLiabilities + financialData.value.totalEquity) ? 'Yes' : 'No'},Status\n`;
            
            return csvContent;
        };

        const createFinancialSummaryCSVContent = () => {
            const profitMargin = ((financialData.value.netProfit / financialData.value.totalRevenue) * 100).toFixed(1);
            const roi = calculateROI().toFixed(1);
            
            let csvContent = "Report,Financial Summary Report\n";
            csvContent += `Period,${getCurrentMonthName()}\n`;
            csvContent += `Generated,${formatDate(new Date())}\n\n`;
            
            csvContent += "Financial Summary\n";
            csvContent += "Item,Amount (BDT),Change,Currency\n";
            csvContent += `Total Revenue,${financialData.value.totalRevenue},+${Math.abs(financialData.value.revenueChange)}%,BDT\n`;
            csvContent += `Total Expenses,${financialData.value.totalExpenses},${financialData.value.expenseChange >= 0 ? '+' : ''}${financialData.value.expenseChange}%,BDT\n`;
            csvContent += `Net Profit,${financialData.value.netProfit},${profitMargin}% margin,BDT\n`;
            csvContent += `Pending Revenue,${financialData.value.pendingRevenue},${financialData.value.pendingCount} invoices,BDT\n\n`;
            
            csvContent += "Key Performance Indicators\n";
            csvContent += "KPI,Value,Unit\n";
            csvContent += `Profit Margin,${profitMargin},%\n`;
            csvContent += `Return on Investment (ROI),${roi},%\n`;
            csvContent += `Revenue Growth Rate,${Math.abs(financialData.value.revenueChange)},%\n`;
            csvContent += `Expense Efficiency,${((financialData.value.totalExpenses / financialData.value.totalRevenue) * 100).toFixed(1)},% of revenue\n`;
            csvContent += `Cash Flow Status,${financialData.value.netProfit >= 0 ? 'Positive' : 'Negative'},Status\n`;
            
            return csvContent;
        };

        // CSV download function
        const downloadCSV = (csvContent, filename) => {
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        const closeCustomReportModal = () => {
            showCustomReportModal.value = false;
        };

        const loadMonthData = () => {
            // In real implementation, this would fetch data for the selected month
            console.log('Loading data for month:', selectedMonth.value);
            
            Swal.fire({
                title: 'Data Loaded!',
                text: `Financial data for ${getCurrentMonthName()} has been loaded.`,
                icon: 'info',
                confirmButtonColor: '#007bff',
                timer: 2000,
                timerProgressBar: true
            });
        };

        // CSV Generation Functions
        const generateExpenseReportCSV = async () => {
            await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate generation time
            
            // Create expense report CSV content
            const csvContent = createExpenseCSVContent();
            downloadCSV(csvContent, `Expense-Report-${getCurrentMonthName().replace(' ', '-')}.csv`);
        };

        const generateBalanceSheetCSV = async () => {
            await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate generation time
            
            // Create balance sheet CSV content
            const csvContent = createBalanceSheetCSVContent();
            downloadCSV(csvContent, `Balance-Sheet-${getCurrentMonthName().replace(' ', '-')}.csv`);
        };

        const generateFinancialSummaryCSV = async () => {
            await new Promise(resolve => setTimeout(resolve, 2000)); // Simulate generation time
            
            // Create financial summary CSV content
            const csvContent = createFinancialSummaryCSVContent();
            downloadCSV(csvContent, `Financial-Summary-${getCurrentMonthName().replace(' ', '-')}.csv`);
        };

        // HTML content generators for reports
        const createExpenseReportHTML = () => {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Monthly Expense Report - ${getCurrentMonthName()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                        .header { text-align: center; border-bottom: 3px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .report-title { font-size: 18px; color: #666; }
                        .report-period { font-size: 14px; color: #999; margin-top: 10px; }
                        .summary-section { margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px; }
                        .summary-item { display: flex; justify-content: space-between; margin: 10px 0; }
                        .summary-item.total { font-weight: bold; border-top: 2px solid #007bff; padding-top: 10px; margin-top: 15px; }
                        .categories-section { margin: 30px 0; }
                        .category-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #e9ecef; margin: 10px 0; border-radius: 5px; }
                        .category-name { font-weight: 500; }
                        .category-amount { font-weight: bold; color: #dc3545; }
                        .category-percentage { font-size: 12px; color: #666; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e9ecef; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="company-name">Your Company Name</div>
                        <div class="report-title">Monthly Expense Report</div>
                        <div class="report-period">Report Period: ${getCurrentMonthName()}</div>
                    </div>

                    <div class="summary-section">
                        <h3>Expense Summary</h3>
                        <div class="summary-item">
                            <span>Total Monthly Expenses:</span>
                            <span>৳${formatNumber(financialData.value.totalExpenses)}</span>
                        </div>
                        <div class="summary-item">
                            <span>Number of Categories:</span>
                            <span>${expenseCategories.value.length}</span>
                        </div>
                        <div class="summary-item">
                            <span>Average per Category:</span>
                            <span>৳${formatNumber(financialData.value.totalExpenses / expenseCategories.value.length)}</span>
                        </div>
                        <div class="summary-item total">
                            <span>Net Impact on Cash Flow:</span>
                            <span>-৳${formatNumber(financialData.value.totalExpenses)}</span>
                        </div>
                    </div>

                    <div class="categories-section">
                        <h3>Expense Categories Breakdown</h3>
                        ${expenseCategories.value.map(category => `
                            <div class="category-item">
                                <div>
                                    <div class="category-name">${category.name}</div>
                                    <div class="category-percentage">${((category.amount / financialData.value.totalExpenses) * 100).toFixed(1)}% of total expenses</div>
                                </div>
                                <div class="category-amount">৳${formatNumber(category.amount)}</div>
                            </div>
                        `).join('')}
                    </div>

                    <div class="footer">
                        <p>Generated on ${formatDate(new Date())} | Finance Dashboard Report</p>
                        <p>This report contains confidential financial information</p>
                    </div>
                </body>
                </html>
            `;
        };

        const createBalanceSheetHTML = () => {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Balance Sheet - ${getCurrentMonthName()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                        .header { text-align: center; border-bottom: 3px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .report-title { font-size: 18px; color: #666; }
                        .report-period { font-size: 14px; color: #999; margin-top: 10px; }
                        .balance-section { margin: 30px 0; }
                        .section-title { font-size: 18px; font-weight: bold; color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
                        .balance-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e9ecef; }
                        .balance-total { display: flex; justify-content: space-between; padding: 15px; background: #f8f9fa; margin: 20px 0; font-weight: bold; font-size: 16px; border-radius: 5px; }
                        .equation-section { margin: 40px 0; padding: 20px; background: #e7f3ff; border-radius: 8px; text-align: center; }
                        .equation { font-size: 18px; font-weight: bold; }
                        .balanced { color: #28a745; }
                        .unbalanced { color: #dc3545; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e9ecef; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="company-name">Your Company Name</div>
                        <div class="report-title">Balance Sheet</div>
                        <div class="report-period">As of ${getCurrentMonthName()}</div>
                    </div>

                    <div class="balance-section">
                        <div class="section-title">ASSETS</div>
                        <div class="balance-item">
                            <span>Cash and Cash Equivalents</span>
                            <span>৳${formatNumber(financialData.value.totalAssets * 0.4)}</span>
                        </div>
                        <div class="balance-item">
                            <span>Accounts Receivable</span>
                            <span>৳${formatNumber(financialData.value.pendingRevenue)}</span>
                        </div>
                        <div class="balance-item">
                            <span>Equipment and Assets</span>
                            <span>৳${formatNumber(financialData.value.totalAssets * 0.6 - financialData.value.pendingRevenue)}</span>
                        </div>
                        <div class="balance-total">
                            <span>TOTAL ASSETS</span>
                            <span>৳${formatNumber(financialData.value.totalAssets)}</span>
                        </div>
                    </div>

                    <div class="balance-section">
                        <div class="section-title">LIABILITIES</div>
                        <div class="balance-item">
                            <span>Accounts Payable</span>
                            <span>৳${formatNumber(financialData.value.totalLiabilities * 0.6)}</span>
                        </div>
                        <div class="balance-item">
                            <span>Accrued Expenses</span>
                            <span>৳${formatNumber(financialData.value.totalLiabilities * 0.4)}</span>
                        </div>
                        <div class="balance-total">
                            <span>TOTAL LIABILITIES</span>
                            <span>৳${formatNumber(financialData.value.totalLiabilities)}</span>
                        </div>
                    </div>

                    <div class="balance-section">
                        <div class="section-title">EQUITY</div>
                        <div class="balance-item">
                            <span>Owner's Capital</span>
                            <span>৳${formatNumber(financialData.value.totalEquity * 0.7)}</span>
                        </div>
                        <div class="balance-item">
                            <span>Retained Earnings</span>
                            <span>৳${formatNumber(financialData.value.totalEquity * 0.3)}</span>
                        </div>
                        <div class="balance-total">
                            <span>TOTAL EQUITY</span>
                            <span>৳${formatNumber(financialData.value.totalEquity)}</span>
                        </div>
                    </div>

                    <div class="equation-section">
                        <div class="equation balanced">
                            Assets = Liabilities + Equity
                        </div>
                        <div style="margin-top: 10px; font-size: 16px;">
                            ৳${formatNumber(financialData.value.totalAssets)} = ৳${formatNumber(financialData.value.totalLiabilities)} + ৳${formatNumber(financialData.value.totalEquity)}
                        </div>
                    </div>

                    <div class="footer">
                        <p>Generated on ${formatDate(new Date())} | Balance Sheet Report</p>
                        <p>This report contains confidential financial information</p>
                    </div>
                </body>
                </html>
            `;
        };

        const createFinancialSummaryHTML = () => {
            const profitMargin = ((financialData.value.netProfit / financialData.value.totalRevenue) * 100).toFixed(1);
            const roi = calculateROI().toFixed(1);
            
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Financial Summary - ${getCurrentMonthName()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
                        .header { text-align: center; border-bottom: 3px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
                        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                        .report-title { font-size: 18px; color: #666; }
                        .report-period { font-size: 14px; color: #999; margin-top: 10px; }
                        .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0; }
                        .summary-card { padding: 20px; border: 1px solid #e9ecef; border-radius: 8px; }
                        .card-title { font-size: 14px; color: #666; margin-bottom: 10px; }
                        .card-value { font-size: 24px; font-weight: bold; }
                        .card-value.positive { color: #28a745; }
                        .card-value.negative { color: #dc3545; }
                        .card-change { font-size: 12px; margin-top: 5px; }
                        .performance-section { margin: 40px 0; padding: 20px; background: #f8f9fa; border-radius: 8px; }
                        .performance-item { display: flex; justify-content: space-between; margin: 15px 0; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
                        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e9ecef; padding-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="company-name">Your Company Name</div>
                        <div class="report-title">Financial Summary Report</div>
                        <div class="report-period">Period: ${getCurrentMonthName()}</div>
                    </div>

                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="card-title">Total Revenue</div>
                            <div class="card-value positive">৳${formatNumber(financialData.value.totalRevenue)}</div>
                            <div class="card-change">+${Math.abs(financialData.value.revenueChange)}% from last month</div>
                        </div>
                        <div class="summary-card">
                            <div class="card-title">Total Expenses</div>
                            <div class="card-value negative">৳${formatNumber(financialData.value.totalExpenses)}</div>
                            <div class="card-change">${financialData.value.expenseChange >= 0 ? '+' : ''}${financialData.value.expenseChange}% from last month</div>
                        </div>
                        <div class="summary-card">
                            <div class="card-title">Net Profit</div>
                            <div class="card-value ${financialData.value.netProfit >= 0 ? 'positive' : 'negative'}">৳${formatNumber(financialData.value.netProfit)}</div>
                            <div class="card-change">Profit Margin: ${profitMargin}%</div>
                        </div>
                        <div class="summary-card">
                            <div class="card-title">Pending Revenue</div>
                            <div class="card-value">৳${formatNumber(financialData.value.pendingRevenue)}</div>
                            <div class="card-change">${financialData.value.pendingCount} outstanding invoices</div>
                        </div>
                    </div>

                    <div class="performance-section">
                        <h3>Key Performance Indicators</h3>
                        <div class="performance-item">
                            <span>Profit Margin</span>
                            <span>${profitMargin}%</span>
                        </div>
                        <div class="performance-item">
                            <span>Return on Investment (ROI)</span>
                            <span>${roi}%</span>
                        </div>
                        <div class="performance-item">
                            <span>Revenue Growth Rate</span>
                            <span>+${Math.abs(financialData.value.revenueChange)}%</span>
                        </div>
                        <div class="performance-item">
                            <span>Expense Efficiency</span>
                            <span>${((financialData.value.totalExpenses / financialData.value.totalRevenue) * 100).toFixed(1)}% of revenue</span>
                        </div>
                        <div class="performance-item">
                            <span>Cash Flow Status</span>
                            <span class="${financialData.value.netProfit >= 0 ? 'positive' : 'negative'}">${financialData.value.netProfit >= 0 ? 'Positive' : 'Negative'}</span>
                        </div>
                    </div>

                    <div class="footer">
                        <p>Generated on ${formatDate(new Date())} | Financial Summary Report</p>
                        <p>This report contains confidential financial information</p>
                    </div>
                </body>
                </html>
            `;
        };

        // Download function to create and download PDF
        const downloadPDF = (htmlContent, filename) => {
            // Create a blob with the HTML content
            const blob = new Blob([htmlContent], { type: 'text/html' });
            const url = URL.createObjectURL(blob);
            
            // Create a temporary anchor element and trigger download
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        return {
            selectedMonth,
            showCustomReportModal,
            generatingReport,
            financialData,
            customReport,
            availableMonths,
            trendData,
            maxAmount,
            expenseCategories,
            recentTransactions,
            budgetStatus,
            financialAlerts,
            formatNumber,
            formatDate,
            getCurrentMonthName,
            calculateROI,
            getBudgetProgressClass,
            generateMonthlyExpenseReport,
            generateMonthlyBalanceSheet,
            generateFinancialSummary,
            generateCustomReport,
            closeCustomReportModal,
            loadMonthData
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

/* Metric Cards */
.metric-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.metric-card.revenue-card {
    border-left: 4px solid #28a745;
}

.metric-card.expenses-card {
    border-left: 4px solid #dc3545;
}

.metric-card.profit-card {
    border-left: 4px solid #17a2b8;
}

.metric-card.pending-card {
    border-left: 4px solid #ffc107;
}

.metric-icon {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 50px;
    height: 50px;
    background: rgba(0,123,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #007bff;
    font-size: 1.5rem;
}

.metric-value {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.metric-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.metric-change {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.metric-change.positive {
    color: #28a745;
}

.metric-change.negative {
    color: #dc3545;
}

.profit-margin, .pending-count {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Trend Chart */
.chart-container {
    position: relative;
}

.trend-chart {
    display: flex;
    align-items: flex-end;
    height: 200px;
    gap: 1rem;
    margin-bottom: 1rem;
}

.trend-month {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
}

.trend-bars {
    display: flex;
    align-items: flex-end;
    gap: 0.25rem;
    height: 180px;
    margin-bottom: 0.5rem;
}

.trend-bar {
    width: 15px;
    border-radius: 2px 2px 0 0;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.trend-bar:hover {
    opacity: 0.8;
}

.revenue-bar {
    background: linear-gradient(to top, #28a745, #20c997);
}

.expense-bar {
    background: linear-gradient(to top, #dc3545, #fd7e14);
}

.trend-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.chart-legend {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.revenue-color {
    background: #28a745;
}

.expense-color {
    background: #dc3545;
}

/* Quick Stats */
.quick-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Expense Categories */
.expense-categories {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.category-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.category-info {
    display: flex;
    justify-content: between;
    align-items: center;
}

.category-name {
    font-weight: 500;
    color: #2c3e50;
}

.category-amount {
    font-weight: bold;
    color: #28a745;
}

.category-progress {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.progress {
    flex: 1;
    height: 0.5rem;
    background: #e9ecef;
}

/* Recent Transactions */
.recent-transactions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.transaction-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    transition: background-color 0.3s ease;
}

.transaction-item:hover {
    background-color: #f8f9fa;
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.transaction-icon.income {
    background: #28a745;
}

.transaction-icon.expense {
    background: #dc3545;
}

.transaction-details {
    flex: 1;
}

.transaction-description {
    font-weight: 500;
    color: #2c3e50;
    font-size: 0.9rem;
}

.transaction-amount {
    font-weight: bold;
    font-size: 1rem;
}

/* Budget Overview */
.budget-overview {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.budget-item {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.budget-header {
    display: flex;
    justify-content: between;
    align-items: center;
}

.budget-name {
    font-weight: 500;
    color: #2c3e50;
}

.budget-amounts {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: bold;
}

.used {
    color: #dc3545;
}

.total {
    color: #6c757d;
}

.divider {
    color: #6c757d;
}

.budget-progress .progress {
    height: 0.75rem;
    margin-bottom: 0.25rem;
}

.budget-status {
    display: flex;
    justify-content: between;
    align-items: center;
    font-size: 0.8rem;
}

/* Financial Alerts */
.financial-alerts {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.alert-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid;
}

.alert-item.warning {
    background: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-item.info {
    background: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-item.success {
    background: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    margin-top: 0.125rem;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.alert-message {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.alert-time {
    font-size: 0.75rem;
    opacity: 0.7;
}

.no-alerts {
    text-align: center;
    padding: 2rem;
}

.no-alerts i {
    font-size: 2rem;
}

/* Modal Styles */
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

.form-check-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Enhanced Mobile and Tablet Responsive Styles */
@media (max-width: 768px) {
    .metric-icon {
        position: static;
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }
    
    .trend-chart {
        height: 150px;
        gap: 0.5rem;
    }
    
    .trend-bars {
        height: 130px;
    }
    
    .trend-bar {
        width: 12px;
    }
    
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
    
    .metric-card {
        margin-bottom: 1.5rem;
    }
    
    .metric-value {
        font-size: 1.5rem;
    }
    
    .quick-stats .stat-item {
        padding: 0.5rem;
        gap: 0.5rem;
    }
    
    .stat-icon {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .recent-transactions .transaction-item {
        padding: 0.5rem;
        gap: 0.5rem;
    }
    
    .transaction-icon {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
    
    .budget-overview .budget-item {
        gap: 0.5rem;
    }
    
    .modal-content {
        margin: 10px;
        width: calc(100% - 20px);
        max-height: calc(100vh - 20px);
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .form-check-group {
        flex-direction: column;
        gap: 0.5rem;
    }
}

/* Tablet Responsive Styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .btn-group {
        gap: 8px;
    }
    
    .metric-value {
        font-size: 1.6rem;
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
    
    .btn {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }
    
    .metric-card {
        padding: 1rem;
    }
    
    .metric-value {
        font-size: 1.3rem;
    }
    
    .metric-label {
        font-size: 0.8rem;
    }
    
    .alert {
        font-size: 0.85rem;
        padding: 0.75rem;
    }
    
    .form-control, .form-select {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}
</style>