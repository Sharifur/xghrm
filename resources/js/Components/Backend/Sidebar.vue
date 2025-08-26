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
                <li>
                    <Link :href="route('admin.employee.attendance.request')" :class="{'active' : $page.url === '/admin-home/employee/attendancelogs/request'}">
                        Attendance Request
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.employee.attendance.check.global')" :class="{'active' : $page.url === '/admin-home/employee/attendancelogs/check'}">
                        Attendance Check
                    </Link>
                </li>
            </ul>
        </li>

        <li class="list has-submenu " :class="{'active' : $page.url.startsWith('/admin-home/finance/')}">
            <span> Finance </span>
            <ul class="sub-menu">
                <li>
                    <Link :href="route('admin.finance.dashboard')" :class="{'active' : $page.url === '/admin-home/finance/dashboard'}">
                        Dashboard
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.balance.sheet')" :class="{'active' : $page.url.startsWith('/admin-home/finance/balance-sheet')}">
                        Balance Sheet
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.recurring.expenses')" :class="{'active' : $page.url.startsWith('/admin-home/finance/recurring-expenses')}">
                        Recurring Expenses
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.assets')" :class="{'active' : $page.url.startsWith('/admin-home/finance/assets')}">
                        Assets Management
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.equity')" :class="{'active' : $page.url.startsWith('/admin-home/finance/equity')}">
                        Equity Management
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.expenses')" :class="{'active' : $page.url.startsWith('/admin-home/finance/expenses')}">
                        One-time Expenses
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.budgets')" :class="{'active' : $page.url.startsWith('/admin-home/finance/budgets')}">
                        Budgets
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.reports')" :class="{'active' : $page.url.startsWith('/admin-home/finance/reports')}">
                        Revenue Tracking
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.finance.documentation')" :class="{'active' : $page.url.startsWith('/admin-home/finance/documentation')}">
                        <i class="fas fa-book me-1"></i> Documentation
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
                    <a :href="route('admin.general.sync.data')" target="_blank" download>
                        Download Data from ZKTECO
                    </a>
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
import {onMounted, watch} from "vue";
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    name: "sidebar",
    components:{
        Link
    },
    setup(){
        const page = usePage();
        
        function activeSidebarMenu(){
            const dashboardMenuList = document.querySelectorAll('li.has-submenu');
            for(let i=0; i < dashboardMenuList.length; i++ ){
                dashboardMenuList[i].addEventListener('click',function (e){
                    e.preventDefault();
                    let submenu = this.children[1];
                    let currentMenu = this;
                    let currentMenuClass = this;
                    
                    // Check if this is the Finance menu and we're on a finance page
                    const span = this.querySelector('span');
                    const currentUrl = window.location.pathname;
                    const pageUrl = page.url;
                    const isFinanceMenu = span && span.textContent.trim() === 'Finance';
                    const isOnFinancePage = currentUrl.includes('/finance/') || pageUrl.includes('/finance/') || 
                                          currentUrl.startsWith('/admin-home/finance/') || pageUrl.startsWith('/admin-home/finance/');
                    
                    // If it's the Finance menu and we're on a finance page, keep it open
                    if (isFinanceMenu && isOnFinancePage) {
                        currentMenu.classList = 'list has-submenu active';
                        submenu.classList = 'sub-menu active';
                    } else {
                        // Normal toggle behavior for other menus
                        currentMenu.classList = !currentMenu.classList.contains('active') ? 'list has-submenu active' : 'list has-submenu';
                        submenu.classList = !submenu.classList.contains('active') ? 'sub-menu active' : 'sub-menu';
                    }
                });
            }
        } // end active sidebar menu function

        function autoExpandActiveMenu() {
            // Clear all active states first
            const allMenus = document.querySelectorAll('li.has-submenu');
            allMenus.forEach(menu => {
                menu.classList.remove('active');
                const submenu = menu.querySelector('.sub-menu');
                if (submenu) {
                    submenu.classList.remove('active');
                }
            });
            
            const currentUrl = window.location.pathname;
            
            // Auto-expand finance menu if on any finance page
            if (currentUrl.startsWith('/admin-home/finance/') || currentUrl.includes('/finance/')) {
                const financeMenus = document.querySelectorAll('li.has-submenu');
                financeMenus.forEach(menu => {
                    const span = menu.querySelector('span');
                    if (span && span.textContent.trim() === 'Finance') {
                        const submenu = menu.querySelector('.sub-menu');
                        if (submenu) {
                            menu.classList.add('active');
                            submenu.classList.add('active');
                        }
                    }
                });
            }
            
            // Auto-expand employee menu if on any employee page  
            if (currentUrl.startsWith('/admin-home/employee/')) {
                const employeeMenus = document.querySelectorAll('li.has-submenu');
                employeeMenus.forEach(menu => {
                    const span = menu.querySelector('span');
                    if (span && span.textContent.trim() === 'Employees') {
                        const submenu = menu.querySelector('.sub-menu');
                        if (submenu) {
                            menu.classList.add('active');
                            submenu.classList.add('active');
                        }
                    }
                });
            }

            // Auto-expand notices menu if on any notice page
            if (currentUrl.startsWith('/admin-home/notice/')) {
                const noticeMenus = document.querySelectorAll('li.has-submenu');
                noticeMenus.forEach(menu => {
                    const span = menu.querySelector('span');
                    if (span && span.textContent.trim() === 'Notices') {
                        const submenu = menu.querySelector('.sub-menu');
                        if (submenu) {
                            menu.classList.add('active');
                            submenu.classList.add('active');
                        }
                    }
                });
            }

            // Auto-expand settings menu if on any settings page
            if (currentUrl.startsWith('/admin-home/general/')) {
                const settingsMenus = document.querySelectorAll('li.has-submenu');
                settingsMenus.forEach(menu => {
                    const span = menu.querySelector('span');
                    if (span && span.textContent.trim() === 'Settings') {
                        const submenu = menu.querySelector('.sub-menu');
                        if (submenu) {
                            menu.classList.add('active');
                            submenu.classList.add('active');
                        }
                    }
                });
            }
        }

        function ensureFinanceMenuExpanded() {
            // Specific function to ensure Finance menu stays open
            const currentUrl = window.location.pathname;
            const pageUrl = page.url;
            
            if (currentUrl.includes('/finance/') || pageUrl.includes('/finance/') || 
                currentUrl.startsWith('/admin-home/finance/') || pageUrl.startsWith('/admin-home/finance/')) {
                
                const financeMenus = document.querySelectorAll('li.has-submenu');
                financeMenus.forEach(menu => {
                    const span = menu.querySelector('span');
                    if (span && span.textContent.trim() === 'Finance') {
                        const submenu = menu.querySelector('.sub-menu');
                        if (submenu) {
                            menu.classList.add('active');
                            submenu.classList.add('active');
                        }
                    }
                });
            }
        }

        onMounted(() => {
            activeSidebarMenu();
            autoExpandActiveMenu();
            // Additional check specifically for finance menu
            ensureFinanceMenuExpanded();
        });

        // Watch for page changes and update sidebar accordingly
        watch(() => page.url, (newUrl) => {
            // Use setTimeout to ensure DOM is updated
            setTimeout(() => {
                autoExpandActiveMenu();
                ensureFinanceMenuExpanded(); // Additional finance menu check
            }, 100);
            
            // Immediate check for finance pages with shorter timeout
            setTimeout(() => {
                ensureFinanceMenuExpanded();
            }, 50);
        }, { immediate: true });

    }
}
</script>
