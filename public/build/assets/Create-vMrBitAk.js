import{A as v}from"./AdminMaster-ECLE8g3e.js";import{H as S,L as b,K as D,u as w,l as h,m as V,c as A,a as i,b as e,g as B,w as k,e as m,t as n,F as x,r,o as F}from"./app-bWgNDweR.js";import{B as I}from"./Button-SkAGLveQ.js";import{I as E}from"./Input-BXP8YESH.js";import{S as P}from"./Select-5erWhWuO.js";import{T as C}from"./Textarea-qDf2O4QI.js";import{s as M}from"./vue3-datepicker.esm-Q37PwnIw.js";import"./sweetalert2.all-h8zvUiN9.js";import{h as N}from"./html2pdf-Ws_JUrEx.js";import{_ as T}from"./_plugin-vue_export-helper-x3n3nnut.js";const L={layout:v,components:{BsButton:I,Input:E,Select:P,Textarea:C,Head:S,Datepicker:M,Link:b,VueDatePicker:D},setup(){const c=w().props.value.all_employee,a=h(0),d=h({name:"Name",amount:0,category:"Designation"}),t=V({employee_id:null,month:new Date,amount:null});function y(){a.value=parseFloat(t.amount)}function _(){let o=d.value.name+"-"+p(t.month)+"-advance-salary-application.pdf";N(document.getElementById("element-to-convert"),{margin:1,filename:o}),axios.post(route("admin.employee.advance.salary.store"),t)}function u(){axios.post(route("admin.employee.details",t.employee_id),{month:t.month}).then(o=>{let s=o.data.details;d.value={name:s.name,amount:d.value.amount,category:s.designation}})}function p(o){let s=["January","February","March","April","May","June","July","August","September","October","November","December"];return o!=null?s[new Date(o).getMonth()]:""}return{employeeList:c,salarySlipData:t,exportToPDF:_,selectedEmployee:d,changeEmployeeSelect:u,getSelectedMonthName:p,changeAmount:y,payableAmount:a}}},H={class:"row"},W={class:"col-lg-12"},J={class:"header-wrap d-flex justify-content-between"},U=e("h2",{class:"dashboards-title margin-bottom-40"},"Create Advance Salary",-1),j={class:"btn-wrp"},K={class:"row"},O={class:"col-lg-4"},X={class:"single-info-input margin-top-30"},q=e("label",{class:"info-title"},"Salary Month",-1),z=e("span",{class:"info-text"},"Select Month First",-1),R={class:"col-lg-8"},Y={id:"element-to-convert"},G={class:"salarySlipPdfOuterWrapper advanceSalary"},Q=e("div",{class:"headerPart"},[e("div",{class:"subjectWarp"},[e("p",null,"Advance salary")])],-1),Z={class:"paratext"},$=e("strong",null,"Xgenious",-1),ee=e("p",null,[e("strong",{class:"d-block"},"I agree to repay this advance as follow: "),m(" This payroll deduction to be made from my salary on the first pay period immediately following the pay period from which this advance is made.")],-1),te=e("p",null,"I also agree that if I terminate employment prior to total repayment of this advance, I authorize the company to deduct any unpaid advance amount from the salary owed me at the time of termination of employment. ",-1),ae={class:"footerPartWrapper"},oe=e("div",{class:"signature"},[e("span",{class:"signature"},"Signature"),e("h2",null,"Sharifur Rahman"),e("span",{class:"designation"},"Ceo - Xgenious")],-1),ne={class:"signature"},le=e("span",{class:"signature"},"Signature",-1),se={class:"designation"},ie={class:"btn-wrapper margin-left-60"};function re(c,a,d,t,y,_){const u=r("Head"),p=r("Link"),o=r("VueDatePicker"),s=r("Select"),f=r("Input"),g=r("BsButton");return F(),A(x,null,[i(u,{title:"Create Advance Salary"}),e("div",H,[e("div",W,[e("div",J,[U,e("div",j,[i(p,{class:"btn btn-info m-1",href:c.route("admin.employee.advance.salary.all")},{default:B(()=>[m("Advance salary List")]),_:1},8,["href"])])])]),e("div",K,[e("div",O,[e("form",{onSubmit:a[5]||(a[5]=k(()=>{},["prevent"]))},[e("div",X,[q,i(o,{modelValue:t.salarySlipData.month,"onUpdate:modelValue":a[0]||(a[0]=l=>t.salarySlipData.month=l)},null,8,["modelValue"]),z]),i(s,{title:"Employee",onChange:a[1]||(a[1]=l=>t.changeEmployeeSelect()),modelValue:t.salarySlipData.employee_id,"onUpdate:modelValue":a[2]||(a[2]=l=>t.salarySlipData.employee_id=l),options:t.employeeList},null,8,["modelValue","options"]),i(f,{title:"Amount",type:"number",onInput:a[3]||(a[3]=l=>t.changeAmount()),modelValue:t.salarySlipData.amount,"onUpdate:modelValue":a[4]||(a[4]=l=>t.salarySlipData.amount=l)},null,8,["modelValue"])],32)]),e("div",R,[e("div",Y,[e("div",G,[Q,e("div",Z,[e("p",null,[m("I, "),e("strong",null,n(t.selectedEmployee.name),1),m(", "),e("strong",null,"Department: "+n(t.selectedEmployee.category),1),m(" request an advance payment of "+n(t.payableAmount)+"/- ("+n(c.numberToWord(t.payableAmount))+") BDT on my salary to be paid on 5th "+n(t.getSelectedMonthName(t.salarySlipData.month))+" "+n(new Date().getFullYear())+" due to personal problem as permitted by ",1),$,m(" policy. ")]),ee,te]),e("div",ae,[oe,e("div",ne,[le,e("h2",null,n(t.selectedEmployee.name),1),e("span",se,n(t.selectedEmployee.category),1)])])])]),e("div",ie,[i(g,{type:"button",onClick:t.exportToPDF,"button-text":"Save and Download PDF"},null,8,["onClick"])])])])])],64)}const ve=T(L,[["render",re]]);export{ve as default};