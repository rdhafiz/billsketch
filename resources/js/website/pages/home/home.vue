<template>
    <Home_banner></Home_banner>
    <Home_features></Home_features>
    <Home_works></Home_works>
    <Dummy_section :bg="'bg-white'" :title="'Testimonials'"></Dummy_section>

    <Cta :title="'Ready to Simplify Your Invoicing?'" :action_title="'Get Started'"></Cta>
</template>
<script>
import Home_banner from "./widgets/home_banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";
import Cta from "../layouts/cta.vue";

import AOS from 'aos';
import Home_features from "./widgets/home_features.vue";
import Home_works from "./widgets/home_works.vue";

export default {
    components: {
        Home_works,
        Home_features,
        Dummy_section,
        Home_banner,
        Cta
    },
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
