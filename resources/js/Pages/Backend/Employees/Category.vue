<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Categories</h2>
                    <div class="btn-wrp">
                        <BsModalButton target="addnewcategory" @click="addNew">Add New</BsModalButton>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Icon</th>
                        <th>Status</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="category in employeeCategoryLists()" v-bind:key="category.id">
                            <td>{{category.id}}</td>
                            <td>{{ category.title }}</td>
                            <td><i class="fa-3x" :class="category.icon"></i></td>
                            <td><StatusShow :status=" category.status"/></td>
                            <td>
                                <Link as="button" class="btn btn-danger m-1" @click="deleteItem(category)">
                                    <i class="fas fa-trash"></i>
                                </Link>
                                <BsModalButton target="addnewcategory" button-class="btn btn-info m-1" @click="categoryEdit(category)"><i class="fas fa-pencil-alt"></i></BsModalButton>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="employeeCategoryListsLinks()"/>
            </div>
        </div>
    </div>
<BsModal :modalTitle="modalTitle" modal-id="addnewcategory" modal-size="modal-md">
    <BreezeValidationErrors class="mb-4" />
    <FlashMsg class="mb-4"/>
    <form @submit.prevent>
        <BsInput title="Title" type="text" placeholder="enter title" v-model="addCategoryForm.title"/>
        <BsSelect title="Status" :options="options" v-model="addCategoryForm.status"/>
        <icon-picker v-model="addCategoryForm.icon" />
        <BsButton button-text="Add New" v-if="isNew" button-type="submit" @click="addCategoryFormSubmit" :disabled="addCategoryForm.processing"/>
        <BsButton button-text="Save Changes" v-if="isEdit" button-type="submit" @click="editCategoryFormSubmit" :disabled="addCategoryForm.processing"/>
    </form>
</BsModal>
</template>

<script>
import { ref } from 'vue';
import AdminMaster from "@/Layouts/AdminMaster.vue";
import BsModal from "@/Components/BsModal.vue";
import BsModalButton from "@/Components/BsModalButton.vue";
import BsInput from "@/Components/BsForm/Input.vue";
import BsSelect from "@/Components/BsForm/Select.vue";
import IconPicker from "@/Components/IconPicker.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import {useForm,Link} from "@inertiajs/inertia-vue3";
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import FlashMsg from "@/Components/FlashMsg.vue";
import Pagination from "@/Components/Pagination.vue";
import StatusShow from "@/Components/StatusShow.vue";


let options = [
    {
        label: 'Publish',
        value: 1
    },
    {
        label: 'Draft',
        value: 0
    }
]

export default {
    name: "category",
    layout: AdminMaster,
    components:{
        BsModal,
        BsModalButton,
        BsInput,
        BsSelect,
        IconPicker,
        BsButton,
        BreezeValidationErrors,
        FlashMsg,
        Link,
        Pagination,
        StatusShow
    },
    data(){
        return {
            options,
            addCategoryForm: useForm({
                icon: ref('far fa-grin-hearts'),
                title: '',
                status: 1,
                id: null
            }),
            isEdit: false,
            isNew: true,
            modalTitle: {
                type: String,
                default : 'Add New Category'
            }
        }
    },
    methods:{
         addCategoryFormSubmit(){
            this.addCategoryForm.post(route('admin.employee.category.new'),{
               onFinish: () => {
                   this.addCategoryForm.title = '';
               }
            });
        },
        editCategoryFormSubmit(){
            this.addCategoryForm.post(route('admin.employee.category.update'),{
               onFinish: () => {

               }
            });
        },
        employeeCategoryLists(){
             return this.$page.props.categories.data;
        },
        employeeCategoryListsLinks(){
            return this.$page.props.categories.links;
        },
        categoryEdit(category){
            this.addCategoryForm = useForm({
                icon: category.icon,
                title: category.title,
                status: category.status,
                id: category.id
            });
            this.isEdit = true;
            this.isNew = false;
            this.modalTitle = 'Edit Category';
            this.$page.props.flashMsg.type = undefined;
            this.$page.props.flashMsg.msg = undefined;
            this.$page.props.errors = {};
        },
        addNew(){
            this.isEdit = false;
            this.isNew = true;
            this.$page.props.flashMsg.type = undefined;
            this.$page.props.flashMsg.msg = undefined;
            this.modalTitle = 'Add New Category';
            this.addCategoryForm = useForm({
                icon: ref('far fa-grin-hearts'),
                title: '',
                status: 1,
                id: null
            });
            this.$page.props.errors = {};
        },
        deleteItem(item) {
            this.$swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                const deleteCategory = useForm({id:item.id})
                if (result.isConfirmed) {
                    deleteCategory.post(route('admin.employee.category.delete'),{
                        onFinish : () => {
                            this.$swal.fire(
                                'Deleted!',
                                'Category deleted.',
                                'success'
                            )
                        }
                    })

                }
            })
        },
    }
}
</script>

