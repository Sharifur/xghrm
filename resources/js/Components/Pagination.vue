<template>
    <div v-if="links.length > 3" class="pagination-wrapper">
        <div class="nav-wrapper">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null"
                     class="nav-item"
                     v-html="link.label" />
                <Link v-else
                      class="nav-item"
                      :class="{ 'active': link.active }"
                      :href="link.url"
                      v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";
export default {
    name: "Pagination",
    props:{
        links: Array
    },
    components:{
        Link
    }
}
</script>
