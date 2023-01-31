<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title"> Add New Employee</h2>
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
                                        <BsButton button-text="Add New" @click="addNewEmployee" classes="cmn-btn btn-bg-1"/>
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
import AdminMaster from "@/Layouts/AdminMaster";
import BsInput from "@/Components/BsForm/Input";
import BsSelect from "@/Components/BsForm/Select";
import BsTextarea from "@/Components/BsForm/Textarea";
import BsButton from "@/Components/BsForm/Button";
import Datepicker from 'vue3-datepicker'
import ValidationErrors from "@/Components/ValidationErrors";
import Swal from "sweetalert2";
import MediaUploader from "@/Components/MediaUploader";



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
        MediaUploader
    },
    setup(props,context){
        const emplyeeFormdata = useForm({
                paymentInfo: null,
            emergencyNumber: null,
            address:null,
            mobile:null,
            salary:null,
            name:null,
            email:null,
            catId:null,
            personalInfo:null,
            imageId:null,
            status:null,
            att_id:null,
            joinDate: new Date(),
            dateOfBirth: new Date(),
        });

        function addNewEmployee(){
            emplyeeFormdata.post(route('admin.employee.new'),{
                onSuccess: () => {
                    Swal.fire(
                        'Added!',
                        'New Employee Entry',
                        'success'
                    );
                    emplyeeFormdata.reset('personalInfo','paymentInfo','emergencyNumber','address','mobile','salary','name','email','imageId','joinDate','dateOfBirth','att_id')
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
            addNewEmployee,
            statusOption
        }
    }
}
</script>
