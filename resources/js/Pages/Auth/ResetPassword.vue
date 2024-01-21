<template>
    <Head title="Reset Password" />

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
                    <h2>Reset password?</h2>
<!--                    <p>No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>-->
                    <BreezeValidationErrors class="mb-4"/>
                    <form @submit.prevent="submit">
                        <div class="inputBx">
                            <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
                            <span>Email</span>
                        </div>
                        <div class="inputBx">
                            <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                            <span>Password</span>
                        </div>
                        <div class="inputBx">
                            <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                            <span>Confirm Password</span>
                        </div>
                        <div class="inputBx btn-box">
                            <input type="submit" value="Reset Password" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>


<!--    <form @submit.prevent="submit">-->
<!--        <div>-->
<!--            <BreezeLabel for="email" value="Email" />-->
<!--            <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />-->
<!--        </div>-->

<!--        <div class="mt-4">-->
<!--            <BreezeLabel for="password" value="Password" />-->
<!--            <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />-->
<!--        </div>-->

<!--        <div class="mt-4">-->
<!--            <BreezeLabel for="password_confirmation" value="Confirm Password" />-->
<!--            <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />-->
<!--        </div>-->

<!--        <div class="flex items-center justify-end mt-4">-->
<!--            <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">-->
<!--                Reset Password-->
<!--            </BreezeButton>-->
<!--        </div>-->
<!--    </form>-->
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
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
        email: String,
        token: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: '',
                password_confirmation: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.update'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>

