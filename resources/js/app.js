/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import vSelect from "vue-select";
import CoolLightBox from "vue-cool-lightbox";

Vue.component("v-select", vSelect);
Vue.component("cool-lightbox", CoolLightBox);
Vue.component("search-app", require("./components/SearchApp.vue").default);
Vue.component("search-bar", require("./components/SearchBar.vue").default);
Vue.component(
    "search-results",
    require("./components/SearchResults.vue").default
);
Vue.component("herb", require("./components/Herb.vue").default);
Vue.component("herb-formula", require("./components/HerbFormula.vue").default);

Vue.component("form-app", require("./components/FormApp.vue").default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
