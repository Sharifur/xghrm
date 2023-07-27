<template>
    <Head title="Admin Dashboard" />
    <BreezeValidationErrors class="mb-4" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="row">
                    <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                        <div class="single-orders">
                            <div class="orders-shapes">
                                <img src="images/dashboard//orders-shapes.png" alt="">
                            </div>
                            <div class="orders-flex-content">
                                <div class="icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="contents">
                                    <h2 class="order-titles"> {{ totalProducts }} </h2>
                                    <span class="order-para">Laravel Products </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                        <div class="single-orders">
                            <div class="orders-shapes">
                                <img src="images/dashboard//orders-shapes2.png" alt="">
                            </div>
                            <div class="orders-flex-content">
                                <div class="icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="contents">
                                    <h2 class="order-titles"> {{ totalUsers }} </h2>
                                    <span class="order-para"> Total Users</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                        <div class="single-orders">
                            <div class="orders-shapes">
                                <img src="images/dashboard/orders-shapes3.png" alt="">
                            </div>
                            <div class="orders-flex-content">
                                <div class="icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="contents">
                                    <h2 class="order-titles"> {{totalTickets}}</h2>
                                    <span class="order-para"> Total Employee </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                        <div class="single-orders">
                            <div class="orders-shapes">
                                <img src="images/dashboard/orders-shapes4.png" alt="">
                            </div>
                            <div class="orders-flex-content">
                                <div class="icon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="contents">
                                    <h2 class="order-titles"> {{ totalAdmins }} </h2>
                                    <span class="order-para"> Total Admin </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        a chart with all user attendance
                        a chart of each month salary spends

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import { Head, Link ,usePage} from '@inertiajs/inertia-vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
export default {
    layout: AdminMaster,
    name: "Dashboard",
    components:{
        BreezeValidationErrors,
        Head,
        Link
    },
    setup(props,context){
        const totalProducts = usePage().props.value.products;
        const totalUsers = usePage().props.value.users;
        const totalAdmins = usePage().props.value.admins;
        const totalTickets = usePage().props.value.tickets;
        return {
            totalProducts,
            totalUsers,
            totalAdmins,
            totalTickets
        }
    }

}

</script>

<style scoped>

</style>
