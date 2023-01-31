<template>
    <Head title="All Users" />
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title margin-bottom-40">All Users</h2>
                    <div class="btn-wrp">
                        <Link :href="route('admin.users.new')" class="btn btn-secondary">Add New</Link>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-stripped">
                        <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <tr v-for="user in usersList()">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }} ( {{ user.username }} ) <Link v-show="user?.employee" :href="employeeRoute(user)"><i class="fas fa-briefcase btn-success text-white btn btn-sm" title="An Employee" ></i></Link> </td>
                            <td>{{ user.email }} <i v-show="user.email_verified_at != null"
                                                    class="fas fa-check-circle text-success"></i></td>
                            <td>
                                <DropdownButtons title="Actions">
                                    <Link class="dropdown-item" :href="route('admin.users.view',user.id)">View</Link>
                                    <Link class="dropdown-item" @click="deleteItem(user)">Delete</Link>
                                    <Link class="dropdown-item" :href="route('admin.users.edit',user.id)">Edit</Link>
                                    <Link class="dropdown-item" @click="banUser(user)">
                                        {{ user.banned === 0 ? 'Ban' : 'Unban' }} user
                                    </Link>
                                    <Link class="dropdown-item" @click="sendVerifyMail(user)">Send Verify Mail</Link>
                                    <Link class="dropdown-item" @click="disableEmailVerify(user)">
                                        {{ user.email_verified_at == null ? 'Disable' : 'Enable' }} Email Verify
                                    </Link>

                                    <BsModalButton target="addnewcategory" button-class="dropdown-item"
                                                   @click="changePassword(user)">Change Password
                                    </BsModalButton>
                                    <BsModalButton v-if="!user?.employee" target="convertToEmployee" button-class="dropdown-item"
                                                   @click="convertToEmployee(user)">Convert To Employee
                                    </BsModalButton>
                                </DropdownButtons>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="usersListLinks()"/>
            </div>
        </div>
    </div>
    <BsModal modal-title="Change Password" modal-id="addnewcategory" modal-size="modal-md">
        <BreezeValidationErrors class="mb-4"/>
        <form @submit.prevent>
            <BsInput title="Password" type="password" v-model="changePasswordData.password"/>
            <BsInput title="Confirm Password" type="password" v-model="changePasswordData.password_confirmation"/>
            <BsButton button-text="Save Changes" button-type="submit" @click="changePasswordFormSubmit"
                      :disabled="changePasswordData.processing"/>
        </form>
    </BsModal>
    <BsModal modal-title="Convert To Employee" modal-id="convertToEmployee" modal-size="modal-md">
        <BreezeValidationErrors class="mb-4"/>
        <form @submit.prevent>
            <BsSelect title="Employee Type" @change="employeeTypeChange($event.target.value)" :options="employeeType"
                      v-model="convertToEmployeeData.employee_type"/>
            <div class="newemployee" v-if="!isExistingEmployee">
                <BsSelect title="Category*" :options="catList()" v-model="convertToEmployeeData.catId"/>
                <BsSelect title="Status*" :options="statusOption" v-model="convertToEmployeeData.status"/>
                <BsInput type="number" title="Salary*" v-model="convertToEmployeeData.salary"/>
                <div class="single-dashboard-input">
                    <div class="single-info-input margin-top-30">
                        <label class="info-title"> Date Of Birth* </label>
                        <Datepicker v-model="convertToEmployeeData.dateOfBirth"/>
                    </div>
                </div>
                <div class="single-dashboard-input">
                    <div class="single-info-input margin-top-30">
                        <label class="info-title"> Join Date* </label>
                        <Datepicker v-model="convertToEmployeeData.joinDate"/>
                    </div>
                </div>
                <BsTextarea title="Address" v-model="convertToEmployeeData.address"/>
                <BsInput type="text" title="Emergency Number*" v-model="convertToEmployeeData.att_id"/>
                <BsInput type="number" title="Emergency Number*" v-model="convertToEmployeeData.emergencyNumber"/>
                <BsTextarea title="Personal Information (NID/Passport Info)"
                            v-model="convertToEmployeeData.personalInfo"/>
                <MediaUploader v-model="convertToEmployeeData.imageId"/>
            </div>
            <div v-else class="oldemployee">
                <BsSelect title="Existing Employees" :options="employeesList()"
                          v-model="convertToEmployeeData.existing_employee_id"/>
            </div>
            <BsButton button-text="Save Changes" button-type="submit" @click="convertToEmployeeFormSubmit"
                      :disabled="convertToEmployeeData.processing"/>
        </form>
    </BsModal>

</template>

<script>
import AdminMaster from "@/Layouts/AdminMaster";
import {usePage, Link, useForm,Head} from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination";
import DropdownButtons from "@/Components/DropdownButtons";
import Swal from "sweetalert2";
import BsModal from "@/Components/BsModal";
import BsModalButton from "@/Components/BsModalButton";
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import BsInput from "@/Components/BsForm/Input";
import BsButton from "@/Components/BsForm/Button";
import BsSelect from "@/Components/BsForm/Select";
import MediaUploader from "@/Components/MediaUploader";
import BsTextarea from "@/Components/BsForm/Textarea";
import Datepicker from 'vue3-datepicker'
import {ref} from "vue";

export default {
    name: "AllUsers",
    layout: AdminMaster,
    components: {
        Pagination,
        Link,
        DropdownButtons,
        BsModalButton,
        BsModal,
        BreezeValidationErrors,
        BsInput,
        BsButton,
        BsSelect,
        MediaUploader,
        BsTextarea,
        Datepicker,
        Head

    },
    setup() {
        const employeeType = [{'label': 'New', 'value': 'new'}, {'label': 'Existing', 'value': 'existing'}];
        const isExistingEmployee = ref(false);
        const changePasswordData = useForm({
            id: null,
            password: null,
            password_confirmation: null
        })
        const convertToEmployeeData = useForm({
            userId: null,
            employee_type: 'new',
            existing_employee_id: null,
            catId: null,
            status: null,
            dateOfBirth: new Date(),
            joinDate: new Date(),
            address: null,
            emergencyNumber: null,
            personalInfo: null,
            imageId: null,

        })

        function usersList() {
            return usePage().props.value.users.data
        }

        function usersListLinks() {
            return usePage().props.value.users.links
        }

        function deleteItem(item) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                const deleteItem = useForm({
                    id: item.id
                });
                deleteItem.post(route('admin.users.delete'), {
                    onSuccess: () => {
                        Swal.fire('Deleted!!', 'your won\'t able to revert it', 'error');
                    }
                });
            })


        }

        function changePassword(item) {
            changePasswordData.id = item.id
            changePasswordData.reset('password', 'password_confirmation')
        }

        function disableEmailVerify(item) {
            const EmailData = useForm({id: item.id, email_verified_at: item.email_verified_at});
            EmailData.post(route('admin.users.disable.mail.verify'), {
                onSuccess: () => {
                    Swal.fire('Success!!', 'Settings Changed', 'warning');
                }
            });
        }

        function banUser(item) {
            const banData = useForm({id: item.id, banned: item.banned});
            let msgData = item.banned === 0 ? 'Banned' : 'Unbanned';
            banData.post(route('admin.users.ban.user'), {
                onSuccess: () => {
                    Swal.fire('success' , msgData +' user successfully', 'warning');
                }
            });
        }

        function convertToEmployeeFormSubmit() {
            convertToEmployeeData.post(route('admin.users.make.employee'), {
                onSuccess: (data) => {
                    Swal.fire('success','converted to employee','success');
                },
                onError: (res) => {

                }
            });
        }

        function convertToEmployee(item) {
            convertToEmployeeData.userId = item.id;
        }

        function changePasswordFormSubmit() {
            changePasswordData.post(route('admin.users.change.password'), {
                onSuccess: () => {
                    Swal.fire('Success!!', 'Password Changed', 'success');
                }
            });
        }

        function sendVerifyMail(item) {
            const deleteItem = useForm({
                id: item.id
            });
            deleteItem.post(route('admin.users.resend.verify.mail'), {
                onSuccess: (res) => {
                    Swal.fire('Success!!', 'verify mail send', 'success');
                },
                onError: (res) => {
                    console.log(res)
                }
            });
        }

        function employeeTypeChange(value) {
            isExistingEmployee.value = value !== 'new';
        }

        function catList() {
            return usePage().props.value.category;
        }

        function employeesList() {
            return usePage().props.value.employees;
        }

        const statusOption = [
            {label: 'Working', value: 1},
            {label: 'Left Job', value: 0}
        ]
        function employeeRoute(user){
            if (user?.employee?.id){
                return  route('admin.employee.view',user?.employee.id)
            }
            return null;
        }


        return {
            employeeType,
            employeesList,
            isExistingEmployee,
            statusOption,
            catList,
            usersList,
            usersListLinks,
            deleteItem,
            changePassword,
            disableEmailVerify,
            sendVerifyMail,
            banUser,
            changePasswordData,
            convertToEmployeeData,
            changePasswordFormSubmit,
            convertToEmployeeFormSubmit,
            convertToEmployee,
            employeeTypeChange,
            employeeRoute
        }
    }
}
</script>

