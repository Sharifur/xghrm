<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title"> Edit User Details</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.users.all')">All Users</Link>
                    </div>
                </div>

                <div class="edit-profile">
                    <div class="profile-info-dashboard">
                        <ValidationErrors/>
                        <div class="dashboard-profile-flex">
                            <div class="dashboard-address-details">
                                <form @submit.prevent>
                                    <input type="hidden" :value="UserFormData.id">
                                    <BsInput type="text" title="Name" v-model="UserFormData.name"/>
                                    <BsInput type="email" title="Email" v-model="UserFormData.email"/>
                                    <BsInput type="number" title="Phone" v-model="UserFormData.phone"/>
                                    <BsTextarea title="Address" v-model="UserFormData.address"/>
                                    <BsInput type="text" title="State" v-model="UserFormData.state"/>
                                    <BsInput type="text" title="City" v-model="UserFormData.city"/>
                                    <BsInput type="number" title="Zipcode" v-model="UserFormData.zipcode"/>
                                    <BsSelect title="Country" :options="country" v-model="UserFormData.country"/>
                                    <MediaUploader v-model="UserFormData.image"/>
                                    <div class="btn-wrapper margin-top-35">
                                        <BsButton button-text="Save Changes" @click="editUser" classes="cmn-btn btn-bg-1"/>
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
import country from "@/Components/data/country";

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
        const userInfo = usePage().props.value.userInfo;
        const UserFormData = useForm({
            id: userInfo.id,
            'name' : userInfo.name,
            'email':userInfo.email,
            'phone' : userInfo.phone,
            'address' : userInfo.address,
            'state' : userInfo.state,
            'city' : userInfo.city,
            'zipcode' : userInfo.zipcode,
            'country' : userInfo.country,
            'image' : userInfo.image,
        });

        function editUser(){
            UserFormData.post(route('admin.users.update'),{
               onSuccess: () => {
                   Swal.fire(
                       'Updated!',
                       'User Entry',
                       'success'
                   );
               }
            });
        }

        return {
            UserFormData,
            userInfo,
            editUser,
            country
        }
    }
}
</script>
