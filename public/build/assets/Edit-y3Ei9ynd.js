import{A as S}from"./AdminMaster-niXjrFAq.js";import{H as b,L as D,u as f,l as v,m as B,r,o as w,c as A,a as m,b as e,g as x,w as E,e as d,t as s,F as V}from"./app-i-P7TbwR.js";import{B as k}from"./Button-EdfEcMu6.js";import{B as F}from"./Input-AGVaQRm8.js";import{B as I}from"./Select-QK1i74w-.js";import{B as M}from"./Textarea-5syyXWbD.js";import{s as T}from"./vue3-datepicker.esm-_z1PbtWX.js";import"./sweetalert2.all-icJphP2-.js";import{h as L}from"./html2pdf-HWt-ClU2.js";import{_ as N}from"./_plugin-vue_export-helper-x3n3nnut.js";const P={layout:S,components:{BsButton:k,Input:F,Select:I,Textarea:M,Head:b,Datepicker:T,Link:D},setup(){const c=f().props.value.all_employee,a=f().props.value.advance_salary,p=v(0);p.value=a.amount;const t=v({name:a.name,amount:a.amount,category:a.category}),n=B({employee_id:a.employee_id,month:new Date(a.month),amount:a.amount});function h(){p.value=parseFloat(n.amount)}function y(){let o=t.value.name+"-"+u(n.month)+"-salary-slip.pdf";L(document.getElementById("element-to-convert"),{margin:1,filename:o}),axios.post(route("admin.employee.advance.salary.store"),n)}function _(){axios.post(route("admin.employee.details",n.employee_id),{month:n.month}).then(o=>{let i=o.data.details;t.value={name:i.name,amount:t.value.amount,category:i.designation}})}function u(o){let i=["January","February","March","April","May","June","July","August","September","October","November","December"];return o!=null?i[new Date(o).getMonth()]:""}return{employeeList:c,salarySlipData:n,exportToPDF:y,selectedEmployee:t,changeEmployeeSelect:_,getSelectedMonthName:u,changeAmount:h,payableAmount:p}}},C={class:"row"},H={class:"col-lg-12"},W={class:"header-wrap d-flex justify-content-between"},J=e("h2",{class:"dashboards-title margin-bottom-40"},"Edit Advance Salary",-1),U={class:"btn-wrp"},j={class:"row"},O={class:"col-lg-4"},X={class:"single-info-input margin-top-30"},q=e("label",{class:"info-title"},"Salary Month",-1),z=e("span",{class:"info-text"},"Select Month First",-1),R={class:"col-lg-8"},Y={id:"element-to-convert"},G={class:"salarySlipPdfOuterWrapper advanceSalary"},K=e("div",{class:"headerPart"},[e("div",{class:"subjectWarp"},[e("p",null,"Advance salary")])],-1),Q={class:"paratext"},Z=e("strong",null,"Xgenious",-1),$=e("p",null,[e("strong",{class:"d-block"},"I agree to repay this advance as follow: "),d(" This payroll deduction to be made from my salary on the first pay period immediately following the pay period from which this advance is made.")],-1),ee=e("p",null,"I also agree that if I terminate employment prior to total repayment of this advance, I authorize the company to deduct any unpaid advance amount from the salary owed me at the time of termination of employment. ",-1),te={class:"footerPartWrapper"},ae=e("div",{class:"signature"},[e("span",{class:"signature"},"Signature"),e("h2",null,"Sharifur Rahman"),e("span",{class:"designation"},"Ceo - Xgenious")],-1),oe={class:"signature"},ne=e("span",{class:"signature"},"Signature",-1),le={class:"btn-wrapper margin-left-60"};function se(c,a,p,t,n,h){const y=r("Head"),_=r("Link"),u=r("Datepicker"),o=r("Select"),i=r("Input"),g=r("BsButton");return w(),A(V,null,[m(y,{title:"Edit Advance Salary"}),e("div",C,[e("div",H,[e("div",W,[J,e("div",U,[m(_,{class:"btn btn-info m-1",href:c.route("admin.employee.advance.salary.all")},{default:x(()=>[d("Advance salary List")]),_:1},8,["href"])])])]),e("div",j,[e("div",O,[e("form",{onSubmit:a[5]||(a[5]=E(()=>{},["prevent"]))},[e("div",X,[q,m(u,{modelValue:t.salarySlipData.month,"onUpdate:modelValue":a[0]||(a[0]=l=>t.salarySlipData.month=l)},null,8,["modelValue"]),z]),m(o,{title:"Employee",onChange:a[1]||(a[1]=l=>t.changeEmployeeSelect()),modelValue:t.salarySlipData.employee_id,"onUpdate:modelValue":a[2]||(a[2]=l=>t.salarySlipData.employee_id=l),options:t.employeeList},null,8,["modelValue","options"]),m(i,{title:"Amount",type:"number",onInput:a[3]||(a[3]=l=>t.changeAmount()),modelValue:t.salarySlipData.amount,"onUpdate:modelValue":a[4]||(a[4]=l=>t.salarySlipData.amount=l)},null,8,["modelValue"])],32)]),e("div",R,[e("div",Y,[e("div",G,[K,e("div",Q,[e("p",null,[d("I, "),e("strong",null,s(t.selectedEmployee.name),1),d(", "),e("strong",null,"Department: "+s(t.selectedEmployee.category),1),d(" request an advance payment of "+s(t.payableAmount)+"/- ("+s(c.numberToWord(t.payableAmount))+") BDT on my salary to be paid on 5th "+s(t.getSelectedMonthName(t.salarySlipData.month))+" "+s(new Date().getFullYear())+" due to personal problem as permitted by ",1),Z,d(" policy. ")]),$,ee]),e("div",te,[ae,e("div",oe,[ne,e("h2",null,s(t.selectedEmployee.name),1)])])])]),e("div",le,[m(g,{type:"button",onClick:t.exportToPDF,"button-text":"Save and Download PDF"},null,8,["onClick"])])])])])],64)}const fe=N(P,[["render",se]]);export{fe as default};