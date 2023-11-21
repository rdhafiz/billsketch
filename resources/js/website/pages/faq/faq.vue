<template>
    <Home_banner></Home_banner>
    <Faq></Faq>

    <Cta :title="'Still Have Questions?'"
         :sub_title="'If your question is not answered here, feel free to contact our support team. We\'re here to help.'"
         :action_title="'Contact Support'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";

import Faq from './widgets/content.vue';

import Cta from "../layouts/cta.vue";

import AOS from 'aos';

export default {
    components: {
        Dummy_section,
        Home_banner,
        Cta,
        Faq
    },
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
