<template>
    <div class="row">
        <div class="col-lg-12">
            <h2>General Settings</h2>
            <form @submit.prevent>
                //settings form will be there
                <Button type="submit" button-text="Save Changes" @click="earningSettingsSubmit"/>
            </form>
        </div>
    </div>
</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster.vue";
import Input from "@/Components/BsForm/Input.vue";
import Select from "@/Components/BsForm/Select.vue";
import Button from "@/Components/BsForm/Button.vue";
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
        // function EmployeeList(){
        //     return usePage().props.value.employees;
        // }
        const saveSettings = usePage();//.props.value.settings.length > 0 ? usePage().props.value.settings[0] : null;
        const settingsData = useForm({
           calculate_expect_employee :  null
        });

        function earningSettingsSubmit(){
            settingsData.post(route('admin.earning.settings'),{
                onSuccess: (res) => {
                    Swal.fire('Success','settings changed','success');
                }
            });
        }
        return {
            settingsData
        }
    }
}
</script>

