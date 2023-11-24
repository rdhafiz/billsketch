import {createRouter, createWebHistory} from "vue-router";


//=====================
// Pages
//=====================
import Layout from "../pages/layouts/layout.vue";
import Dashboard from "../pages/dashboard/dashboard.vue";
import Clients from "../pages/clients/clients.vue";
import Employees from "../pages/employees/employees.vue";
import Invoices from "../pages/invoices/invoices.vue";
import RecurringInvoices from "../pages/recurring-invoices/recurring-invoices.vue";
import Profile from "../pages/profile/Profile.vue";
import UpdateProfile from "../pages/profile/UpdateProfile.vue";
import ChangePassword from "../pages/profile/ChangePassword.vue";
import ClientEdit from "../pages/clients/clientEdit.vue";
import ClientCreate from "../pages/clients/clientCreate.vue";

//=====================
// Routes
//=====================
const ROOT_URL = "/portal";
const routes = [

    // Portal Page Routes
    {
        path: ROOT_URL, name: 'Layout', component: Layout, meta: {requiresAuth: true},
        children: [
            {path: ROOT_URL + '/', redirect: {name: 'Dashboard'}},
            {path: ROOT_URL + '/dashboard', name: 'Dashboard', component: Dashboard},

            /*client routes*/
            {path: ROOT_URL + '/clients', name: 'Clients', component: Clients},
            {path: ROOT_URL + '/clients/create', name: 'ClientCreate', component: ClientCreate},
            {path: ROOT_URL + '/clients/edit', name: 'ClientEdit', component: ClientEdit},

            {path: ROOT_URL + '/employees', name: 'Employees', component: Employees},
            {path: ROOT_URL + '/invoices', name: 'Invoices', component: Invoices},
            {path: ROOT_URL + '/recurring-invoices', name: 'RecurringInvoices', component: RecurringInvoices},

            /* profile routes */
            {path: ROOT_URL + '/profile', name: 'Profile', component: Profile},
            {path: ROOT_URL + '/profile/edit', name: 'UpdateProfile', component: UpdateProfile},
            {path: ROOT_URL + '/profile/change-password', name: 'ChangePassword', component: ChangePassword},
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        let BilifyAccessToken = null;
        let cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let c = cookies[i].trim();
            if (c.includes('BilifyAccessToken')) {
                BilifyAccessToken = c.replace('BilifyAccessToken=', '');
            }
        }
        if (BilifyAccessToken === null) {
            window.location.href = '/login';
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router;
