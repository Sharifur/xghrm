<template>
    <ul class="dashboard-list dashboard-menu-ul">
        <li class="list" :class="{'active' : $page.url === '/admin-home'}">
            <Link :href="route('admin.home')" >
                Dashboard
            </Link>
        </li>
        <li class="list" :class="{'active' : $page.url === '/admin-home/users/all'}">
            <Link :href="route('admin.users.all')" >
                All Users
            </Link>
        </li>
        <li class="list has-submenu " :class="{'active' : $page.url.startsWith('/admin-home/employee/')}">
            <span> Employees </span>
            <ul class="sub-menu">
                <li>
                    <Link :href="route('admin.employee.all')" :class="{'active' : $page.url === ('/admin-home/employee/all')}">
                         All
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.category')" :class="{'active' : $page.url.startsWith('/admin-home/employee/category')}">
                        Category
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.new')" :class="{'active' : $page.url.startsWith('/users')}">
                        Add New
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.leaves.all')" :class="{'active' : $page.url.startsWith('/leaves')}">
                        Leaves
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.salary.slip')" :class="{'active' : $page.url.startsWith('/salary-slip')}">
                        Salary Slip
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.advance.salary.all')" :class="{'active' : $page.url === ('/admin-home/employee/advance-salary/all')}">
                        Advance Salary
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.attendance')" :class="{'active' : $page.url === ('/admin-home/employee/attendance/all')}">
                        Attendance Reports
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.attendance.logs')" :class="{'active' : $page.url === '/admin-home/employee/attendancelogs/all'}">
                        Attendance Logs
                    </Link>
                </li>
            </ul>
        </li>

        <li class="list has-submenu " :class="{'active' : $page.url.startsWith('/admin-home/notice/')}">
            <span> Notices </span>
            <ul class="sub-menu">
                <li>
                    <Link :href="route('admin.notice')" :class="{'active' : $page.url === ('/admin-home/notice')}">
                        All Notice
                    </Link>
                </li>
            </ul>
        </li>
        <li class="list has-submenu " :class="{'active' : $page.url.startsWith('/admin-home/general/')}">
            <span> Settings </span>
            <ul class="sub-menu">
                <li>
                    <Link :href="route('admin.general.settings')" :class="{'active' : $page.url === ('/admin-home/general/settings')}">
                        General
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.general.sync.data')" as="button" method="post" :class="{'active' : $page.url === ('/admin-home/general/sync-data')}">
                        Sync Data from ZKTECO
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.database.upgrade')" as="button" method="post" :class="{'active' : $page.url === ('/admin-home/general/database-upgrade')}">
                        Upgrade Database
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.smtp.test')" method="post" as="button" :class="{'active' : $page.url === ('/admin-home/general/smtp-test')}">
                        Send SMTP Test Mail
                    </Link>
                </li>
            </ul>
        </li>
        <li class="list logoutbtn">
            <Link :href="route('admin.logout')" method="post"  as="button" >
                <i class="las la-sign-out-alt"></i> Log Out
            </Link>
        </li>
    </ul>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3';
import {onMounted} from "vue";

export default {
    name: "sidebar",
    components:{
        Link
    },
    setup(){
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

    }
}
</script>
