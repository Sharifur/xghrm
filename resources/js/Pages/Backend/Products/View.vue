<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="top-part margin-bottom-40">
                        <h2 class="dashboards-title margin-bottom-10">{{ product.title }} Details  </h2>
                    </div>
                    <div class="btn-wrp">
                        <Link :href="route('admin.products.all')" class="btn btn-secondary mr-3">All Product</Link>
                        <Link :href="route('admin.products.edit',product.id)" class="btn btn-warning">Edit
                            Details
                        </Link>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-default table-stripped">
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{ product.id }}</td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>{{ product.title }}</td>
                        </tr>
                        <tr>
                            <td>Author</td>
                            <td>{{ getAuthorName(product.author) }}</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>{{ getCategoryName(product.category) }}</td>
                        </tr>
                        <tr>
                            <td>Envato Item ID</td>
                            <td>{{ product.enItemId }}</td>
                        </tr>
                        <tr>
                            <td>Release Date</td>
                            <td>{{ getReadableDateFormat(product.releaseDate) }}</td>
                        </tr>
                        <tr>
                            <td>Developer</td>
                            <td>{{ product.dev?.name }}</td>
                        </tr>
                        <tr>
                            <td>Thumbnail</td>
                            <td>//image will show here //todo: write a function to render only image data and show it </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link, usePage} from "@inertiajs/inertia-vue3";
import AdminMaster from "@/Layouts/AdminMaster";

export default {
    name: "View",
    layout: AdminMaster,
    components: {
        Link
    },
    data() {
        return {
            product: usePage().props.value.product
        }
    },
    methods: {
        getReadableDateFormat(datetime) {
            const dateMonth = new Date(datetime).toLocaleDateString('en-US', {month: 'long'});
            const dateYear = new Date(datetime).getFullYear();
            const date = new Date(datetime).getDate();
            return date + ' ' + dateMonth + ' ' + dateYear;
        },
        getAuthorName(author) {
            return author === 0 ? 'Xgenious' : 'Bytesed';
        },
        getCategoryName(author) {
            switch (author) {
                case(0):
                    return 'HTML';
                    break;
                case(1):
                    return 'PSD';
                    break;
                case(2):
                    return 'WordPress';
                    break;
                case(3):
                    return 'React';
                    break;
                default:
                    return 'Laravel';
                    break;
            }
        }
    }
}
</script>

