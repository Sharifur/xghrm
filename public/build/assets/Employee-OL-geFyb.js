import{L as B,H as S,c as f,a as i,b as t,g as l,t as r,F as k,p,u as h,m as D,r as _,o as d,e as C,d as E,s as A,n as x,q as T,h as F}from"./app-bWgNDweR.js";import{P as N}from"./Pagination-SfFcosUE.js";import{A as P}from"./AdminMaster-ECLE8g3e.js";import{S as u}from"./sweetalert2.all-h8zvUiN9.js";import{S as Y}from"./StatusShow-z1O2Qoo-.js";import{_ as H}from"./_plugin-vue_export-helper-x3n3nnut.js";const V={name:"Employee",layout:P,components:{StatusShow:Y,Link:B,Pagination:N,Head:S},setup(){function c(){return console.log(h().props.value.allEmployees.data),h().props.value.allEmployees.data}function b(s){u.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(a=>{const o=D({id:s.id});a.isConfirmed&&o.post(route("admin.employee.delete"),{onFinish:()=>{u.fire("Deleted!","Entry deleted.","success")}})})}function m(){return h().props.value.allEmployees.links}function e(s){return s===1?"Working":"Left Job"}function g(s){var a,o;return(a=s==null?void 0:s.user)!=null&&a.id?route("admin.users.view",(o=s==null?void 0:s.user)==null?void 0:o.id):null}function w(s){u.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, make user!"}).then(a=>{a.isConfirmed&&axios.post(route("admin.employee.convert.user"),s).then(o=>{let n=o.data;u.fire(n.msg,"",n.type)})})}return{employeeListLinks:m,employeeList:c,deleteItem:b,employeeStatus:e,userRoute:g,convertToUser:w}}},I={class:"row"},U={class:"col-lg-12"},j={class:"dashboard-settings margin-top-40"},J={class:"header-wrap d-flex justify-content-between"},R=t("h2",{class:"dashboards-title margin-bottom-40"},"All Employees",-1),q={class:"btn-wrp"},z={class:"table-responsive"},M={class:"table table-stripped"},W=t("th",null,"Id",-1),G=t("th",null,"Title",-1),K=t("th",null,"Category",-1),O=t("th",null,"Join Date",-1),Q=t("th",null,"Salary",-1),X=t("th",null,"Status",-1),Z=t("th",null,"Action",-1),$=t("i",{class:"fas fa-user btn-success text-white btn btn-sm",title:"An User"},null,-1),y=t("i",{class:"fas fa-eye"},null,-1),tt=t("i",{class:"fas fa-trash"},null,-1),nt=t("i",{class:"fas fa-pencil-alt"},null,-1),st=t("i",{class:"fas fa-calendar-alt"},null,-1),at=t("i",{class:"fas fa-retweet"},null,-1);function et(c,b,m,e,g,w){const s=_("Head"),a=_("Link"),o=_("Pagination");return d(),f(k,null,[i(s,{title:"All Employee"}),t("div",I,[t("div",U,[t("div",j,[t("div",J,[R,t("div",q,[i(a,{class:"btn btn-info m-1",href:c.route("admin.employee.new")},{default:l(()=>[C("Add New")]),_:1},8,["href"])])]),t("div",z,[t("table",M,[t("thead",null,[W,G,K,O,Q,t("th",null,"Total Leave ( "+r(new Date().getFullYear())+" )",1),X,Z]),t("tbody",null,[(d(!0),f(k,null,p(e.employeeList(),n=>{var v;return d(),f("tr",{key:n.id},[t("td",null,r(n.id),1),t("td",null,[C(r(n.name)+" ",1),E(i(a,{href:e.userRoute(n)},{default:l(()=>[$]),_:2},1032,["href"]),[[A,n==null?void 0:n.user]])]),t("td",null,r((v=n==null?void 0:n.category)==null?void 0:v.title),1),t("td",null,r(new Date(n.joinDate).toLocaleDateString()),1),t("td",null,r(n.salary)+" BDT",1),t("td",null,r(n!=null&&n.attendance_log?n==null?void 0:n.attendance_log.length:0),1),t("td",null,[t("span",{class:x(["alert",n.status===1?"alert-success":"alert-danger"])},r(e.employeeStatus(n.status)),3)]),t("td",null,[i(a,{class:"btn btn-secondary m-1",href:c.route("admin.employee.view",n.id)},{default:l(()=>[y]),_:2},1032,["href"]),i(a,{as:"button",class:"btn btn-danger m-1",onClick:L=>e.deleteItem(n)},{default:l(()=>[tt]),_:2},1032,["onClick"]),i(a,{class:"btn btn-info m-1",href:c.route("admin.employee.edit",n.id)},{default:l(()=>[nt]),_:2},1032,["href"]),i(a,{class:"btn btn-warning m-1",href:c.route("admin.employee.attendance.check",n.id)},{default:l(()=>[st]),_:2},1032,["href"]),n.user===null?(d(),T(a,{key:0,class:"btn btn-warning m-1",as:"button",onClick:L=>e.convertToUser(n)},{default:l(()=>[at]),_:2},1032,["onClick"])):F("",!0)])])}),128))])])]),i(o,{links:e.employeeListLinks()},null,8,["links"])])])])],64)}const ut=H(V,[["render",et]]);export{ut as default};