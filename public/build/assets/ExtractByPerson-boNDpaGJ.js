import{A as D}from"./AdminMaster-kp-HkNmh.js";import{L as b,H as k,u as v,m as B,l as S,c as h,a as o,b as l,g as V,w as A,q as f,h as g,F as E,r as s,o as m,e as L}from"./app-NMZH-3Gf.js";import{B as w}from"./Input--3e-HiE7.js";import{B as T}from"./Button-dMU1l4IO.js";import{S as x}from"./Select-4yHcRvsE.js";import{M as H}from"./MediaUploader-ylINNrM0.js";import{s as I}from"./vue3-datepicker.esm-gtpJu_BA.js";import{S as i}from"./sweetalert2.all-co24XUOH.js";import{V as U}from"./ValidationErrors-N59rxZEB.js";import{_ as F}from"./_plugin-vue_export-helper-x3n3nnut.js";const N={name:"ExtractByPerson",layout:D,components:{Select:x,Link:b,Input:w,MediaUploader:H,Datepicker:I,Button:T,ValidationErrors:U,Head:k},setup(){const c=v().props.value.csv_headers,t=v().props.value.employees,u=v().props.value.attendance,e=B({attendance_report_id:u.id,csv_column:null,column_value:null,attendance_column_value:null,attendance_type_column_value:null,employee_id:null,importType:"bulk"});function y(){e.post(route("admin.employee.attendance.log.from.csv"),{onSuccess:n=>{e.reset("csv_column","employee_id","column_value"),i.fire("Success","Attendance Log Insert","success")}})}const p=S([{label:"select csv Column first",value:null}]),_=[{label:"Bulk Import",value:"bulk"},{label:"Individual Import",value:"individual"}];function d(){e.csv_column===null&&i.fire("Warning","Select Csv Column First","warning"),axios.post(route("admin.employee.attendance.csv.column.value"),{attendance_report_id:e.attendance_report_id,csv_column:e.csv_column,column_value:e.column_value}).then(n=>{Object.keys(n.data).length>1?p.value=n.data:i.fire("Error!","no data found in selected column","warning")}).catch(n=>{i.fire("Error!",n.message,"warning")})}return{columnValueData:p,createAttendanceLog:y,getCsvColumnValues:d,attendanceLogData:e,csvHeader:c,attendance:u,employees:t,importTypes:_}}},P={class:"row"},M={class:"col-lg-12"},R={class:"dashboard-settings margin-top-40"},j={class:"header-wrap d-flex justify-content-between"},O=l("h2",{class:"dashboards-title margin-bottom-40"},"Extract Attendance Report By Person",-1),q={class:"btn-wrapper"};function W(c,t,u,e,y,p){const _=s("Head"),d=s("Link"),n=s("ValidationErrors"),r=s("Select"),C=s("Button");return m(),h(E,null,[o(_,{title:"Extract Attendance Report By Person"}),l("div",P,[l("div",M,[l("div",R,[l("div",j,[O,l("div",q,[o(d,{class:"btn btn-info m-2",href:c.route("admin.employee.attendance")},{default:V(()=>[L("All Attendance Reports")]),_:1},8,["href"]),o(d,{class:"btn btn-secondary m-2",href:c.route("admin.employee.attendance.create")},{default:V(()=>[L("Create Attendance Reports")]),_:1},8,["href"])])]),o(n),l("form",{onSubmit:t[6]||(t[6]=A(()=>{},["prevent"]))},[o(r,{title:"Employee",modelValue:e.attendanceLogData.importType,"onUpdate:modelValue":t[0]||(t[0]=a=>e.attendanceLogData.importType=a),options:e.importTypes},null,8,["modelValue","options"]),e.attendanceLogData.importType==="individual"?(m(),f(r,{key:0,title:"Employee",modelValue:e.attendanceLogData.employee_id,"onUpdate:modelValue":t[1]||(t[1]=a=>e.attendanceLogData.employee_id=a),options:e.employees},null,8,["modelValue","options"])):g("",!0),e.attendanceLogData.importType==="individual"?(m(),f(r,{key:1,title:"CSV Column For Search Name",onChange:e.getCsvColumnValues,modelValue:e.attendanceLogData.csv_column,"onUpdate:modelValue":t[2]||(t[2]=a=>e.attendanceLogData.csv_column=a),options:e.csvHeader},null,8,["onChange","modelValue","options"])):g("",!0),e.attendanceLogData.importType==="individual"?(m(),f(r,{key:2,title:"Column Value For Name",modelValue:e.attendanceLogData.column_value,"onUpdate:modelValue":t[3]||(t[3]=a=>e.attendanceLogData.column_value=a),options:e.columnValueData},null,8,["modelValue","options"])):g("",!0),o(r,{title:"CSV Attendance Column Date/Time",modelValue:e.attendanceLogData.attendance_column_value,"onUpdate:modelValue":t[4]||(t[4]=a=>e.attendanceLogData.attendance_column_value=a),options:e.csvHeader,info:"select date column"},null,8,["modelValue","options"]),o(r,{title:"CSV Attendance In/Out Type",modelValue:e.attendanceLogData.attendance_type_column_value,"onUpdate:modelValue":t[5]||(t[5]=a=>e.attendanceLogData.attendance_type_column_value=a),options:e.csvHeader,info:"select check in/ check out column"},null,8,["modelValue","options"]),o(C,{"button-text":"Submit Changes",onClick:e.createAttendanceLog},null,8,["onClick"])],32)])])])],64)}const te=F(N,[["render",W]]);export{te as default};