<template>
    <Head title="Extract Attendance Report By Person" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">Extract Attendance Report By Person</h2>
                    <div class="btn-wrapper">
                        <Link class="btn btn-info m-2" :href="route('admin.employee.attendance')">All Attendance Reports</Link>
                        <Link class="btn btn-secondary m-2" :href="route('admin.employee.attendance.create')">Create Attendance Reports</Link>
                    </div>
                </div>
                <ValidationErrors/>
                <form @submit.prevent>

                    <Select title="Employee" v-model="attendanceLogData.importType" :options="importTypes" />

                    <Select v-if="attendanceLogData.importType === 'individual'" title="Employee" v-model="attendanceLogData.employee_id" :options="employees" />
                    <Select v-if="attendanceLogData.importType === 'individual'" title="CSV Column For Search Name" @change="getCsvColumnValues" v-model="attendanceLogData.csv_column" :options="csvHeader" />
                    <Select v-if="attendanceLogData.importType === 'individual'" title="Column Value For Name" v-model="attendanceLogData.column_value" :options="columnValueData" />

                    <Select
                    title="CSV Attendance Column Date/Time"
                    v-model="attendanceLogData.attendance_column_value"
                    :options="csvHeader"
                    info="select date column"
                    />
                    <Select
                    title="CSV Attendance In/Out Type"
                     v-model="attendanceLogData.attendance_type_column_value"
                     :options="csvHeader"
                     info="select check in/ check out column"
                     />
                    <Button button-text="Submit Changes" @click="createAttendanceLog"/>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster";
import {Link, useForm, usePage,Head} from "@inertiajs/inertia-vue3";
import Input from "@/Components/BsForm/Input";
import Button from "@/Components/BsForm/Button";
import Select from "@/Components/BsForm/Select";
import MediaUploader from "@/Components/MediaUploader";
import Datepicker from "vue3-datepicker";
import Swal from "sweetalert2";
import ValidationErrors from "@/Components/ValidationErrors";
import {ref} from "vue";

export default {
    name: "ExtractByPerson",
    layout: AdminMaster,
    components: {
        Select,
        Link,
        Input,
        MediaUploader,
        Datepicker,
        Button,
        ValidationErrors,
        Head
    },
    setup() {
        const csvHeader = usePage().props.value.csv_headers;
        const employees = usePage().props.value.employees;
        const attendance = usePage().props.value.attendance;

        const attendanceLogData = useForm({
            attendance_report_id: attendance.id,
            csv_column: null,
            column_value: null,
            attendance_column_value: null,
            attendance_type_column_value: null,
            employee_id: null,
            importType: 'bulk',
        });
        function createAttendanceLog(){
            attendanceLogData.post(route('admin.employee.attendance.log.from.csv'),{
                onSuccess: (data) => {
                    attendanceLogData.reset('csv_column','employee_id','column_value')
                    Swal.fire('Success','Attendance Log Insert','success');
                }
            })
        }
        const columnValueData = ref([{'label' : 'select csv Column first','value': null}]);
        const importTypes = [
            {'label' : 'Bulk Import','value': 'bulk'},
            {'label' : 'Individual Import','value': 'individual'},
        ];

        function getCsvColumnValues(){
            if ( attendanceLogData.csv_column === null){
                Swal.fire('Warning','Select Csv Column First','warning');
            }
            axios
            .post(route('admin.employee.attendance.csv.column.value'),{
                attendance_report_id : attendanceLogData.attendance_report_id,
                csv_column : attendanceLogData.csv_column,
                column_value : attendanceLogData.column_value,
            })
            .then((response) => {
                if (Object.keys(response.data).length > 1){
                    columnValueData.value = response.data;
                }else {
                    Swal.fire('Error!','no data found in selected column','warning');
                }
            })
            .catch((error) => {
                Swal.fire('Error!',error.message,'warning');
            })
        }
        return {
            columnValueData,
            createAttendanceLog,
            getCsvColumnValues,
            attendanceLogData,
            csvHeader,
            attendance,
            employees,
            importTypes
        }
    }
}
</script>

