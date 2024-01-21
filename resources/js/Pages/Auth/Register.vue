<template>
    <Head title="Register" />
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
                    <h2>Welcome, Register Into XGENIOUS CRM</h2>

                    <BreezeValidationErrors class="mb-4"/>

                    <form @submit.prevent="submit">
                        <div class="inputBx">
                            <BreezeInput type="text" v-model="form.name" required autofocus autocomplete="name" />
                            <span>Name</span>
                        </div>
                        <div class="inputBx">
                            <BreezeInput  type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" />
                            <span>Email</span>
                        </div>
                        <div class="inputBx">
                            <BreezeInput type="text" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" />
                            <span>Username</span>
                        </div>
                        <div class="inputBx">
                            <BreezeInput type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                            <span>Password</span>
                        </div>
                        <div class="inputBx">
                            <BreezeInput type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                            <span>Confirm Password</span>
                        </div>
                        <div class="inputBx btn-box">
                            <input type="submit" value="Register" >
                        </div>
                    </form>
                    <p>
                        <Link :href="route('login')">
                            Already registered?
                        </Link>
                    </p>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                username: '',
                terms: false,
            })
        }
    },

    methods: {
        submit() {
            console.log(this.form);
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
