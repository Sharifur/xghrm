<template>
    <Head title="Add New Product"/>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title"> Add New Product</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.products.all')">All Products</Link>
                    </div>
                </div>

                <div class="edit-profile">
                    <div class="profile-info-dashboard">
                        <ValidationErrors/>
                        <div class="dashboard-profile-flex">
                            <div class="dashboard-address-details">
                                <form @submit.prevent>
                                    <BsInput type="text" title="Title*" v-model="productData.title"/>
                                    <BsSelect title="Author*" :options="authors" v-model="productData.author"/>
                                    <BsInput type="number" title="Envato Item ID*" v-model="productData.enItemId"/>
                                    <BsSelect title="Category*" :options="categories" v-model="productData.category"/>
                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label class="info-title"> Release Date* </label>
                                            <Datepicker v-model="productData.releaseDate"/>
                                        </div>
                                    </div>
                                    <BsSelect title="Developer*" :options="developers()" v-model="productData.developer"/>
                                    <MediaUploader v-model="productData.thumbnail"/>
                                    <div class="btn-wrapper margin-top-35">
                                        <BsButton button-text="Add New" @click="addNewProduct" classes="cmn-btn btn-bg-1"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Link, useForm, usePage,Head} from "@inertiajs/inertia-vue3";
import AdminMaster from "@/Layouts/AdminMaster";
import BsTextarea from "@/Components/BsForm/Textarea";
import BsButton from "@/Components/BsForm/Button";
import BsInput from "@/Components/BsForm/Input";
import Datepicker from "vue3-datepicker";
import BsSelect from "@/Components/BsForm/Select";
import ValidationErrors from "@/Components/ValidationErrors";
import Swal from "sweetalert2";
import MediaUploader from "@/Components/MediaUploader";

export default {
    name: "New",
    layout: AdminMaster,
    components:{
        BsTextarea,
        BsButton,
        BsInput,
        Datepicker,
        BsSelect,
        Link,
        ValidationErrors,
        MediaUploader,
        Head
    },
    setup(){
        const productData = useForm({
           title : null,
           author : null,
           enItemId : null,
           category : null,
           releaseDate : new Date(),
           thumbnail :null,
           developer :null
        });
        const authors = [
            {label:'Xgenious',value:0},
            {label:'Bytesed',value:1}
        ];
        const categories = [
            {label:'HTML',value:0},
            {label:'PSD',value:1},
            {label:'WordPress',value:2},
            {label:'React',value:3},
            {label:'Laravel',value:4}
        ];

        function developers(){
            return usePage().props.value.developers;
        }

        function addNewProduct(){
            productData.post(route('admin.products.new'),{
                onSuccess: () => {
                    Swal.fire('Added!','New Product','success');
                    productData.reset('title','author','enItemId','category','thumbnail','developer');
                    productData.thumbnail = null;
                }
            })
        }
       const productThumbnail = '';
        return {
            authors,
            productData,
            categories,
            productThumbnail,
            developers,
            addNewProduct
        }
    }
}
</script>

