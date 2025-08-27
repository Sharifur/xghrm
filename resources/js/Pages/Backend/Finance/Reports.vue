<template>
    <Head title="Revenue Tracking & Client Forecasting" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Revenue Tracking & Client Forecasting
                        </h4>
                        <div class="d-flex gap-2">
                            <select v-model="selectedPeriod" @change="updatePeriod" class="form-select form-select-sm" style="width: 150px;">
                                <option value="current">This Month</option>
                                <option value="quarter">This Quarter</option>
                                <option value="year">This Year</option>
                            </select>
                            <button @click="showAddClient = true" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Add Client
                            </button>
                            <button @click="showAddRevenue = true" class="btn btn-primary">
                                <i class="fas fa-dollar-sign"></i> Log Revenue
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted mb-0">
                                    Track your revenue streams from different services like Webflow templates, Shopify apps, 
                                    and recurring revenue. Monitor pending payments and forecast upcoming earnings.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="revenue-summary">
                                    <div class="total-revenue">
                                        <small class="text-muted">This Month Revenue</small>
                                        <div class="h5 mb-0 text-success">৳{{ formatNumber(monthlyRevenue) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solo Entrepreneur Revenue Management Guide -->
                <div class="card mb-4 entrepreneur-guide">
                    <div class="card-header bg-success-light">
                        <h6 class="text-success mb-0">
                            <button 
                                class="btn btn-link text-success p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#revenueGuide" 
                                aria-expanded="false" 
                                aria-controls="revenueGuide"
                            >
                                <i class="fas fa-lightbulb me-2"></i>
                                Solo Entrepreneur Revenue & Client Management Guide
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h6>
                    </div>
                    <div id="revenueGuide" class="collapse">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-question-circle me-2"></i>
                                    Why Track Revenue by Client & Service?
                                </h6>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Identify Best Clients:</strong> See who pays the most consistently
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Service Performance:</strong> Know which services are most profitable
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Predict Cash Flow:</strong> Forecast upcoming payments and revenue
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <strong>Plan Growth:</strong> Focus on high-value services and clients
                                    </li>
                                </ul>

                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-clock me-2"></i>
                                    Pending Payment Forecasting
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Track invoices sent but not yet paid
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Predict when payments will arrive
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Plan for cash flow gaps
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-info me-2"></i>
                                        Follow up on overdue payments
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Solo Business Revenue Streams
                                </h6>
                                <div class="revenue-streams">
                                    <div class="stream-example mb-3">
                                        <strong class="text-success d-block mb-2">
                                            <i class="fas fa-globe me-2"></i>Webflow Templates
                                        </strong>
                                        <small class="text-muted d-block">
                                            One-time sales, license fees, custom template development
                                        </small>
                                        <div class="example-amounts mt-1">
                                            <span class="badge bg-light text-dark">$50 - $500 each</span>
                                            <span class="badge bg-light text-dark">Recurring licenses</span>
                                        </div>
                                    </div>
                                    <div class="stream-example mb-3">
                                        <strong class="text-info d-block mb-2">
                                            <i class="fas fa-shopping-cart me-2"></i>Shopify Apps
                                        </strong>
                                        <small class="text-muted d-block">
                                            Monthly subscriptions, app store revenue, custom development
                                        </small>
                                        <div class="example-amounts mt-1">
                                            <span class="badge bg-light text-dark">$10-50/month</span>
                                            <span class="badge bg-light text-dark">Recurring MRR</span>
                                        </div>
                                    </div>
                                    <div class="stream-example mb-3">
                                        <strong class="text-warning d-block mb-2">
                                            <i class="fas fa-handshake me-2"></i>Client Services
                                        </strong>
                                        <small class="text-muted d-block">
                                            Web development, consulting, maintenance contracts, design work
                                        </small>
                                        <div class="example-amounts mt-1">
                                            <span class="badge bg-light text-dark">$500-5000</span>
                                            <span class="badge bg-light text-dark">Project-based</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-success mt-3">
                                    <strong>
                                        <i class="fas fa-star me-2"></i>Pro Revenue Tips:
                                    </strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Set up automatic payment reminders for clients</li>
                                        <li>Offer payment terms that favor faster payments (2/10 net 30)</li>
                                        <li>Diversify revenue streams to reduce dependency on single clients</li>
                                        <li>Track monthly recurring revenue (MRR) vs one-time payments</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Overview Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">৳{{ formatNumber(totalRevenue) }}</div>
                                <div class="stat-label">Total Revenue</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-warning">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">৳{{ formatNumber(pendingRevenue) }}</div>
                                <div class="stat-label">Pending Payments</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon bg-primary">
                                <i class="fas fa-crystal-ball"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">৳{{ formatNumber(forecastRevenue) }}</div>
                                <div class="stat-label">Next Month Forecast</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Streams and Clients -->
                <div class="row">
                    <!-- Revenue by Service Type -->
                    <div class="col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    Revenue by Service Type
                                </h6>
                            </div>
                            <div class="card-body">
                                <div v-for="service in revenueByService" :key="service.type" class="service-revenue mb-3">
                                    <div class="service-header d-flex justify-content-between align-items-center mb-2">
                                        <div class="service-info">
                                            <i :class="service.icon" class="me-2"></i>
                                            <strong>{{ service.type }}</strong>
                                            <span class="badge bg-secondary ms-2">{{ service.count }} items</span>
                                        </div>
                                        <div class="service-amount">৳{{ formatNumber(service.amount) }}</div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div 
                                            class="progress-bar" 
                                            :class="service.color"
                                            :style="`width: ${(service.amount / totalRevenue) * 100}%`"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Pending Payments Forecast -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    Pending Payments Forecast - Next 90 Days
                                </h6>
                                <div class="header-actions">
                                    <button @click="clearAllPendingPayments" class="btn btn-outline-danger btn-sm me-2" :disabled="pendingPayments.length === 0">
                                        <i class="fas fa-trash-alt me-1"></i>
                                        Clear All
                                    </button>
                                    <button @click="exportPendingPayments" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>
                                        Export CSV
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div v-for="payment in pendingPayments" :key="payment.id" class="col-lg-4 col-md-6 mb-4">
                                        <div class="pending-payment-card" :class="getPaymentCardClass(payment)">
                                            <div class="payment-header">
                                                <div class="payment-client">
                                                    <i class="fas fa-user-circle me-2"></i>
                                                    <strong>{{ payment.client_name }}</strong>
                                                </div>
                                                <div class="payment-amount">৳{{ formatNumber(payment.amount) }}</div>
                                            </div>
                                            <div class="payment-details">
                                                <div class="payment-service">
                                                    <i :class="payment.service_icon" class="me-2"></i>
                                                    {{ payment.service_type }}
                                                </div>
                                                <div class="payment-date">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    Expected: {{ payment.expected_date }}
                                                    <span class="badge ms-2" :class="getPaymentBadgeClass(payment)">
                                                        {{ getPaymentStatus(payment) }}
                                                    </span>
                                                </div>
                                                <div class="payment-notes" v-if="payment.notes">
                                                    <i class="fas fa-sticky-note me-2"></i>
                                                    <small class="text-muted">{{ payment.notes }}</small>
                                                </div>
                                            </div>
                                            <div class="payment-actions mt-2">
                                                <button @click="markPaid(payment)" class="btn btn-sm btn-success me-2">
                                                    <i class="fas fa-check"></i> Mark Paid
                                                </button>
                                                <button @click="sendReminder(payment)" class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="fas fa-bell"></i> Remind
                                                </button>
                                                <button @click="removePendingPayment(payment)" class="btn btn-sm btn-outline-danger" title="Remove this pending payment">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div v-if="pendingPayments.length === 0" class="col-12">
                                        <div class="text-center py-5">
                                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                                            <h5 class="text-muted">All Payments Up to Date!</h5>
                                            <p class="text-muted">No pending payments found. Great job keeping on top of collections!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Client Modal -->
                <div v-if="showAddClient" class="modal-overlay" @click="closeClientModal">
                    <div class="modal-content" @click.stop>
                        <div class="modal-header">
                            <h5>Add New Client</h5>
                            <button @click="closeClientModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveClient">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Client Name *</label>
                                        <input 
                                            v-model="clientForm.name" 
                                            type="text" 
                                            class="form-control" 
                                            required 
                                            placeholder="Company or Person Name"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input 
                                            v-model="clientForm.email" 
                                            type="email" 
                                            class="form-control" 
                                            placeholder="client@company.com"
                                        >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input 
                                            v-model="clientForm.phone" 
                                            type="text" 
                                            class="form-control" 
                                            placeholder="+1234567890"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Terms</label>
                                        <select v-model="clientForm.payment_terms" class="form-select">
                                            <option value="net_15">Net 15 days</option>
                                            <option value="net_30">Net 30 days</option>
                                            <option value="net_60">Net 60 days</option>
                                            <option value="immediate">Immediate</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea 
                                        v-model="clientForm.notes" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Client preferences, project details, etc."
                                    ></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeClientModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success" :disabled="saving">
                                        <i class="fas fa-save"></i>
                                        {{ saving ? 'Saving...' : 'Save Client' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add Revenue Modal -->
                <div v-if="showAddRevenue" class="modal-overlay" @click="closeRevenueModal">
                    <div class="modal-content large-modal" @click.stop>
                        <div class="modal-header">
                            <h5>Log Revenue / Pending Payment</h5>
                            <button @click="closeRevenueModal" class="btn-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveRevenue">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Client *</label>
                                        <select v-model="revenueForm.client_id" class="form-select" required>
                                            <option value="">Select Client</option>
                                            <option v-for="client in sortedClients" :key="client.id" :value="client.id">
                                                {{ client.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Service Type *</label>
                                        <select v-model="revenueForm.service_type" class="form-select" required>
                                            <option value="webflow_template">Webflow Template</option>
                                            <option value="shopify_app">Shopify App</option>
                                            <option value="web_development">Web Development</option>
                                            <option value="consulting">Consulting</option>
                                            <option value="maintenance">Maintenance Contract</option>
                                            <option value="other">Other Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Amount *</label>
                                        <div class="input-group">
                                            <select v-model="revenueForm.currency" class="input-group-text" style="border-right: 0; background: #e9ecef;">
                                                <option value="BDT">৳</option>
                                                <option value="USD">$</option>
                                            </select>
                                            <input 
                                                v-model.number="revenueForm.amount" 
                                                type="number" 
                                                class="form-control" 
                                                step="0.01"
                                                min="0"
                                                required
                                                placeholder="5000.00"
                                            >
                                        </div>
                                        <div v-if="revenueForm.currency === 'USD'" class="form-text text-info">
                                            <small>
                                                <i class="fas fa-exchange-alt me-1"></i>
                                                Converted: ৳{{ formatNumber(revenueForm.amount * USD_TO_BDT_RATE) }} BDT (1 USD = {{ USD_TO_BDT_RATE }} BDT)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status *</label>
                                        <select v-model="revenueForm.status" class="form-select" required>
                                            <option value="paid">Paid (Received)</option>
                                            <option value="pending">Pending Payment</option>
                                            <option value="overdue">Overdue</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" v-if="revenueForm.status !== 'paid'">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expected Payment Date</label>
                                        <input 
                                            v-model="revenueForm.expected_date" 
                                            type="date" 
                                            class="form-control"
                                        >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Invoice Date</label>
                                        <input 
                                            v-model="revenueForm.invoice_date" 
                                            type="date" 
                                            class="form-control"
                                        >
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input 
                                        v-model="revenueForm.description" 
                                        type="text" 
                                        class="form-control" 
                                        placeholder="Brief description of the service"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea 
                                        v-model="revenueForm.notes" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Additional notes, payment terms, follow-up reminders"
                                    ></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" @click="closeRevenueModal" class="btn btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary" :disabled="saving">
                                        <i class="fas fa-save"></i>
                                        {{ saving ? 'Saving...' : 'Save Revenue' }}
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
import { ref, reactive, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    layout: AdminMaster,
    name: "Reports",
    components: {
        Head
    },
    props: {
        clients: Array,
        revenues: Array
    },
    setup(props) {
        console.log('Reports component setup started', props);
        const selectedPeriod = ref('current');
        const showAddClient = ref(false);
        const showAddRevenue = ref(false);
        const saving = ref(false);
        const clients = ref([...props.clients || []]);
        const revenues = ref([...props.revenues || []]);

        const clientForm = reactive({
            name: '',
            email: '',
            phone: '',
            payment_terms: 'net_30',
            notes: ''
        });

        const revenueForm = reactive({
            client_id: '',
            service_type: 'web_development',
            amount: 0,
            currency: 'BDT',
            status: 'paid',
            expected_date: '',
            invoice_date: '',
            description: '',
            notes: ''
        });

        // Currency conversion constants
        const USD_TO_BDT_RATE = 120;
        const previousRevenueCurrency = ref('BDT');

        // Watch for currency changes and convert amount automatically
        watch(() => revenueForm.currency, (newCurrency) => {
            const oldCurrency = previousRevenueCurrency.value;
            if (oldCurrency && revenueForm.amount > 0 && oldCurrency !== newCurrency) {
                if (oldCurrency === 'BDT' && newCurrency === 'USD') {
                    // Convert from BDT to USD
                    revenueForm.amount = Number((revenueForm.amount / USD_TO_BDT_RATE).toFixed(2));
                } else if (oldCurrency === 'USD' && newCurrency === 'BDT') {
                    // Convert from USD to BDT
                    revenueForm.amount = Number((revenueForm.amount * USD_TO_BDT_RATE).toFixed(2));
                }
            }
            previousRevenueCurrency.value = newCurrency;
        });

        // Computed values
        const monthlyRevenue = computed(() => {
            return revenues.value
                .filter(r => r.status === 'paid' && new Date(r.created_at).getMonth() === new Date().getMonth())
                .reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
        });

        const totalRevenue = computed(() => {
            return revenues.value
                .filter(r => r.status === 'paid')
                .reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
        });

        const pendingRevenue = computed(() => {
            return revenues.value
                .filter(r => r.status === 'pending' || r.status === 'overdue')
                .reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
        });


        const sortedClients = computed(() => {
            return clients.value
                .filter(client => client.is_active !== false)
                .sort((a, b) => a.name.localeCompare(b.name));
        });

        const forecastRevenue = computed(() => {
            const lastMonthRevenue = revenues.value
                .filter(r => r.status === 'paid' && new Date(r.created_at).getMonth() === (new Date().getMonth() - 1))
                .reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
            return lastMonthRevenue * 1.1 + pendingRevenue.value * 0.8; // Simple forecast
        });

        const revenueByService = computed(() => {
            const services = {};
            revenues.value.filter(r => r.status === 'paid').forEach(r => {
                if (!services[r.service_type]) {
                    services[r.service_type] = { amount: 0, count: 0 };
                }
                services[r.service_type].amount += (parseFloat(r.amount) || 0);
                services[r.service_type].count += 1;
            });

            return Object.entries(services).map(([type, data]) => ({
                type: formatServiceType(type),
                amount: data.amount,
                count: data.count,
                icon: getServiceIcon(type),
                color: getServiceColor(type)
            })).sort((a, b) => b.amount - a.amount);
        });


        const pendingPayments = computed(() => {
            return revenues.value
                .filter(r => r.status === 'pending' || r.status === 'overdue')
                .map(r => ({
                    ...r,
                    client_name: clients.value.find(c => c.id === r.client_id)?.name || 'Unknown Client',
                    service_icon: getServiceIcon(r.service_type),
                    expected_date: formatDate(r.expected_date)
                }))
                .sort((a, b) => new Date(a.expected_date) - new Date(b.expected_date));
        });

        // Helper functions
        const formatNumber = (num) => {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(num || 0);
        };

        const formatServiceType = (type) => {
            const types = {
                webflow_template: 'Webflow Templates',
                shopify_app: 'Shopify Apps',
                web_development: 'Web Development',
                consulting: 'Consulting',
                maintenance: 'Maintenance',
                other: 'Other Services'
            };
            return types[type] || type;
        };

        const getServiceIcon = (type) => {
            const icons = {
                webflow_template: 'fas fa-globe',
                shopify_app: 'fas fa-shopping-cart',
                web_development: 'fas fa-code',
                consulting: 'fas fa-handshake',
                maintenance: 'fas fa-cogs',
                other: 'fas fa-star'
            };
            return icons[type] || 'fas fa-star';
        };

        const getServiceColor = (type) => {
            const colors = {
                webflow_template: 'bg-success',
                shopify_app: 'bg-info',
                web_development: 'bg-primary',
                consulting: 'bg-warning',
                maintenance: 'bg-secondary',
                other: 'bg-dark'
            };
            return colors[type] || 'bg-secondary';
        };

        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleDateString();
        };

        const getPaymentCardClass = (payment) => {
            if (payment.status === 'overdue') return 'payment-overdue';
            if (new Date(payment.expected_date) < new Date()) return 'payment-due-soon';
            return '';
        };

        const getPaymentBadgeClass = (payment) => {
            if (payment.status === 'overdue') return 'bg-danger';
            if (new Date(payment.expected_date) < new Date()) return 'bg-warning';
            return 'bg-success';
        };

        const getPaymentStatus = (payment) => {
            if (payment.status === 'overdue') return 'Overdue';
            if (new Date(payment.expected_date) < new Date()) return 'Due Soon';
            return 'On Track';
        };

        // Modal functions
        const closeClientModal = () => {
            showAddClient.value = false;
            Object.keys(clientForm).forEach(key => {
                clientForm[key] = key === 'payment_terms' ? 'net_30' : '';
            });
        };

        const closeRevenueModal = () => {
            showAddRevenue.value = false;
            Object.keys(revenueForm).forEach(key => {
                if (key === 'service_type') revenueForm[key] = 'web_development';
                else if (key === 'status') revenueForm[key] = 'paid';
                else if (key === 'currency') revenueForm[key] = 'BDT';
                else if (key === 'amount') revenueForm[key] = 0;
                else revenueForm[key] = '';
            });
            previousRevenueCurrency.value = 'BDT'; // Reset previous currency tracking
        };

        const saveClient = async () => {
            saving.value = true;
            try {
                const response = await axios.post('/admin-home/finance/clients', clientForm);

                if (response.data.success) {
                    clients.value.push(response.data.client);
                    closeClientModal();
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
                    text: error.response?.data?.message || 'Failed to save client',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                saving.value = false;
            }
        };

        const saveRevenue = async () => {
            saving.value = true;
            try {
                const response = await axios.post('/admin-home/finance/revenues', revenueForm);

                if (response.data.success) {
                    revenues.value.push(response.data.revenue);
                    closeRevenueModal();
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
                console.error('Revenue save failed:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Failed to log revenue',
                    icon: 'error',
                    confirmButtonColor: '#e74a3b'
                });
            } finally {
                saving.value = false;
            }
        };

        const markPaid = async (payment) => {
            const result = await Swal.fire({
                title: 'Mark as Paid?',
                text: `Mark payment of ৳${formatNumber(payment.amount)} as received?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, mark as paid!'
            });

            if (result.isConfirmed) {
                const revenueIndex = revenues.value.findIndex(r => r.id === payment.id);
                if (revenueIndex !== -1) {
                    revenues.value[revenueIndex].status = 'paid';
                    revenues.value[revenueIndex].paid_date = new Date().toISOString();
                    
                    Swal.fire({
                        title: 'Payment Recorded!',
                        text: 'Payment has been marked as paid',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }
        };

        const sendReminder = async (payment) => {
            Swal.fire({
                title: 'Reminder Sent!',
                text: `Payment reminder sent to ${payment.client_name}`,
                icon: 'success',
                confirmButtonColor: '#007bff',
                timer: 3000,
                timerProgressBar: true
            });
        };

        const updatePeriod = () => {
            console.log('Period changed to:', selectedPeriod.value);
        };

        const removePendingPayment = async (payment) => {
            const result = await Swal.fire({
                title: 'Remove Pending Payment?',
                text: `Remove pending payment of ৳${formatNumber(payment.amount)} from ${payment.client_name}?`,
                html: `
                    <div class="text-left">
                        <p><strong>Client:</strong> ${payment.client_name}</p>
                        <p><strong>Amount:</strong> ৳${formatNumber(payment.amount)}</p>
                        <p><strong>Service:</strong> ${formatServiceType(payment.service_type)}</p>
                        <p><strong>Expected Date:</strong> ${payment.expected_date}</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!'
            });

            if (result.isConfirmed) {
                const revenueIndex = revenues.value.findIndex(r => r.id === payment.id);
                if (revenueIndex !== -1) {
                    revenues.value.splice(revenueIndex, 1);
                    
                    Swal.fire({
                        title: 'Payment Removed!',
                        text: 'Pending payment has been removed from forecast',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }
        };

        const clearAllPendingPayments = async () => {
            if (pendingPayments.value.length === 0) {
                Swal.fire({
                    title: 'No Pending Payments',
                    text: 'There are no pending payments to clear',
                    icon: 'info',
                    confirmButtonColor: '#007bff'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Clear All Pending Payments?',
                html: `
                    <div class="text-left">
                        <p>This will remove <strong>${pendingPayments.value.length}</strong> pending payments totaling <strong>৳${formatNumber(pendingRevenue.value)}</strong></p>
                        <div class="mt-3">
                            <h6>Payments to be removed:</h6>
                            <ul class="list-unstyled">
                                ${pendingPayments.value.map(p => `
                                    <li class="mb-1">
                                        <i class="fas fa-user me-1"></i> ${p.client_name} - 
                                        <strong>৳${formatNumber(p.amount)}</strong>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone. All pending payment forecasts will be permanently removed.
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear all!',
                cancelButtonText: 'Cancel',
                width: '600px'
            });

            if (result.isConfirmed) {
                const removedCount = pendingPayments.value.length;
                const removedAmount = pendingRevenue.value;
                
                // Remove all pending and overdue revenues
                revenues.value = revenues.value.filter(r => r.status !== 'pending' && r.status !== 'overdue');
                
                Swal.fire({
                    title: 'All Pending Payments Cleared!',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                            <p><strong>${removedCount}</strong> pending payments removed</p>
                            <p><strong>৳${formatNumber(removedAmount)}</strong> cleared from forecast</p>
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                You can now start fresh with your payment tracking!
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

        const exportPendingPayments = () => {
            if (pendingPayments.value.length === 0) {
                Swal.fire({
                    title: 'No Data to Export',
                    text: 'There are no pending payments to export',
                    icon: 'info',
                    confirmButtonColor: '#007bff'
                });
                return;
            }

            let csvContent = "Client,Service Type,Amount (BDT),Status,Expected Date,Invoice Date,Description,Notes\n";
            
            pendingPayments.value.forEach(payment => {
                csvContent += `"${payment.client_name}","${formatServiceType(payment.service_type)}",${payment.amount},"${payment.status}","${payment.expected_date}","${formatDate(payment.invoice_date)}","${payment.description || ''}","${payment.notes || ''}"\n`;
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `Pending-Payments-${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            Swal.fire({
                title: 'Export Complete!',
                text: `Pending payments exported to CSV file with ${pendingPayments.value.length} records`,
                icon: 'success',
                confirmButtonColor: '#007bff',
                timer: 3000,
                timerProgressBar: true
            });
        };

        // Initialize with real data from props - no need for mock data
        onMounted(() => {
            // Data is already populated from props
            console.log('Clients loaded:', clients.value.length);
            console.log('Revenues loaded:', revenues.value.length);
        });

        return {
            selectedPeriod,
            showAddClient,
            showAddRevenue,
            saving,
            clients,
            revenues,
            clientForm,
            revenueForm,
            monthlyRevenue,
            totalRevenue,
            pendingRevenue,
            forecastRevenue,
            revenueByService,
            sortedClients,
            pendingPayments,
            formatNumber,
            getPaymentCardClass,
            getPaymentBadgeClass,
            getPaymentStatus,
            USD_TO_BDT_RATE,
            closeClientModal,
            closeRevenueModal,
            saveClient,
            saveRevenue,
            markPaid,
            sendReminder,
            updatePeriod,
            removePendingPayment,
            clearAllPendingPayments,
            exportPendingPayments
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

.entrepreneur-guide .bg-success-light {
    background-color: #d4edda !important;
    border-bottom: 1px solid #c3e6cb;
}

.entrepreneur-guide .stream-example {
    padding: 1rem;
    border-left: 3px solid #28a745;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
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

.service-revenue {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 0.25rem;
    border: 1px solid #e3e6f0;
}

.client-revenue {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 0.25rem;
    border: 1px solid #e3e6f0;
}

.pending-payment-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.5rem;
    padding: 1.25rem;
    height: 100%;
    transition: all 0.3s ease;
}

.pending-payment-card.payment-overdue {
    border-left: 4px solid #e74a3b;
}

.pending-payment-card.payment-due-soon {
    border-left: 4px solid #f6c23e;
}

.pending-payment-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.payment-amount {
    font-size: 1.2rem;
    font-weight: bold;
    color: #28a745;
}

.payment-details {
    margin-bottom: 1rem;
}

.payment-service, .payment-date, .payment-notes {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #5a5c69;
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

/* Ensure form labels are left-aligned */
.form-label,
.modal-body label {
    text-align: left !important;
    display: block;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .stat-card {
        text-align: center;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .payment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>