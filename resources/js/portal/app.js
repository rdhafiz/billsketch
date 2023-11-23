// jQuery
import jQuery from "jquery";
const $ = jQuery;
window.$ = $;

// Bootstrap 5
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js'
window.bootstrap = bootstrap

// Axios
import axios from "axios";

// Vue Templates
import App from "./App.vue";
import {createApp} from "vue";

// Page Routes
import router from "./router/router";

// Init Vue App
createApp(App)
    .use(router, axios)
    .mount('#app')
