<template>
    <Head title="Admin Log in" />

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
                    <h2>Welcome, Login To the XGENIOUS CRM</h2>

                    <BreezeValidationErrors class="mb-4"/>
                    <form @submit.prevent="submit">
                        <div class="inputBx">
                            <BreezeInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus autocomplete="username" />
                            <span>Email or Username</span>
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
<!--                    <p>Forgot password?-->
<!--                        <Link :href="route('admin.password.request')">-->
<!--                            Reset Now-->
<!--                        </Link>-->
<!--                    </p>-->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeCheckbox from '@/Components/Checkbox.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeButton,
        BreezeCheckbox,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                username: '',
                password: '',
                remember: false
            }),
            showPassword: false
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('admin.login'), {
                onError: (error) => {
                  console.log(error);
                },
                onFinish: () => this.form.reset('password'),
            })
        },
        show_hide_password() {
            this.showPassword = !this.showPassword;
        }
    }
}
</script>
