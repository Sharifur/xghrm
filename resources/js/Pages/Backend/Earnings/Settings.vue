<template>
    <div class="row">
        <div class="col-lg-12">
            <h2>Earning Settings</h2>
            <form @submit.prevent>
                <Select title="Calculate Statements Employee Except" :options="EmployeeList()" v-model="settingsData.calculate_expect_employee"/>
                <Button type="submit" button-text="Save Changes" @click="earningSettingsSubmit"/>
            </form>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster";
import Input from "@/Components/BsForm/Input";
import Select from "@/Components/BsForm/Select";
import Button from "@/Components/BsForm/Button";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";

export default {
    name: "Settings",
    layout: AdminMaster,
    components: {
        Input,
        Select,
        Button
    },
    setup(props,context){
        function EmployeeList(){
            return usePage().props.value.employees;
        }
        const saveSettings = usePage().props.value.settings.length > 0 ? usePage().props.value.settings[0] : null;
        const settingsData = useForm({
           calculate_expect_employee : saveSettings != null  ? saveSettings.calculate_expect_employee : null
        });

        function earningSettingsSubmit(){
            settingsData.post(route('admin.earning.settings'),{
                onSuccess: (res) => {
                    Swal.fire('Success','settings changed','success');
                }
            });
        }

        return {
            EmployeeList,
            settingsData,
            earningSettingsSubmit
        }
    }
}
</script>

