import{A as f}from"./AdminMaster-T4TN4Ype.js";import{L as h,u as b,m as V,c as v,b as a,a as n,g as l,w,r as s,o as g,e as c}from"./app-gltJhGw6.js";import{I as k}from"./Input-N_VnS9ZC.js";import{B as A}from"./Button-_xX5v1SM.js";import{M as D}from"./MediaUploader-OqSQcZjO.js";import{s as U}from"./vue3-datepicker.esm-EqqSdteq.js";import{S as y}from"./sweetalert2.all-DleBhdmB.js";import{V as B}from"./ValidationErrors-K-JALEkY.js";import{_ as E}from"./_plugin-vue_export-helper-x3n3nnut.js";const x={name:"Edit",layout:f,components:{Link:h,Input:k,MediaUploader:D,Datepicker:U,Button:A,ValidationErrors:B},setup(){const t=b().props.value.attendance,e=V({id:t.id,name:t.name,start_date:new Date(t.start_date),end_date:new Date(t.end_date),file_id:t.file_id});function r(){e.post(route("admin.employee.attendance.update"),{onSuccess:()=>{y.fire("Updated","Attendance Updated","success")}})}return{attendanceData:e,createAttendance:r}}},C={class:"row"},M={class:"col-lg-12"},N={class:"dashboard-settings margin-top-40"},S={class:"header-wrap d-flex justify-content-between"},I=a("h2",{class:"dashboards-title margin-bottom-40"},"Edit Attendance Report",-1),L={class:"btn-wrapper"},F={class:"single-info-input margin-top-30"},R=a("small",null,"select only csv file",-1);function j(t,e,r,o,P,T){const i=s("Link"),m=s("ValidationErrors"),p=s("Input"),_=s("MediaUploader"),u=s("Button");return g(),v("div",C,[a("div",M,[a("div",N,[a("div",S,[I,a("div",L,[n(i,{class:"btn btn-info m-2",href:t.route("admin.employee.attendance")},{default:l(()=>[c("All Attendance Reports")]),_:1},8,["href"]),n(i,{class:"btn btn-secondary m-2",href:t.route("admin.employee.attendance.create")},{default:l(()=>[c("Create New ")]),_:1},8,["href"])])]),n(m),a("form",{onSubmit:e[2]||(e[2]=w(()=>{},["prevent"]))},[n(p,{title:"Name",modelValue:o.attendanceData.name,"onUpdate:modelValue":e[0]||(e[0]=d=>o.attendanceData.name=d)},null,8,["modelValue"]),a("div",F,[n(_,{"button-text":"Upload File",modelValue:o.attendanceData.file_id,"onUpdate:modelValue":e[1]||(e[1]=d=>o.attendanceData.file_id=d)},null,8,["modelValue"]),R]),n(u,{"button-text":"Save Changes",onClick:o.createAttendance},null,8,["onClick"])],32)])])])}const X=E(x,[["render",j]]);export{X as default};