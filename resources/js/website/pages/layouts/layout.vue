<template>

    <div class="page_load w-100">
        <div style="height: 100vh" class="w-100 d-flex justify-content-center align-items-center">
            <div class="w-100 text-center">
                <img style="width: 50px" :src="'/assets/images/icon.png'" alt="">
            </div>
        </div>
    </div>

    <div class="page_preview w-100" style="display: none">
        <Header/>
        <div class="w-100">
            <router-view></router-view>
        </div>
        <Footer></Footer>
    </div>

</template>
<script>
import Header from "./header.vue";
import Footer from "./footer.vue";
const animationTime = 500;

export default {
    components: {Header, Footer},
    data() {
        return {
        }
    },
    watch:{
        $route (to, from){
            $('.page_preview').hide();
            $('.page_load').fadeIn();
            setTimeout(() => {
                $('.page_load').hide();
                $('.page_preview').fadeIn();
            }, animationTime)
        }
    },
    mounted() {
        setTimeout(() => {
            $('.page_load').hide();
            $('.page_preview').fadeIn();
        }, animationTime)
    }
}
</script>
