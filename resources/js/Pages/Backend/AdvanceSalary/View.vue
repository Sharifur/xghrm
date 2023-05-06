<template>
    <Head title="Edit Advance Salary"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="header-wrap d-flex justify-content-between">
                <h2 class="dashboards-title margin-bottom-40">Edit Advance Salary</h2>
                <div class="btn-wrp">
                    <Link class="btn btn-info m-1" :href="route('admin.employee.advance.salary.all')">Advance salary List</Link>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div id="element-to-convert">
                    <div class="salarySlipPdfOuterWrapper advanceSalary">
                        <div class="headerPart">
                            <div class="subjectWarp">
                                <p>Advance salary</p>
                            </div>
                        </div>
                        <div class="paratext">
                            <p>I, <strong>{{selectedEmployee.name}}</strong>, <strong>Department: {{selectedEmployee.category}}</strong>  request an advance payment of {{payableAmount}}/- ({{ numberToWord(payableAmount) }}) BDT on my salary to be paid on 5th {{getSelectedMonthName(salarySlipData.month)}} {{new Date().getFullYear()}} due to personal problem as permitted by <strong>Xgenious</strong> policy. </p>
                            <p><strong class="d-block">I agree to repay this advance as follow: </strong>
                                This payroll deduction to be made from my salary on the first pay period immediately following the pay period from which this advance is made.</p>
                            <p>I also agree that if I terminate employment prior to total repayment of this advance, I authorize the company to deduct any unpaid advance amount from the salary owed me at the time of termination of employment. </p>
                        </div>
                        <div class="footerPartWrapper">
                            <div class="signature">
                                <span class="signature">Signature</span>
                                <h2>Sharifur Rahman</h2>
                                <span class="designation">Ceo - Xgenious</span>
                            </div>
                            <div class="signature">
                                <span class="signature">Signature</span>
                                <h2>{{selectedEmployee.name}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper margin-left-60">
                    <BsButton type="button" @click="exportToPDF" button-text="Download PDF"/>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import AdminMaster from "@/Layouts/AdminMaster";
import {useForm,usePage,Head,Link} from "@inertiajs/inertia-vue3";
import BsButton from "@/Components/BsForm/Button";
import Input from "@/Components/BsForm/Input";
import Select from "@/Components/BsForm/Select";
import Textarea from "@/Components/BsForm/Textarea";
import Datepicker from "vue3-datepicker";
import Swal from "sweetalert2";
import {ref, watch} from "vue";
import html2pdf from "html2pdf.js";

export default {
    layout: AdminMaster,
    components:{
        BsButton,
        Input,
        Select,
        Textarea,
        Head,
        Datepicker,
        Link
    },
    setup(){
        const advanceSalary = usePage().props.value.advance_salary;
        const payableAmount = ref(0);
        payableAmount.value = advanceSalary.amount;
        const selectedEmployee = ref({
            name : advanceSalary.name,
            amount : advanceSalary.amount,
            category : advanceSalary.category,
        });

        const salarySlipData = useForm({
            employee_id: advanceSalary.employee_id,
            month: advanceSalary.month,
            amount: advanceSalary.amount,
        });

        function changeAmount(){
            payableAmount.value = parseFloat(salarySlipData.amount);
        }

        function  exportToPDF(){
            let pdfFileName = selectedEmployee.value.name +'-'+getSelectedMonthName(salarySlipData.month)+"-salary-slip.pdf";
            html2pdf(document.getElementById("element-to-convert"), {
                margin: 1,
                filename: pdfFileName
            });
        }

        function getSelectedMonthName(month){
            let  months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            return month != null ?  months[new Date(month).getMonth()] : '';
        }

        return {
            salarySlipData,
            exportToPDF,
            selectedEmployee,
            getSelectedMonthName,
            changeAmount,
            payableAmount
        }
    }
}

</script>
