<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">Add New Attendance Report</h2>
                    <div class="btn-wrapper">
                        <Link class="btn btn-info" :href="route('admin.employee.attendance')">All Attendance Reports
                        </Link>
                    </div>
                </div>
                <ValidationErrors/>
                <form @submit.prevent>
                    <Input title="Name" v-model="attendanceData.name"/>
                    <div class="single-info-input margin-top-30">
                        <MediaUploader button-text="Upload File" v-model="attendanceData.file_id"/>
                        <small>select only csv file</small>
                    </div>
                    <Button button-text="Submit Changes" @click="createAttendance"/>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import {Link, useForm} from "@inertiajs/inertia-vue3";
import Input from "@/Components/BsForm/Input.vue";
import Button from "@/Components/BsForm/Button.vue";
import MediaUploader from "@/Components/MediaUploader.vue";
import Datepicker from "vue3-datepicker";
import Swal from "sweetalert2";
import ValidationErrors from "@/Components/ValidationErrors.vue";


export default {
    name: "Create",
    layout: AdminMaster,
    components: {
        Link,
        Input,
        MediaUploader,
        Datepicker,
        Button,
        ValidationErrors
    },
    setup() {
        const attendanceData = useForm({
            name: null,
            start_date: new Date(),
            end_date: new Date(),
            file_id: null
        });
        function createAttendance(){
            attendanceData.post(route('admin.employee.attendance.create'),{
                onSuccess: () => {
                    attendanceData.reset('name','start_date','end_date','file_id')
                    Swal.fire('Success','Attendance Insert','success');
                }
            })
        }
        return {
            attendanceData,
            createAttendance
        }
    }
}
</script>

