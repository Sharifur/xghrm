import{L as _,A as y,r as g,o as u,c,b as s,a as t,g as o,e as n,n as a,l as S,h as f,t as p,k}from"./app-NMZH-3Gf.js";import{_ as v}from"./_plugin-vue_export-helper-x3n3nnut.js";const A={name:"sidebar",components:{Link:_},setup(){function e(){const i=document.querySelectorAll("li.has-submenu");for(let d=0;d<i.length;d++)i[d].addEventListener("click",function(r){r.preventDefault();let h=this.children[1],m=this;m.classList=m.classList.contains("active")?"list has-submenu":"list has-submenu active",h.classList=h.classList.contains("active")?"sub-menu":"sub-menu active"})}y(e)}},L={class:"dashboard-list dashboard-menu-ul"},C=s("span",null," Employees ",-1),M={class:"sub-menu"},N=s("span",null," Notices ",-1),W={class:"sub-menu"},w=s("span",null," Settings ",-1),D={class:"sub-menu"},q=["href"],E={class:"list logoutbtn"},T=s("i",{class:"las la-sign-out-alt"},null,-1);function V(e,i,d,r,h,m){const l=g("Link");return u(),c("ul",L,[s("li",{class:a(["list",{active:e.$page.url==="/admin-home"}])},[t(l,{href:e.route("admin.home")},{default:o(()=>[n(" Dashboard ")]),_:1},8,["href"])],2),s("li",{class:a(["list",{active:e.$page.url==="/admin-home/users/all"}])},[t(l,{href:e.route("admin.users.all")},{default:o(()=>[n(" All Users ")]),_:1},8,["href"])],2),s("li",{class:a(["list has-submenu",{active:e.$page.url.startsWith("/admin-home/employee/")}])},[C,s("ul",M,[s("li",null,[t(l,{href:e.route("admin.employee.all"),class:a({active:e.$page.url==="/admin-home/employee/all"})},{default:o(()=>[n(" All ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.category"),class:a({active:e.$page.url.startsWith("/admin-home/employee/category")})},{default:o(()=>[n(" Category ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.new"),class:a({active:e.$page.url.startsWith("/users")})},{default:o(()=>[n(" Add New ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.leaves.all"),class:a({active:e.$page.url.startsWith("/leaves")})},{default:o(()=>[n(" Leaves ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.salary.slip"),class:a({active:e.$page.url.startsWith("/salary-slip")})},{default:o(()=>[n(" Salary Slip ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.advance.salary.all"),class:a({active:e.$page.url==="/admin-home/employee/advance-salary/all"})},{default:o(()=>[n(" Advance Salary ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.attendance"),class:a({active:e.$page.url==="/admin-home/employee/attendance/all"})},{default:o(()=>[n(" Attendance Reports ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.attendance.logs"),class:a({active:e.$page.url==="/admin-home/employee/attendancelogs/all"})},{default:o(()=>[n(" Attendance Logs ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.attendance.request"),class:a({active:e.$page.url==="/admin-home/employee/attendancelogs/request"})},{default:o(()=>[n(" Attendance Request ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.employee.attendance.check.global"),class:a({active:e.$page.url==="/admin-home/employee/attendancelogs/check"})},{default:o(()=>[n(" Attendance Check ")]),_:1},8,["href","class"])])])],2),s("li",{class:a(["list has-submenu",{active:e.$page.url.startsWith("/admin-home/notice/")}])},[N,s("ul",W,[s("li",null,[t(l,{href:e.route("admin.notice"),class:a({active:e.$page.url==="/admin-home/notice"})},{default:o(()=>[n(" All Notice ")]),_:1},8,["href","class"])])])],2),s("li",{class:a(["list has-submenu",{active:e.$page.url.startsWith("/admin-home/general/")}])},[w,s("ul",D,[s("li",null,[t(l,{href:e.route("admin.general.settings"),class:a({active:e.$page.url==="/admin-home/general/settings"})},{default:o(()=>[n(" General ")]),_:1},8,["href","class"])]),s("li",null,[s("a",{href:e.route("admin.general.sync.data"),target:"_blank",download:""}," Download Data from ZKTECO ",8,q)]),s("li",null,[t(l,{href:e.route("admin.database.upgrade"),as:"button",method:"post",class:a({active:e.$page.url==="/admin-home/general/database-upgrade"})},{default:o(()=>[n(" Upgrade Database ")]),_:1},8,["href","class"])]),s("li",null,[t(l,{href:e.route("admin.smtp.test"),method:"post",as:"button",class:a({active:e.$page.url==="/admin-home/general/smtp-test"})},{default:o(()=>[n(" Send SMTP Test Mail ")]),_:1},8,["href","class"])])])],2),s("li",E,[t(l,{href:e.route("admin.logout"),method:"post",as:"button"},{default:o(()=>[T,n(" Log Out ")]),_:1},8,["href"])])])}const B=v(A,[["render",V]]),O={name:"AdminMaster",components:{AdminSidebar:B,Link:_},setup(){var e=S(!1);function i(){e.value=!0}function d(){e.value=!1}return{toggleSidebar:i,toggleStatus:e,toggleSidebarClose:d}}},R={class:"dashboard-area dashboard-padding"},U={class:"container-fluid"},j={class:"dashboard-contents-wrapper"},z={key:0,class:"dashboard-icon"},G=s("i",{class:"fas fa-bars"},null,-1),K=[G],P={class:"dashboard-left-content"},Z=s("i",{class:"fas fa-times"},null,-1),F=[Z],H={class:"dashboard-top padding-top-40"},I={class:"thumb"},J={key:0,class:"img"},Q=s("img",{src:"assets/img/dashboard/authors.jpg",alt:""},null,-1),X=[Q],Y={key:1,class:"char"},x={class:"author-content"},ee={class:"title"},se={class:"small-title"},ae={class:"dashboard-bottom margin-top-35 margin-bottom-50"},le={class:"dashboard-logo padding-top-100"},te=s("img",{src:"/images/fav-icon.png",alt:"xgenious logo"},null,-1),oe={class:"dashboard-right"};function ne(e,i,d,r,h,m){const l=g("AdminSidebar"),b=g("Link");return u(),c("div",R,[s("div",U,[s("div",j,[r.toggleStatus?f("",!0):(u(),c("div",z,[s("div",{class:"sidebar-icon",onClick:i[0]||(i[0]=$=>r.toggleSidebar())},K)])),s("div",P,[s("div",{class:a(["dashboard-close-main",{active:r.toggleStatus}])},[r.toggleStatus?(u(),c("div",{key:0,class:"close-bars",onClick:i[1]||(i[1]=$=>r.toggleSidebarClose())},F)):f("",!0),s("div",H,[s("div",I,[e.$page.props.auth.user.image!=null?(u(),c("div",J,X)):(u(),c("div",Y,p(e.$page.props.auth.user.name.slice(0,1)),1))]),s("div",x,[s("h4",ee,p(e.$page.props.auth.user.name),1),s("span",se,p(e.$page.props.auth.user.username),1)])]),s("div",ae,[t(l)]),s("div",le,[t(b,{href:e.route("admin.home"),class:"logo"},{default:o(()=>[te]),_:1},8,["href"])])],2)]),s("div",oe,[k(e.$slots,"default")])])])])}const de=v(O,[["render",ne]]);export{de as A};