import{A as S}from"./AdminMaster-ECLE8g3e.js";import{H as b,L as D,K as w,u as f,l as v,m as V,c as A,a as r,b as e,g as B,w as k,e as d,t as s,F as x,r as m,o as E}from"./app-bWgNDweR.js";import{B as F}from"./Button-SkAGLveQ.js";import{I}from"./Input-BXP8YESH.js";import{S as P}from"./Select-5erWhWuO.js";import{T as M}from"./Textarea-qDf2O4QI.js";import{s as T}from"./vue3-datepicker.esm-Q37PwnIw.js";import"./sweetalert2.all-h8zvUiN9.js";import{h as L}from"./html2pdf-Ws_JUrEx.js";import{_ as N}from"./_plugin-vue_export-helper-x3n3nnut.js";const C={layout:S,components:{BsButton:F,Input:I,Select:P,Textarea:M,Head:b,Datepicker:T,Link:D,VueDatePicker:w},setup(){const c=f().props.value.all_employee,a=f().props.value.advance_salary,p=v(0);p.value=a.amount;const t=v({name:a.name,amount:a.amount,category:a.category}),n=V({employee_id:a.employee_id,month:new Date(a.month),amount:a.amount});function h(){p.value=parseFloat(n.amount)}function y(){let o=t.value.name+"-"+u(n.month)+"-salary-slip.pdf";L(document.getElementById("element-to-convert"),{margin:1,filename:o}),axios.post(route("admin.employee.advance.salary.store"),n)}function _(){axios.post(route("admin.employee.details",n.employee_id),{month:n.month}).then(o=>{let i=o.data.details;t.value={name:i.name,amount:t.value.amount,category:i.designation}})}function u(o){let i=["January","February","March","April","May","June","July","August","September","October","November","December"];return o!=null?i[new Date(o).getMonth()]:""}return{employeeList:c,salarySlipData:n,exportToPDF:y,selectedEmployee:t,changeEmployeeSelect:_,getSelectedMonthName:u,changeAmount:h,payableAmount:p}}},H={class:"row"},W={class:"col-lg-12"},J={class:"header-wrap d-flex justify-content-between"},U=e("h2",{class:"dashboards-title margin-bottom-40"},"Edit Advance Salary",-1),j={class:"btn-wrp"},K={class:"row"},O={class:"col-lg-4"},X={class:"single-info-input margin-top-30"},q=e("label",{class:"info-title"},"Salary Month",-1),z=e("span",{class:"info-text"},"Select Month First",-1),R={class:"col-lg-8"},Y={id:"element-to-convert"},G={class:"salarySlipPdfOuterWrapper advanceSalary"},Q=e("div",{class:"headerPart"},[e("div",{class:"subjectWarp"},[e("p",null,"Advance salary")])],-1),Z={class:"paratext"},$=e("strong",null,"Xgenious",-1),ee=e("p",null,[e("strong",{class:"d-block"},"I agree to repay this advance as follow: "),d(" This payroll deduction to be made from my salary on the first pay period immediately following the pay period from which this advance is made.")],-1),te=e("p",null,"I also agree that if I terminate employment prior to total repayment of this advance, I authorize the company to deduct any unpaid advance amount from the salary owed me at the time of termination of employment. ",-1),ae={class:"footerPartWrapper"},oe=e("div",{class:"signature"},[e("span",{class:"signature"},"Signature"),e("h2",null,"Sharifur Rahman"),e("span",{class:"designation"},"Ceo - Xgenious")],-1),ne={class:"signature"},le=e("span",{class:"signature"},"Signature",-1),se={class:"btn-wrapper margin-left-60"};function ie(c,a,p,t,n,h){const y=m("Head"),_=m("Link"),u=m("VueDatePicker"),o=m("Select"),i=m("Input"),g=m("BsButton");return E(),A(x,null,[r(y,{title:"Edit Advance Salary"}),e("div",H,[e("div",W,[e("div",J,[U,e("div",j,[r(_,{class:"btn btn-info m-1",href:c.route("admin.employee.advance.salary.all")},{default:B(()=>[d("Advance salary List")]),_:1},8,["href"])])])]),e("div",K,[e("div",O,[e("form",{onSubmit:a[5]||(a[5]=k(()=>{},["prevent"]))},[e("div",X,[q,r(u,{modelValue:t.salarySlipData.month,"onUpdate:modelValue":a[0]||(a[0]=l=>t.salarySlipData.month=l)},null,8,["modelValue"]),z]),r(o,{title:"Employee",onChange:a[1]||(a[1]=l=>t.changeEmployeeSelect()),modelValue:t.salarySlipData.employee_id,"onUpdate:modelValue":a[2]||(a[2]=l=>t.salarySlipData.employee_id=l),options:t.employeeList},null,8,["modelValue","options"]),r(i,{title:"Amount",type:"number",onInput:a[3]||(a[3]=l=>t.changeAmount()),modelValue:t.salarySlipData.amount,"onUpdate:modelValue":a[4]||(a[4]=l=>t.salarySlipData.amount=l)},null,8,["modelValue"])],32)]),e("div",R,[e("div",Y,[e("div",G,[Q,e("div",Z,[e("p",null,[d("I, "),e("strong",null,s(t.selectedEmployee.name),1),d(", "),e("strong",null,"Department: "+s(t.selectedEmployee.category),1),d(" request an advance payment of "+s(t.payableAmount)+"/- ("+s(c.numberToWord(t.payableAmount))+") BDT on my salary to be paid on 5th "+s(t.getSelectedMonthName(t.salarySlipData.month))+" "+s(new Date().getFullYear())+" due to personal problem as permitted by ",1),$,d(" policy. ")]),ee,te]),e("div",ae,[oe,e("div",ne,[le,e("h2",null,s(t.selectedEmployee.name),1)])])])]),e("div",se,[r(g,{type:"button",onClick:t.exportToPDF,"button-text":"Save and Download PDF"},null,8,["onClick"])])])])])],64)}const ve=N(C,[["render",ie]]);export{ve as default};