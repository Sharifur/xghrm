<template>
    <div v-if="hasFlashMsg" class="alert" :class="msgType">{{this.$page.props.flashMsg.msg}}</div>
</template>

<script>
export default {
    name: "FlashMsg",
    computed: {
        msgType(){
            const type = this.$page.props.flashMsg.type;
            return type !== undefined ? 'alert-'+type : '';
        },
        hasFlashMsg(){
            return this.$page.props.flashMsg.msg !== undefined || this.$page.props.flashMsg.type !== undefined;
        }
    }
}
</script>
