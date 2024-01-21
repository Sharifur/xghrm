<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40 margin-bottom-30">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">Attendance Reports</h2>
                    <div class="btn-wrapper">
                        <Link class="btn btn-info" :href="route('admin.employee.attendance.create')">Add New</Link>
                    </div>
                </div>
                <div class="table-wrap table-responsive">
                    <table class="table table-light">
                        <thead>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <tr v-for="attendant in attendancesData()" v-bind:key="attendant.id">
                                <td>{{attendant.id}}</td>
                                <td>{{attendant.name}}</td>
                                <td>{{readableDateFormat(attendant.start_date)}}</td>
                                <td>{{readableDateFormat(attendant.end_date)}}</td>
                                <td>
                                    <Link :href="route('admin.employee.attendance.edit',attendant.id)" class="btn btn-secondary m-2"><i class="fas fa-pencil-alt"></i></Link>
                                    <Link @click="deleteItem(attendant)" class="btn btn-danger m-2"><i class="fas fa-trash"></i></Link>
                                    <Link :href="route('admin.employee.attendance.extract',attendant.id)" class="btn btn-warning m-2"><i class="fas fa-box-open"></i></Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="attendancesLink()"/>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import {Link,usePage,useForm} from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";

export default {
    name: "Index",
    layout: AdminMaster,
    components:{
        Pagination,
        Link,
        usePage
    },
    setup(){
        function attendancesData(){
            return usePage().props.value.attendances.data;
        }
        function attendancesLink(){
            return usePage().props.value.attendances.links;
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
                const deleteStatement = useForm({id: item.id})
                if (result.isConfirmed) {
                    useForm({
                        id: item.id
                    }).post(route('admin.employee.attendance.delete'),{
                        onSuccess: () => {
                            Swal.fire('Deleted','item removed from system','warning');
                        }
                    });

                }
            })

        }

        return {
            attendancesData,
            attendancesLink,
            deleteItem
        }
    }
}
</script>
