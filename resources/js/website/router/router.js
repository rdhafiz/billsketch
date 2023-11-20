import {createRouter, createWebHistory} from "vue-router";


//=====================
// Pages
//=====================
import Layout from "../pages/layouts/layout.vue";
import Home from "../pages/home/home.vue";

// import AboutUs from "../pages/about/about-us.vue";
// import ContactUs from "../pages/contact/contact-us.vue";
// import Terms from "../pages/terms/terms.vue";
// import Policy from "../pages/policy/policy.vue";

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

            // {path: ROOT_URL + '/about-us', name:'AboutUs', component: AboutUs  },
            // {path: ROOT_URL + '/contact-us', name:'ContactUs', component: ContactUs  },
            // {path: ROOT_URL + '/terms-of-use', name:'Terms', component: Terms  },
            // {path: ROOT_URL + '/privacy-policy', name:'Policy', component: Policy  }
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
