import{B as u}from"./Button-7Nc2j9JD.js";import{B as _,a as p}from"./Label-FAGyZEea.js";import{B as v}from"./ValidationErrors-QvQ5Gqlh.js";import{H as f,c as r,a as l,b as s,t as h,h as w,w as y,d as B,i as b,n as g,F as q,f as x,r as n,o as d}from"./app-LGDTzxTG.js";import{_ as V}from"./_plugin-vue_export-helper-x3n3nnut.js";const z={components:{BreezeButton:u,BreezeInput:_,BreezeLabel:p,BreezeValidationErrors:v,Head:f},props:{status:String},data(){return{form:this.$inertia.form({email:""})}},methods:{submit(){this.form.post(this.route("password.email"))}}},k={class:"box"},E=x('<div class="square" style="--i:0;"></div><div class="square" style="--i:1;"></div><div class="square" style="--i:2;"></div><div class="square" style="--i:3;"></div><div class="square" style="--i:4;"></div><div class="square" style="--i:5;"></div>',6),F={class:"container"},N={class:"form"},H=s("h2",null,"Forgot your password?",-1),S=s("p",null,"No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.",-1),C={key:0,class:"alert alert-success"},P={class:"inputBx"},D=s("span",null,"Email",-1),L=s("img",{src:"/svg/lnr-user.svg",alt:""},null,-1),M={class:"inputBx btn-box"},I=["disabled"];function J(R,e,i,T,t,a){const c=n("Head"),m=n("BreezeValidationErrors");return d(),r(q,null,[l(c,{title:"Forgot Password"}),s("section",null,[s("div",k,[E,s("div",F,[s("div",N,[H,S,l(m,{class:"mb-4"}),i.status?(d(),r("div",C,h(i.status),1)):w("",!0),s("form",{onSubmit:e[1]||(e[1]=y((...o)=>a.submit&&a.submit(...o),["prevent"]))},[s("div",P,[B(s("input",{type:"email",required:"required","onUpdate:modelValue":e[0]||(e[0]=o=>t.form.email=o)},null,512),[[b,t.form.email]]),D,L]),s("div",M,[s("input",{type:"submit",value:"Email Password Reset Link",class:g({"opacity-25":t.form.processing}),disabled:t.form.processing},null,10,I)])],32)])])])])],64)}const O=V(z,[["render",J]]);export{O as default};