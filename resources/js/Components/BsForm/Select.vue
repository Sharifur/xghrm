<template>
    <div class="single-dashboard-input">
        <div class="single-info-input margin-top-30">
            <label class="info-title">{{ title }} </label>
            <select class="form-control" :class="inputClass" v-bind:modelValue="modelValue" @change="$emit('update:modelValue', $event.target.value)">
                <option v-for="option in options" :value="option.value" >{{option.label}}</option>
            </select>
            <span v-show="info != ''">{{ info }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "Select",
    props: {
        options: [Array,Object],
        modelValue: {
            type: [String,Number,Object],
            default: null
        },
        title: String,
        inputClass: {
            type: String,
            default: null
        },
        info: String
    },
    emits: ['update:modelValue'],
}
</script>
