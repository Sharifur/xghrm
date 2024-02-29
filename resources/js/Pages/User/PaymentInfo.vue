<template>
    <Head title="Payment Information"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title">Payment Info</h2>
                </div>
                <div class="edit-profile">
                    <div class="profile-info-dashboard">
                        <ValidationErrors/>
                        <div class="dashboard-profile-flex">
                            <div class="dashboard-address-details">
                                <form @submit.prevent>
                                    <BsTextarea  title="Payment Info" v-model="userData.paymentInfo"/>
                                    <div class="btn-wrapper margin-top-35">
                                        <BsButton button-text="Submit Changes" @click="changePaymentInfo" classes="cmn-btn btn-bg-1"/>
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
import BsInput from "@/Components/BsForm/Input.vue";
import BsTextarea from "@/Components/BsForm/Textarea.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import UserMaster from "@/Layouts/UserMaster.vue";
import Swal from "sweetalert2";


export default {
    name: "PaymentInfo",
    layout: UserMaster,
    components: {
        BsInput,
        ValidationErrors,
        Link,
        Head,
        BsButton,
        BsTextarea
    },
    setup(){

        const userData = useForm({
            paymentInfo : usePage().props.value.auth.user?.employee?.paymentInfo,
        });
        function changePaymentInfo(){
            //todo: submit form
            userData.post(route('user.profile.payment.info'),{
                onSuccess: (response) => {
                    Swal.fire('Success','updated payment info','success');
                }
            })
        }
        return {
            userData,
            changePaymentInfo
        }
    }
}
</script>
