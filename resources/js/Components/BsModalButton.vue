<template>
    <button type="button" data-bs-toggle="modal" :data-bs-target="'#'+target" class="btn " :class="buttonClass"><slot/></button>
</template>

<script>
export default {
    name: "BsModalButton",
    props:{
        target: String,
        buttonText: {
            type: String,
            default: 'Modal Button'
        },
        buttonClass: {
            type: String,
            default: 'btn-info'
        }
    }
}
</script>
