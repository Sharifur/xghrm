<template>
    <Head title="All Products"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Products</h2>
                    <div class="btn-wrp">
                        <Link :href="route('admin.products.new')" class="btn btn-secondary">Add New</Link>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Envato ID</th>
                        <th>Release Date</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="product in productsList()">
                            <td>{{product.id}}</td>
                            <td>
                                <h4>{{ product.title }}</h4>
                              <h6><strong>By</strong> {{ product.dev?.name }}</h6>
                            </td>
                            <td>{{ getAuthorName(product.author) }}</td>
                            <td>{{ getCategoryName(product.category) }}</td>
                            <td>{{  product.enItemId }}</td>
                            <td>{{ getReadableDateFormat( product.releaseDate )}}</td>
                            <td>
                                <Link as="button" class="btn btn-danger m-1" @click="deleteItem(product)">
                                    <i class="fas fa-trash"></i>
                                </Link>
                                <Link as="button" class="btn btn-info m-1" :href="route('admin.products.edit',product.id)">
                                    <i class="fas fa-pencil-alt"></i>
                                </Link>
                                <Link as="button" class="btn btn-secondary m-1" :href="route('admin.products.view',product.id)">
                                    <i class="fas fa-eye"></i>
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="productListsLinks()"/>
            </div>
        </div>
    </div>
</template>

<script>

import {Link,usePage,useForm,Head} from "@inertiajs/inertia-vue3";
import AdminMaster from "@/Layouts/AdminMaster";
import Pagination from "@/Components/Pagination";
import Swal from "sweetalert2";

export default {
    name: "Product",
    layout: AdminMaster,
    components: {
        Pagination,
        Link,
        Head
    },
    setup(){
        function productsList(){
            return usePage().props.value.products.data;
        }
        function productListsLinks(){
            return usePage().props.value.products.links;
        }
        function deleteItem(item){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                    const ItemId = useForm({id:item.id});
                if (result.isConfirmed) {
                    ItemId.post(route('admin.products.delete'),{
                        onSuccess: () => {
                            Swal.fire('Deleted','Product Item','success');
                        }
                    });
                }
            })


        }

        function getReadableDateFormat(datetime) {
            const dateMonth = new Date(datetime).toLocaleDateString('en-US', {month: 'long'});
            const dateYear = new Date(datetime).getFullYear();
            const date = new Date(datetime).getDate();
            return date +' ' +dateMonth + ' ' + dateYear;
        }
        function getAuthorName(author){
            return author === 0 ? 'Xgenious' : 'Bytesed';
        }
        function getCategoryName(author){
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
        return {
            productsList,
            deleteItem,
            productListsLinks,
            getReadableDateFormat,
            getAuthorName,
            getCategoryName
        }
    }
}
</script>
