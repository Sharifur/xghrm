import{A as S}from"./AdminMaster-rt2mnpO9.js";import{L as C,u as i,H as x,K as A,m as v,c as u,a as n,b as e,g as p,F as w,p as I,r as s,o as _,e as P,t as d,n as M,w as z}from"./app-3xCpkM7L.js";import{P as E}from"./Pagination-PS5xb2Xu.js";import{S as f}from"./sweetalert2.all-eSlDtIAn.js";import{B as F,a as N}from"./BsModalButton-T2FbLyrp.js";import{I as T}from"./Input-aOmcNyKo.js";import{S as H}from"./Select-Rqff3XlX.js";import{B as U}from"./Button-dXg_TwjF.js";import{B as K}from"./ValidationErrors-Ev_PrKRm.js";import{s as Y}from"./vue3-datepicker.esm-of5srOun.js";import{_ as j}from"./_plugin-vue_export-helper-x3n3nnut.js";const q={name:"Index",layout:S,components:{Pagination:E,Link:C,usePage:i,BsModal:F,BsModalButton:N,BsInput:T,BsSelect:H,BsButton:U,BreezeValidationErrors:K,Datepicker:Y,Head:x,VueDatePicker:A},setup(){const r=[{label:"Leave",value:"leave"},{label:"Sick Leave",value:"sick-leave"},{label:"Paid Leave",value:"paid-leave"}],a=v({employee_id:null,type:null,date_time:null});function g(){return i().props.value.attendance_logs.data}function t(){return i().props.value.attendance_logs.links}function b(l){f.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then(m=>{m.isConfirmed&&v({id:l.id}).post(route("admin.employee.attendance.logs.delete"),{onSuccess:()=>{f.fire("Deleted","item removed from system","warning")}})})}function B(){a.post(route("admin.employee.attendance.log.add"),{onSuccess:l=>{f.fire("Success","new attendance log added","success"),a.reset("employee_id","type","date_time","name")}})}function c(){return i().props.value.employees}return{attendancesData:g,attendancesLink:t,deleteItem:b,addAttendanceLogFormSubmit:B,attendanceTypes:r,newLogData:a,employeesList:c}}},G={class:"row"},J={class:"col-lg-12"},O={class:"dashboard-settings margin-top-40 margin-bottom-30"},Q={class:"header-wrap d-flex justify-content-between"},R=e("h2",{class:"dashboards-title margin-bottom-40"},"All Leaves",-1),W={class:"btn-wrapper"},X={class:"table-wrap table-responsive"},Z={class:"table table-light"},$=e("thead",null,[e("th",null,"ID"),e("th",null,"Employee Name"),e("th",null,"Type"),e("th",null,"Date"),e("th",null,"Actions")],-1),ee=e("i",{class:"fas fa-trash"},null,-1),te={class:"single-info-input margin-top-30"},oe=e("label",{class:"info-title"},"Date Time",-1);function ae(r,a,g,t,b,B){const c=s("Head"),l=s("BsModalButton"),m=s("Link"),L=s("Pagination"),h=s("BreezeValidationErrors"),y=s("BsSelect"),k=s("VueDatePicker"),D=s("BsButton"),V=s("BsModal");return _(),u(w,null,[n(c,{title:"All Leaves"}),e("div",G,[e("div",J,[e("div",O,[e("div",Q,[R,e("div",W,[n(l,{target:"addnewcategory","button-class":"btn btn-info m-1"},{default:p(()=>[P("Add New Leave")]),_:1})])]),e("div",X,[e("table",Z,[$,e("tbody",null,[(_(!0),u(w,null,I(t.attendancesData(),o=>(_(),u("tr",{key:o.id},[e("td",null,d(o.id),1),e("td",null,d(o.name),1),e("td",null,[e("span",{class:M(["alert text-capitalize",o.type==="C/In"?"alert-success":"alert-danger"])},d(o.type.replace("-"," ")),3)]),e("td",null,d(r.readableDateFormat(o.date_time)),1),e("td",null,[n(m,{onClick:ne=>t.deleteItem(o),class:"btn btn-danger m-2"},{default:p(()=>[ee]),_:2},1032,["onClick"])])]))),128))])])]),n(L,{links:t.attendancesLink()},null,8,["links"])])])]),n(V,{"modal-title":"Add New Attendance Log","modal-id":"addnewcategory","modal-size":"modal-md"},{default:p(()=>[n(h,{class:"mb-4"}),e("form",{onSubmit:a[3]||(a[3]=z(()=>{},["prevent"]))},[n(y,{title:"Employee",options:t.employeesList(),modelValue:t.newLogData.employee_id,"onUpdate:modelValue":a[0]||(a[0]=o=>t.newLogData.employee_id=o)},null,8,["options","modelValue"]),n(y,{title:"Status",options:t.attendanceTypes,modelValue:t.newLogData.type,"onUpdate:modelValue":a[1]||(a[1]=o=>t.newLogData.type=o)},null,8,["options","modelValue"]),e("div",te,[oe,n(k,{modelValue:t.newLogData.date_time,"onUpdate:modelValue":a[2]||(a[2]=o=>t.newLogData.date_time=o)},null,8,["modelValue"])]),n(D,{"button-text":"Submit","button-type":"submit",onClick:t.addAttendanceLogFormSubmit,disabled:t.newLogData.processing},null,8,["onClick","disabled"])],32)]),_:1})],64)}const ge=j(q,[["render",ae]]);export{ge as default};