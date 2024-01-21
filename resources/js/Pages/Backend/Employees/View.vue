<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="top-part margin-bottom-40">
                        <h2 class="dashboards-title margin-bottom-10">{{ employeeDetails.name }} Details - <small>{{employeeDetails.category?.title}}</small> </h2>
                        <h4>Salary : {{employeeDetails.salary}}BDT  - <span class="alert" :class="employeeDetails.status === 1 ? 'alert-success' : 'alert-danger'">{{employeeStatus(employeeDetails.status)}}</span></h4>
                    </div>
                    <div class="btn-wrp">
                        <Link :href="route('admin.employee.all')" class="btn btn-secondary mr-3">All Employee</Link>
                        <Link :href="route('admin.employee.edit',employeeDetails.id)" class="btn btn-warning">Edit Details</Link>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-default table-stripped">
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{employeeDetails.id}}</td>
                        </tr>
                        <tr>
                            <td>Join Date</td>
                            <td>{{employeeDetails.joinDate}}</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>{{employeeDetails.name}}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{employeeDetails.email}}</td>
                        </tr>
                        <tr>
                            <td>Mobile Number</td>
                            <td>{{employeeDetails.mobile}}</td>
                        </tr>
                        <tr>
                            <td>Emergency Number</td>
                            <td>{{employeeDetails.emergencyNumber}}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>{{employeeDetails.address}}</td>
                        </tr>
                        <tr>
                            <td>Date Of Birth</td>
                            <td>{{employeeDetails.joinDate}}</td>
                        </tr>
                        <tr>
                            <td>Personal Information (NID/Passport)</td>
                            <td>{{employeeDetails.personalInfo}}</td>
                        </tr>
                        <tr>
                            <td>Payment Info</td>
                            <td>{{employeeDetails.paymentInfo}}</td>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <td>//image will show here</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link,usePage} from "@inertiajs/inertia-vue3";
import  AdminMaster from "@/Layouts/AdminMaster.vue";
export default {
    name: "View",
    layout: AdminMaster,
    components:{
        Link
    },
    data(){
        return {
            employeeDetails : usePage().props.value.employeeDetails
        }
    },
    methods:{
         employeeStatus(status){
            return status === 1 ? 'Working' : 'Left Job';
        }
    }
}
</script>

