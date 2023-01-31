<template>
    <div class="alert d-inline-block text-capitalize" :class="'alert-'+statusType">{{getStatus() }}</div>
</template>

<script>

export default {
    name: "StatusShow",
    data(){
      return {
          statusType: String
      }
    },
    props:{
        status : [Number,String]
    },
    methods:{
         getStatus (){
            if (this.isNumber(this.status)){
                this.statusType = this.status === 1 ? 'success' : 'info';
                return this.status === 1 ? 'publish' : 'draft';
            }
            return this.status;
        },
         isNumber(n) {
             return /^-?[\d.]+(?:e-?\d+)?$/.test(n);
         }
    }
}
</script>
