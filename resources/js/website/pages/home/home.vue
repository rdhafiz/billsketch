<template>
    <Home_banner></Home_banner>

    <Dummy_section :bg="'bg-white'" :title="'Features'"></Dummy_section>
    <Dummy_section :bg="'bg-light border border-top border-bottom'" :title="'How It Works'"></Dummy_section>
    <Dummy_section :bg="'bg-white'" :title="'Testimonials'"></Dummy_section>

    <Cta :title="'Ready to Simplify Your Invoicing?'" :action_title="'Get Started'"></Cta>
</template>
<script>
import Home_banner from "./widgets/home_banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";
import Cta from "../layouts/cta.vue";

import AOS from 'aos';

export default {
    components: {
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
