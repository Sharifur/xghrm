import{A}from"./AdminMaster-T4TN4Ype.js";import{H as B,L as W,K as U,u as y,l as u,m as O,c as d,a as m,b as s,g as T,w as H,F as S,p as C,e as n,t as o,r as f,o as c,h as b}from"./app-gltJhGw6.js";import{B as J}from"./Button-_xX5v1SM.js";import{I as R}from"./Input-N_VnS9ZC.js";import{S as G}from"./Select-GSljLRwI.js";import{T as j}from"./Textarea-DjIvpRQB.js";import{s as K}from"./vue3-datepicker.esm-EqqSdteq.js";import"./sweetalert2.all-DleBhdmB.js";import{h as X}from"./html2pdf-b3SbOidI.js";import{_ as Y}from"./_plugin-vue_export-helper-x3n3nnut.js";const q={layout:A,components:{Button:J,Input:R,Select:G,Textarea:j,Head:B,Datepicker:K,Link:W,VueDatePicker:U},setup(){const x=y().props.value.all_employee,a=y().props.value.salarySlipData,v=u({name:a.employee.name,salary:a.salary,category:a.designation}),e=u(y().props.value.attenadnceCount),k=u(y().props.value.holidayCount),V=u(y().props.value.leaveCount),F=u(y().props.value.sickLeaveCount),E=u(y().props.value.paidLeaveCount),g=u(0),D=u(0),h=u(0),l=O({salarySlipID:a.id,employee_id:a.employee.id,month:new Date(a.month),extraEarningFields:JSON.parse(a.extraEarningFields),extraDeductionFields:JSON.parse(a.extraDeductionFields)});function t(){l.extraEarningFields.push({description:null,amount:0})}function i(){l.extraDeductionFields.push({description:null,amount:0})}function L(r){l.extraEarningFields.length>1&&l.extraEarningFields.splice(l.extraEarningFields.indexOf(r),1)}function M(r){l.extraDeductionFields.length>1&&l.extraDeductionFields.splice(l.extraDeductionFields.indexOf(r),1)}function P(){let r=v.value.name+"-"+N(l.month)+"-salary-slip.pdf";X(document.getElementById("element-to-convert"),{margin:1,filename:r}),axios.post(route("admin.employee.salary.slip.update"),l)}function I(){axios.post(route("admin.employee.details",l.employee_id),{month:l.month}).then(r=>{let p=r.data,_=r.data.details;v.value={name:_.name,salary:_.salary,category:_.designation},e.value=p.attenadnceCount,k.value=p.holidayCount,V.value=p.leaveCount,F.value=p.sickLeaveCount,E.value=p.paidLeaveCount,w()})}function N(r){let p=["January","February","March","April","May","June","July","August","September","October","November","December"];return r!=null?p[new Date(r).getMonth()]:""}function w(){let r=0;l.extraEarningFields.map(_=>{_.amount>0&&(r+=parseFloat(_.amount))});let p=0;l.extraDeductionFields.map(_=>{_.amount>0&&(p+=parseFloat(_.amount))}),g.value=r>0?parseFloat(r):0,D.value=p>0?parseFloat(p):0,v.salary!=="undefined"&&(g.value+=parseFloat(v.value.salary)),h.value=parseFloat(g.value)-parseFloat(D.value)}return{employeeList:x,salarySlipData:l,addMoreEarningField:t,addMoreDeductionField:i,removeEarningField:L,removeDeductionField:M,exportToPDF:P,selectedEmployee:v,changeEmployeeSelect:I,holidayCount:k,leaveCount:V,sickLeaveCount:F,paidLeaveCount:E,attenadnceCount:e,getSelectedMonthName:N,grossEarning:g,grossDeduction:D,payableAmount:h,calculateSalary:w}},mounted(){this.calculateSalary()}},z={class:"row"},Q={class:"col-lg-12"},Z={class:"header-wrap d-flex justify-content-between"},$=s("h2",{class:"dashboards-title margin-bottom-40"},"Edit Salary Slip",-1),ss={class:"btn-wrp"},es={class:"row"},ts={class:"col-lg-4"},as={class:"single-info-input margin-top-30"},os=s("label",{class:"info-title"},"Salary Month",-1),ls=s("span",{class:"info-text"},"Select Month First",-1),ns={class:"repeaterFieldWarp"},is=s("h4",{class:"repeater-title"},"Extra Earning Fields",-1),rs={class:"repeater-inner-wrap"},ds={class:"actionButtonWrap"},cs=s("i",{class:"fas fa-plus"},null,-1),ps=[cs],_s=["onClick"],us=s("i",{class:"fas fa-times"},null,-1),ms=[us],hs={class:"repeaterFieldWarp"},ys=s("h4",{class:"repeater-title"},"Extra Deduction Fields",-1),vs={class:"repeater-inner-wrap"},gs={class:"actionButtonWrap"},fs=s("i",{class:"fas fa-plus"},null,-1),Ds=[fs],Ss=["onClick"],xs=s("i",{class:"fas fa-times"},null,-1),Fs=[xs],Es={class:"col-lg-8"},Cs={class:"head-raw margin-top-40 margin-left-60"},bs=s("h4",null,"Color Explanation",-1),ks={class:"color-explanation"},Vs={class:"bg-success"},Ns={class:"badge"},ws={class:"holiday"},Ls={class:"badge"},Ms={class:"leave"},Ps={class:"badge"},Is={class:"sick-leave"},As={class:"badge"},Bs={class:"paid-leave"},Ws={class:"badge"},Us={id:"element-to-convert"},Os={class:"salarySlipPdfOuterWrapper"},Ts={class:"headerPart"},Hs={class:"subjectWarp"},Js={class:"period"},Rs={class:"employeeInfoWrap"},Gs=s("strong",null,"Name: ",-1),js=s("strong",null,"Department:",-1),Ks={class:"tableWithCalculation table-responsive"},Xs={class:"salary-sheet"},Ys={class:"salary-sheet-flex"},qs={class:"salary-sheet-item"},zs={class:"salary-sheet-contents"},Qs={class:"salary-sheet-contents-list"},Zs=s("li",{class:"salary-sheet-list salary-sheet-head"},[s("span",{class:"list-title left"},"EARNINGS"),n(),s("span",{class:"list-title list-para right"}," PER MONTH ")],-1),$s={class:"salary-sheet-list"},se=s("span",{class:"list-para left"},"Basic Salary",-1),ee={class:"list-para right"},te={key:0,class:"list-para left"},ae={key:1,class:"list-para right"},oe={class:"salary-sheet-item"},le={class:"salary-sheet-contents"},ne={class:"salary-sheet-contents-list"},ie=s("li",{class:"salary-sheet-list salary-sheet-head"},[s("span",{class:"list-title left"},"DEDUCTIONS"),n(),s("span",{class:"list-title list-para right"}," PER MONTH ")],-1),re={key:0,class:"list-para left"},de={key:1,class:"list-para right"},ce={class:"salary-sheet-gross"},pe={class:"salary-sheet-gross-item"},_e={class:"gross-list"},ue=s("span",{class:"gross-para left"},"Gross Earnings(A)",-1),me={class:"gross-para right"},he={class:"salary-sheet-gross-item"},ye={class:"gross-list"},ve=s("span",{class:"gross-para left"},"Gross Deductions(B)",-1),ge={class:"gross-para right"},fe={class:"salary-sheet-gross"},De={class:"salary-sheet-gross-item"},Se={class:"gross-list"},xe=s("span",{class:"gross-para left"},"Net Salary Payable(A-B)",-1),Fe={class:"gross-para right"},Ee={class:"salary-sheet-gross"},Ce={class:"salary-sheet-gross-item"},be={class:"gross-list"},ke=s("span",{class:"gross-para left"},"Net Salary Payable(in words)",-1),Ve={class:"gross-para right"},Ne={class:"footerPartWrapper"},we=s("div",{class:"signature"},[s("span",{class:"signature"},"Signature"),s("h2",null,"Sharifur Rahman"),s("span",{class:"designation"},"Ceo - Xgenious")],-1),Le={class:"signature"},Me=s("span",{class:"signature"},"Signature",-1),Pe={class:"btn-wrapper margin-left-60"};function Ie(x,a,v,e,k,V){const F=f("Head"),E=f("Link"),g=f("VueDatePicker"),D=f("Select"),h=f("Input"),l=f("Button");return c(),d(S,null,[m(F,{title:"Edit Salary Slip"}),s("div",z,[s("div",Q,[s("div",Z,[$,s("div",ss,[m(E,{class:"btn btn-info m-1",href:x.route("admin.employee.salary.slip")},{default:T(()=>[n("All Salary Slip")]),_:1},8,["href"])])])]),s("div",es,[s("div",ts,[s("form",{onSubmit:a[7]||(a[7]=H(()=>{},["prevent"]))},[s("div",as,[os,m(g,{modelValue:e.salarySlipData.month,"onUpdate:modelValue":a[0]||(a[0]=t=>e.salarySlipData.month=t)},null,8,["modelValue"]),ls]),m(D,{title:"Employee",onChange:a[1]||(a[1]=t=>e.changeEmployeeSelect()),modelValue:e.salarySlipData.employee_id,"onUpdate:modelValue":a[2]||(a[2]=t=>e.salarySlipData.employee_id=t),options:e.employeeList},null,8,["modelValue","options"]),s("div",ns,[is,s("div",rs,[(c(!0),d(S,null,C(e.salarySlipData.extraEarningFields,t=>(c(),d("div",{class:"individualItemWrapper",key:t.index},[m(h,{type:"text",title:"Description",modelValue:t.description,"onUpdate:modelValue":i=>t.description=i},null,8,["modelValue","onUpdate:modelValue"]),m(h,{type:"number",min:"0",onInput:a[3]||(a[3]=i=>e.calculateSalary()),title:"Amount",modelValue:t.amount,"onUpdate:modelValue":i=>t.amount=i},null,8,["modelValue","onUpdate:modelValue"]),s("div",ds,[s("span",{class:"addMore",onClick:a[4]||(a[4]=i=>e.addMoreEarningField())},ps),s("span",{class:"removeItem",onClick:i=>e.removeEarningField(t)},ms,8,_s)])]))),128))])]),s("div",hs,[ys,s("div",vs,[(c(!0),d(S,null,C(e.salarySlipData.extraDeductionFields,t=>(c(),d("div",{class:"individualItemWrapper",key:t.index},[m(h,{type:"text",title:"Description",modelValue:t.description,"onUpdate:modelValue":i=>t.description=i},null,8,["modelValue","onUpdate:modelValue"]),m(h,{type:"number",min:"0",onInput:a[5]||(a[5]=i=>e.calculateSalary()),title:"Amount",modelValue:t.amount,"onUpdate:modelValue":i=>t.amount=i},null,8,["modelValue","onUpdate:modelValue"]),s("div",gs,[s("span",{class:"addMore",onClick:a[6]||(a[6]=i=>e.addMoreDeductionField())},Ds),s("span",{class:"removeItem",onClick:i=>e.removeDeductionField(t)},Fs,8,Ss)])]))),128))])])],32)]),s("div",Es,[s("div",Cs,[bs,s("ul",ks,[s("li",Vs,[n("Attendance "),s("span",Ns,o(e.attenadnceCount),1)]),s("li",ws,[n("Holiday "),s("span",Ls,o(e.holidayCount),1)]),s("li",Ms,[n("leave "),s("span",Ps,o(e.leaveCount),1)]),s("li",Is,[n("Sick Leave "),s("span",As,o(e.sickLeaveCount),1)]),s("li",Bs,[n("Paid Leave "),s("span",Ws,o(e.paidLeaveCount),1)])])]),s("div",Us,[s("div",Os,[s("div",Ts,[s("div",Hs,[s("p",null,[n("Payslip for the month of "),s("span",Js,o(e.getSelectedMonthName(e.salarySlipData.month)),1),n(" "+o(new Date().getFullYear()),1)])])]),s("div",Rs,[s("h3",null,[Gs,n(" "+o(e.selectedEmployee.name),1)]),s("h4",null,[js,n(" "+o(e.selectedEmployee.category),1)])]),s("div",Ks,[s("div",Xs,[s("div",Ys,[s("div",qs,[s("div",zs,[s("ul",Qs,[Zs,s("li",$s,[se,n(),s("span",ee,o(parseFloat(e.selectedEmployee.salary).toFixed(2)),1)]),(c(!0),d(S,null,C(e.salarySlipData.extraEarningFields,t=>(c(),d("li",{class:"salary-sheet-list",key:t.index},[t.description!==null?(c(),d("span",te,o(t.description),1)):b("",!0),t.description!==null?(c(),d("span",ae,o(t.amount>0?parseFloat(t.amount).toFixed(2):0),1)):b("",!0)]))),128))])])]),s("div",oe,[s("div",le,[s("ul",ne,[ie,(c(!0),d(S,null,C(e.salarySlipData.extraDeductionFields,t=>(c(),d("li",{class:"salary-sheet-list",key:t.index},[t.description!==null?(c(),d("span",re,o(t.description),1)):b("",!0),t.description!==null?(c(),d("span",de,o(t.amount>0?parseFloat(t.amount).toFixed(2):0),1)):b("",!0)]))),128))])])])]),s("div",ce,[s("div",pe,[s("div",_e,[ue,n(),s("span",me,o(parseFloat(e.grossEarning).toFixed(2)),1)])]),s("div",he,[s("div",ye,[ve,n(),s("span",ge,o(parseFloat(e.grossDeduction).toFixed(2)),1)])])]),s("div",fe,[s("div",De,[s("div",Se,[xe,n(),s("span",Fe,o(parseFloat(e.payableAmount).toFixed(2)),1)])])]),s("div",Ee,[s("div",Ce,[s("div",be,[ke,n(),s("span",Ve,o(x.numberToWord(e.payableAmount))+" Tk Only",1)])])])])]),s("div",Ne,[we,s("div",Le,[Me,s("h2",null,o(e.selectedEmployee.name),1)])])])]),s("div",Pe,[m(l,{type:"button",onClick:e.exportToPDF,"button-text":"Save and Download PDF"},null,8,["onClick"])])])])])],64)}const je=Y(q,[["render",Ie]]);export{je as default};