<template>
    <div class="dashboard-area dashboard-padding">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                <div class="dashboard-left-content">
                    <div class="dashboard-close-main">
                        <div class="close-bars"> <i class="las la-times"></i> </div>
                        <div class="dashboard-top padding-top-40">
                            <div class="thumb">
                                <div class="img" v-if="$page.props.auth.user.image != null">
                                    <img src="assets/img/dashboard/authors.jpg" alt="">
                                </div>
                                <div class="char" v-else>
                                    {{$page.props.auth.user.name.slice(0,1)}}
                                </div>

                            </div>
                            <div class="author-content">
                                <h4 class="title"> {{ $page.props.auth.user.name }}</h4>
                                <span class="small-title"> {{$page.props.auth.user.username}} </span>
                            </div>
                        </div>
                        <div class="dashboard-bottom margin-top-35 margin-bottom-50">
                            <UserSidebar/>
                        </div>
                        <div class="dashboard-logo padding-top-100">
                            <Link :href="route('admin.home')" class="logo">
                                <img src="/images/fav-icon.png" alt="xgenious logo">
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="dashboard-right">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserSidebar from '@/Components/User/Sidebar.vue';
import { Link } from '@inertiajs/inertia-vue3';
export default {
    name: "UserMaster",
    components:{
        UserSidebar,
        Link
    }
}
</script>
