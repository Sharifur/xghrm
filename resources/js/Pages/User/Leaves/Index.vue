<template>
    <Head title="All Leaves"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40 margin-bottom-30">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Leaves</h2>
                    <div class="btn-wrapper">
                         <BsModalButton target="addnewcategory" button-class="btn btn-info m-1" >Add New Leave</BsModalButton>
                    </div>
                </div>
                <div class="table-wrap table-responsive">
                    <table class="table table-light table-secondary">
                        <thead>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            <tr v-for="attendant in attendancesData()" v-bind:key="attendant.id">
                                <td>{{attendant.id}}</td>
                                <td><span class="alert text-capitalize" :class="attendant.type === 'C/In' ? 'alert-success' : 'alert-danger'">{{attendant.type.replace('-',' ')}}</span></td>
                                <td><span class="alert text-capitalize" :class="attendant.status === 1 ? 'alert-success' : 'alert-danger'">{{attendant.status === 1 ? "Approved" : "pending"}}</span></td>
                                <td>{{readableDateFormat(attendant.date_time)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="attendancesLink()"/>
            </div>
        </div>
    </div>

<BsModal modal-title="Add New Attendance Log" modal-id="addnewcategory" modal-size="modal-md">
    <BreezeValidationErrors class="mb-4" />
    <form @submit.prevent>
        <BsSelect title="Status" :options="attendanceTypes" v-model="newLogData.type"/>
        <div class="single-info-input margin-top-30">
            <label class="info-title">Date Time</label>
            <VueDatePicker :start-date="new Date()" :disabled-week-days="[5]" v-model="newLogData.date_time" :min-date="minDate" :max-date="maxDate" />
        </div>
        <BsButton button-text="Submit" button-type="submit" @click="addAttendanceLogFormSubmit" :disabled="newLogData.processing"/>
    </form>
</BsModal>

</template>

<script>
import {Link,usePage,useForm,Head} from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";
import BsModal from "@/Components/BsModal.vue";
import BsModalButton from "@/Components/BsModalButton.vue";
import BsInput from "@/Components/BsForm/Input.vue";
import BsSelect from "@/Components/BsForm/Select.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import UserMaster from "@/Layouts/UserMaster.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import { addMonths, getMonth, getYear, subMonths } from 'date-fns';

export default {
    name: "Index",
    layout: UserMaster,
    components:{
        Pagination,
        Link,
        usePage,
        BsModal,
        BsModalButton,
        BsInput,
        BsSelect,
        BsButton,
        BreezeValidationErrors,
        VueDatePicker,
        Head
    },
    setup(){

        const attendanceTypes = [
            {label: 'Leave' , value: 'leave'},
            {label: 'Sick Leave' , value: 'sick-leave'}
        ];

        const newLogData = useForm({
            employee_id: null,
            type: null,
            date_time: null
        });

        const minDate =  new Date();
        const maxDate = addMonths(new Date(getYear(new Date()), getMonth(new Date())),1);

        function attendancesData(){
            return usePage().props.value.attendance_logs.data;
        }
        function attendancesLink(){
            return usePage().props.value.attendance_logs.links;
        }
        function addAttendanceLogFormSubmit(){
            newLogData.post(route('user.leaves.new'),{
                onSuccess: (response) => {
                    Swal.fire('Success','new attendance log added','success');
                    newLogData.reset( 'employee_id','type','date_time','name');
                }
            })
        }
        function employeesList(){
            return usePage().props.value.employees
        }


        return {
            attendancesData,
            attendancesLink,
            addAttendanceLogFormSubmit,
            attendanceTypes,
            newLogData,
            employeesList,
            VueDatePicker,
            minDate,
            maxDate
        }
    }
}
</script>
