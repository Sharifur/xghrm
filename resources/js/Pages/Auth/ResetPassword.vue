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

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=El+Messiri:wght@700&display=swap");

* {
    margin: 0;
    padding: 0;
    font-family: "El Messiri", sans-serif;
}

body {
    background: #031323;
    overflow: hidden;
}

.fas {
    width: 32px;
}

section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradient 10s ease infinite;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.box {
    position: relative;
}

.box .square {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 15px;
    animation: square 10s linear infinite;
    animation-delay: calc(-1s * var(--i));
}

@keyframes square {
    0%, 100% {
        transform: translateY(-20px);
    }
    50% {
        transform: translateY(20px);
    }
}

.box .square:nth-child(1) {
    width: 100px;
    height: 100px;
    top: -15px;
    right: -45px;
}

.box .square:nth-child(2) {
    width: 150px;
    height: 150px;
    top: 105px;
    left: -125px;
    z-index: 2;
}

.box .square:nth-child(3) {
    width: 60px;
    height: 60px;
    bottom: 85px;
    right: -45px;
    z-index: 2;
}

.box .square:nth-child(4) {
    width: 50px;
    height: 50px;
    bottom: 35px;
    left: -95px;
}

.box .square:nth-child(5) {
    width: 50px;
    height: 50px;
    top: -15px;
    left: -25px;
}

.box .square:nth-child(6) {
    width: 85px;
    height: 85px;
    top: 165px;
    right: -155px;
    z-index: 2;
}

.container {
    position: relative;
    padding: 50px;
    width: 600px;
    min-height: 380px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border-radius: 10px;
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
}

.container::after {
    content: "";
    position: absolute;
    top: 5px;
    right: 5px;
    bottom: 5px;
    left: 5px;
    border-radius: 5px;
    pointer-events: none;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.1) 2%);
}

.form {
    position: relative;
    width: 100%;
    height: 100%;
}

.form h2 {
    color: #fff;
    letter-spacing: 2px;
    margin-bottom: 30px;
    text-align: center;
    font-size: 50px;
    line-height: 54px;
}

.form .inputBx {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
}
.form .inputBx img {
    position: absolute;
    left: 10px;
    top: 10px;
}
.form .inputBx input {
    width: 100%;
    outline: none;
    border: none;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 10px;
    padding-left: 10px;
    border-radius: 15px;
    color: #fff;
    font-size: 16px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.form .inputBx .password-control {
    position: absolute;
    top: 11px;
    right: 10px;
    display: inline-block;
    width: 20px;
    height: 20px;
    background: url(https://snipp.ru/demo/495/view.svg) 0 0 no-repeat;
    transition: 0.5s;
}

.form .inputBx .view {
    background: url(https://snipp.ru/demo/495/no-view.svg) 0 0 no-repeat;
    transition: 0.5s;
}

.form .inputBx .fas {
    position: absolute;
    top: 13px;
    left: 13px;
}

.form .inputBx input[type=submit] {
    background: #fff;
    color: #111;
    max-width: 280px;
    padding: 8px 10px;
    box-shadow: none;
    letter-spacing: 1px;
    cursor: pointer;
    transition: 1.5s;
}

.form .inputBx input[type=submit]:hover {
    background: linear-gradient(115deg, rgba(0, 0, 0, 0.1), rgba(255, 255, 255, 0.25));
    color: #fff;
    transition: 0.5s;
}

.form .inputBx input::placeholder {
    color: #fff;
}

.form .inputBx span {
    position: absolute;
    left: 10px;
    padding: 10px;
    display: inline-block;
    color: #fff;
    transition: 0.5s;
    pointer-events: none;
}

.form .inputBx input:focus ~ span,
.form .inputBx input:valid ~ span {
    transform: translateX(-10px) translateY(-25px);
    font-size: 12px;
}

.form p {
    color: #fff;
    font-size: 15px;
    margin-top: 5px;
}

.form p a {
    color: #fff;
}

.form p a:hover {
    background-color: #000;
    background-image: linear-gradient(to right, #434343 0%, black 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.remember {
    position: relative;
    display: inline-block;
    color: #fff;
    margin-bottom: 10px;
    cursor: pointer;
}
.inputBx.btn-box {text-align: center;}
.form p {
    text-align: center;
}
</style>

