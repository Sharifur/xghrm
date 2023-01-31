<template>
    <Head title="New Statement"/>
    <div class="row">
        <div class="col-lg-6">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">Add New Statements</h2>
                    <div class="div">
                        <Link class="btn btn-secondary m-1" :href="route('admin.earning.all')">
                            All Earnings
                        </Link>
                    </div>
                </div>
                <ValidationErrors/>
                <FlashMsg/>
                <form @submit.prevent="createStatement">
                    <Input title="Title" v-model="statementsData.title"/>
                    <BsSelect title="From" :options="options"  v-model="statementsData.from"/>
                    <BsSelect title="Envato Username" :options="enUser"  v-model="statementsData.en_username"/>
                    <div class="form-group margin-top-20">
                        <label class="info-title">Month</label>
                        <datepicker v-model="statementsData.month"/>
                    </div>
<!--                    <div class="form-group" v-if="isPersonalOrClients()">-->
<!--                        <label>Personal Earning</label>-->
<!--                        <input type="number" step="0.05" class="form-control" v-model="statementsData.personal_earning"/>-->
<!--                    </div>-->
<!--                    <div class="form-group" v-if="isPersonalOrClients()">-->
<!--                        <label>Office Earning</label>-->
<!--                        <input type="number" step="0.05" class="form-control" v-model="statementsData.office_earning"/>-->
<!--                    </div>-->
                    <Input title="Percentage" type="number" step="0.01" v-show="!isPersonalOrClients()" v-model="statementsData.percentage"/>

<!--                    <div class="form-group">-->
<!--                        <input type="file" class="form-control-file" @input="statementsData.statement = $event.target.files[0]"/>-->
<!--                    </div>-->
                    <div class="single-info-input margin-top-30">
                        <MediaUploader button-text="Upload File" v-model="statementsData.statement"/>
                        <small>select only csv file</small>
                    </div>
                    <BsButton button-text="Submit" button-type="submit"/>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster";
import BsButton from "@/Components/BsForm/Button"
import Input from "@/Components/BsForm/Input"
import BsSelect from "@/Components/BsForm/Select"
import {useForm, Link,Head} from "@inertiajs/inertia-vue3";
import ValidationErrors from "@/Components/ValidationErrors";
import FlashMsg from "@/Components/FlashMsg";
import Datepicker from 'vue3-datepicker'
import MediaUploader from "@/Components/MediaUploader";

const options = [
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
const enUser = [{label:'Xgenious', value:'xgenious'},{label:'bytesed',value:'bytesed'}];
export default {
    name: "New",
    layout: AdminMaster,
    components: {
        BsButton,
        Input,
        BsSelect,
        Datepicker,
        ValidationErrors,
        FlashMsg,
        Link,
        Head,
        MediaUploader
    },
    data() {
        return {
            // options,
        }
    },
    setup() {

        const  statementsData = useForm({
            title: '',
            from: 0,
            month: new Date(),
            personal_earning: '',
            office_earning : '',
            statement : '',
            en_username : 'xgenious',
            percentage : 12.50,
        });
        function createStatement(){
            statementsData.post(route('admin.earning.new'),{
                onSuccess: () => {
                    statementsData.reset('title','statement','percentage');
                }
            })
        }
        function isPersonalOrClients(){
            const formList = [0,3];
           return formList.includes(parseInt(statementsData.from));
        }

        return {
            options,
            statementsData,
            createStatement,
            isPersonalOrClients,
            enUser
        }
    }
}
</script>
