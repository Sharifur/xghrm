<template>
    <Head title="All Salary Slip"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Salary Slip</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.employee.salary.slip.create')">Add New</Link>
                    </div>
                </div>
                <form method="get" @submit.prevent class="filter_form mb-5">
                    <Select title="Employee" :options="employeesList()" v-model="filterData.employee"/>
                    <BsButton button-text="Submit" button-type="submit" @click="filterFormSubmit" :disabled="filterData.processing"/>
                </form>
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Employee Name</th>
                        <th>Salary Month</th>
                        <th>Base Salary</th>
                        <th>Created At</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="salary in salaryList()" v-bind:key="salary.id">
                            <td>{{salary.id}}</td>
                            <td>{{ salary?.employee?.name }}</td>
                            <td>{{ salary.monthName }} {{ salary.year }}</td>
                            <td>{{ salary.salary }}BDT</td>
                            <td>{{ salary.created }}</td>
                            <td>
                                <Link as="button" class="btn btn-danger m-1" @click="deleteItem(salary)">
                                    <i class="fas fa-trash"></i>
                                </Link>
                                <Link class="btn btn-info m-1" :href="route('admin.employee.salary.slip.edit',salary.id)">
                                    <i class="fas fa-pencil-alt"></i>
                                </Link>
                                <Link class="btn btn-secondary m-1" :href="route('admin.employee.salary.slip.view',salary.id)">
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
import BsButton from "@/Components/BsForm/Button.vue";
import Select from "@/Components/BsForm/Select.vue";


export default {
    name: "Employee",
    layout: AdminMaster,
    components:{
        Select, BsButton,
        StatusShow,
        Link,
        Pagination,
        Head
    },
    setup(){
        function salaryList(){
            return usePage().props.value.allSalaries.data;
        }

        const filterData = useForm({
            employee: usePage().props.value.employee,
        });
        function filterFormSubmit(){
            filterData.get(route('admin.employee.salary.slip'));
        }
        function employeesList(){
            return usePage().props.value.employees
        }

        function deleteItem(item){
            console.log('test')
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                const EmployeeData = useForm({id:item.id})
                if (result.isConfirmed) {
                    // write code for delete salary slip
                    EmployeeData.post(route('admin.employee.salary.slip.delete',item.id),{
                        onFinish : () => {
                            Swal.fire(
                                'Deleted!',
                                'Entry deleted.',
                                'success'
                            )
                        }
                    });
                }
            })
        }
        function salaryListLinks(){
            return usePage().props.value.allSalaries.links;
        }


        return {
            salaryListLinks,
            salaryList,
            deleteItem,
            filterData,
            employeesList,
            filterFormSubmit
        }
    }
}
</script>

<style scoped>

</style>
