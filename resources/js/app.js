require('./bootstrap');
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import country from "./Components/data/country";

import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';
window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;

const numToWordObj = {
    0: 'zero',
    1: 'one',
    2: 'two',
    3: 'three',
    4: 'four',
    5: 'five',
    6: 'six',
    7: 'seven',
    8: 'eight',
    9: 'nine',
    10: 'ten',
    11: 'eleven',
    12: 'twelve',
    13: 'thirteen',
    14: 'fourteen',
    15: 'fifteen',
    16: 'sixteen',
    17: 'seventeen',
    18: 'eighteen',
    19: 'nineteen',
    20: 'twenty',
    30: 'thirty',
    40: 'forty',
    50: 'fifty',
    60: 'sixty',
    70: 'seventy',
    80: 'eighty',
    90: 'ninety'
};
const placement = {
    100: ' hundred',
    1000: ' thousand',
    1000000: ' million',
    1000000000000: ' trillion'
};

const numToWord = (num) => {
    const limiter = (val) => num < val;
    const limiterIndex = Object.keys(placement).findIndex(limiter);
    const limiterKey = Object.keys(placement)[limiterIndex];
    const limiterVal = Object.values(placement)[limiterIndex - 1];
    const limiterMod = Object.keys(placement)[limiterIndex - 1];

    if (numToWordObj[num]) {
        return numToWordObj[num];
    }
    if (num < 100) {
        let whole = Math.floor(num / 10) * 10;
        let part = num % 10;
        return numToWordObj[whole] + ' ' + numToWordObj[part];
    }

    if (num < limiterKey) {

        let whole = Math.floor(num / limiterMod);
        let part = num % limiterMod;
        if (part === 0) {
            return numToWord(whole) + limiterVal;
        } else {
            return numToWord(whole) + limiterVal + ' ' + numToWord(part);
        }
    }
};



const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
         return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(VueSweetalert2)
            .use(SetupCalendar, {})
            // Use the components
            .component('Calendar', Calendar)
            .component('DatePicker', DatePicker)
            .mixin({ methods: {
                route,
                countryNameBySlug(slug){
                    const countryObject =  country.find((cunName) =>  cunName.value === slug);
                    return countryObject !== 'undefined' ? countryObject?.label : 'not found';
                },
               readableDateFormat(datetime){
                    const date = new Date(datetime);
                    return date.getDate() +' '+ date.toLocaleDateString('en-US', {month: 'long'}) +' '+ date.getFullYear();
                },
               getImageUrlById(id){
                    //todo: write function to return image path
               },
               numberToWord(number){
                    return numToWord(number)
               }
            } })
            .mount(el);
    },
});
InertiaProgress.init({ color: '#4B5563' });
