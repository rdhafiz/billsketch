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
            {path: ROOT_URL + '/clients', name:'Clients', component: Clients  },
            {path: ROOT_URL + '/employees', name:'Employees', component: Employees  },
            {path: ROOT_URL + '/invoices', name:'Invoices', component: Invoices  },
            {path: ROOT_URL + '/recurring-invoices', name:'RecurringInvoices', component: RecurringInvoices  },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;