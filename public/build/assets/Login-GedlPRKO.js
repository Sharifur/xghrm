import{B as u}from"./Button-UHH-X_5H.js";import{B as p}from"./Checkbox-5Yylaj9T.js";import{B as _,a as h}from"./Label-W_HG5gj6.js";import{V as f}from"./ValidationErrors-ge_0yJkI.js";import{H as w,L as v,r as i,o as b,c as B,a as n,b as e,w as g,d as x,v as V,e as k,F as y,f as z}from"./app-i-P7TbwR.js";import{_ as q}from"./_plugin-vue_export-helper-x3n3nnut.js";const E={components:{BreezeButton:u,BreezeCheckbox:p,BreezeInput:_,BreezeLabel:h,BreezeValidationErrors:f,Head:w,Link:v},props:{canResetPassword:Boolean,status:String},data(){return{form:this.$inertia.form({username:"",password:"",remember:!1}),showPassword:!1}},methods:{submit(){this.form.post(this.route("admin.login"),{onError:a=>{console.log(a)},onFinish:()=>this.form.reset("password")})},show_hide_password(){this.showPassword=!this.showPassword}}},L={class:"box"},C=z('<div class="square" style="--i:0;"></div><div class="square" style="--i:1;"></div><div class="square" style="--i:2;"></div><div class="square" style="--i:3;"></div><div class="square" style="--i:4;"></div><div class="square" style="--i:5;"></div>',6),P={class:"container"},N={class:"form"},U=e("h2",null,"Welcome, Login To the XGENIOUS CRM",-1),H={class:"inputBx"},I=e("span",null,"Email or Username",-1),S=e("img",{src:"/svg/lnr-user.svg",alt:""},null,-1),F={class:"inputBx password"},M=["type"],R=e("span",null,"Password",-1),D=e("img",{src:"/svg/lnr-question-circle.svg",alt:""},null,-1),T={class:"remember"},A=e("div",{class:"inputBx btn-box"},[e("input",{type:"submit",value:"Log in"})],-1);function G(a,s,O,W,r,t){const l=i("Head"),d=i("BreezeValidationErrors"),m=i("BreezeInput"),c=i("BreezeCheckbox");return b(),B(y,null,[n(l,{title:"Admin Log in"}),e("section",null,[e("div",L,[C,e("div",P,[e("div",N,[U,n(d,{class:"mb-4"}),e("form",{onSubmit:s[4]||(s[4]=g((...o)=>t.submit&&t.submit(...o),["prevent"]))},[e("div",H,[n(m,{id:"username",type:"text",class:"mt-1 block w-full",modelValue:r.form.username,"onUpdate:modelValue":s[0]||(s[0]=o=>r.form.username=o),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),I,S]),e("div",F,[x(e("input",{id:"password-input","onUpdate:modelValue":s[1]||(s[1]=o=>r.form.password=o),type:r.showPassword?"text":"password",name:"password",required:"required"},null,8,M),[[V,r.form.password]]),R,e("a",{href:"#",class:"password-control",onClick:s[2]||(s[2]=(...o)=>t.show_hide_password&&t.show_hide_password(...o))}),D]),e("label",T,[n(c,{name:"remember",checked:r.form.remember,"onUpdate:checked":s[3]||(s[3]=o=>r.form.remember=o)},null,8,["checked"]),k(" Remember")]),A],32)])])])])],64)}const Z=q(E,[["render",G]]);export{Z as default};