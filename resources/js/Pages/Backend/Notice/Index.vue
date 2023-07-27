<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Notice</h2>
                    create new button
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
<!--                        <tr v-for="earning in earningLists()">-->
<!--                            <td>{{ earning.id }}</td>-->
<!--                            <td>{{ earning.title }}</td>-->
<!--                            <td>{{ earning.en_username }}</td>-->
<!--                            <td>{{ getEarningMonth(earning.month) }}</td>-->
<!--                            <td>-->
<!--                                <Link as="button" class="btn btn-danger m-1" @click="deleteItem(earning)">-->
<!--                                    <i class="fas fa-trash"></i>-->
<!--                                </Link>-->
<!--                                <BsModalButton-->
<!--                                    target="viewEarning"-->
<!--                                    button-class="btn btn-secondary m-1"-->
<!--                                    @click="viewEarning(earning)">-->
<!--                                    <i class="fas fa-calculator"></i>-->
<!--                                </BsModalButton>-->
<!--                                <BsModalButton-->
<!--                                    target="editStatement"-->
<!--                                    button-class="btn btn-info m-1"-->
<!--                                    @click="editStatement(earning)">-->
<!--                                    <i class="fas fa-pencil-alt"></i>-->
<!--                                </BsModalButton>-->
<!--                                <BsModalButton-->
<!--                                    target="viewStatement"-->
<!--                                    button-class="btn btn-primary m-1"-->
<!--                                    @click="viewStatement(earning)">-->
<!--                                    <i class="fas fa-eye"></i>-->
<!--                                </BsModalButton>-->

<!--                            </td>-->
<!--                        </tr>-->
                        </tbody>
                    </table>
                </div>
<!--                <Pagination :links="earningListsLinks()"/>-->
            </div>
        </div>
    </div>


<!--    <BsModal modal-title="Statement Details" modal-id="viewStatement" modal-size="modal-md">-->
<!--        <div class="table-responsive">-->
<!--            <table class="table table-striped">-->
<!--                <tbody>-->
<!--                    <tr>-->
<!--                        <td>Title</td>-->
<!--                        <td>{{statementsData.title}}</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>Account</td>-->
<!--                        <td>{{statementsData.en_username}}</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>Month</td>-->
<!--                        <td>{{getEarningMonth(statementsData.month)}}</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>Percentage</td>-->
<!--                        <td>{{statementsData.percentage}}%</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>From</td>-->
<!--                        <td>{{getFormName(statementsData.from)}}</td>-->
<!--                    </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!--    </BsModal>-->

<!--    <BsModal modal-title="Earning Details" modal-id="viewEarning" modal-size="modal-md">-->
<!--        <div class="table-responsive">-->
<!--            <table class="table table-striped">-->
<!--                <tbody>-->
<!--                    <tr>-->
<!--                        <td>Office Earning (Approx)</td>-->
<!--                        <td>{{viewEarningData.office_earning}}</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>Personal Earning (Approx)</td>-->
<!--                        <td>{{viewEarningData.personal_earning}}</td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>Total Earning (Approx)</td>-->
<!--                        <td>{{viewEarningData.total_earning}}</td>-->
<!--                    </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
<!--    </BsModal>-->


<!--    <BsModal modal-title="Update Statement" modal-id="editStatement" modal-size="modal-md">-->
<!--        <BreezeValidationErrors class="mb-4"/>-->
<!--        <FlashMsg class="mb-4"/>-->
<!--        <form @submit.prevent>-->
<!--            <BsInput title="Title" v-model="statementsData.title"/>-->
<!--            <BsSelect title="From" :options="options" v-model="statementsData.from"/>-->
<!--            <BsSelect title="Envato Username" :options="enUser" v-model="statementsData.en_username"/>-->
<!--            <div class="form-group">-->
<!--                <label>Month</label>-->
<!--                <datepicker v-model="statementsData.month"/>-->
<!--            </div>-->
<!--&lt;!&ndash;            <div class="form-group" v-if="isPersonalOrClients()">&ndash;&gt;-->
<!--&lt;!&ndash;                <label>Personal Earning</label>&ndash;&gt;-->
<!--&lt;!&ndash;                <input type="number" step="0.05" class="form-control" v-model="statementsData.personal_earning"/>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="form-group" v-if="isPersonalOrClients()">&ndash;&gt;-->
<!--&lt;!&ndash;                <label>Office Earning</label>&ndash;&gt;-->
<!--&lt;!&ndash;                <input type="number" step="0.05" class="form-control" v-model="statementsData.office_earning"/>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;            <BsInput title="Percentage" type="number" step="0.01" v-show="!isPersonalOrClients()"&ndash;&gt;-->
<!--&lt;!&ndash;                     v-model="statementsData.percentage"/>&ndash;&gt;-->
<!--            <div class="single-info-input margin-top-30">-->
<!--                <MediaUploader button-text="Upload File" v-model="statementsData.statement"/>-->
<!--                <small>select only csv file</small>-->
<!--            </div>-->
<!--&lt;!&ndash;            <div class="form-group">&ndash;&gt;-->
<!--&lt;!&ndash;                <input type="file" class="form-control-file"&ndash;&gt;-->
<!--&lt;!&ndash;                       @input="statementsData.statement = $event.target.files[0]"/>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--            <BsButton button-text="Save Changes" button-type="submit" @click="updateStatement"/>-->
<!--        </form>-->
<!--    </BsModal>-->

</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster";
import Pagination from "@/Components/Pagination";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import BsModal from "@/Components/BsModal";
import BsModalButton from "@/Components/BsModalButton";
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import FlashMsg from "@/Components/FlashMsg";
import BsInput from "@/Components/BsForm/Input";
import BsSelect from "@/Components/BsForm/Select";
import BsButton from "@/Components/BsForm/Button";
import Datepicker from 'vue3-datepicker'
import MediaUploader from "@/Components/MediaUploader";
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
    }
}
</script>
