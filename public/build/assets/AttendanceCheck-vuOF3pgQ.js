import{K as A,L as S,H,l as n,u as F,m as O,c as y,a as l,b as e,t as s,g as b,w as M,e as o,h as N,F as L,x as P,r,o as D,p as j,n as W}from"./app-gltJhGw6.js";import{A as T}from"./AdminMaster-T4TN4Ype.js";import{S as I}from"./Select-GSljLRwI.js";import{B as K}from"./Button-_xX5v1SM.js";import{s as z}from"./vue3-datepicker.esm-EqqSdteq.js";import{V as G}from"./ValidationErrors-K-JALEkY.js";import{S as B}from"./sweetalert2.all-DleBhdmB.js";import{_ as U}from"./_plugin-vue_export-helper-x3n3nnut.js";const X={name:"AttendanceCheck",layout:T,components:{VueDatePicker:A,BsSelect:I,BsButton:K,Datepicker:z,ValidationErrors:G,Link:S,Head:H},setup(){const c=n(0),i=n(0),g=n(0),a=n(0),k=n(0),w=n(0),h=n(!1),d=n([]),p=F().props.value.allEmployees,u=O({employee_id:p.id,startDate:new Date,endDate:new Date});function C(){P.post(route("admin.employee.attendance.post"),{...u}).then(t=>{let f=t.data.logs;if(Object.keys(f).length>0){let v=f,m=[];Object.keys(v).forEach(function(x){let V=v[x];Object.keys(V).forEach(_=>{_!=="dateTime"&&m.push({key:`${_}-${Math.random()}`,customData:{title:`${_.replaceAll("_"," ")}: ${V[_]}`,class:_},dates:new Date(V.dateTime)})})}),d.value=m,h.value=!0,c.value=t.data.holidayCount,i.value=t.data.leaveCount,g.value=t.data.OfficeDays,a.value=t.data.sickLeaveCount,k.value=t.data.paidLeaveCount,w.value=t.data.workFormHome}else B.fire("Error!","NO Data found for selected Dates","warning")}).catch(t=>{B.fire("Error!",t.message,"warning")})}new Date().getMonth(),new Date().getFullYear();function E(){return{masks:{weekdays:"WWW"},attributes:d.value}}return{employeeData:u,getAttendanceDetails:C,employeeDetails:p,CalendarData:E,attendanceShow:h,CalendarAttributes:d,holidayCount:c,leaveCount:i,OfficeDays:g,sickLeaveCount:a,paidLeaveCount:k,workFormHome:w}}},Y={class:"row"},q={class:"col-lg-12"},J={class:"dashboard-settings margin-top-40"},Q={class:"header-wrap d-flex justify-content-between"},R={class:"dashboards-title"},Z={class:"btn-wrp"},$={class:"edit-profile"},ee={class:"profile-info-dashboard"},ae={class:"dashboard-profile-flex"},te={class:"dashboard-address-details"},se={class:"single-dashboard-input"},oe={class:"single-info-input margin-top-30"},ne=e("label",{class:"info-title"}," Select Month ",-1),le=e("span",null,"any date you select in this calendar, system will get data for the selected date month",-1),ie={class:"btn-wrapper margin-top-35"},de={key:0,class:"cleardar-wrapper"},ce={class:"head-rapw margin-top-40"},re=e("h4",null,"Color Explanation",-1),me={class:"color-explanation"},_e={class:"holiday"},he={class:"badge"},pe={class:"leave"},ue={class:"badge"},fe={class:"C/In"},ve={class:"badge"},ye={class:"sick-leave"},be={class:"badge"},De={class:"paid-leave"},ge={class:"badge"},ke={class:"work-from-home"},we={class:"badge"},Ce={class:"date-box-wrapper"},Ee={class:"day-label"},Ve={class:"custom-data-wrapper"};function Le(c,i,g,a,k,w){const h=r("Head"),d=r("Link"),p=r("ValidationErrors"),u=r("VueDatePicker"),C=r("BsButton"),E=r("Calendar");return D(),y(L,null,[l(h,{title:"Employee Attendance Check - Xgenious"}),e("div",Y,[e("div",q,[e("div",J,[e("div",Q,[e("h2",R,s(a.employeeDetails.name)+" Attendance Check",1),e("div",Z,[l(d,{class:"btn btn-info m-1",href:c.route("admin.employee.all")},{default:b(()=>[o("All Employees")]),_:1},8,["href"]),l(d,{class:"btn btn-secondary m-1",href:c.route("admin.employee.edit",a.employeeDetails.id)},{default:b(()=>[o("Edit Employee Details")]),_:1},8,["href"]),l(d,{class:"btn btn-primary m-1",href:c.route("admin.employee.view",a.employeeDetails.id)},{default:b(()=>[o("View Employee Details")]),_:1},8,["href"])])]),e("div",$,[e("div",ee,[l(p),e("div",ae,[e("div",te,[e("form",{onSubmit:i[1]||(i[1]=M(()=>{},["prevent"]))},[e("div",se,[e("div",oe,[ne,l(u,{modelValue:a.employeeData.startDate,"onUpdate:modelValue":i[0]||(i[0]=t=>a.employeeData.startDate=t)},null,8,["modelValue"]),le])]),e("div",ie,[l(C,{"button-text":"Get Details",onClick:a.getAttendanceDetails,classes:"cmn-btn btn-bg-1"},null,8,["onClick"])])],32)])])])]),a.attendanceShow?(D(),y("div",de,[e("div",ce,[re,e("ul",me,[e("li",_e,[o("Holiday"),e("span",he,s(a.holidayCount),1)]),e("li",pe,[o("leave"),e("span",ue,s(a.leaveCount),1)]),e("li",fe,[o("OfficeDays"),e("span",ve,s(a.OfficeDays),1)]),e("li",ye,[o("Sick Leave"),e("span",be,s(a.sickLeaveCount),1)]),e("li",De,[o("Paid Leave"),e("span",ge,s(a.paidLeaveCount),1)]),e("li",ke,[o("Work From Home"),e("span",we,s(a.workFormHome),1)])])]),l(E,{class:"custom-calendar-outer-wrap",masks:a.CalendarData().masks,attributes:a.CalendarData().attributes,"disable-page-swipe":"","is-expanded":""},{"day-content":b(({day:t,attributes:f})=>[e("div",Ce,[e("span",Ee,s(t.day),1),e("div",Ve,[(D(!0),y(L,null,j(f,({key:v,customData:m})=>(D(),y("p",{key:v,class:W(["data-item",m.class])},s(m.title),3))),128))])])]),_:1},8,["masks","attributes"])])):N("",!0)])])])],64)}const Ne=U(X,[["render",Le]]);export{Ne as default};