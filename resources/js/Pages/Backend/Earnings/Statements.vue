<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Monthly Statements</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Account</th>
                        <th>Month</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="earning in earningLists()">
                            <td>{{ earning.id }}</td>
                            <td>{{ earning.title }}</td>
                            <td>{{ earning.en_username }}</td>
                            <td>{{ getEarningMonth(earning.month) }}</td>
                            <td>
                                <Link as="button" class="btn btn-danger m-1" @click="deleteItem(earning)">
                                    <i class="fas fa-trash"></i>
                                </Link>
                                <BsModalButton
                                    target="viewEarning"
                                    button-class="btn btn-secondary m-1"
                                    @click="viewEarning(earning)">
                                    <i class="fas fa-calculator"></i>
                                </BsModalButton>
                                <BsModalButton
                                    target="editStatement"
                                    button-class="btn btn-info m-1"
                                    @click="editStatement(earning)">
                                    <i class="fas fa-pencil-alt"></i>
                                </BsModalButton>
                                <BsModalButton
                                    target="viewStatement"
                                    button-class="btn btn-primary m-1"
                                    @click="viewStatement(earning)">
                                    <i class="fas fa-eye"></i>
                                </BsModalButton>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="earningListsLinks()"/>
            </div>
        </div>
    </div>


    <BsModal modal-title="Statement Details" modal-id="viewStatement" modal-size="modal-md">
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Title</td>
                        <td>{{statementsData.title}}</td>
                    </tr>
                    <tr>
                        <td>Account</td>
                        <td>{{statementsData.en_username}}</td>
                    </tr>
                    <tr>
                        <td>Month</td>
                        <td>{{getEarningMonth(statementsData.month)}}</td>
                    </tr>
                    <tr>
                        <td>Percentage</td>
                        <td>{{statementsData.percentage}}%</td>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td>{{getFormName(statementsData.from)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BsModal>

    <BsModal modal-title="Earning Details" modal-id="viewEarning" modal-size="modal-md">
        <div class="table-responsive">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Office Earning (Approx)</td>
                        <td>{{viewEarningData.office_earning}}</td>
                    </tr>
                    <tr>
                        <td>Personal Earning (Approx)</td>
                        <td>{{viewEarningData.personal_earning}}</td>
                    </tr>
                    <tr>
                        <td>Total Earning (Approx)</td>
                        <td>{{viewEarningData.total_earning}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BsModal>


    <BsModal modal-title="Update Statement" modal-id="editStatement" modal-size="modal-md">
        <BreezeValidationErrors class="mb-4"/>
        <FlashMsg class="mb-4"/>
        <form @submit.prevent>
            <BsInput title="Title" v-model="statementsData.title"/>
            <BsSelect title="From" :options="options" v-model="statementsData.from"/>
            <BsSelect title="Envato Username" :options="enUser" v-model="statementsData.en_username"/>
            <div class="form-group">
                <label>Month</label>
                <datepicker v-model="statementsData.month"/>
            </div>
<!--            <div class="form-group" v-if="isPersonalOrClients()">-->
<!--                <label>Personal Earning</label>-->
<!--                <input type="number" step="0.05" class="form-control" v-model="statementsData.personal_earning"/>-->
<!--            </div>-->
<!--            <div class="form-group" v-if="isPersonalOrClients()">-->
<!--                <label>Office Earning</label>-->
<!--                <input type="number" step="0.05" class="form-control" v-model="statementsData.office_earning"/>-->
<!--            </div>-->
<!--            <BsInput title="Percentage" type="number" step="0.01" v-show="!isPersonalOrClients()"-->
<!--                     v-model="statementsData.percentage"/>-->
            <div class="single-info-input margin-top-30">
                <MediaUploader button-text="Upload File" v-model="statementsData.statement"/>
                <small>select only csv file</small>
            </div>
<!--            <div class="form-group">-->
<!--                <input type="file" class="form-control-file"-->
<!--                       @input="statementsData.statement = $event.target.files[0]"/>-->
<!--            </div>-->
            <BsButton button-text="Save Changes" button-type="submit" @click="updateStatement"/>
        </form>
    </BsModal>

</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import Pagination from "@/Components/Pagination.vue";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import BsModal from "@/Components/BsModal.vue";
import BsModalButton from "@/Components/BsModalButton.vue";
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import FlashMsg from "@/Components/FlashMsg.vue";
import BsInput from "@/Components/BsForm/Input.vue";
import BsSelect from "@/Components/BsForm/Select.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import Datepicker from 'vue3-datepicker'
import MediaUploader from "@/Components/MediaUploader.vue";
import Swal from "sweetalert2";

const options = [
    {
        label: 'Personal',
        value: 0
    },
    {
        label: 'Codecanyon',
        value: 1
    },
    {
        label: 'Themeforest',
        value: 2
    },
    {
        label: 'Clients',
        value: 3
    },
]
const enUser = [{label: 'Xgenious', value: 'xgenious'}, {label: 'bytesed', value: 'bytesed'}];


export default {
    name: "Statements",
    layout: AdminMaster,
    components: {
        Pagination,
        Link,
        BsModalButton,
        BsModal,
        BreezeValidationErrors,
        FlashMsg,
        BsSelect,
        BsButton,
        BsInput,
        Datepicker,
        MediaUploader
    },
    data() {
        return {
            statementsData: useForm({
                title: '',
                from: 0,
                month: new Date(),
                personal_earning: '',
                office_earning: '',
                statement: '',
                en_username: 'xgenious',
                percentage: null
            }),
            viewEarningData: {
                office_earning : 0,
                personal_earning : 0,
                total_earning : 0,
            },
            options,
            enUser,
            caldata : useForm({
                id: null
            })
        }
    },
    methods: {
        earningLists() {
            return this.$page.props.statements.data;
        },
        earningListsLinks() {
            return this.$page.props.statements.links;
        },
        getEarningMonth(month) {
            const dateMonth = new Date(month).toLocaleDateString('en-US', {month: 'long'});
            const dateYear = new Date(month).getFullYear();
            return dateMonth + ' ' + dateYear;
        },
        viewEarning(item){
            axios.get(route('admin.earning.calculate',item.id))
                .then((response) => {
                    if (Object.keys(response.data).length > 1){
                        this.viewEarningData.office_earning = response.data.office_earning.toFixed(2);
                        this.viewEarningData.personal_earning = response.data.personal_earning.toFixed(2);
                        this.viewEarningData.total_earning = response.data.total_earning.toFixed(2);
                    }else {
                        Swal.fire('Error!','no data found','warning');
                    }
                })
                .catch((error) => {
                    Swal.fire('Error!',error.message,'warning');
                })
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
                const deleteStatement = useForm({id: item.id})
                if (result.isConfirmed) {
                    deleteStatement.post(route('admin.earning.delete'), {
                        onFinish: () => {
                            this.$swal.fire(
                                'Deleted!',
                                'Statement deleted.',
                                'success'
                            )
                        }
                    })

                }
            })
        },
        editStatement(item) {
            this.statementsData = useForm({
                title: item.title,
                from: item.from,
                month: new Date(item.month),
                personal_earning: item.personal_earning,
                office_earning: item.office_earning,
                statement: item.statement,
                en_username: item.en_username,
                percentage: item.percentage,
                id: item.id
            });
            this.$page.props.flashMsg.type = undefined;
            this.$page.props.flashMsg.msg = undefined;
            this.$page.props.errors = {};
        },
        viewStatement(item) {
            this.statementsData = useForm({
                title: item.title,
                from: item.from,
                month: new Date(item.month),
                personal_earning: item.personal_earning,
                office_earning: item.office_earning,
                statement: item.statement,
                en_username: item.en_username,
                percentage: item.percentage,
                id: item.id
            });
            this.$page.props.flashMsg.type = undefined;
            this.$page.props.flashMsg.msg = undefined;
            this.$page.props.errors = {};
        },
        isPersonalOrClients() {
            const formList = [0, 3];
            return formList.includes(parseInt(this.statementsData.from));
        },
        updateStatement() {
            this.statementsData.post(route('admin.earning.update'));
        },
        getFormName( form){
            let value = 'Clients';
            if (form === 0){
                value = 'Personal';
            }else if(form === 1){
                value = 'Codecanyon';
            }else if(form === 2){
                value = 'Themeforest';
            }
            return value;
        }
    }
}
</script>
