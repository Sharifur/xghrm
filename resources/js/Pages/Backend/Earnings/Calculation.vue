<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">Earning Statements</h2>
                    <div class="div">
                        <Link class="btn btn-secondary m-1" :href="route('admin.earning.all')">
                            All Earnings
                        </Link>
                    </div>
                </div>
                <h4><strong>Office Earning :</strong> {{Math.floor(earningData.office)}}USD (Approx)</h4>
                <h4><strong>Personal Earning :</strong> {{Math.floor(earningData.personal)}}USD (Approx)</h4>
                <h4><strong>Total Earning :</strong> {{Math.floor(earningData.total)}}USD (Approx)</h4>

            </div>
        </div>
    </div>
</template>

<script>
import {usePage,Link} from "@inertiajs/inertia-vue3";
import AdminMaster from "@/Layouts/AdminMaster.vue";

export default {
    name: "Calculation",
    layout: AdminMaster,
    components:{
        Link
    },
    setup(){
        const pageData = usePage().props.value
        const earningData = {
            personal : pageData.personal_earning,
            office: pageData.office_earning,
            total: pageData.total_earning
        }
        return {
            earningData
        }
    }
}
</script>
