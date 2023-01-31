<template>
    <div class="alert alert-danger" v-if="hasErrors">
        <div class="text-white font-weight-bold">Whoops! Something went wrong.</div>
        <ul class="text-white">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    computed: {
        errors() {
            return this.$page.props.errors
        },
        hasErrors() {
            return Object.keys(this.errors).length > 0
        },
    }
}
</script>
