import {createRouter, createWebHistory} from "vue-router";


//=====================
// Pages
//=====================
import Layout from "../pages/layouts/layout.vue";
import Home from "../pages/home/home.vue";
import HowItWorks from "../pages/how-it-works/how-it-works.vue";
import ContactUs from "../pages/contact-us/contact-us.vue";
import PrivacyPolicy from "../pages/privacy-policy/privacy-policy.vue";
import TermsOfService from "../pages/terms-of-service/terms=of-service.vue";
import Faq from "../pages/faq/faq.vue";

import Login from "../pages/auth/login.vue";
import Register from "../pages/auth/register.vue";
import Forgot from "../pages/auth/forgot.vue";
import Reset from "../pages/auth/reset.vue";
import Verify from "../pages/auth/verify.vue";

//=====================
// Routes
//=====================
const ROOT_URL = "";
const routes = [

    // Website Page Routes
    {
        path: ROOT_URL, name: 'Layout', component: Layout,
        children: [
            {path: ROOT_URL + '', name: 'Home', component: Home},
            {path: ROOT_URL + '/how-it-works', name: 'HowItWorks', component: HowItWorks},
            {path: ROOT_URL + '/contact-us', name: 'ContactUs', component: ContactUs},
            {path: ROOT_URL + '/privacy-policy', name: 'PrivacyPolicy', component: PrivacyPolicy},
            {path: ROOT_URL + '/terms-of-service', name: 'TermsOfService', component: TermsOfService},
            {path: ROOT_URL + '/faq', name: 'Faq', component: Faq},

            {path: ROOT_URL + '/login', name: 'Login', component: Login, meta: {AuthCheck: true}},
            {path: ROOT_URL + '/register', name: 'Register', component: Register, meta: {AuthCheck: true}},
            {path: ROOT_URL + '/forgot', name: 'Forgot', component: Forgot, meta: {AuthCheck: true}},
            {path: ROOT_URL + '/reset', name: 'Reset', component: Reset, meta: {AuthCheck: true}},
            {path: ROOT_URL + '/verify-account', name: 'Verify', component: Verify, meta: {AuthCheck: true}},
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.AuthCheck) {
        let BilifyAccessToken = null;
        let cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let c = cookies[i].trim();
            if (c.includes('BilifyAccessToken')) {
                BilifyAccessToken = c.replace('BilifyAccessToken=', '');
            }
        }
        if (BilifyAccessToken !== null) {
            window.location.href = '/portal';
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router;
