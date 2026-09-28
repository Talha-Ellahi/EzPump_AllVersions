/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import SettingsPage from './components/SettingsPage.vue';
import ShiftChange from './components/ShiftChange.vue';
import Index from './components/Index.vue';
import TankList from './components/TankList.vue';
import TankList2 from './components/TankList2.vue';
import AddTank from './components/AddTank.vue';
import StockHistory from './components/StockHistory.vue';
import DipChartUpload from './components/DipChartUpload.vue';
import tankShift from './components/tankShift.vue';
import Dashboard from './components/Dashboard.vue';
import SummaryReportForm from './components/SummaryReportForm.vue';
import StockReportForm from './components/StockReportForm.vue';
import Example_Dashboard from './components/Example_Dashboard.vue';
import ShiftChangeReport from './components/ShiftChangeReport.vue';
import ShiftChange2 from './components/ShiftChange2.vue';
import AllReports from './components/AllReports.vue';
import AtgSetup from "@/components/AtgSetup.vue";
app.component('tank-shift', tankShift);
app.component('dashboard', Dashboard);
app.component('example-component', ExampleComponent);
app.component('settings-page', SettingsPage);
app.component('shift-change', ShiftChange);
app.component('index', Index);
app.component('tank-list', TankList);
app.component('tank-list2', TankList2);
app.component('add-tank', AddTank);
app.component('stock-history', StockHistory);
app.component('dip-chart-upload', DipChartUpload);
app.component('summary-report-form', SummaryReportForm);
app.component('StockReportForm', StockReportForm);
app.component('example-dashboard', Example_Dashboard);
app.component('shift-change-report', ShiftChangeReport);
app.component('shift-change2', ShiftChange2);
app.component('all-reports', AllReports);
app.component('atg-setup', AtgSetup);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
