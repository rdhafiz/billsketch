<template>
    <Home_banner></Home_banner>
    <Content></Content>
    <Cta :title="'Your Privacy Matters'" :sub_title="'Read our full Privacy Policy to understand how we protect your information.'" :action_title="'Read Privacy Policy'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";

import Cta from "../layouts/cta.vue";

import AOS from 'aos';
import Content from "./widgets/content.vue";

export default {
    components: {
        Content,
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
