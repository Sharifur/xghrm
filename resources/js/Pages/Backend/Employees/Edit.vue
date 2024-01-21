<template>
    <Head title="Edit Employee Details" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title"> Edit Employee Details</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.employee.all')">All Employee</Link>
                    </div>
                </div>

                <div class="edit-profile">
                    <div class="profile-info-dashboard">
                        <ValidationErrors/>
                        <div class="dashboard-profile-flex">
                            <div class="dashboard-address-details">
                                <form @submit.prevent>
                                    <input type="hidden" :value="emplyeeFormdata.id">
                                    <BsInput type="text" title="Name*" v-model="emplyeeFormdata.name"/>
                                    <BsInput type="email" title="Email*" v-model="emplyeeFormdata.email"/>
                                    <BsSelect title="Category*" :options="catList()" v-model="emplyeeFormdata.catId"/>
                                    <BsSelect title="Status*" :options="statusOption" v-model="emplyeeFormdata.status"/>
                                    <BsInput type="number" title="Mobile Number*" v-model="emplyeeFormdata.mobile"/>
                                    <BsInput type="number" title="Salary*" v-model="emplyeeFormdata.salary"/>
                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label class="info-title"> Date Of Birth* </label>
                                            <Datepicker v-model="emplyeeFormdata.dateOfBirth"/>
                                        </div>
                                    </div>
                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label class="info-title"> Join Date* </label>
                                            <Datepicker v-model="emplyeeFormdata.joinDate"/>
                                        </div>
                                    </div>
                                    <BsTextarea title="Address" v-model="emplyeeFormdata.address"/>
                                    <BsInput type="text" title="Attendance ID" v-model="emplyeeFormdata.att_id"/>
                                    <BsInput type="number" title="Emergency Number*" v-model="emplyeeFormdata.emergencyNumber"/>
                                    <BsTextarea title="Payment Information" v-model="emplyeeFormdata.paymentInfo"/>
                                    <BsTextarea title="Personal Information (NID/Passport Info)" v-model="emplyeeFormdata.personalInfo"/>
                                    <MediaUploader v-model="emplyeeFormdata.imageId"/>
                                    <div class="btn-wrapper margin-top-35">
                                        <BsButton button-text="Save Changes" @click="editEmployee" classes="cmn-btn btn-bg-1"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Head,Link,useForm,usePage} from '@inertiajs/inertia-vue3';
import AdminMaster from "@/Layouts/AdminMaster.vue";
import BsInput from "@/Components/BsForm/Input.vue";
import BsSelect from "@/Components/BsForm/Select.vue";
import BsTextarea from "@/Components/BsForm/Textarea.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import Datepicker from 'vue3-datepicker'
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Swal from "sweetalert2";
import MediaUploader from "@/Components/MediaUploader.vue";

export default {
    name: "new",
    layout: AdminMaster,
    components:{
        BsInput,
        BsSelect,
        BsTextarea,
        BsButton,
        Datepicker,
        ValidationErrors,
        Link,
        MediaUploader,
        Head
    },
    setup(props,context){
        const employeeDetails = usePage().props.value.employeeDetails;
        const emplyeeFormdata = useForm({
                paymentInfo: employeeDetails.paymentInfo,
            emergencyNumber: employeeDetails.emergencyNumber,
            address:employeeDetails.address,
            mobile:employeeDetails.mobile,
            salary:employeeDetails.salary,
            name:employeeDetails.name,
            email:employeeDetails.email,
            catId:employeeDetails.catId,
            personalInfo:employeeDetails.personalInfo,
            imageId:employeeDetails.imageId,
            att_id:employeeDetails.att_id,
            status:employeeDetails.status,
            joinDate: new Date(employeeDetails.joinDate),
            dateOfBirth: new Date(employeeDetails.dateOfBirth),
            id: employeeDetails.id
        });

        function editEmployee(){
            emplyeeFormdata.post(route('admin.employee.update'),{
               onSuccess: () => {
                   Swal.fire(
                       'Updated!',
                       'Employee Entry',
                       'success'
                   );
               }
            });
        }

        function catList(){
            return usePage().props.value.category;
        }
        const statusOption = [
            {label:'Working',value:1},
            {label:'Left Job',value:0}
        ]
        return {
            emplyeeFormdata,
            catList,
            editEmployee,
            statusOption
        }
    }
}
</script>
