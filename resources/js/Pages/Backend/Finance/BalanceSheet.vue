<template>
    <Head title="Balance Sheet" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-balance-scale me-2"></i>
                            Balance Sheet
                        </h4>
                        <div class="d-flex gap-2">
                            <input 
                                type="month" 
                                v-model="currentDate" 
                                @change="loadMonth"
                                class="form-control form-control-sm"
                                style="width: 150px;"
                            >
                            <button @click="generateForecast" class="btn btn-warning btn-sm" :disabled="forecastLoading">
                                <i class="fas fa-crystal-ball"></i> 
                                {{ forecastLoading ? 'Generating...' : 'Generate Forecast' }}
                            </button>
                            <button @click="exportToCsv" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                            <button @click="printView" class="btn btn-info btn-sm">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="balance-status">
                            <div v-if="isBalanced" class="alert alert-success d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Balanced!</strong> Assets = Liabilities + Equity (৳{{ formatNumber(totals.assets) }})
                            </div>
                            <div v-else class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Not Balanced!</strong> 
                                Assets (৳{{ formatNumber(totals.assets) }}) ≠ Liabilities + Equity (৳{{ formatNumber(totals.liabilitiesEquity) }})
                                <br>
                                <small>Difference: ৳{{ formatNumber(Math.abs(totals.assets - totals.liabilitiesEquity)) }}</small>
                            </div>
                        </div>

                        <!-- Monthly Summary Panel -->
                        <div v-if="dynamicData && monthlyExpenses" class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Monthly Financial Summary ({{ currentDate }})
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Expense Breakdown by Category</h6>
                                        <div v-if="monthlyExpenses.length > 0" class="expense-summary">
                                            <div v-for="expense in monthlyExpenses" :key="expense.category" class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-secondary me-2">{{ expense.category }}</span>
                                                <span>
                                                    <strong>৳{{ formatNumber(expense.total_amount) }}</strong>
                                                    <small class="text-muted">({{ expense.count }} items)</small>
                                                </span>
                                            </div>
                                        </div>
                                        <div v-else class="text-muted">No expenses recorded for this month</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-success">Financial Overview</h6>
                                        <div class="financial-overview">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>One-time Expenses:</span>
                                                <span class="text-danger">৳{{ formatNumber(dynamicData.one_time_expenses) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Recurring Expenses:</span>
                                                <span class="text-warning">৳{{ formatNumber(dynamicData.recurring_expenses) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total Assets:</span>
                                                <span class="text-primary">৳{{ formatNumber(dynamicData.total_assets) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total Equity:</span>
                                                <span class="text-info">৳{{ formatNumber(dynamicData.total_equity) }}</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <strong>Net Position:</strong>
                                                <strong :class="dynamicData.net_position >= 0 ? 'text-success' : 'text-danger'">
                                                    ৳{{ formatNumber(dynamicData.net_position) }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Forecast Panel -->
                        <div v-if="showForecastPanel && balanceData.forecast_data && Object.keys(balanceData.forecast_data).length > 0" class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Forecast Data (Based on Previous 6 Months)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div v-for="(forecast, name) in balanceData.forecast_data" :key="name" class="col-md-6 mb-3">
                                        <div class="forecast-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ name }}</strong>
                                                    <small class="text-muted d-block">{{ forecast.type }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <div class="forecast-amount">৳{{ formatNumber(forecast.average) }}</div>
                                                    <div class="confidence-badge" :class="getConfidenceBadgeClass(forecast.confidence)">
                                                        {{ Math.round(forecast.confidence) }}% confidence
                                                    </div>
                                                </div>
                                                <button 
                                                    @click="applyForecastItem(name)" 
                                                    class="btn btn-sm btn-outline-primary ms-2"
                                                    :disabled="forecastLoading"
                                                >
                                                    Apply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button @click="applyAllForecast" class="btn btn-primary" :disabled="forecastLoading">
                                        <i class="fas fa-magic"></i> Apply All Forecasts
                                    </button>
                                    <button @click="closeForecastPanel" class="btn btn-secondary ms-2">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solo Entrepreneur Balance Sheet Guide -->
                <div class="card mb-4 entrepreneur-guide">
                    <div class="card-header bg-primary-light">
                        <h6 class="text-primary mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            Solo Entrepreneur Balance Sheet Understanding Guide
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-coins me-2"></i>
                                    Assets (What You Own)
                                </h6>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Cash:</strong> Business bank accounts, PayPal, Stripe
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Equipment:</strong> Laptop, camera, software licenses
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Receivables:</strong> Money clients owe you
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Digital Assets:</strong> Domains, templates, apps
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-danger mb-3">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Liabilities (What You Owe)
                                </h6>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-danger me-2"></i>
                                        <strong>Credit Cards:</strong> Business credit card debt
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-danger me-2"></i>
                                        <strong>Loans:</strong> Equipment loans, lines of credit
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-danger me-2"></i>
                                        <strong>Payables:</strong> Unpaid invoices to vendors
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-danger me-2"></i>
                                        <strong>Subscriptions:</strong> Outstanding software payments
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Equity (Your Ownership)
                                </h6>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-plus text-info me-2"></i>
                                        <strong>Initial Investment:</strong> Money you put in
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-plus text-info me-2"></i>
                                        <strong>Retained Earnings:</strong> Profits kept in business
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-warning me-2"></i>
                                        <strong>Owner Draws:</strong> Money you've taken out
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-minus text-warning me-2"></i>
                                        <strong>Losses:</strong> Business losses over time
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>
                                        <i class="fas fa-balance-scale me-2"></i>The Balance Sheet Equation:
                                    </strong>
                                    <br>
                                    <code class="text-dark">Assets = Liabilities + Equity</code>
                                    <br>
                                    <small class="mt-2 d-block">This must always balance! It shows that everything your business owns is either financed by debt (liabilities) or your own investment (equity).</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <strong>
                                        <i class="fas fa-lightbulb me-2"></i>Why This Matters:
                                    </strong>
                                    <ul class="mb-0 mt-2 small">
                                        <li><strong>Track Growth:</strong> See your business value increase over time</li>
                                        <li><strong>Financial Health:</strong> Monitor debt-to-equity ratios</li>
                                        <li><strong>Tax Preparation:</strong> Accurate records for accountant</li>
                                        <li><strong>Loan Applications:</strong> Banks need balance sheet data</li>
                                        <li><strong>Sell Your Business:</strong> Know your true business worth</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <strong>
                                        <i class="fas fa-star me-2"></i>Pro Tips for Solo Entrepreneurs:
                                    </strong>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <ul class="mb-0 small">
                                                <li><strong>Monthly Review:</strong> Check your balance sheet monthly</li>
                                                <li><strong>Separate Accounts:</strong> Keep business and personal finances separate</li>
                                                <li><strong>Track Everything:</strong> Even small assets and debts matter</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0 small">
                                                <li><strong>Use the Data:</strong> This page shows real data from your expenses and assets</li>
                                                <li><strong>Plan Ahead:</strong> Use forecasting to predict future financial position</li>
                                                <li><strong>Stay Balanced:</strong> If it doesn't balance, investigate the differences</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Sheet Content - Read Only Display -->
                <div class="row">
                    <!-- Assets Section -->
                    <div class="col-lg-4 mb-4">
                        <div class="balance-section assets-section">
                            <div class="section-header">
                                <h5><i class="fas fa-coins me-2"></i>ASSETS</h5>
                                <small class="text-muted">Things we own</small>
                                <div class="section-total">৳{{ formatNumber(totals.assets) }}</div>
                            </div>
                            <div class="section-content">
                                <div v-for="(asset, index) in balanceData.assets" :key="index" class="balance-item readonly-item">
                                    <div class="item-display">
                                        <div class="item-name">
                                            <i :class="asset.icon || 'fas fa-coins'" class="me-2 text-primary"></i>
                                            {{ asset.name }}
                                            <span v-if="asset.tooltip" class="info-tooltip" :title="asset.tooltip">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </div>
                                        <div class="item-amount">
                                            <span class="original-amount">{{ asset.formatted_amount }}</span>
                                            <span v-if="asset.converted_amount" class="converted-amount">
                                                {{ asset.converted_amount }}
                                            </span>
                                            <div class="bdt-amount">৳{{ formatNumber(asset.bdt_amount) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="balanceData.assets.length === 0" class="empty-state">
                                    <i class="fas fa-coins text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No assets found</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liabilities Section -->
                    <div class="col-lg-4 mb-4">
                        <div class="balance-section liabilities-section">
                            <div class="section-header">
                                <h5><i class="fas fa-credit-card me-2"></i>LIABILITIES</h5>
                                <small class="text-muted">Things we owe</small>
                                <div class="section-total">৳{{ formatNumber(totals.liabilities) }}</div>
                            </div>
                            <div class="section-content">
                                <div v-for="(liability, index) in balanceData.liabilities" :key="index" class="balance-item readonly-item">
                                    <div class="item-display">
                                        <div class="item-name">
                                            <i :class="liability.icon || 'fas fa-credit-card'" class="me-2 text-warning"></i>
                                            {{ liability.name }}
                                            <span v-if="liability.tooltip" class="info-tooltip" :title="liability.tooltip">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </div>
                                        <div class="item-amount">
                                            <span class="original-amount">{{ liability.formatted_amount }}</span>
                                            <span v-if="liability.converted_amount" class="converted-amount">
                                                {{ liability.converted_amount }}
                                            </span>
                                            <div class="bdt-amount">৳{{ formatNumber(liability.bdt_amount) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="balanceData.liabilities.length === 0" class="empty-state">
                                    <i class="fas fa-credit-card text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No liabilities found</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equity Section -->
                    <div class="col-lg-4 mb-4">
                        <div class="balance-section equity-section">
                            <div class="section-header">
                                <h5><i class="fas fa-chart-line me-2"></i>EQUITY</h5>
                                <small class="text-muted">Company value</small>
                                <div class="section-total">৳{{ formatNumber(totals.equity) }}</div>
                            </div>
                            <div class="section-content">
                                <div v-for="(equity, index) in balanceData.equity" :key="index" class="balance-item readonly-item">
                                    <div class="item-display">
                                        <div class="item-name">
                                            <i :class="equity.icon || 'fas fa-chart-line'" class="me-2 text-success"></i>
                                            {{ equity.name }}
                                            <span v-if="equity.tooltip" class="info-tooltip" :title="equity.tooltip">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </div>
                                        <div class="item-amount">
                                            <span class="original-amount">{{ equity.formatted_amount }}</span>
                                            <span v-if="equity.converted_amount" class="converted-amount">
                                                {{ equity.converted_amount }}
                                            </span>
                                            <div class="bdt-amount">৳{{ formatNumber(equity.bdt_amount) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="balanceData.equity.length === 0" class="empty-state">
                                    <i class="fas fa-chart-line text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No equity found</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <h3 class="text-success">৳{{ formatNumber(totals.assets) }}</h3>
                                        <p class="mb-0">Total Assets</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h3 class="text-warning">৳{{ formatNumber(totals.liabilities) }}</h3>
                                        <p class="mb-0">Total Liabilities</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h3 class="text-info">৳{{ formatNumber(totals.equity) }}</h3>
                                        <p class="mb-0">Total Equity</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-12">
                                        <h4>Balance Check: Assets = Liabilities + Equity</h4>
                                        <h2 :class="isBalanced ? 'text-success' : 'text-danger'">
                                            ৳{{ formatNumber(totals.assets) }} = ৳{{ formatNumber(totals.liabilitiesEquity) }}
                                            <i :class="isBalanced ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger'" class="ms-2"></i>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Status -->
                <div class="text-center mt-3">
                    <small v-if="lastSaved" class="text-muted">
                        <i class="fas fa-save"></i> Auto-saved at {{ lastSaved }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import { Head } from '@inertiajs/inertia-vue3';
import { ref, reactive, computed, onMounted, watch } from 'vue';
import axios from 'axios';

export default {
    layout: AdminMaster,
    name: "BalanceSheet",
    components: {
        Head
    },
    props: {
        currentDate: String,
        balanceSheetData: Object,
        categories: Array,
        monthlyExpenses: Array,
        dynamicData: Object
    },
    setup(props) {
        const currentDate = ref(props.currentDate);
        const balanceData = reactive(JSON.parse(JSON.stringify(props.balanceSheetData)));
        const monthlyExpenses = ref(props.monthlyExpenses || []);
        const dynamicData = ref(props.dynamicData || {});
        const lastSaved = ref('');
        const saveTimeout = ref(null);
        const showForecastPanel = ref(false);
        const forecastLoading = ref(false);

        const totals = reactive({
            assets: 0,
            liabilities: 0,
            equity: 0,
            liabilitiesEquity: 0
        });

        const isBalanced = computed(() => {
            return Math.abs(totals.assets - totals.liabilitiesEquity) < 0.01;
        });

        const calculateTotals = () => {
            totals.assets = balanceData.assets.reduce((sum, item) => sum + (Number(item.amount) || 0), 0);
            totals.liabilities = balanceData.liabilities.reduce((sum, item) => sum + (Number(item.amount) || 0), 0);
            totals.equity = balanceData.equity.reduce((sum, item) => sum + (Number(item.amount) || 0), 0);
            totals.liabilitiesEquity = totals.liabilities + totals.equity;
        };

        const formatNumber = (num) => {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num || 0);
        };


        const loadMonth = async () => {
            try {
                const response = await axios.get(route('admin.finance.balance.sheet.load', currentDate.value));
                Object.assign(balanceData, response.data.balanceSheetData);
                monthlyExpenses.value = response.data.monthlyExpenses || [];
                dynamicData.value = response.data.dynamicData || {};
                calculateTotals();
            } catch (error) {
                console.error('Load failed:', error);
            }
        };

        const exportToCsv = () => {
            window.open(route('admin.finance.balance.sheet.export', currentDate.value));
        };

        const printView = () => {
            window.print();
        };

        const generateForecast = async () => {
            forecastLoading.value = true;
            try {
                const response = await axios.get(route('admin.finance.balance.sheet.forecast', currentDate.value));
                if (response.data.success) {
                    balanceData.forecast_data = response.data.forecast_data;
                    showForecastPanel.value = true;
                }
            } catch (error) {
                console.error('Forecast generation failed:', error);
                alert('Failed to generate forecast. Please try again.');
            } finally {
                forecastLoading.value = false;
            }
        };

        const applyForecastItem = async (itemName) => {
            try {
                const response = await axios.post(route('admin.finance.balance.sheet.apply.forecast'), {
                    date: currentDate.value,
                    items: [itemName]
                });

                if (response.data.success) {
                    Object.assign(balanceData, response.data.balanceSheet);
                    calculateTotals();
                    lastSaved.value = new Date().toLocaleTimeString();
                }
            } catch (error) {
                console.error('Apply forecast failed:', error);
                alert('Failed to apply forecast. Please try again.');
            }
        };

        const applyAllForecast = async () => {
            const itemNames = Object.keys(balanceData.forecast_data || {});
            if (itemNames.length === 0) return;

            try {
                const response = await axios.post(route('admin.finance.balance.sheet.apply.forecast'), {
                    date: currentDate.value,
                    items: itemNames
                });

                if (response.data.success) {
                    Object.assign(balanceData, response.data.balanceSheet);
                    calculateTotals();
                    lastSaved.value = new Date().toLocaleTimeString();
                    showForecastPanel.value = false;
                }
            } catch (error) {
                console.error('Apply all forecast failed:', error);
                alert('Failed to apply all forecasts. Please try again.');
            }
        };

        const closeForecastPanel = () => {
            showForecastPanel.value = false;
        };

        const getConfidenceBadgeClass = (confidence) => {
            if (confidence >= 80) return 'badge bg-success';
            if (confidence >= 60) return 'badge bg-warning';
            return 'badge bg-danger';
        };

        onMounted(() => {
            calculateTotals();
        });

        watch([() => balanceData.assets, () => balanceData.liabilities, () => balanceData.equity], () => {
            calculateTotals();
        }, { deep: true });

        return {
            currentDate,
            balanceData,
            totals,
            isBalanced,
            lastSaved,
            showForecastPanel,
            forecastLoading,
            monthlyExpenses,
            dynamicData,
            calculateTotals,
            formatNumber,
            loadMonth,
            exportToCsv,
            printView,
            generateForecast,
            applyForecastItem,
            applyAllForecast,
            closeForecastPanel,
            getConfidenceBadgeClass
        };
    }
}
</script>

<style scoped>
.balance-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
}

.assets-section {
    border-left: 4px solid #28a745;
}

.liabilities-section {
    border-left: 4px solid #ffc107;
}

.equity-section {
    border-left: 4px solid #17a2b8;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f8f9fc;
}

.section-header h5 {
    margin: 0;
    color: #5a5c69;
    font-weight: bold;
}

.add-item-btn {
    border-radius: 50%;
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.balance-item {
    margin-bottom: 15px;
    padding: 15px;
    background: #f8f9fc;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}

.item-header {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.item-name-input {
    font-weight: 600;
    color: #5a5c69;
    border: none;
    background: transparent;
    font-size: 14px;
}

.item-name-input:focus {
    background: white;
    border: 1px solid #4e73df;
}

.remove-btn {
    min-width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-input-group {
    display: flex;
    align-items: center;
    gap: 5px;
}

.currency-symbol {
    font-weight: bold;
    color: #5a5c69;
    font-size: 18px;
}

.amount-input {
    font-size: 18px;
    font-weight: bold;
    text-align: right;
    border: 1px solid #d1d3e2;
    padding: 10px;
    border-radius: 5px;
}

.amount-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.tooltip-icon {
    color: #4e73df;
    cursor: help;
    font-size: 14px;
}

.section-total {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #4e73df;
    text-align: center;
    font-size: 16px;
    color: #5a5c69;
}

.balance-status {
    margin-bottom: 20px;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.25rem;
}

.card-body {
    padding: 1.25rem;
}

/* Print Styles */
@media print {
    .btn, .add-item-btn, .remove-btn {
        display: none !important;
    }
    
    .balance-section {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .item-input-group input {
        border: none;
        border-bottom: 1px solid #000;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .item-header {
        flex-direction: column;
        gap: 5px;
    }
    
    .remove-btn {
        align-self: flex-end;
    }
}

/* Forecast Panel Styles */
.forecast-item {
    background: #f8f9fc;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.forecast-item:hover {
    background: #e3e6f0;
}

.forecast-amount {
    font-size: 1.2rem;
    font-weight: bold;
    color: #5a5c69;
}

.confidence-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

.badge.bg-success {
    background-color: #1cc88a !important;
}

.badge.bg-warning {
    background-color: #f6c23e !important;
    color: #333 !important;
}

.badge.bg-danger {
    background-color: #e74a3b !important;
}

/* Enhanced item styles for recurring items */
.balance-item.recurring {
    border-left: 4px solid #17a2b8;
}

.balance-item.has-forecast {
    position: relative;
}

.balance-item.has-forecast::after {
    content: '📈';
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 12px;
}

/* Solo Entrepreneur Guide Styles */
.entrepreneur-guide .bg-primary-light {
    background-color: #cce7ff !important;
    border-bottom: 1px solid #99d3ff;
}

/* Responsive forecast panel */
@media (max-width: 768px) {
    .forecast-item {
        margin-bottom: 1rem;
    }
    
    .forecast-item .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .forecast-item .text-end {
        margin-top: 0.5rem;
        text-align: left !important;
    }
    
    .forecast-item button {
        margin-top: 0.5rem;
        margin-left: 0 !important;
    }
}

/* Read-only Balance Sheet Styles */
.readonly-item {
    background: #f8f9fc;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.readonly-item:hover {
    background: #e3e6f0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.item-display {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.item-name {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #5a5c69;
    flex: 1;
}

.info-tooltip {
    margin-left: 5px;
    color: #858796;
    cursor: help;
}

.item-amount {
    text-align: right;
    min-width: 120px;
}

.original-amount {
    font-size: 16px;
    font-weight: bold;
    color: #5a5c69;
}

.converted-amount {
    display: block;
    font-size: 12px;
    color: #858796;
    margin-top: 2px;
}

.bdt-amount {
    font-size: 18px;
    font-weight: bold;
    color: #1cc88a;
    margin-top: 5px;
}

.section-total {
    font-size: 18px;
    font-weight: bold;
    color: #5a5c69;
    background: #e3e6f0;
    padding: 10px 15px;
    border-radius: 6px;
    margin-top: 10px;
    text-align: center;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #858796;
}

/* Enhanced expense summary styles */
.expense-summary {
    max-height: 300px;
    overflow-y: auto;
}

.financial-overview {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}

@media (max-width: 768px) {
    .item-display {
        flex-direction: column;
        gap: 10px;
    }
    
    .item-amount {
        text-align: left;
        min-width: auto;
    }
    
    .section-total {
        font-size: 16px;
    }
}
</style>