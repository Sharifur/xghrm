import{o as a,c as l,b as s,t as n,n as r,F as u,p as c,d,s as m}from"./app-i-P7TbwR.js";import{_}from"./_plugin-vue_export-helper-x3n3nnut.js";const f={name:"Select",props:{options:[Array,Object],modelValue:{type:[String,Number,Object],default:""},title:String,inputClass:{type:String,default:null},info:String},emits:["update:modelValue"]},p={class:"single-dashboard-input"},g={class:"single-info-input margin-top-30"},h={class:"info-title"},v=["modelValue"],S=["value"];function b(i,o,e,V,B,y){return a(),l("div",p,[s("div",g,[s("label",h,n(e.title),1),s("select",{class:r(["form-control",e.inputClass]),modelValue:e.modelValue,onChange:o[0]||(o[0]=t=>i.$emit("update:modelValue",t.target.value))},[(a(!0),l(u,null,c(e.options,t=>(a(),l("option",{value:t.value},n(t.label),9,S))),256))],42,v),d(s("span",null,n(e.info),513),[[m,e.info!=""]])])])}const k=_(f,[["render",b]]);export{k as B};