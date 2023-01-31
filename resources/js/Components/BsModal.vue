<template>
    <div class="modal fade" :id="modalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" :class="modalSize">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{modalTitle}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "BsModal",
    props: {
        modalTitle:{
            type: [String,Object],
        },
        buttonText:{
            type: String,
            default: 'Save Changes'
        },
        modalId:String,
        modalSize:String
    },

}
</script>

<style scoped>

</style>
