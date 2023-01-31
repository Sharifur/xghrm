<template>
    <Head title="Welcome" />

</template>


<script>
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
      Head,
      Link,
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
    },
}
</script>
