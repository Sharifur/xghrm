<template>
    <Head title="User Dashboard" />
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
                                    <h2 class="order-titles"> 12 </h2>
                                    <span class="order-para"> Total Leave </span>
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
                                    <h2 class="order-titles"> 2350 </h2>
                                    <span class="order-para"> Salary Slip </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserMaster from "@/Layouts/UserMaster.vue";
import {Head} from "@inertiajs/inertia-vue3";


export default {
    name: "UserDashboard",
    layout: UserMaster,
    components: {
        Head
    }
}
</script>

<style scoped>

</style>
