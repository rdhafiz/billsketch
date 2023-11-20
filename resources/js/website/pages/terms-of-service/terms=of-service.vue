<template>
    <Home_banner></Home_banner>
    <Content></Content>
    <Cta :title="'Understanding Our Terms'"
         :sub_title="'Review our full Terms of Service for a complete understanding of your rights and responsibilities.'"
         :action_title="'Read Terms of Service'"></Cta>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";

import Cta from "../layouts/cta.vue";

import AOS from 'aos';
import Content from "../terms-of-service/widgets/content.vue";

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
