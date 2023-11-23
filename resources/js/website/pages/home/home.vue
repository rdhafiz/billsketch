<template>
    <Home_banner></Home_banner>
    <Home_works></Home_works>
    <Home_features></Home_features>
    <Home_testimonials></Home_testimonials>
    <Cta :title="'Ready to Simplify Your Invoicing?'" :action_title="'Get Started'"></Cta>
</template>
<script>
import Home_banner from "./widgets/home_banner.vue";
import Cta from "../layouts/cta.vue";
import AOS from 'aos';
import Home_features from "./widgets/home_features.vue";
import Home_works from "./widgets/home_works.vue";
import Home_testimonials from "./widgets/home_testimonials.vue";

export default {
    components: {
        Home_testimonials,
        Home_works,
        Home_features,
        Home_banner,
        Cta
    },
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
