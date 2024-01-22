<template>
    <Head title="All Attendance Logs"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40 margin-bottom-30">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All {{page_type}} Attendance Logs</h2>

                    <div class="btn-wrapper">
                        <button  @click="approveAllLogs" class="btn btn-danger" >Approve All Pending Log</button>
                         <BsModalButton target="addnewcategory" button-class="btn btn-info m-1" >Add New Log</BsModalButton>
                    </div>
                </div>
<!--                <Select title="Filter Attendance" :options="filterOptions" v-model="filterData.filter" @change="applyFilter($event.target.value)"/>-->
                <div class="table-wrap table-responsive mt-5">
                    <table class="table table-light">
                        <thead>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Time </th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <tr v-for="attendant in attendancesData()" v-bind:key="attendant.id">
                                <td>{{attendant.id}}</td>
                                <td>{{attendant.name}}</td>
                                <td>
                                    <span class="alert text-capitalize" :class="attendant.type === 'C/In' ? 'alert-success' : 'alert-danger'">{{attendant.type.replace('-',' ')}}</span>
                                    <Link v-show="attendant.status === 0" @click="submitApproveData(attendant.id)" class="btn btn-success m-2"><i class="fas fa-check"></i></Link>
                                </td>
                                <td>{{readableDateFormat(attendant.date_time)}}</td>

                                <td>{{new Date(attendant.date_time).toLocaleTimeString('en-US',{timeZone:'Asia/Dhaka'})}}</td>
                                <td>
                                    <Link @click="deleteItem(attendant)" class="btn btn-danger m-2"><i class="fas fa-trash"></i></Link>
                                </td>
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
        <BsSelect title="Employee" :options="employeesList()" v-model="newLogData.employee_id"/>
        <BsSelect title="Status" :options="attendanceTypes" v-model="newLogData.type"/>
        <div class="single-info-input margin-top-30">
            <label class="info-title">Date Time</label>
            <VueDatePicker v-model="newLogData.date_time"/>
        </div>
        <BsButton button-text="Submit" button-type="submit" @click="addAttendanceLogFormSubmit" :disabled="newLogData.processing"/>

    </form>
</BsModal>

</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import {Link,usePage,useForm,Head} from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";
import BsModal from "@/Components/BsModal.vue";
import BsModalButton from "@/Components/BsModalButton.vue";
import BsInput from "@/Components/BsForm/Input.vue";
import BsSelect from "@/Components/BsForm/Select.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import Datepicker from "vue3-datepicker";
import Select from "@/Components/BsForm/Select.vue";
import {ref} from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";

export default {
    name: "Index",
    layout: AdminMaster,
    components:{
        VueDatePicker,
        Select,
        Pagination,
        Link,
        usePage,
        BsModal,
        BsModalButton,
        BsInput,
        BsSelect,
        BsButton,
        BreezeValidationErrors,
        Datepicker,
        Head
    },
    setup(){
        const filterOptions = [
            {"label": "Pending", value: 0},
            {"label": "Approved", value: 1}
        ];
        const attendanceTypes = [
            {label: 'C/In' , value: 'C/In'},
            {label: 'C/Out' , value: 'C/Out'},
            {label: 'Leave' , value: 'leave'},
            {label: 'Sick Leave' , value: 'sick-leave'},
            {label: 'Paid Leave' , value: 'paid-leave'},
            {label: 'Holiday' , value: 'holiday'},
            {label: 'Work From Home' , value: 'work-from-home'},
        ];

        const filterData = useForm({
            filter: null
        });
        const newLogData = useForm({
            employee_id: null,
            type: null,
            date_time: null
        });
        const page_type = usePage().props.value.page_type;
        function applyFilter(){
            filterData.get(route('admin.employee.attendance.logs'));
        }

        function attendancesData(){
            return usePage().props.value.attendance_logs.data;
        }
        function attendancesLink(){
            return usePage().props.value.attendance_logs.links;
        }
        function deleteItem(item){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    useForm({
                        id: item.id
                    }).post(route('admin.employee.attendance.logs.delete'),{
                        onSuccess: () => {
                            Swal.fire('Deleted','item removed from system','warning');
                        }
                    });

                }
            })
        }
        function submitApproveData(item){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    useForm({
                        id: item
                    }).post(route('admin.employee.attendance.logs.approve'),{
                        onSuccess: () => {
                            //Swal.fire('Approved','log approve','success');
                        }
                    });

                }
            })
        }

        function addAttendanceLogFormSubmit(){
            newLogData.post(route('admin.employee.attendance.log.add'),{
                onSuccess: (response) => {
                    Swal.fire('Success','new attendance log added','success');
                }
            })
        }
        function employeesList(){
            return usePage().props.value.employees
        }

        function approveAllLogs(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    //todo:: fire an axios call to update it
                    //admin.employee.attendance.logs.approve.pending
                    axios.post(route('admin.employee.attendance.logs.approve.pending'));
                    window.location.reload();
                }
            })
        }

        return {
            attendancesData,
            attendancesLink,
            deleteItem,
            addAttendanceLogFormSubmit,
            attendanceTypes,
            newLogData,
            employeesList,
            filterOptions,
            applyFilter,
            filterData,
            page_type,
            submitApproveData,
            approveAllLogs
        }
    }
}
</script>
