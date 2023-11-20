<template>

    <!-- Start Header Section -->
    <div class="header" :class="header_theme">
        <nav class="navbar navbar-expand-lg p-0">
            <div class="container-lg">

                <!-- Start Website Name -->
                <a class="navbar-brand" href="/">
                    <img :src="'/assets/images/mediprospects.png'" alt="">
                </a>
                <!-- End Website Name -->

                <!-- Start Mobile Menu Bar -->
                <div class="mobile_menu_bar">
                    <svg class="ham hamRotate ham1" :class="{'active': openMenu === true}" viewBox="0 0 100 100" width="80" @click="toggleMobileMenu">
                        <path
                            class="line top"
                            d="m 30,33 h 40 c 0,0 9.044436,-0.654587 9.044436,-8.508902 0,-7.854315 -8.024349,-11.958003 -14.89975,-10.85914 -6.875401,1.098863 -13.637059,4.171617 -13.637059,16.368042 v 40"/>
                        <path
                            class="line middle"
                            d="m 30,50 h 40"/>
                        <path
                            class="line bottom"
                            d="m 30,67 h 40 c 12.796276,0 15.357889,-11.717785 15.357889,-26.851538 0,-15.133752 -4.786586,-27.274118 -16.667516,-27.274118 -11.88093,0 -18.499247,6.994427 -18.435284,17.125656 l 0.252538,40"/>
                    </svg>
                </div>
                <!-- Start Mobile Menu Bar -->

                <!-- Start Main Menu -->
                <ul class="navbar-nav animate__animated ms-auto mb-2 mb-lg-0" :class="{'navbar-nav-show': openMenu === true}">
                    <li class="nav-item">
                        <router-link :to="{name: 'HowItWorks'}" class="nav-link">How It Works</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'ContactUs'}" class="nav-link">Contact Us</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{name: 'Faq'}" class="nav-link">FAQ</router-link>
                    </li>
<!--                    <li class="nav-item">-->
<!--                        <router-link :to="{name: 'AboutUs'}" class="nav-link">About Us</router-link>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <router-link :to="{name: 'ContactUs'}" class="nav-link">Contact Us</router-link>-->
<!--                    </li>-->
                </ul>
                <!-- End Main Menu -->

            </div>
        </nav>
    </div>
    <!-- End Header Section -->


</template>
<script>
export default {
    data() {
        return {
            header_theme: '',
            openMenu: false,
            screenWidth: window.innerWidth,
            mobileDropPreview: false
        }
    },
    computed: {
        currentPage() {
            return this.$route.name;
        }
    },
    watch:{
        $route (to, from){
            document.body.className = '';
            this.openMenu = false;
        }
    },
    methods: {
        initHeaderScroll() {
            let THIS = this;
            window.onscroll = function () {
                if (window.pageYOffset > 50) {
                    THIS.header_theme = 'header-white shadow-lg';
                } else {
                    THIS.header_theme = '';
                }
            }
        },
        toggleMobileMenu() {
            this.openMenu = !this.openMenu;
            this.mobileDropPreview = false;
            if (this.openMenu === true) {
                document.body.className = 'overflow-hidden';
            } else {
                document.body.className = '';
            }
        },
        isServiceActive() {
            const servicePages = ['Services', 'ITSupport', 'ITSolution', 'CloudServices', 'CyberSecurity', 'SoftwareDevelopment', 'WebDevelopment', 'MobileAppDevelopment', 'AIDataScience', 'GDPRConsultancy', 'ISOConsultancy', 'ITConsultancy'];
            if(servicePages.indexOf(this.currentPage) > -1){
                return false;
            } else {
                return false;
            }
        },
        initMobileDrop() {
            this.screenWidth = window.innerWidth;
            if(this.screenWidth <= 991){
                this.mobileDropPreview = false;
            } else {
                this.mobileDropPreview = true;
            }
        }
    },
    created() {
        this.initHeaderScroll();
        this.initMobileDrop();
    },
    mounted() {
        const THIS = this;
        window.addEventListener("resize",  function (){
            THIS.initMobileDrop();
        });
    }
}
</script>
