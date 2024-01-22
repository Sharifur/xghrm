<template>
    <ul class="dashboard-list dashboard-menu-ul">
        <li class="list" :class="{'active' : $page.url === '/user-home'}">
            <Link :href="route('user.home')" >
                <span>Dashboard</span>
            </Link>
        </li>
        <li class="list" v-if="isEmployee">
            <Link :href="route('user.attendance.index')">
                <span>Attendance Check</span>
            </Link>
        </li>
        <li class="list" v-if="isEmployee">
            <Link :href="route('user.leaves.index')">
               <span>Leaves</span>
            </Link>
        </li>
        <li class="list" v-if="isEmployee">
            <Link :href="route('user.salary.slip.index')">
                <span> Salary Slip</span>
            </Link>
        </li>
        <li class="list" v-if="isEmployee">
            <Link :href="route('user.profile.change.password')">
                <span>Change Password</span>
            </Link>
        </li>
        <li class="list logoutbtn">
            <Link :href="route('logout')" method="post"  as="button" >
                <i class="las la-sign-out-alt"></i> Log Out
            </Link>
        </li>
    </ul>
</template>

<script>
import {Link,usePage} from '@inertiajs/inertia-vue3';
import {onMounted} from "vue";

export default {
    name: "sidebar",
    components:{
        Link
    },
    setup(){
        const isEmployee =  usePage().props.value.auth.user.employee !== null;
        console.log( usePage().props.value.auth.user.employee)
        console.log(typeof usePage().props.value.auth.user.employee)
        function activeSidebarMenu(){
            const dashboardMenuList = document.querySelectorAll('li.has-submenu');
            for(let i=0; i < dashboardMenuList.length; i++ ){
                dashboardMenuList[i].addEventListener('click',function (e){
                    e.preventDefault();
                    let submenu = this.children[1];
                    let currentMenu = this;
                    let currentMenuClass = this;
                    // console.log(this.classList)
                    currentMenu.classList = !currentMenu.classList.contains('active') ? 'list has-submenu active' : 'list has-submenu';
                    submenu.classList = !submenu.classList.contains('active') ? 'sub-menu active' : 'sub-menu';
                });
            }
        } // end active sidebar menu function

        onMounted(activeSidebarMenu);

        return {
            isEmployee
        }
    }
}
</script>
