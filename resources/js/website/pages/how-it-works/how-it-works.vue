<template>
    <Home_banner></Home_banner>
    <How_it_works></How_it_works>

    <Cta :title="'Ready to Simplify Your Invoicing?'" :action_title="'Get Started'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Cta from "../layouts/cta.vue";
import Dummy_section from "../layouts/dummy_section.vue";

import AOS from 'aos';
import How_it_works from "./widgets/how_it_works.vue";

export default {
    components: {
        How_it_works,
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
