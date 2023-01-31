<template>
    <div class="single-dashboard-input">
        <div class="single-info-input margin-top-30">
            <label class="info-title">{{ title }} </label>
            <input :type="type" v-bind="$attrs" :placeholder="placeholder" class="form-control" :class="inputClass" :value="modelValue"  @input="$emit('update:modelValue', $event.target.value)">
        </div>
    </div>
</template>

<script>
export default {
    name: "Input",
    props: {
        modelValue: [String,Number,Array],
        title: String,
        type: {
            type:String,
            default: 'text'
        },
        placeholder: {
            type: String,
            default: null
        },
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
