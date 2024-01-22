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
                    <table class="table table-light">
                        <thead>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <tr v-for="attendant in attendancesData()" v-bind:key="attendant.id">
                                <td>{{attendant.id}}</td>
                                <td>{{attendant.name}}</td>
                                <td><span class="alert text-capitalize" :class="attendant.type === 'C/In' ? 'alert-success' : 'alert-danger'">{{attendant.type.replace('-',' ')}}</span></td>
                                <td>{{readableDateFormat(attendant.date_time)}}</td>
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
import VueDatePicker from "@vuepic/vue-datepicker";

export default {
    name: "Index",
    layout: AdminMaster,
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
        Datepicker,
        Head,
        VueDatePicker
    },
    setup(){

        const attendanceTypes = [
            {label: 'Leave' , value: 'leave'},
            {label: 'Sick Leave' , value: 'sick-leave'},
            {label: 'Paid Leave' , value: 'paid-leave'},
        ];

        const newLogData = useForm({
            employee_id: null,
            type: null,
            date_time: null
        });

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
        function addAttendanceLogFormSubmit(){
            newLogData.post(route('admin.employee.attendance.log.add'),{
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
            deleteItem,
            addAttendanceLogFormSubmit,
            attendanceTypes,
            newLogData,
            employeesList
        }
    }
}
</script>
