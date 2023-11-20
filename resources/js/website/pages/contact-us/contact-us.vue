<template>
    <Home_banner :title="'Contact Us'" :sub_title="'Have questions or need assistance? Reach out to our team.'"></Home_banner>
    <Dummy_section :bg="'bg-white'" :title="'Contact Information'"></Dummy_section>
    <Dummy_section :bg="'bg-light border border-top border-bottom'" :title="'Contact Form'"></Dummy_section>
    <Cta :title="'We\'re Here to Help'" :sub_title="'Reach out to our team for any assistance or inquiries.'" :action_title="'Send a Message'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";

import AOS from 'aos';
import Cta from "../layouts/cta.vue";

export default {
    components: {
        Cta,
        Dummy_section,
        Home_banner
    },
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
