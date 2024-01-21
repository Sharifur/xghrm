<template>
    <Head title="All Employee"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Employees</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.employee.new')">Add New</Link>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Join Date</th>
                        <th>Salary</th>
                        <th>Total Leave ( {{ new Date().getFullYear() }} )</th>
                        <th>Status</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="employee in employeeList()" v-bind:key="employee.id">
                            <td>{{employee.id}}</td>
                            <td>{{ employee.name }} <Link v-show="employee?.user" :href="userRoute(employee)"><i class="fas fa-user btn-success text-white btn btn-sm" title="An User" ></i></Link></td>
                            <td>{{ employee?.category?.title }}</td>
                            <td>{{ new Date(employee.joinDate).toLocaleDateString() }}</td>
                            <td>{{employee.salary}} BDT</td>
                            <td>{{ employee?.attendance_log ? employee?.attendance_log.length : 0 }}</td>
                            <td><span class="alert" :class="employee.status === 1 ? 'alert-success' : 'alert-danger'">{{employeeStatus(employee.status)}}</span></td>
                            <td>
                                <Link class="btn btn-secondary m-1" :href="route('admin.employee.view',employee.id)">
                                    <i class="fas fa-eye"></i>
                                </Link>
                                <Link as="button" class="btn btn-danger m-1" @click="deleteItem(employee)">
                                    <i class="fas fa-trash"></i>
                                </Link>
                                <Link class="btn btn-info m-1" :href="route('admin.employee.edit',employee.id)">
                                    <i class="fas fa-pencil-alt"></i>
                                </Link>
                                <Link class="btn btn-warning m-1" :href="route('admin.employee.attendance.check',employee.id)">
                                    <i class="fas fa-calendar-alt"></i>
                                </Link>
                                <Link v-if="employee.user === null" class="btn btn-warning m-1" as="button" @click="convertToUser(employee)" >
                                    <i class="fas fa-retweet"></i>
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="employeeListLinks()"/>
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


export default {
    name: "Employee",
    layout: AdminMaster,
    components:{
        StatusShow,
        Link,
        Pagination,
        Head
    },
    setup(){
        function employeeList(){
            console.log(usePage().props.value.allEmployees.data);
            return usePage().props.value.allEmployees.data;
        }

        function deleteItem(item){
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
                    EmployeeData.post(route('admin.employee.delete'),{
                        onFinish : () => {
                            Swal.fire(
                                'Deleted!',
                                'Entry deleted.',
                                'success'
                            )
                        }
                    })

                }
            })
        }
        function employeeListLinks(){
            return usePage().props.value.allEmployees.links;
        }

        function employeeStatus(status){
            return status === 1 ? 'Working' : 'Left Job';
        }
        function userRoute(employee){
            if (employee?.user?.id){
                return  route('admin.users.view',employee?.user?.id);
            }
            return null;
        }
        function convertToUser(employee){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, make user!'
            }).then((result) => {

                if (result.isConfirmed) {
                    //axios request
                    axios.post(route('admin.employee.convert.user'),employee)
                        .then((response) => {
                            let responseData = response.data;
                            Swal.fire(
                                // 'Deleted!',
                                responseData.msg,
                                '',
                                responseData.type
                            )
                        });

                }
            })
        }

        return {
            employeeListLinks,
            employeeList,
            deleteItem,
            employeeStatus,
            userRoute,
            convertToUser
        }
    }
}
</script>

<style scoped>

</style>
