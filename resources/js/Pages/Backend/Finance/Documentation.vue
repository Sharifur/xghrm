<template>
    <Head title="Finance Documentation" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-book me-2"></i>
                            Finance System Documentation
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Complete guide for using the finance management system. Follow these step-by-step instructions
                            to properly track your business finances, manage expenses, monitor revenue, and maintain accurate financial records.
                        </p>
                    </div>
                </div>

                <!-- Quick Navigation -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Quick Navigation</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                                <a href="#initial-setup" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-play-circle"></i> Initial Setup
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                                <a href="#daily-tasks" class="btn btn-outline-info btn-sm w-100">
                                    <i class="fas fa-calendar-day"></i> Daily Tasks
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                                <a href="#monthly-process" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-calendar-alt"></i> Monthly Process
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                                <a href="#best-practices" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="fas fa-star"></i> Best Practices
                                </a>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                                <a href="#quick-reference" class="btn btn-outline-dark btn-sm w-100">
                                    <i class="fas fa-bookmark"></i> Quick Reference
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Initial Setup Guide -->
                <div id="initial-setup" class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <button 
                                class="btn btn-link text-white p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#setupGuide" 
                                aria-expanded="false" 
                                aria-controls="setupGuide"
                            >
                                <i class="fas fa-rocket me-2"></i>
                                Initial Setup Guide - Start Here!
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="setupGuide" class="collapse">
                        <div class="card-body">
                        <div class="setup-timeline">
                            <!-- Step 1: Foundation -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success">1</div>
                                <div class="timeline-content">
                                    <h6 class="text-primary">Set Up Your Financial Foundation</h6>
                                    <div class="mt-3">
                                        <div class="sub-step">
                                            <strong>A. Configure Equity (Owner's Investment)</strong>
                                            <ol class="mt-2">
                                                <li>Go to <span class="badge bg-info">Finance → Equity Management</span></li>
                                                <li>Click "Add New Equity Entry"</li>
                                                <li>Enter your initial business investment amount</li>
                                                <li>Add any retained earnings from previous years</li>
                                                <li>Record any owner's drawings (negative equity)</li>
                                            </ol>
                                            <div class="alert alert-warning mt-2">
                                                <i class="fas fa-lightbulb"></i> <strong>Example:</strong> If you started with ৳500,000 personal investment, enter this as "Owner's Capital"
                                            </div>
                                        </div>

                                        <div class="sub-step mt-4">
                                            <strong>B. Record Your Business Assets</strong>
                                            <ol class="mt-2">
                                                <li>Navigate to <span class="badge bg-info">Finance → Assets Management</span></li>
                                                <li>Add all business assets:</li>
                                                <ul>
                                                    <li><strong>Cash Assets:</strong> Bank account balances, cash on hand</li>
                                                    <li><strong>Equipment:</strong> Laptops, phones, printers (with purchase value)</li>
                                                    <li><strong>Software:</strong> Purchased licenses, domains</li>
                                                    <li><strong>Receivables:</strong> Money owed by clients</li>
                                                </ul>
                                            </ol>
                                            <div class="alert alert-info mt-2">
                                                <i class="fas fa-info-circle"></i> <strong>Tip:</strong> Include purchase date and depreciation info for accurate tracking
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Recurring Expenses -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning">2</div>
                                <div class="timeline-content">
                                    <h6 class="text-primary">Set Up Recurring Expenses</h6>
                                    <div class="mt-3">
                                        <ol>
                                            <li>Go to <span class="badge bg-info">Finance → Recurring Expenses</span></li>
                                            <li>Add all monthly fixed costs:</li>
                                        </ol>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="expense-example">
                                                    <h6>BDT Expenses (Local)</h6>
                                                    <ul class="list-unstyled">
                                                        <li>✓ Employee Salaries</li>
                                                        <li>✓ Office Rent</li>
                                                        <li>✓ Electricity Bill</li>
                                                        <li>✓ Internet Bill</li>
                                                        <li>✓ Mobile Bills</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="expense-example">
                                                    <h6>USD Expenses (International)</h6>
                                                    <ul class="list-unstyled">
                                                        <li>✓ Server/Hosting (AWS, DigitalOcean)</li>
                                                        <li>✓ SaaS Tools (Adobe, GitHub)</li>
                                                        <li>✓ Domain Renewals</li>
                                                        <li>✓ International Subscriptions</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-success mt-3">
                                            <i class="fas fa-calculator"></i> <strong>Auto-Conversion:</strong> USD amounts automatically convert to BDT at 1:120 rate
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Budget Planning -->
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info">3</div>
                                <div class="timeline-content">
                                    <h6 class="text-primary">Create Your Budget Plan</h6>
                                    <div class="mt-3">
                                        <ol>
                                            <li>Navigate to <span class="badge bg-info">Finance → Budgets</span></li>
                                            <li>Set quarterly budgets for each category:</li>
                                            <ul class="mt-2">
                                                <li><strong>Technology & Software:</strong> Tools, licenses, equipment</li>
                                                <li><strong>Marketing & Advertising:</strong> Ads, promotions, content</li>
                                                <li><strong>Operations:</strong> Rent, utilities, supplies</li>
                                                <li><strong>Professional Services:</strong> Legal, accounting, consulting</li>
                                            </ul>
                                            <li>System will track spending against budgets automatically</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Operations -->
                <div id="daily-tasks" class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <button 
                                class="btn btn-link text-white p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#dailyGuide" 
                                aria-expanded="false" 
                                aria-controls="dailyGuide"
                            >
                                <i class="fas fa-tasks me-2"></i>
                                Daily Operations Guide
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="dailyGuide" class="collapse">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="daily-task-card">
                                    <h6 class="text-primary">
                                        <i class="fas fa-shopping-cart"></i> Recording Expenses
                                    </h6>
                                    <div class="task-steps mt-3">
                                        <div class="step-item">
                                            <strong>One-Time Expenses:</strong>
                                            <ol class="mt-2">
                                                <li>Go to <span class="badge bg-info">Finance → One-time Expenses</span></li>
                                                <li>Click "Add New Expense"</li>
                                                <li>Fill in:
                                                    <ul>
                                                        <li>Description (e.g., "MacBook Pro for development")</li>
                                                        <li>Amount and Currency (USD/BDT)</li>
                                                        <li>Category</li>
                                                        <li>Date of purchase</li>
                                                    </ul>
                                                </li>
                                                <li>Save the expense</li>
                                            </ol>
                                            <div class="alert alert-light mt-2">
                                                <strong>Examples:</strong> New laptop, software license, office furniture, domain purchase
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="daily-task-card">
                                    <h6 class="text-success">
                                        <i class="fas fa-dollar-sign"></i> Tracking Revenue
                                    </h6>
                                    <div class="task-steps mt-3">
                                        <div class="step-item">
                                            <strong>Client Payments:</strong>
                                            <ol class="mt-2">
                                                <li>Go to <span class="badge bg-info">Finance → Revenue Tracking</span></li>
                                                <li>Add new client or select existing</li>
                                                <li>Record payment details:
                                                    <ul>
                                                        <li>Invoice amount</li>
                                                        <li>Payment status (Paid/Pending)</li>
                                                        <li>Due date for pending payments</li>
                                                        <li>Service type delivered</li>
                                                    </ul>
                                                </li>
                                                <li>System auto-calculates pending revenue</li>
                                            </ol>
                                            <div class="alert alert-light mt-2">
                                                <strong>Service Types:</strong> Web development, Shopify apps, consulting, maintenance
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="daily-checklist mt-4">
                            <h6 class="text-primary">Daily Checklist</h6>
                            <div class="checklist-items">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Record any new expenses immediately</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Update client payment status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Check pending payment due dates</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">Review balance sheet for accuracy</label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Process -->
                <div id="monthly-process" class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <button 
                                class="btn btn-link text-white p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#monthlyGuide" 
                                aria-expanded="false" 
                                aria-controls="monthlyGuide"
                            >
                                <i class="fas fa-calendar-check me-2"></i>
                                Monthly Review & Reporting Process
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="monthlyGuide" class="collapse">
                        <div class="card-body">
                        <div class="monthly-steps">
                            <!-- Week 1 -->
                            <div class="week-section">
                                <h6 class="text-primary">Week 1: Opening Tasks</h6>
                                <ol>
                                    <li><strong>Review Previous Month:</strong>
                                        <ul>
                                            <li>Go to <span class="badge bg-info">Finance → Dashboard</span></li>
                                            <li>Select previous month from dropdown</li>
                                            <li>Review total revenue vs expenses</li>
                                        </ul>
                                    </li>
                                    <li><strong>Update Recurring Expenses:</strong>
                                        <ul>
                                            <li>Check if any recurring expense amounts changed</li>
                                            <li>Add new subscriptions if any</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>

                            <!-- Week 2-3 -->
                            <div class="week-section mt-4">
                                <h6 class="text-primary">Week 2-3: Ongoing Tracking</h6>
                                <ol>
                                    <li><strong>Monitor Budget Utilization:</strong>
                                        <ul>
                                            <li>Check <span class="badge bg-info">Finance → Dashboard</span> for budget status</li>
                                            <li>Review categories approaching limits</li>
                                        </ul>
                                    </li>
                                    <li><strong>Follow Up Payments:</strong>
                                        <ul>
                                            <li>Check overdue invoices in Revenue Tracking</li>
                                            <li>Send reminders to clients</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>

                            <!-- Month End -->
                            <div class="week-section mt-4">
                                <h6 class="text-primary">Month End: Closing Process</h6>
                                <div class="closing-steps">
                                    <div class="step-card">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <strong>Verify Balance Sheet</strong>
                                            <p>Go to <span class="badge bg-info">Finance → Balance Sheet</span></p>
                                            <p>Ensure: Assets = Liabilities + Equity (green checkmark)</p>
                                        </div>
                                    </div>

                                    <div class="step-card">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <strong>Generate Monthly Reports</strong>
                                            <p>From <span class="badge bg-info">Finance → Dashboard</span>:</p>
                                            <ul>
                                                <li>Click "Export Data" dropdown</li>
                                                <li>Generate: Expense Report, Balance Sheet, Financial Summary</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="step-card">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <strong>Archive Reports</strong>
                                            <p>Save CSV files in organized structure:</p>
                                            <code>/2024/December/[report-files].csv</code>
                                        </div>
                                    </div>

                                    <div class="step-card">
                                        <div class="step-number">4</div>
                                        <div class="step-content">
                                            <strong>Analyze & Plan</strong>
                                            <ul>
                                                <li>Review profit margins</li>
                                                <li>Identify cost-saving opportunities</li>
                                                <li>Update next month's budget if needed</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Best Practices & Tips -->
                <div id="best-practices" class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <button 
                                class="btn btn-link text-dark p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#practicesGuide" 
                                aria-expanded="false" 
                                aria-controls="practicesGuide"
                            >
                                <i class="fas fa-star me-2"></i>
                                Best Practices & Pro Tips
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="practicesGuide" class="collapse">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="practice-section">
                                    <h6 class="text-primary">
                                        <i class="fas fa-coins"></i> Currency Management
                                    </h6>
                                    <ul class="practice-list">
                                        <li><strong>Always select correct currency:</strong> System converts USD to BDT at 1:120</li>
                                        <li><strong>International payments:</strong> Record in USD for accurate conversion</li>
                                        <li><strong>Local expenses:</strong> Always use BDT for local transactions</li>
                                        <li><strong>Reports:</strong> All exports show consolidated amounts in BDT</li>
                                    </ul>
                                </div>

                                <div class="practice-section mt-4">
                                    <h6 class="text-success">
                                        <i class="fas fa-chart-line"></i> Revenue Tracking
                                    </h6>
                                    <ul class="practice-list">
                                        <li><strong>Add clients immediately:</strong> Even before first invoice</li>
                                        <li><strong>Set payment terms:</strong> Net 15/30/45 for automatic alerts</li>
                                        <li><strong>Track service types:</strong> Helps identify profitable services</li>
                                        <li><strong>Update payment status:</strong> Mark as paid immediately upon receipt</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="practice-section">
                                    <h6 class="text-danger">
                                        <i class="fas fa-receipt"></i> Expense Management
                                    </h6>
                                    <ul class="practice-list">
                                        <li><strong>Categorize properly:</strong> Helps with budget tracking</li>
                                        <li><strong>Add receipts/notes:</strong> Use description field for details</li>
                                        <li><strong>Regular vs One-time:</strong> Separate recurring from one-off expenses</li>
                                        <li><strong>Review monthly:</strong> Look for unnecessary subscriptions</li>
                                    </ul>
                                </div>

                                <div class="practice-section mt-4">
                                    <h6 class="text-info">
                                        <i class="fas fa-balance-scale"></i> Balance Sheet Health
                                    </h6>
                                    <ul class="practice-list">
                                        <li><strong>Daily check:</strong> Ensure equation balances</li>
                                        <li><strong>Asset updates:</strong> Depreciate equipment annually</li>
                                        <li><strong>Liability tracking:</strong> Update loan balances monthly</li>
                                        <li><strong>Equity changes:</strong> Record drawings and new investments</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Common Scenarios -->
                        <div class="scenarios-section mt-5">
                            <h6 class="text-primary">Common Scenarios & Solutions</h6>
                            <div class="accordion" id="scenariosAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#scenario1">
                                            <i class="fas fa-question-circle me-2"></i> Client paid partial invoice amount
                                        </button>
                                    </h2>
                                    <div id="scenario1" class="accordion-collapse collapse" data-bs-parent="#scenariosAccordion">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Go to Revenue Tracking</li>
                                                <li>Find the client and invoice</li>
                                                <li>Update payment amount to partial amount received</li>
                                                <li>Keep status as "Pending" for remaining amount</li>
                                                <li>Add note about partial payment in description</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#scenario2">
                                            <i class="fas fa-question-circle me-2"></i> Purchased equipment with both USD and BDT
                                        </button>
                                    </h2>
                                    <div id="scenario2" class="accordion-collapse collapse" data-bs-parent="#scenariosAccordion">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Add two separate expense entries</li>
                                                <li>First entry: USD portion with USD currency selected</li>
                                                <li>Second entry: BDT portion with BDT currency selected</li>
                                                <li>Use same description with (Part 1) and (Part 2) notation</li>
                                                <li>System will automatically convert and consolidate in reports</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#scenario3">
                                            <i class="fas fa-question-circle me-2"></i> Need to track project-specific expenses
                                        </button>
                                    </h2>
                                    <div id="scenario3" class="accordion-collapse collapse" data-bs-parent="#scenariosAccordion">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Use the description field to tag expenses</li>
                                                <li>Format: "[ProjectName] - Expense description"</li>
                                                <li>Export monthly data and filter by project name in Excel</li>
                                                <li>This helps track project profitability</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Reference Card -->
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <button 
                                class="btn btn-link text-white p-0 text-decoration-none w-100 text-start" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#referenceGuide" 
                                aria-expanded="false" 
                                aria-controls="referenceGuide"
                            >
                                <i class="fas fa-bookmark me-2"></i>
                                Quick Reference Guide
                                <i class="fas fa-chevron-down float-end mt-1"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="referenceGuide" class="collapse">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="reference-card">
                                    <h6>Page Directory</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Dashboard:</strong> Overview & exports</li>
                                        <li><strong>Balance Sheet:</strong> Financial position</li>
                                        <li><strong>Recurring Expenses:</strong> Monthly fixed costs</li>
                                        <li><strong>Assets:</strong> What you own</li>
                                        <li><strong>Equity:</strong> Owner's investment</li>
                                        <li><strong>One-time Expenses:</strong> Variable costs</li>
                                        <li><strong>Budgets:</strong> Spending limits</li>
                                        <li><strong>Revenue Tracking:</strong> Client payments</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="reference-card">
                                    <h6>Important Formulas</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Balance Sheet:</strong> Assets = Liabilities + Equity</li>
                                        <li><strong>Net Profit:</strong> Revenue - Expenses</li>
                                        <li><strong>Profit Margin:</strong> (Profit ÷ Revenue) × 100</li>
                                        <li><strong>ROI:</strong> (Profit ÷ Investment) × 100</li>
                                        <li><strong>USD to BDT:</strong> 1 USD = 120 BDT</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="reference-card">
                                    <h6>Export Schedule</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Daily:</strong> Transaction records</li>
                                        <li><strong>Weekly:</strong> Budget status check</li>
                                        <li><strong>Monthly:</strong> All financial reports</li>
                                        <li><strong>Quarterly:</strong> Budget analysis</li>
                                        <li><strong>Yearly:</strong> Complete financial archive</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success mt-4">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Pro Tip:</strong> Set calendar reminders for month-end tasks. Consistency in financial tracking leads to better business insights and growth opportunities.
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Support Section -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-life-ring me-2"></i>
                            Need Help?
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>If you encounter any issues or need clarification:</p>
                        <ul>
                            <li>Review this documentation for step-by-step guides</li>
                            <li>Check the Balance Sheet - it should always balance</li>
                            <li>Export your data regularly for backup</li>
                            <li>Use the Dashboard to monitor financial health</li>
                        </ul>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            Remember: Accurate financial tracking today leads to better business decisions tomorrow. Take time to enter data correctly and review reports regularly.
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
import { onMounted } from 'vue';

export default {
    layout: AdminMaster,
    name: "FinanceDocumentation",
    components: {
        Head
    },
    setup() {
        onMounted(() => {
            // Enhanced navigation for anchor links that opens collapsible sections
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const target = document.querySelector('#' + targetId);
                    
                    if (target) {
                        // Map section IDs to their collapse target IDs
                        const collapseMap = {
                            'initial-setup': 'setupGuide',
                            'daily-tasks': 'dailyGuide',
                            'monthly-process': 'monthlyGuide',
                            'best-practices': 'practicesGuide',
                            'quick-reference': 'referenceGuide'
                        };
                        
                        // Check if this target has a corresponding collapsible section
                        const collapseTargetId = collapseMap[targetId];
                        if (collapseTargetId) {
                            const collapseElement = document.querySelector('#' + collapseTargetId);
                            if (collapseElement) {
                                // Use Bootstrap's Collapse API to show the section
                                const bsCollapse = new bootstrap.Collapse(collapseElement, {
                                    show: true
                                });
                                
                                // Wait for the collapse animation to complete, then scroll
                                setTimeout(() => {
                                    target.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start'
                                    });
                                }, 350); // Bootstrap's collapse animation is ~350ms
                            }
                        } else {
                            // For non-collapsible sections, just scroll normally
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });
        });

        return {};
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

/* Timeline Styles */
.setup-timeline {
    position: relative;
    padding-left: 50px;
}

.timeline-item {
    position: relative;
    padding-bottom: 40px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -35px;
    top: 40px;
    height: calc(100% - 40px);
    width: 2px;
    background: #e3e6f0;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -50px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.timeline-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.sub-step {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}

/* Daily Task Cards */
.daily-task-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    height: 100%;
}

.task-steps {
    background: white;
    padding: 15px;
    border-radius: 5px;
}

.step-item {
    margin-bottom: 20px;
}

/* Checklist Styles */
.daily-checklist {
    background: #fff3cd;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ffeaa7;
}

.checklist-items .form-check {
    margin-bottom: 10px;
    padding: 10px;
    background: white;
    border-radius: 5px;
}

/* Monthly Process Styles */
.week-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #28a745;
}

.closing-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.step-card {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 20px;
    position: relative;
}

.step-number {
    position: absolute;
    top: -15px;
    left: 20px;
    background: #007bff;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* Expense Examples */
.expense-example {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}

.expense-example h6 {
    color: #007bff;
    margin-bottom: 15px;
}

.expense-example ul li {
    margin-bottom: 8px;
}

/* Practice Sections */
.practice-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.practice-list {
    list-style: none;
    padding: 0;
}

.practice-list li {
    margin-bottom: 10px;
    padding-left: 25px;
    position: relative;
}

.practice-list li:before {
    content: '→';
    position: absolute;
    left: 0;
    color: #007bff;
}

/* Reference Cards */
.reference-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    height: 100%;
}

.reference-card h6 {
    color: #007bff;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

/* Accordion Customization */
.accordion-button {
    background: #f8f9fa;
    font-weight: 500;
}

.accordion-button:not(.collapsed) {
    background: #e7f1ff;
    color: #007bff;
}

.accordion-body {
    background: white;
    padding: 20px;
}

/* Badge Styles */
.badge {
    padding: 5px 10px;
    font-weight: normal;
}

/* Alert Styles */
.alert {
    border-radius: 8px;
}

.alert i {
    margin-right: 8px;
}

/* Code Block */
code {
    background: #f4f4f4;
    padding: 8px 12px;
    border-radius: 4px;
    display: inline-block;
    margin-top: 5px;
    font-size: 0.9rem;
    color: #e83e8c;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .setup-timeline {
        padding-left: 30px;
    }
    
    .timeline-marker {
        left: -40px;
    }
    
    .timeline-item:before {
        left: -26px;
    }
    
    .closing-steps {
        grid-template-columns: 1fr;
    }
}

/* Smooth Scroll Padding */
[id] {
    scroll-margin-top: 80px;
}

/* Hover Effects */
.btn-outline-primary:hover,
.btn-outline-info:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.scenarios-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    margin-top: 30px;
}
</style>