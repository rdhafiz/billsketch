<template>
    <Home_banner :title="'How Bilify Works'" :sub_title="'Explore the seamless workflow of Bilify and simplify your invoicing'" :action_title="'Get Started with Bilify'"></Home_banner>
    <Dummy_section :bg="'bg-light border border-top border-bottom'" :title="'How It Works'"></Dummy_section>

    <Cta :title="'Ready to Simplify Your Invoicing?'" :action_title="'Get Started'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Cta from "../layouts/cta.vue";
import Dummy_section from "../layouts/dummy_section.vue";

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
