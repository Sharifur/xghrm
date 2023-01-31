<template>
    <div class="single-dashboard-input">
        <div class="single-info-input margin-top-30">
            <label class="info-title">{{ title }}</label>
        <textarea v-bind="$attrs"  class="form--control textarea--form" :class="inputClass" cols="30" rows="10" @input="$emit('update:modelValue', $event.target.value)" :value="modelValue"></textarea>
        </div>
    </div>
</template>

<script>
export default {
    name: "Textarea",
    props: {
        modelValue: [String,Number],
        title: String,
        inputClass: {
            type: String,
            default: null
        }
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>
