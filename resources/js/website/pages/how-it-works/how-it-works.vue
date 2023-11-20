<template>
    <Home_banner></Home_banner>
    <Dummy_section :bg="'bg-light border border-top border-bottom'" :title="'How It Works'"></Dummy_section>

    <Dummy_section :bg="'bg-light border border-top border-bottom'" :title="'Call-to-Action'"></Dummy_section>
</template>
<script>
import Home_banner from "./widgets/banner.vue";
import Dummy_section from "../layouts/dummy_section.vue";

import AOS from 'aos';

export default {
    components: {
        Dummy_section,
        Home_banner
    },
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
