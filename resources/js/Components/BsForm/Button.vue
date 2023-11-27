<template>
        <button :type="buttonType" class="btn" :disabled="disabled" :class="classes">{{buttonText}}</button>
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
            default: 'btn-primary  margin-top-40'
        },
        disabled: Boolean,
        buttonType:{
            type: String,
            default: 'button'
        }
    }
}
</script>
