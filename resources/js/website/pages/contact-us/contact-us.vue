<template>
    <Home_banner></Home_banner>
    <Contact_form></Contact_form>
    <Cta :title="'We\'re Here to Help'" :sub_title="'Reach out to our team for any assistance or inquiries.'" :action_title="'Send a Message'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";

import AOS from 'aos';
import Cta from "../layouts/cta.vue";
import Contact_form from "./widgets/contact_form.vue";

export default {
    components: {
        Contact_form,
        Cta,
        Home_banner
    },
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
