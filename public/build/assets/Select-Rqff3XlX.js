import{o as s,c as l,b as t,t as o,n as r,F as u,p as c,d,s as m}from"./app-3xCpkM7L.js";import{_}from"./_plugin-vue_export-helper-x3n3nnut.js";const f={name:"Select",props:{options:[Array,Object],modelValue:{type:[String,Number,Object],default:null},title:String,inputClass:{type:String,default:"unknown"},info:String},emits:["update:modelValue"]},p={class:"single-dashboard-input"},g={class:"single-info-input margin-top-30"},h={class:"info-title"},S=["modelValue"],v=t("option",null,"------",-1),b=["value"];function V(i,a,e,y,C,k){return s(),l("div",p,[t("div",g,[t("label",h,o(e.title),1),t("select",{class:r(["form-control",e.inputClass]),modelValue:e.modelValue,onChange:a[0]||(a[0]=n=>i.$emit("update:modelValue",n.target.value))},[v,(s(!0),l(u,null,c(e.options,n=>(s(),l("option",{value:n.value},o(n.label),9,b))),256))],42,S),d(t("span",null,o(e.info),513),[[m,e.info!=""]])])])}const j=_(f,[["render",V]]);export{j as S};