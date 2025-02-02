<template>
    <Head title="Create Salary Slip"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="header-wrap d-flex justify-content-between">
                <h2 class="dashboards-title margin-bottom-40">Create Salary Slip</h2>
                <div class="btn-wrp">
                    <Link class="btn btn-info m-1" :href="route('admin.employee.salary.slip')">All Salary Slip</Link>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <form @submit.prevent>
                    <div class="single-info-input margin-top-30">
                        <label class="info-title">Salary Month</label>
                        <VueDatePicker v-model="salarySlipData.month"/>
                        <span class="info-text">Select Month First</span>
                    </div>
                    <Select title="Employee" @change="changeEmployeeSelect()" v-model="salarySlipData.employee_id" :options="employeeList" />
                    <div class="repeaterFieldWarp">
                        <h4 class="repeater-title">Extra Earning Fields</h4>
                        <div class="repeater-inner-wrap" >
                            <div class="individualItemWrapper" v-for="earningField in salarySlipData.extraEarningFields" :key="earningField.index">
                                <Input type="text" title="Description" v-model="earningField.description"/>
                                <Input type="number" min="0" @input="calculateSalary()" title="Amount" v-model="earningField.amount"/>
                                <div class="actionButtonWrap">
                                    <span class="addMore" @click="addMoreEarningField()"><i class="fas fa-plus"></i></span>
                                    <span class="removeItem" @click="removeEarningField(earningField)"><i class="fas fa-times"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="repeaterFieldWarp">
                        <h4 class="repeater-title">Extra Deduction Fields</h4>
                        <div class="repeater-inner-wrap" >
                            <div class="individualItemWrapper" v-for="deductionField in salarySlipData.extraDeductionFields" :key="deductionField.index">
                                <Input type="text" title="Description" v-model="deductionField.description"/>
                                <Input type="number" min="0" @input="calculateSalary()" title="Amount" v-model="deductionField.amount"/>
                                <div class="actionButtonWrap">
                                    <span class="addMore" @click="addMoreDeductionField()"><i class="fas fa-plus"></i></span>
                                    <span class="removeItem" @click="removeDeductionField(deductionField)"><i class="fas fa-times"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-8">
                <div class="head-raw margin-top-40 margin-left-60">
                    <h4>Color Explanation</h4>
                    <ul class="color-explanation">
                        <li class="bg-success">Attendance <span class="badge">{{attenadnceCount}}</span></li>
                        <li class="holiday">Holiday <span class="badge">{{holidayCount}}</span></li>
                        <li class="leave">leave<span class="badge">{{leaveCount}}</span></li>
                        <li class="sick-leave">Sick Leave  <span class="badge">{{sickLeaveCount}}</span></li>
                        <li class="paid-leave">Paid Leave  <span class="badge">{{paidLeaveCount}}</span></li>
                    </ul>
                </div>
                <div id="element-to-convert">
                    <div class="salarySlipPdfOuterWrapper">
                        <div class="headerPart">

                            <div class="subjectWarp">
                                <p>Payslip for the month of <span class="period">{{getSelectedMonthName(salarySlipData.month)}}</span> {{new Date().getFullYear()}}</p>
                            </div>
                        </div>
                        <div class="employeeInfoWrap">
                            <h3><strong>Name: </strong> {{selectedEmployee.name}}</h3>
                            <h4><strong>Department:</strong> {{selectedEmployee.category}}</h4>
                        </div>
                        <div class="tableWithCalculation table-responsive">
                            <div class="salary-sheet">
                                <div class="salary-sheet-flex">
                                    <div class="salary-sheet-item">
                                        <div class="salary-sheet-contents">
                                            <ul class="salary-sheet-contents-list">
                                                <li class="salary-sheet-list salary-sheet-head"><span class="list-title left">EARNINGS</span> <span class="list-title list-para right"> PER MONTH </span></li>
                                                <li class="salary-sheet-list"><span class="list-para left">Basic Salary</span> <span class="list-para right">{{parseFloat(selectedEmployee.salary).toFixed(2)}} </span></li>
                                                <li class="salary-sheet-list" v-for="earningField in salarySlipData.extraEarningFields" :key="earningField.index" >
                                                    <span v-if="earningField.description !== null" class="list-para left">{{ earningField.description }}</span>
                                                    <span v-if="earningField.description !== null" class="list-para right"> {{ earningField.amount > 0 ? parseFloat(earningField.amount).toFixed(2) : 0.00 }} </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="salary-sheet-item">
                                        <div class="salary-sheet-contents">
                                            <ul class="salary-sheet-contents-list">
                                                <li class="salary-sheet-list salary-sheet-head"><span class="list-title left">DEDUCTIONS</span> <span class="list-title list-para right"> PER MONTH </span></li>
                                                <li class="salary-sheet-list"  v-for="deductionField in salarySlipData.extraDeductionFields" :key="deductionField.index">
                                                    <span class="list-para left" v-if="deductionField.description !== null" >{{ deductionField.description }}</span>
                                                    <span class="list-para right" v-if="deductionField.description !== null " > {{ deductionField.amount > 0 ? parseFloat(deductionField.amount).toFixed(2) : 0.00 }} </span>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="salary-sheet-gross">
                                    <div class="salary-sheet-gross-item">
                                        <div class="gross-list"><span class="gross-para left">Gross Earnings(A)</span> <span class="gross-para right">{{ parseFloat(grossEarning).toFixed(2)  }}</span></div>
                                    </div>
                                    <div class="salary-sheet-gross-item">
                                        <div class="gross-list"><span class="gross-para left">Gross Deductions(B)</span> <span class="gross-para right">{{ parseFloat(grossDeduction).toFixed(2)  }}</span></div>
                                    </div>
                                </div>
                                <div class="salary-sheet-gross">
                                    <div class="salary-sheet-gross-item">
                                        <div class="gross-list"><span class="gross-para left">Net Salary Payable(A-B)</span> <span class="gross-para right">{{ parseFloat(payableAmount).toFixed(2)  }}</span></div>
                                    </div>
                                </div>
                                <div class="salary-sheet-gross">
                                    <div class="salary-sheet-gross-item">
                                        <div class="gross-list"><span class="gross-para left">Net Salary Payable(in words)</span> <span class="gross-para right">{{ numberToWord(payableAmount) }} Tk Only</span></div>
                                    </div>
                                </div>
                            </div>
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
                                <span class="designation">{{selectedEmployee.designation}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper margin-left-60">
                    <Button type="button" @click="exportToPDF" button-text="Save and Download PDF"/>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import AdminMaster from "@/Layouts/AdminMaster.vue";
import {useForm,usePage,Head,Link} from "@inertiajs/inertia-vue3";
import Button from "@/Components/BsForm/Button.vue";
import Input from "@/Components/BsForm/Input.vue";
import Select from "@/Components/BsForm/Select.vue";
import Textarea from "@/Components/BsForm/Textarea.vue";
import Datepicker from "vue3-datepicker";
import Swal from "sweetalert2";
import {ref, watch} from "vue";
import html2pdf from "html2pdf.js";
import VueDatePicker from "@vuepic/vue-datepicker";

export default {
    layout: AdminMaster,
    components:{
        Button,
        Input,
        Select,
        Textarea,
        Head,
        Datepicker,
        Link,
        VueDatePicker
    },
    setup(){
        const employeeList = usePage().props.value.all_employee;
        const selectedEmployee = ref({
            name : 'Name',
            salary : 0,
            category : 'Designation',
            designation : 'Designation',
        });
        const attenadnceCount = ref(0);
        const holidayCount = ref(0);
        const leaveCount = ref(0);
        const sickLeaveCount = ref(0);
        const paidLeaveCount = ref(0);
        //gross details
        const grossEarning = ref(0);
        const grossDeduction = ref(0);
        const payableAmount = ref(0);

        const salarySlipData = useForm({
            employee_id: null,
            month: null,
            extraEarningFields: [{
                'description' : null,
                'amount' : 0,
            }],
            extraDeductionFields: [{
                    'description' : null,
                    'amount' : 0,
            }],
        });
        function addMoreEarningField(){
             salarySlipData.extraEarningFields.push({
                'description' : null,
                'amount' : 0,
            });
        }
        function addMoreDeductionField(){
             salarySlipData.extraDeductionFields.push({
                'description' : null,
                'amount' : 0,
            });
        }
        function removeEarningField(item){
            if (salarySlipData.extraEarningFields.length >1){
                salarySlipData.extraEarningFields.splice(salarySlipData.extraEarningFields.indexOf(item),1);
            }
        }
        function removeDeductionField(item){
            if (salarySlipData.extraDeductionFields.length >1){
                salarySlipData.extraDeductionFields.splice(salarySlipData.extraDeductionFields.indexOf(item),1);
            }
        }
        function  exportToPDF(){
            let pdfFileName = selectedEmployee.value.name +'-'+getSelectedMonthName(salarySlipData.month)+"-salary-slip.pdf";
            html2pdf(document.getElementById("element-to-convert"), {
                margin: 1,
                filename: pdfFileName,
            });
            //todo: send ajax request with all the data to store it in database
            axios.post(route('admin.employee.salary.slip.create'),salarySlipData)
        }

        function changeEmployeeSelect(){
            axios.post(route('admin.employee.details',salarySlipData.employee_id),{month: salarySlipData.month})
            .then((response) => {

                salarySlipData.extraEarningFields = [{
                    'description' : null,
                    'amount' : 0,
                }];
                salarySlipData.extraDeductionFields = [{
                    'description' : null,
                    'amount' : 0,
                }];


                let responseData = response.data;
                let employeeDetails = response.data.details;
                 selectedEmployee.value = {
                    name : employeeDetails.name,
                    salary : employeeDetails.salary,
                    category : employeeDetails.designation,
                    designation : employeeDetails.designation,
                };
                 attenadnceCount.value =responseData.attenadnceCount;
                 holidayCount.value = responseData.holidayCount;
                 leaveCount.value = responseData.leaveCount;
                 sickLeaveCount.value = responseData.sickLeaveCount;
                 paidLeaveCount.value = responseData.paidLeaveCount;

                //add lunch meal rate
                salarySlipData.extraEarningFields.push({
                    'description' : `${responseData.attenadnceCount} Days Lunch`,
                    'amount' : parseInt(responseData.attenadnceCount) * 50
                });

                //add sick leave
                const sickLeave = responseData.leaveCount > 2 ? 2 : responseData.leaveCount;
                salarySlipData.extraDeductionFields.push({
                    'description' : `${sickLeave} Days Sick Leave`,
                    'amount' : 0
                });

                //add leave calculation
                if(responseData.leaveCount > 2){
                    salarySlipData.extraDeductionFields.push({
                        'description' : `${responseData.leaveCount - 2} Days Leave`,
                        'amount' : ( employeeDetails.salary / 24) * (responseData.leaveCount - 2)
                    });
                }


                //add advance salary
                if(responseData.AdvanceSalary > 0){
                    salarySlipData.extraDeductionFields.push({
                        'description' : `Advance Salary`,
                        'amount' : responseData.AdvanceSalary
                    });
                }

                calculateSalary();
            })

        }
        function getSelectedMonthName(month){
            let  months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            return month != null ?  months[new Date(month).getMonth()] : '';
        }

        function calculateSalary(){
            let grossEarnings = 0;
                salarySlipData.extraEarningFields.map((item) => {
                    if (item.amount > 0){
                        grossEarnings += parseFloat(item.amount)
                    }
            });

            let grossDeductions = 0;
            salarySlipData.extraDeductionFields.map((item) => {
                if (item.amount > 0){
                    grossDeductions += parseFloat(item.amount)
                }
            });
            grossEarning.value = grossEarnings > 0 ? parseFloat(grossEarnings) : 0;
            grossDeduction.value = grossDeductions > 0 ? parseFloat(grossDeductions) : 0;

           if(selectedEmployee.salary !== "undefined" ){
                grossEarning.value +=  parseFloat(selectedEmployee.value.salary);
           }
           payableAmount.value = parseFloat(grossEarning.value) - parseFloat(grossDeduction.value);

        }


        return {
            employeeList,
            salarySlipData,
            addMoreEarningField,
            addMoreDeductionField,
            removeEarningField,
            removeDeductionField,
            exportToPDF,
            selectedEmployee,
            changeEmployeeSelect,
            holidayCount ,
            leaveCount,
            sickLeaveCount,
            paidLeaveCount,
            attenadnceCount,
            getSelectedMonthName,
            grossEarning,
            grossDeduction,
            payableAmount,
            calculateSalary
        }
    }
}

</script>
