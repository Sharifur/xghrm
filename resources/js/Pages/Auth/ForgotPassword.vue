<template>
    <Head title="Forgot Password" />

    <section>

        <div class="box">

            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <div class="square" style="--i:3;"></div>
            <div class="square" style="--i:4;"></div>
            <div class="square" style="--i:5;"></div>

            <div class="container">
                <div class="form">
                    <h2>Forgot your password?</h2>
                    <p>No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                    <BreezeValidationErrors class="mb-4"/>
                    <div v-if="status" class="alert alert-success">
                        {{ status }}
                    </div>
                    <form @submit.prevent="submit">
                        <div class="inputBx">
                            <input type="email" required="required" v-model="form.email">
                            <span>Email</span>
                            <img src="/svg/lnr-user.svg" alt="">
                        </div>
                        <div class="inputBx btn-box">
                            <input type="submit" value="Email Password Reset Link" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: ''
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.email'))
        }
    }
}
</script>
