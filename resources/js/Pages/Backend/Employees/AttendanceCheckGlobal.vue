<template>
    <Head title="Employee Attendance Check - Xgenious"/>
          <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-settings margin-top-40">
                    <div class="header-wrap d-flex justify-content-between">
                        <h2 class="dashboards-title">Attendance Check test</h2>
                    </div>
                    <div class="edit-profile">
                        <div class="profile-info-dashboard">
                            <ValidationErrors/>
                            <div class="dashboard-profile-flex">
                                <div class="dashboard-address-details">
                                    <form @submit.prevent>
                                        <div class="single-dashboard-input">
                                            <div class="single-info-input margin-top-30">
                                                <label class="info-title"> Select Month </label>
                                                <VueDatePicker v-model="employeeData.startDate"/>
                                                <span>any date you select in this calendar, system will get data for the selected date month</span>
                                            </div>
                                        </div>
                                        <Select title="Employee"
                                            v-model="employeeData.employee_id"
                                            :options="employeeList"
                                         />
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
                            <li class="C/In">OfficeDays<span class="badge">{{OfficeDays}}</span></li>
                            <li class="sick-leave">Sick Leave<span class="badge">{{sickLeaveCount}}</span></li>
                            <li class="paid-leave">Paid Leave<span class="badge">{{paidLeaveCount}}</span></li>
                            <li class="work-from-home">Work From Home<span class="badge">{{workFormHome}}</span></li>
                            <li class="avgOfficeWorkingHours">Avg Office Working Hours<span class="badge">{{avgOfficeWorkingHours}} hr</span></li>
                            <li class="daysWithCompleteData">Days With Complete Data<span class="badge">{{daysWithCompleteData}} days</span></li>
                            <li class="daysWithCompleteData">Late Arrival Count<span class="badge">{{lateArrivalCount}} days</span></li>
                            <li class="officeWorkingDays">Office Working Days<span class="badge">{{officeWorkingDays}} days</span></li>
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
    import Select from "@/Components/BsForm/Select.vue";
    import VueDatePicker from "@vuepic/vue-datepicker";

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
            Select,
            VueDatePicker
        },
        setup(){
            const holidayCount = ref(0);
            const leaveCount = ref(0);
            const OfficeDays = ref(0);
            const sickLeaveCount = ref(0);
            const paidLeaveCount = ref(0);
            const workFormHome = ref(0);
            const avgOfficeWorkingHours = ref(0);
            const daysWithCompleteData = ref(0);
            const lateArrivalCount = ref(0);
            const officeWorkingDays = ref(0);
            //avgOfficeWorkingHours
            //daysWithCompleteData
            //lateArrivalCount
            //officeWorkingDays

            const attendanceShow = ref(false);
            const CalendarAttributes = ref([]);
            const employeeList = usePage().props.value.allEmployees;
            const employeeData = useForm({
                employee_id: null ,
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
                    let logs = response.data.logs;
                    // console.log(Object.keys(logs).length);
                     if (Object.keys(logs).length > 0){
                        let attenData = logs;
                        let calendarData = [];
                         Object.keys(attenData).forEach(function(key) {
                             let currentItem = attenData[key];
                             Object.keys(currentItem).forEach((key) => {
                                 if(key === "dateTime"){
                                     return;
                                 }
                                 calendarData.push({
                                     key: `${key}-${Math.random()}`,
                                     customData: {
                                         title: `${key.replaceAll("_"," ")}: ${currentItem[key]}`,
                                         class: key
                                     },
                                    dates: new Date(currentItem.dateTime),
                                 })
                             });
                         });
                        CalendarAttributes.value = calendarData;
                        attendanceShow.value = true;
                        //todo set value of const
                         holidayCount.value = response.data.holidayCount;
                         leaveCount.value = response.data.leaveCount;
                         OfficeDays.value = response.data.OfficeDays;
                         sickLeaveCount.value = response.data.sickLeaveCount;
                         paidLeaveCount.value = response.data.paidLeaveCount;
                         workFormHome.value = response.data.workFormHome;

                         avgOfficeWorkingHours.value = response.data.avgOfficeWorkingHours;
                         daysWithCompleteData.value = response.data.daysWithCompleteData;
                         lateArrivalCount.value = response.data.lateArrivalCount;
                         officeWorkingDays.value = response.data.officeWorkingDays;

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
                employeeList,
                CalendarData,
                attendanceShow,
                CalendarAttributes,
                holidayCount ,
                leaveCount,
                OfficeDays,
                sickLeaveCount,
                paidLeaveCount,
                workFormHome,
                officeWorkingDays,
                avgOfficeWorkingHours,
                daysWithCompleteData,
                lateArrivalCount
            }
        }
    }
    </script>
