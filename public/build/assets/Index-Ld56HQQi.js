import{L as M,u as c,K as D,H as x,m as N,c as _,a as r,b as t,g as w,F as h,p as A,r as i,o as f,e as F,t as u,n as y,w as I}from"./app-gltJhGw6.js";import{P}from"./Pagination-hDtLDoSz.js";import{S as z}from"./sweetalert2.all-DleBhdmB.js";import{B as j,a as T}from"./BsModalButton-2v7ylkVT.js";import{I as C}from"./Input-N_VnS9ZC.js";import{S as E}from"./Select-GSljLRwI.js";import{B as H}from"./Button-_xX5v1SM.js";import{V as U}from"./ValidationErrors-K-JALEkY.js";import{U as Y}from"./UserMaster-KLh0FY3g.js";import{I as q}from"./Input-2dBvznUW.js";import{_ as O}from"./_plugin-vue_export-helper-x3n3nnut.js";function b(e){const n=Object.prototype.toString.call(e);return e instanceof Date||typeof e=="object"&&n==="[object Date]"?new e.constructor(+e):typeof e=="number"||n==="[object Number]"||typeof e=="string"||n==="[object String]"?new Date(e):new Date(NaN)}function B(e,n){return e instanceof Date?new e.constructor(n):new Date(n)}function R(e,n){const s=b(e);if(isNaN(n))return B(e,NaN);if(!n)return s;const o=s.getDate(),l=B(e,s.getTime());l.setMonth(s.getMonth()+n+1,0);const d=l.getDate();return o>=d?l:(s.setFullYear(l.getFullYear(),l.getMonth(),o),s)}function K(e){return b(e).getMonth()}function W(e){return b(e).getFullYear()}const G={name:"Index",layout:Y,components:{Input:q,Pagination:P,Link:M,usePage:c,BsModal:j,BsModalButton:T,BsInput:C,BsSelect:E,BsButton:H,BreezeValidationErrors:U,VueDatePicker:D,Head:x},setup(){const e=[{label:"Leave",value:"leave"},{label:"Sick Leave",value:"sick-leave"},{label:"Work From Home",value:"work-from-home"}],n=N({employee_id:null,type:null,date_time:null}),s=new Date,o=R(new Date(W(new Date),K(new Date)),1);function l(){return c().props.value.attendance_logs.data}function d(){return c().props.value.attendance_logs.links}function m(){n.post(route("user.leaves.new"),{onSuccess:g=>{z.fire("Success","new attendance log added","success"),n.reset("employee_id","type","date_time","name")}})}function p(){return c().props.value.employees}return{attendancesData:l,attendancesLink:d,addAttendanceLogFormSubmit:m,attendanceTypes:e,newLogData:n,employeesList:p,VueDatePicker:D,minDate:s,maxDate:o}}},J={class:"row"},Q={class:"col-lg-12"},X={class:"dashboard-settings margin-top-40 margin-bottom-30"},Z={class:"header-wrap d-flex justify-content-between"},$=t("h2",{class:"dashboards-title margin-bottom-40"},"All Attendance Request",-1),ee={class:"btn-wrapper"},te={class:"table-wrap table-responsive"},ne={class:"table table-light table-secondary"},oe=t("thead",null,[t("th",null,"ID"),t("th",null,"Type"),t("th",null,"Status"),t("th",null,"Date")],-1),ae={class:"single-info-input margin-top-30"},se=t("label",{class:"info-title"},"Date Time",-1);function le(e,n,s,o,l,d){const m=i("Head"),p=i("BsModalButton"),g=i("Pagination"),v=i("BreezeValidationErrors"),k=i("BsSelect"),S=i("VueDatePicker"),V=i("BsButton"),L=i("BsModal");return f(),_(h,null,[r(m,{title:"All Attendance Request"}),t("div",J,[t("div",Q,[t("div",X,[t("div",Z,[$,t("div",ee,[r(p,{target:"addnewcategory","button-class":"btn btn-info m-1"},{default:w(()=>[F("Add New Request")]),_:1})])]),t("div",te,[t("table",ne,[oe,t("tbody",null,[(f(!0),_(h,null,A(o.attendancesData(),a=>(f(),_("tr",{key:a.id},[t("td",null,u(a.id),1),t("td",null,[t("span",{class:y(["alert text-capitalize",a.type])},u(a.type.replaceAll("-"," ")),3)]),t("td",null,[t("span",{class:y(["alert text-capitalize",a.status===1?"alert-success":"alert-danger"])},u(a.status===1?"Approved":"pending"),3)]),t("td",null,u(e.readableDateFormat(a.date_time)),1)]))),128))])])]),r(g,{links:o.attendancesLink()},null,8,["links"])])])]),r(L,{"modal-title":"Add New Attendance Log","modal-id":"addnewcategory","modal-size":"modal-md"},{default:w(()=>[r(v,{class:"mb-4"}),t("form",{onSubmit:n[2]||(n[2]=I(()=>{},["prevent"]))},[r(k,{title:"Status",options:o.attendanceTypes,modelValue:o.newLogData.type,"onUpdate:modelValue":n[0]||(n[0]=a=>o.newLogData.type=a)},null,8,["options","modelValue"]),t("div",ae,[se,r(S,{"start-date":new Date,"disabled-week-days":[5],modelValue:o.newLogData.date_time,"onUpdate:modelValue":n[1]||(n[1]=a=>o.newLogData.date_time=a),"min-date":o.minDate,"max-date":o.maxDate},null,8,["start-date","modelValue","min-date","max-date"])]),r(V,{"button-text":"Submit","button-type":"submit",onClick:o.addAttendanceLogFormSubmit,disabled:o.newLogData.processing},null,8,["onClick","disabled"])],32)]),_:1})],64)}const De=O(G,[["render",le]]);export{De as default};