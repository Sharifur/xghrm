<template>
    <Head title="Change Info"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title">Change Info</h2>
                </div>
                <div class="edit-profile">
                    <div class="profile-info-dashboard">
                        <ValidationErrors/>
                        <div class="dashboard-profile-flex">
                            <div class="dashboard-address-details">
                                <form @submit.prevent>
                                    <BsInput type="password" title="Old Password*" v-model="userData.oldPassword"/>
                                    <BsInput type="password" title="New Password*" v-model="userData.password"/>
                                    <BsInput type="password" title="Confirm Password*" v-model="userData.password_confirmation"/>
                                    <div class="btn-wrapper margin-top-35">
                                        <BsButton button-text="Change Password" @click="changePassword" classes="cmn-btn btn-bg-1"/>
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
import BsInput from "@/Components/BsForm/Input";
import BsSelect from "@/Components/BsForm/Select";
import BsTextarea from "@/Components/BsForm/Textarea";
import BsButton from "@/Components/BsForm/Button";
import Datepicker from 'vue3-datepicker'
import ValidationErrors from "@/Components/ValidationErrors";
import Swal from "sweetalert2";
import { ref } from '@vue/runtime-core';
import axios from 'axios';
import UserMaster from "@/Layouts/UserMaster";


export default {
    name: "ChangeInfo",
    layout: UserMaster,
    components: {
        BsInput,
        ValidationErrors,
        Link,
        Head,
        BsButton
    },
    setup(){

        const userData = useForm({
            oldPassword: null,
            password : null,
            password_confirmation : null
        });

        function changePassword(){
            //todo: submit form
        }
        return {
            userData,
            changePassword
        }
    }
}
</script>
