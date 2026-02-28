<template>
    <Head title="Monthly Salary Report"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">

                <!-- Page header (excluded from PDF) -->
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">Monthly Salary Report</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.employee.salary.slip')">All Salary Slip</Link>
                    </div>
                </div>

                <!-- Month Filter (excluded from PDF) -->
                <form method="get" @submit.prevent class="filter_form mb-4">
                    <div class="d-flex align-items-end gap-3">
                        <div>
                            <label class="form-label">Select Month</label>
                            <input
                                type="month"
                                class="form-control"
                                v-model="filterData.month"
                            />
                        </div>
                        <BsButton button-text="Generate Report" button-type="submit" @click="filterFormSubmit" :disabled="filterData.processing"/>
                    </div>
                </form>

                <!-- ===== PRINTABLE / PDF AREA ===== -->
                <div id="monthly-report-pdf">

                    <!-- Report Header (visible in PDF) -->
                    <div class="report-pdf-header">
                        <h3 class="report-title">Monthly Salary Report</h3>
                        <p class="report-meta">Period: <strong>{{ currentMonthLabel() }}</strong></p>
                        <p class="report-meta">Generated: <strong>{{ generatedDate() }}</strong></p>
                        <hr/>
                    </div>

                    <!-- Report Table -->
                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Paid Amount</th>
                                    <th>Month</th>
                                    <th class="action-col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="salaryList().length === 0">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No salary records found for this month.
                                    </td>
                                </tr>
                                <tr v-for="(salary, index) in salaryList()" :key="salary.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ salary?.employee?.name }}</td>
                                    <td>{{ parseFloat(salary.finalSalary).toFixed(2) }} BDT</td>
                                    <td>{{ salary.monthName }}</td>
                                    <td class="action-col">
                                        <Link class="btn btn-secondary btn-sm" :href="route('admin.employee.salary.slip.view', salary.id)">
                                            <i class="fas fa-eye"></i> View
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot v-if="salaryList().length > 0">
                                <tr class="table-dark">
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong>{{ totalSalary() }} BDT</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- PDF Footer -->
                    <div class="report-pdf-footer" v-if="salaryList().length > 0">
                        <p>Total Employees: <strong>{{ salaryList().length }}</strong> &nbsp;|&nbsp; Total Payout: <strong>{{ totalSalary() }} BDT</strong></p>
                    </div>

                </div>
                <!-- ===== END PDF AREA ===== -->

                <!-- Download PDF Button (excluded from PDF) -->
                <div class="btn-wrapper mt-3" v-if="salaryList().length > 0">
                    <button type="button" class="btn btn-danger" @click="exportToPDF">
                        <i class="fas fa-file-pdf me-1"></i> Download PDF
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {Link, useForm, usePage, Head} from "@inertiajs/inertia-vue3";
import AdminMaster from "@/Layouts/AdminMaster.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import html2pdf from "html2pdf.js";

export default {
    name: "MonthlySalaryReport",
    layout: AdminMaster,
    components: {
        BsButton,
        Link,
        Head
    },
    setup() {
        const filterData = useForm({
            month: usePage().props.value.month,
        });

        function filterFormSubmit() {
            filterData.get(route('admin.employee.salary.monthly.report'));
        }

        function salaryList() {
            return usePage().props.value.salaries;
        }

        function totalSalary() {
            const total = salaryList().reduce((sum, item) => {
                return sum + parseFloat(item.finalSalary);
            }, 0);
            return total.toFixed(2);
        }

        function currentMonthLabel() {
            const month = usePage().props.value.month;
            if (!month) return '';
            const date = new Date(month + '-01');
            return date.toLocaleString('default', { month: 'long', year: 'numeric' });
        }

        function generatedDate() {
            const now = new Date();
            return now.toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });
        }

        function exportToPDF() {
            // Hide action column before export
            const actionCols = document.querySelectorAll('.action-col');
            actionCols.forEach(el => el.style.display = 'none');

            const month = usePage().props.value.month || 'report';
            const filename = `Monthly-Salary-Report-${month}.pdf`;

            html2pdf(document.getElementById('monthly-report-pdf'), {
                margin: 1,
                filename: filename,
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
            }).then(() => {
                // Restore action column after export
                actionCols.forEach(el => el.style.display = '');
            });
        }

        return {
            filterData,
            filterFormSubmit,
            salaryList,
            totalSalary,
            currentMonthLabel,
            generatedDate,
            exportToPDF,
        };
    }
}
</script>

<style scoped>
.filter_form label {
    font-weight: 600;
}

tfoot tr td {
    font-size: 1rem;
}

/* PDF report header */
.report-pdf-header {
    margin-bottom: 16px;
}

.report-pdf-header .report-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 4px;
}

.report-pdf-header .report-meta {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 2px;
}

/* PDF footer summary */
.report-pdf-footer {
    margin-top: 12px;
    font-size: 0.9rem;
    color: #444;
    border-top: 1px solid #dee2e6;
    padding-top: 8px;
}
</style>
