<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="top-part margin-bottom-40">
                        <h2 class="dashboards-title margin-bottom-10">{{ userInfo.name }} Details </h2>
                    </div>
                    <div class="btn-wrp">
                        <Link :href="route('admin.users.all')" class="btn btn-secondary mr-3">All Users</Link>
                        <Link :href="route('admin.users.edit',userInfo.id)" class="btn btn-warning">Edit Details</Link>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-default table-stripped">
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{userInfo.id}}</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>{{userInfo.name}}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{userInfo.email}} <i v-show="userInfo.email_verified_at != null" class="fas fa-check-circle text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>{{userInfo.phone}}</td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>{{userInfo.username}}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>{{userInfo.address}}</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>{{userInfo.country}}</td>
                        </tr>
                        <tr>
                            <td>State</td>
                            <td>{{userInfo.state}}</td>
                        </tr>

                        <tr>
                            <td>City</td>
                            <td>{{userInfo.city}}</td>
                        </tr>
                        <tr>
                            <td>Zipcode</td>
                            <td>{{userInfo.zipcode}}</td>
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
            userInfo : usePage().props.value.userInfo
        }
    },
    methods:{
         employeeStatus(status){
            return status === 1 ? 'Working' : 'Left Job';
        }
    }
}
</script>

