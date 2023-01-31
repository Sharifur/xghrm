<template>
    <div class="dashboard-area dashboard-padding">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon" v-if="!toggleStatus">
                    <div class="sidebar-icon" @click="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
                <div class="dashboard-left-content">
                    <div class="dashboard-close-main " :class="{'active' : toggleStatus }">
                        <div class="close-bars" v-if="toggleStatus" @click="toggleSidebarClose()"> <i class="fas fa-times"></i> </div>
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
                            <AdminSidebar/>
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
import AdminSidebar from '@/Components/Backend/Sidebar.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { onMounted, onUnmounted, ref } from 'vue'
export default {
    name: "AdminMaster",
    components:{
        AdminSidebar,
        Link,
    },
    setup(){
        var toggleStatus = ref(false);
        function toggleSidebar(){
            toggleStatus.value = true;
        }
        function toggleSidebarClose(){
            toggleStatus.value = false;
        }

        return {
            toggleSidebar,
            toggleStatus,
            toggleSidebarClose
        }
    }
}
</script>
