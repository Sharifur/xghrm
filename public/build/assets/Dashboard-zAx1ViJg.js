import{B as z}from"./ApplicationLogo-64HHftb7.js";import{l as D,A as N,Z as C,o as u,c as m,b as e,k as h,d as y,s as k,a as l,g as n,n as p,W as A,L as _,r as i,q as x,e as g,t as b,h as M,H as j,F as E}from"./app-gltJhGw6.js";import{_ as f}from"./_plugin-vue_export-helper-x3n3nnut.js";const H={props:{align:{default:"right"},width:{default:"48"},contentClasses:{default:()=>["py-1","bg-white"]}},setup(){let t=D(!1);const o=d=>{t.value&&d.keyCode===27&&(t.value=!1)};return N(()=>document.addEventListener("keydown",o)),C(()=>document.removeEventListener("keydown",o)),{open:t}},computed:{widthClass(){return{48:"w-48"}[this.width.toString()]},alignmentClasses(){return this.align==="left"?"origin-top-left left-0":this.align==="right"?"origin-top-right right-0":"origin-top"}}},S={class:"relative"};function V(t,o,d,a,s,c){return u(),m("div",S,[e("div",{onClick:o[0]||(o[0]=r=>a.open=!a.open)},[h(t.$slots,"trigger")]),y(e("div",{class:"fixed inset-0 z-40",onClick:o[1]||(o[1]=r=>a.open=!1)},null,512),[[k,a.open]]),l(A,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:n(()=>[y(e("div",{class:p(["absolute z-50 mt-2 rounded-md shadow-lg",[c.widthClass,c.alignmentClasses]]),style:{display:"none"},onClick:o[2]||(o[2]=r=>a.open=!1)},[e("div",{class:p(["rounded-md ring-1 ring-black ring-opacity-5",d.contentClasses])},[h(t.$slots,"content")],2)],2),[[k,a.open]])]),_:3})])}const O=f(H,[["render",V]]),R={components:{Link:_}};function F(t,o,d,a,s,c){const r=i("Link");return u(),x(r,{class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:n(()=>[h(t.$slots,"default")]),_:3})}const T=f(R,[["render",F]]),q={components:{Link:_},props:["href","active"],computed:{classes(){return this.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition  duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"}}};function U(t,o,d,a,s,c){const r=i("Link");return u(),x(r,{href:d.href,class:p(c.classes)},{default:n(()=>[h(t.$slots,"default")]),_:3},8,["href","class"])}const W=f(q,[["render",U]]),Y={components:{Link:_},props:["href","active"],computed:{classes(){return this.active?"block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"}}};function Z(t,o,d,a,s,c){const r=i("Link");return u(),x(r,{href:d.href,class:p(c.classes)},{default:n(()=>[h(t.$slots,"default")]),_:3},8,["href","class"])}const G=f(Y,[["render",Z]]),I={components:{BreezeApplicationLogo:z,BreezeDropdown:O,BreezeDropdownLink:T,BreezeNavLink:W,BreezeResponsiveNavLink:G,Link:_},data(){return{showingNavigationDropdown:!1}}},J={class:"min-h-screen bg-gray-100"},K={class:"bg-white border-b border-gray-100"},P={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},Q={class:"flex justify-between h-16"},X={class:"flex"},ee={class:"flex-shrink-0 flex items-center"},te={class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},oe={class:"hidden sm:flex sm:items-center sm:ml-6"},se={class:"ml-3 relative"},ne={class:"inline-flex rounded-md"},re={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},ae=e("svg",{class:"ml-2 -mr-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[e("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),ie={class:"-mr-2 flex items-center sm:hidden"},de={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},le={class:"pt-2 pb-3 space-y-1"},ce={class:"pt-4 pb-1 border-t border-gray-200"},ue={class:"px-4"},pe={class:"font-medium text-base text-gray-800"},he={class:"font-medium text-sm text-gray-500"},fe={class:"mt-3 space-y-1"},ge={key:0,class:"bg-white shadow"},me={class:"max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"};function _e(t,o,d,a,s,c){const r=i("BreezeApplicationLogo"),v=i("Link"),$=i("BreezeNavLink"),L=i("BreezeDropdownLink"),B=i("BreezeDropdown"),w=i("BreezeResponsiveNavLink");return u(),m("div",null,[e("div",J,[e("nav",K,[e("div",P,[e("div",Q,[e("div",X,[e("div",ee,[l(v,{href:t.route("dashboard")},{default:n(()=>[l(r,{class:"block h-9 w-auto"})]),_:1},8,["href"])]),e("div",te,[l($,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:n(()=>[g(" Dashboard ")]),_:1},8,["href","active"])])]),e("div",oe,[e("div",se,[l(B,{align:"right",width:"48"},{trigger:n(()=>[e("span",ne,[e("button",re,[g(b(t.$page.props.auth.user.name)+" ",1),ae])])]),content:n(()=>[l(L,{href:t.route("logout"),method:"post",as:"button"},{default:n(()=>[g(" Log Out ")]),_:1},8,["href"])]),_:1})])]),e("div",ie,[e("button",{onClick:o[0]||(o[0]=$e=>s.showingNavigationDropdown=!s.showingNavigationDropdown),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(u(),m("svg",de,[e("path",{class:p({hidden:s.showingNavigationDropdown,"inline-flex":!s.showingNavigationDropdown}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),e("path",{class:p({hidden:!s.showingNavigationDropdown,"inline-flex":s.showingNavigationDropdown}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),e("div",{class:p([{block:s.showingNavigationDropdown,hidden:!s.showingNavigationDropdown},"sm:hidden"])},[e("div",le,[l(w,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:n(()=>[g(" Dashboard ")]),_:1},8,["href","active"])]),e("div",ce,[e("div",ue,[e("div",pe,b(t.$page.props.auth.user.name),1),e("div",he,b(t.$page.props.auth.user.email),1)]),e("div",fe,[l(w,{href:t.route("logout"),method:"post",as:"button"},{default:n(()=>[g(" Log Out ")]),_:1},8,["href"])])])],2)]),t.$slots.header?(u(),m("header",ge,[e("div",me,[h(t.$slots,"header")])])):M("",!0),e("main",null,[h(t.$slots,"default")])])])}const ve=f(I,[["render",_e]]),be={components:{BreezeAuthenticatedLayout:ve,Head:j}},xe=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Dashboard this ",-1),we=e("div",{class:"main-content-wrapper"},[e("div",{class:"py-4"}," sidebar "),e("div",{class:"py-8"}," main content ")],-1),ye=e("div",{class:"py-12"},[e("div",{class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},[e("div",{class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},[e("div",{class:"p-6 bg-white border-b border-gray-200"}," You're logged in! test ")])])],-1);function ke(t,o,d,a,s,c){const r=i("Head"),v=i("BreezeAuthenticatedLayout");return u(),m(E,null,[l(r,{title:"Dashboard"}),l(v,null,{header:n(()=>[xe]),default:n(()=>[we,ye]),_:1})],64)}const De=f(be,[["render",ke]]);export{De as default};