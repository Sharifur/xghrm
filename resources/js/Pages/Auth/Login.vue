<template>
    <section>
        <div class="box">

            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <div class="square" style="--i:3;"></div>
            <div class="square" style="--i:4;"></div>
            <div class="square" style="--i:5;"></div>

            <div class="login_form_container">
                <div class="form">
                    <h2>Welcome, Login To the XGENIOUS HRM</h2>

                    <BreezeValidationErrors class="mb-4"/>
                    <form @submit.prevent="submit">
                        <div class="inputBx">
                            <input type="text" required="required" v-model="form.email">
                            <span>Email Or Username</span>
                            <img src="/svg/lnr-user.svg" alt="">
                        </div>
                        <div class="inputBx password">
                            <input id="password-input" v-model="form.password" :type="showPassword ? 'text' : 'password'" name="password"
                                   required="required">
                            <span>Password</span>
                            <a href="#" class="password-control" @click="show_hide_password"></a>
                            <img src="/svg/lnr-question-circle.svg" alt="">
                        </div>
                        <label class="remember">
                            <BreezeCheckbox name="remember" v-model:checked="form.remember"/>
                            Remember</label>
                        <div class="inputBx btn-box">
                            <input type="submit" value="Log in" >
                        </div>
                    </form>
                    <p>Forgot password?
                        <Link :href="route('password.request')">
                            Click Here
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import BreezeCheckbox from '@/Components/Checkbox.vue'
import "../../../scss/loginscreen.scss"

export default {
    name: "UserLogin",
    components: {
        BreezeValidationErrors,
        BreezeCheckbox,
        Link
    },
    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false
            }),
            showPassword: false
        }
    },
    methods: {
        submit() {
            this.form.post(this.route('login'), {
                onFinish: () => this.form.reset('password'),
            })
        },
        show_hide_password() {
            this.showPassword = !this.showPassword;
        }
    }
}
</script>
