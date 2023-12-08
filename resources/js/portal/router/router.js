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
import EmployeeCreate from "../pages/employees/employeeCreate.vue";
import EmployeeEdit from "../pages/employees/employeeEdit.vue";
import Categories from "../pages/category/categories.vue";
import CategoryCreate from "../pages/category/categoryCreate.vue";
import CategoryEdit from "../pages/category/categoryEdit.vue";
import InvoiceCreate from "../pages/invoices/invoiceCreate.vue";
import InvoiceEdit from "../pages/invoices/invoiceEdit.vue";
import InvoiceView from "../pages/invoices/invoiceView.vue";

import RecurringInvoiceCreate from "../pages/invoices/invoiceCreate.vue";
import RecurringInvoiceEdit from "../pages/invoices/invoiceEdit.vue";
import RecurringInvoiceView from "../pages/invoices/invoiceView.vue";

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
            {path: ROOT_URL + '/clients/edit/:id', name: 'ClientEdit', component: ClientEdit},

            /*employee routes*/
            {path: ROOT_URL + '/employees', name: 'Employees', component: Employees},
            {path: ROOT_URL + '/employees/create', name: 'EmployeeCreate', component: EmployeeCreate},
            {path: ROOT_URL + '/employees/edit/:id', name: 'EmployeeEdit', component: EmployeeEdit},

            /*category routes*/
            {path: ROOT_URL + '/categories', name: 'Categories', component: Categories},
            {path: ROOT_URL + '/categories/create', name: 'CategoryCreate', component: CategoryCreate},
            {path: ROOT_URL + '/categories/edit/:id', name: 'CategoryEdit', component: CategoryEdit},

            /*invoice routes*/
            {path: ROOT_URL + '/invoices', name: 'Invoices', component: Invoices},
            {path: ROOT_URL + '/invoices/create', name: 'InvoiceCreate', component: InvoiceCreate},
            {path: ROOT_URL + '/invoices/edit/:id', name: 'InvoiceEdit', component: InvoiceEdit},
            {path: ROOT_URL + '/invoices/:id', name: 'InvoiceView', component: InvoiceView},

            /*invoice routes*/
            {path: ROOT_URL + '/recurring-invoices', name: 'RecurringInvoices', component: RecurringInvoices},
            {path: ROOT_URL + '/recurring-invoices/create', name: 'RecurringInvoiceCreate', component: RecurringInvoiceCreate},
            {path: ROOT_URL + '/recurring-invoices/edit/:id', name: 'RecurringInvoiceEdit', component: RecurringInvoiceEdit},
            {path: ROOT_URL + '/recurring-invoices/:id', name: 'RecurringInvoiceView', component: RecurringInvoiceView},
            {path: ROOT_URL + '/recurring-invoices/:id', name: 'RecurringInvoiceView', component: RecurringInvoiceView},

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
