import{B as c}from"./Button-bGDvKVp1.js";import{B as u}from"./Guest-Zc9QhWCb.js";import{I as f}from"./Input-2dBvznUW.js";import{B}from"./Label-ySae9vL5.js";import{V as _}from"./ValidationErrors-K-JALEkY.js";import{H as w,c as b,a as e,b as t,g as z,n as V,w as h,F as x,r as o,o as y,e as g}from"./app-gltJhGw6.js";import{_ as v}from"./_plugin-vue_export-helper-x3n3nnut.js";import"./ApplicationLogo-64HHftb7.js";const C={layout:u,components:{BreezeButton:c,BreezeInput:f,BreezeLabel:B,BreezeValidationErrors:_,Head:w},data(){return{form:this.$inertia.form({password:""})}},methods:{submit(){this.form.post(this.route("password.confirm"),{onFinish:()=>this.form.reset()})}}},E=t("div",{class:"mb-4 text-sm text-gray-600"}," This is a secure area of the application. Please confirm your password before continuing. ",-1),I={class:"flex justify-end mt-4"};function H(L,s,P,k,r,n){const i=o("Head"),m=o("BreezeValidationErrors"),l=o("BreezeLabel"),p=o("BreezeInput"),d=o("BreezeButton");return y(),b(x,null,[e(i,{title:"Confirm Password"}),E,e(m,{class:"mb-4"}),t("form",{onSubmit:s[1]||(s[1]=h((...a)=>n.submit&&n.submit(...a),["prevent"]))},[t("div",null,[e(l,{for:"password",value:"Password"}),e(p,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:r.form.password,"onUpdate:modelValue":s[0]||(s[0]=a=>r.form.password=a),required:"",autocomplete:"current-password",autofocus:""},null,8,["modelValue"])]),t("div",I,[e(d,{class:V(["ml-4",{"opacity-25":r.form.processing}]),disabled:r.form.processing},{default:z(()=>[g(" Confirm ")]),_:1},8,["class","disabled"])])],32)],64)}const U=v(C,[["render",H]]);export{U as default};