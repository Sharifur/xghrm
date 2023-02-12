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
        <li class="list has-submenu " :class="{'active' : $page.url.startsWith('/admin-home/earning/')}">
            <span>Earnings </span>
            <ul class="sub-menu">
                <li>
                    <Link :href="route('admin.earning.all')" :class="{'active' : $page.url === ('/admin-home/earning/all')}">
                        Statements
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.earning.new')" :class="{'active' : $page.url === ('/admin-home/earning/new')}">
                        Add New Statement
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.earning.settings')" :class="{'active' : $page.url === '/admin-home/employee/settings'}">
                        Settings
                    </Link>
                </li>
            </ul>
        </li>
        <li class="list has-submenu " :class="{'active' : $page.url.startsWith('/admin-home/products/')}">
            <span> Products </span>
            <ul class="sub-menu">
                <li>
                    <Link :href="route('admin.products.all')" :class="{'active' : $page.url === ('/admin-home/products/all')}">
                        All
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.products.new')" :class="{'active' : $page.url === ('/admin-home/products/new')}">
                        Add New
                    </Link>
                </li>
            </ul>
        </li>
        <li class="list logoutbtn">
            <Link :href="route('admin.database.upgrade')" method="post"  as="button" >
                Database Upgrade
            </Link>
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
