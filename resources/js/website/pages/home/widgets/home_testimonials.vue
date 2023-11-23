<template>

    <!----------------------->
    <!-- Start Home Testimonials -->
    <!----------------------->
    <div class="page_testimonials">

        <!-- Start Home Testimonials Content -->
        <div class="page_testimonials_content">
            <div class="w-100 py-5">
                <div class="container">
                    <h1 class="m-0 p-0 text-center"><strong>Testimonials</strong></h1>
                    <div class="testimonial_items my-5">
                        <swiper :slidesPerView="1"
                                :modules="modules"
                                :navigation="true"
                                :loop="true"
                                :speed="800"
                                :autoplay="{
                                    delay: 3000,
                                    disableOnInteraction: false,
                                }">
                            <swiper-slide>
                                <div class="row justify-content-center">
                                    <div class="col-10 col-lg-6">
                                        <div class="content">
                                            <div class="avatar">
                                                <img :src="'/assets/images/profile.png'" alt="user image">
                                            </div>
                                            <div class="text">
                                                The invoice system has revolutionized how I manage my billing. Highly recommended!
                                            </div>
                                            <div class="name">John Doe</div>
                                            <div class="role text-muted">Small Business Owner</div>
                                        </div>
                                    </div>
                                </div>
                            </swiper-slide>
                            <swiper-slide>
                                <div class="row justify-content-center">
                                    <div class="col-10 col-lg-6">
                                        <div class="content">
                                            <div class="avatar">
                                                <img :src="'/assets/images/woman.png'" alt="user image">
                                            </div>
                                            <div class="text">
                                                Efficient, user-friendly, and packed with features. A game-changer for my business.
                                            </div>
                                            <div class="name">Kate Winslet</div>
                                            <div class="role text-muted">Freelancer</div>
                                        </div>
                                    </div>
                                </div>
                            </swiper-slide>
                        </swiper>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Home Testimonials Content -->

    </div>
    <!--------------------->
    <!-- End Home Testimonials -->
    <!--------------------->

</template>
<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';

// import required modules
import { Navigation, Autoplay } from 'swiper/modules';

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    methods: {},

    mounted() {},
    setup() {
        return {
            modules: [ Navigation, Autoplay],
        };
    },
}
</script>
