<template>

    <!----------------------->
    <!-- Start Home Banner -->
    <!----------------------->
    <div class="page_banner page_banner_50">

        <!-- Start Home Banner Video -->
        <div class="img-bg">
            <img class="w-100" :src="'/assets/images/banner/home-banner.jpg'" alt="" loading="lazy">
        </div>
        <!-- End Home Banner Video -->

        <!-- Start Home Banner Content -->
        <div class="page_banner_content">
            <div class="w-100">
                <div class="container">
                    <div class="banner-positioned">
                        <div class="row">
                            <div class="col-md-12 col-lg-8">
                                <div class="banner_preview" data-aos="zoom-in" data-aos-duration="500" data-aos-delay="700">
                                    <div class="title" data-aos="fade-up" data-aos-duration="500" data-aos-delay="1000">Sign In Now</div>
                                </div>
                                <div class="banner_description_left" data-aos="fade-up" data-aos-duration="500" data-aos-delay="1500">
                                    You can use our one click social login or with your secure credential to access you portal
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Home Banner Content -->

    </div>
    <!--------------------->
    <!-- End Home Banner -->
    <!--------------------->

</template>
<script>
import AOS from "aos";

export default {
    methods: {},
    created() {
        AOS.init();
        window.scroll(0, 0);
    }
}
</script>
