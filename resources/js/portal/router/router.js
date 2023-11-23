import {createRouter, createWebHistory} from "vue-router";


//=====================
// Pages
//=====================
import Layout from "../pages/layouts/layout.vue";
import Dashboard from "../pages/dashboard/dashboard.vue";

//=====================
// Routes
//=====================
const ROOT_URL = "/portal";
const routes = [

    // Portal Page Routes
    {
        path: ROOT_URL, name: 'Layout', component: Layout,
        children: [
            {path: ROOT_URL + '/', redirect: {name: 'Dashboard'}},
            {path: ROOT_URL + '/dashboard', name:'Dashboard', component: Dashboard  },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
