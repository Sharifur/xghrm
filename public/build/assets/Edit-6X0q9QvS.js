import{L as g,H as I,u as c,m as v,c as B,a as l,b as a,g as U,w as D,F as h,r as d,o as E,e as x}from"./app-RrRrrMtY.js";import{A as w}from"./AdminMaster-upIi9uLN.js";import{I as M}from"./Input-K-IaNq0T.js";import{S}from"./Select-z8nEou51.js";import{T as k}from"./Textarea-zizqH6DR.js";import{B as N}from"./Button-0oI3-S2_.js";import{s as O}from"./vue3-datepicker.esm-lWCF35Rg.js";import{V as L}from"./ValidationErrors-qkK8fPvR.js";import{S as C}from"./sweetalert2.all-dJWsu-1m.js";import{M as T}from"./MediaUploader-kMzR8mI3.js";import{_ as j}from"./_plugin-vue_export-helper-x3n3nnut.js";const A={name:"new",layout:w,components:{BsInput:M,BsSelect:S,BsTextarea:k,BsButton:N,Datepicker:O,ValidationErrors:L,Link:g,MediaUploader:T,Head:I},setup(r,o){const n=c().props.value.employeeDetails,e=v({paymentInfo:n.paymentInfo,emergencyNumber:n.emergencyNumber,address:n.address,mobile:n.mobile,salary:n.salary,name:n.name,email:n.email,catId:n.catId,personalInfo:n.personalInfo,imageId:n.imageId,att_id:n.att_id,status:n.status,incrementMonth:new Date(n.incrementMonth),joinDate:new Date(n.joinDate),dateOfBirth:new Date(n.dateOfBirth),id:n.id});function p(){e.post(route("admin.employee.update"),{onSuccess:()=>{C.fire("Updated!","Employee Entry","success")}})}function u(){return c().props.value.category}return{emplyeeFormdata:e,catList:u,editEmployee:p,statusOption:[{label:"Working",value:1},{label:"Left Job",value:0}]}}},H={class:"row"},P={class:"col-lg-12"},J={class:"dashboard-settings margin-top-40"},W={class:"header-wrap d-flex justify-content-between"},q=a("h2",{class:"dashboards-title"}," Edit Employee Details",-1),z={class:"btn-wrp"},G={class:"edit-profile"},K={class:"profile-info-dashboard"},Q={class:"dashboard-profile-flex"},R={class:"dashboard-address-details"},X=["value"],Y={class:"single-dashboard-input"},Z={class:"single-info-input margin-top-30"},$=a("label",{class:"info-title"}," Date Of Birth* ",-1),ee={class:"single-dashboard-input"},oe={class:"single-info-input margin-top-30"},te=a("label",{class:"info-title"}," Join Date* ",-1),ae={class:"single-dashboard-input"},le={class:"single-info-input margin-top-30"},ne=a("label",{class:"info-title"}," Increment Month* ",-1),de={class:"btn-wrapper margin-top-35"};function me(r,o,n,e,p,u){const y=d("Head"),V=d("Link"),_=d("ValidationErrors"),m=d("BsInput"),f=d("BsSelect"),s=d("Datepicker"),i=d("BsTextarea"),b=d("MediaUploader"),F=d("BsButton");return E(),B(h,null,[l(y,{title:"Edit Employee Details"}),a("div",H,[a("div",P,[a("div",J,[a("div",W,[q,a("div",z,[l(V,{class:"btn btn-info m-1",href:r.route("admin.employee.all")},{default:U(()=>[x("All Employee")]),_:1},8,["href"])])]),a("div",G,[a("div",K,[l(_),a("div",Q,[a("div",R,[a("form",{onSubmit:o[15]||(o[15]=D(()=>{},["prevent"]))},[a("input",{type:"hidden",value:e.emplyeeFormdata.id},null,8,X),l(m,{type:"text",title:"Name*",modelValue:e.emplyeeFormdata.name,"onUpdate:modelValue":o[0]||(o[0]=t=>e.emplyeeFormdata.name=t)},null,8,["modelValue"]),l(m,{type:"email",title:"Email*",modelValue:e.emplyeeFormdata.email,"onUpdate:modelValue":o[1]||(o[1]=t=>e.emplyeeFormdata.email=t)},null,8,["modelValue"]),l(f,{title:"Category*",options:e.catList(),modelValue:e.emplyeeFormdata.catId,"onUpdate:modelValue":o[2]||(o[2]=t=>e.emplyeeFormdata.catId=t)},null,8,["options","modelValue"]),l(f,{title:"Status*",options:e.statusOption,modelValue:e.emplyeeFormdata.status,"onUpdate:modelValue":o[3]||(o[3]=t=>e.emplyeeFormdata.status=t)},null,8,["options","modelValue"]),l(m,{type:"number",title:"Mobile Number*",modelValue:e.emplyeeFormdata.mobile,"onUpdate:modelValue":o[4]||(o[4]=t=>e.emplyeeFormdata.mobile=t)},null,8,["modelValue"]),l(m,{type:"number",title:"Salary*",modelValue:e.emplyeeFormdata.salary,"onUpdate:modelValue":o[5]||(o[5]=t=>e.emplyeeFormdata.salary=t)},null,8,["modelValue"]),a("div",Y,[a("div",Z,[$,l(s,{modelValue:e.emplyeeFormdata.dateOfBirth,"onUpdate:modelValue":o[6]||(o[6]=t=>e.emplyeeFormdata.dateOfBirth=t)},null,8,["modelValue"])])]),a("div",ee,[a("div",oe,[te,l(s,{modelValue:e.emplyeeFormdata.joinDate,"onUpdate:modelValue":o[7]||(o[7]=t=>e.emplyeeFormdata.joinDate=t)},null,8,["modelValue"])])]),a("div",ae,[a("div",le,[ne,l(s,{modelValue:e.emplyeeFormdata.incrementMonth,"onUpdate:modelValue":o[8]||(o[8]=t=>e.emplyeeFormdata.incrementMonth=t)},null,8,["modelValue"])])]),l(i,{title:"Address",modelValue:e.emplyeeFormdata.address,"onUpdate:modelValue":o[9]||(o[9]=t=>e.emplyeeFormdata.address=t)},null,8,["modelValue"]),l(m,{type:"text",title:"Attendance ID",modelValue:e.emplyeeFormdata.att_id,"onUpdate:modelValue":o[10]||(o[10]=t=>e.emplyeeFormdata.att_id=t)},null,8,["modelValue"]),l(m,{type:"number",title:"Emergency Number*",modelValue:e.emplyeeFormdata.emergencyNumber,"onUpdate:modelValue":o[11]||(o[11]=t=>e.emplyeeFormdata.emergencyNumber=t)},null,8,["modelValue"]),l(i,{title:"Payment Information",modelValue:e.emplyeeFormdata.paymentInfo,"onUpdate:modelValue":o[12]||(o[12]=t=>e.emplyeeFormdata.paymentInfo=t)},null,8,["modelValue"]),l(i,{title:"Personal Information (NID/Passport Info)",modelValue:e.emplyeeFormdata.personalInfo,"onUpdate:modelValue":o[13]||(o[13]=t=>e.emplyeeFormdata.personalInfo=t)},null,8,["modelValue"]),l(b,{modelValue:e.emplyeeFormdata.imageId,"onUpdate:modelValue":o[14]||(o[14]=t=>e.emplyeeFormdata.imageId=t)},null,8,["modelValue"]),a("div",de,[l(F,{"button-text":"Save Changes",onClick:e.editEmployee,classes:"cmn-btn btn-bg-1"},null,8,["onClick"])])],32)])])])])])])])],64)}const Fe=j(A,[["render",me]]);export{Fe as default};
