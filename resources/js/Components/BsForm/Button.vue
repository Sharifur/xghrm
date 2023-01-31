<template>
        <button :type="buttonType" class="btn margin-top-40" :disabled="disabled" :class="classes">{{buttonText}}</button>
</template>

<script>
export default {
    name: "Button",
    props:{
        buttonText: {
            type: String,
            default: 'Submit'
        },
        classes: {
            type: String,
            default: 'btn-primary'
        },
        disabled: Boolean,
        buttonType:{
            type: String,
            default: 'button'
        }
    }
}
</script>
