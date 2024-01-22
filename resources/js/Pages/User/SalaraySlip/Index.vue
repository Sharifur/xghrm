<template>
    <Head title="All Salary Slip"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Salary Slip</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Salary Month</th>
                        <th>Basic Salary</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="salary in salaryList()" v-bind:key="salary.id">
                            <td>{{salary.id}}</td>
                            <td>{{ salary.monthName }} {{ salary.year }}</td>
                            <td>{{ salary.salary }}BDT</td>
                            <td>
                                <Link class="btn btn-secondary m-1" :href="route('user.salary.slip.view',salary.id)">
                                    <i class="fas fa-eye"></i>
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="salaryListLinks()"/>
            </div>
        </div>
    </div>
</template>

<script>
import {Link, useForm, usePage,Head} from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import AdminMaster from "@/Layouts/AdminMaster.vue";
import Swal from "sweetalert2";
import StatusShow from "@/Components/StatusShow.vue";
import UserMaster from "@/Layouts/UserMaster.vue";


export default {
    name: "Employee",
    layout: UserMaster,
    components:{
        StatusShow,
        Link,
        Pagination,
        Head
    },
    setup(){
        function salaryList(){
            return usePage().props.value.allSalaries.data;
        }

        function salaryListLinks(){
            return usePage().props.value.allSalaries.links;
        }

        return {
            salaryListLinks,
            salaryList
        }
    }
}
</script>

<style scoped>

</style>
