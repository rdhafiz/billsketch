<template>

    <div class="bilify_portal">
        <div class="side_bar">
            <Sidebar/>
        </div>
        <div class="main_body">
            <div class="header">
                <Header/>
            </div>
            <div class="main_body_content">
                <div class="content_view">
                    <router-view/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Header from "./header.vue";
import Sidebar from "./sidebar.vue";

export default {
    components: {Header, Sidebar},
}
</script>
