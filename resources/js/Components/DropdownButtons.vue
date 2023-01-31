<template>
    <div class="dropdown" :class="dropdownStatus ? 'show' : ''">
        <button class="btn btn-secondary dropdown-toggle" @click="toggleClass" type="button" :aria-expanded="dropdownStatus">
            {{ title }}
        </button>
        <div class="dropdown-menu" :class="dropdownStatus ? 'show' : ''" >
           <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: "DropdownButtons",
    props:{
        title: String
    },
    data(){
        return {
            dropdownStatus : false
        }
    },
    methods :{
        toggleClass (){
            this.dropdownStatus = !this.dropdownStatus;
        }
    }
}
</script>
