<template>
    <Home_banner :title="'Frequently Asked Questions (FAQ)'"
                 :sub_title="'Find answers to commonly asked questions about our invoice management system.'"></Home_banner>
    <Dummy_section :bg="'bg-white'" :title="'Contact Information'"></Dummy_section>
    <Dummy_section :bg="'bg-light border border-top border-bottom'" :title="'Contact Form'"></Dummy_section>

    <Cta :title="'Still Have Questions?'"
         :sub_title="'If your question is not answered here, feel free to contact our support team. We\'re here to help.'"
         :action_title="'Contact Support'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
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
