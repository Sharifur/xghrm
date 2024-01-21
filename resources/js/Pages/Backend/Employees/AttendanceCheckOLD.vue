<template>
<Head title="Employee Attendance Check - Xgenious"/>
      <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-settings margin-top-40">
                <div class="header-wrap d-flex justify-content-between">
                    <h2 class="dashboards-title">{{employeeDetails.name}} Attendance Check</h2>
                    <div class="btn-wrp">
                        <Link class="btn btn-info m-1" :href="route('admin.employee.all')">All Employees</Link>
                        <Link class="btn btn-secondary m-1" :href="route('admin.employee.edit',employeeDetails.id)">Edit Employee Details</Link>
                        <Link class="btn btn-primary m-1" :href="route('admin.employee.view',employeeDetails.id)">View Employee Details</Link>
                    </div>
                </div>
                <div class="edit-profile">
                    <div class="profile-info-dashboard">
                        <ValidationErrors/>
                        <div class="dashboard-profile-flex">
                            <div class="dashboard-address-details">
                                <form @submit.prevent>

                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label class="info-title"> Start Date </label>
                                            <Datepicker v-model="employeeData.startDate"/>
                                        </div>
                                    </div>
                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label class="info-title"> End Date</label>
                                            <Datepicker v-model="employeeData.endDate"/>
                                        </div>
                                    </div>
                                    <div class="btn-wrapper margin-top-35">
                                        <BsButton button-text="Get Details" @click="getAttendanceDetails" classes="cmn-btn btn-bg-1"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="cleardar-wrapper" v-if="attendanceShow">
               <div class="head-rapw margin-top-40">
                    <h4>Color Explanation</h4>
                    <ul class="color-explanation">
                        <li class="holiday">Holiday<span class="badge">{{holidayCount}}</span></li>
                        <li class="leave">leave<span class="badge">{{leaveCount}}</span></li>
                        <li class="C/In">C/In<span class="badge">{{inCount}}</span></li>
                        <li class="C/Out">C/Out<span class="badge">{{outCount}}</span></li>
                        <li class="sick-leave">Sick Leave<span class="badge">{{sickLeaveCount}}</span></li>
                        <li class="paid-leave">Paid Leave<span class="badge">{{paidLeaveCount}}</span></li>
                        <li class="paid-leave">Work From Home<span class="badge">{{workFormHome}}</span></li>
                    </ul>
               </div>
                <Calendar
                    class="custom-calendar-outer-wrap"
                    :masks="CalendarData().masks"
                    :attributes="CalendarData().attributes"
                    disable-page-swipe
                    is-expanded
                    >
                    <template v-slot:day-content="{ day, attributes }">
                        <div class="date-box-wrapper">
                            <span class="day-label">{{ day.day }}</span>
                            <div class="custom-data-wrapper">
                                <p
                                v-for="{key,customData} in attributes"
                                :key="key"
                                class="data-item"
                                :class="customData.class"
                                >
                                {{ customData.title }}
                                </p>
                            </div>
                        </div>
                    </template>
                </Calendar>
            </div>


            </div>
        </div>
    </div>
</template>

<script>
import {Head,Link,useForm,usePage} from '@inertiajs/inertia-vue3';
import AdminMaster from "@/Layouts/AdminMaster.vue";
import BsSelect from "@/Components/BsForm/Select.vue";
import BsButton from "@/Components/BsForm/Button.vue";
import Datepicker from 'vue3-datepicker'
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Swal from "sweetalert2";
import { ref } from '@vue/runtime-core';
import axios from 'axios';


export default {
    name: "AttendanceCheck",
    layout: AdminMaster,
    components: {
        BsSelect,
        BsButton,
        Datepicker,
        ValidationErrors,
        Link,
        Head,
    },
    setup(){
        const holidayCount = ref(0);
        const leaveCount = ref(0);
        const inCount = ref(0);
        const outCount = ref(0);
        const sickLeaveCount = ref(0);
        const paidLeaveCount = ref(0);
        const workFormHome = ref(0);

        const attendanceShow = ref(false);
        const CalendarAttributes = ref([]);
        const employeeDetails = usePage().props.value.allEmployees;
        const employeeData = useForm({
            employee_id:employeeDetails.id ,
            startDate: new Date(),
            endDate : new Date()
        });



        function getAttendanceDetails(){
            //submit form

            axios
            .post(route('admin.employee.attendance.post'),{
                ...employeeData
            })
            .then((response) => {
                 if (Object.keys(response.data.logs).length > 1){
                    let attenData = response.data.logs;
                    let calendarData = [];
                    attenData.forEach((value,index) => {
                        calendarData.push({
                            key: index,
                            customData: {
                                title: `${value.type}: ${new Date(value.date_time).toLocaleTimeString('en-US',{timeZone: 'Asia/Dhaka'})} `,
                                class: value.type,
                            },
                            dates: new Date(value.date_time),
                        })
                    });
                    CalendarAttributes.value = calendarData;
                    attendanceShow.value = true;
                    //todo set value of const
                     holidayCount.value = response.data.holidayCount;
                     leaveCount.value = response.data.leaveCount;
                     inCount.value = response.data.inCount;
                     outCount.value = response.data.outCount;
                     sickLeaveCount.value = response.data.sickLeaveCount;
                     paidLeaveCount.value = response.data.paidLeaveCount;
                     workFormHome.value = response.data.workFormHome;

                }else {
                    Swal.fire('Error!','NO Data found for selected Dates','warning');
                }
            })
            .catch((error) => {
                Swal.fire('Error!',error.message,'warning');
            });
        }

        const month = new Date().getMonth();
        const year = new Date().getFullYear();
       function  CalendarData (){

            return {
                masks: {
                    weekdays: 'WWW',
                },
                attributes :CalendarAttributes.value
            }
       }

        return {
            employeeData,
            getAttendanceDetails,
            employeeDetails,
            CalendarData,
            attendanceShow,
            CalendarAttributes,
            holidayCount ,
            leaveCount,
            inCount,
            outCount,
            sickLeaveCount,
            paidLeaveCount,
            workFormHome,
        }
    }
}
</script>
