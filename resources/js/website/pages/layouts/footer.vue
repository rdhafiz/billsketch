<template>
    <div class="footer">
        <div class="container-lg">
            <div class="row">

                <div class="col-lg-5">
                    <div class="w-100 short-links mb-3">
                        <div class="w-100 pt-4">
                            <div class="w-100">
                                <a class="w-100" href="/" style="filter: brightness(0)">
                                    <img :src="'/assets/images/mediprospects.png'" alt="">
                                </a>
                            </div>
                            <div class="w-100 mt-3">
                                <div class="w-100 p-2">
                                    <i style="font-size: 20px" class="fa fa-home"></i> &nbsp; 5-7 High Street, London, E13 0AD
                                </div>
                                <div class="w-100 p-2">
                                    <a class="text-dark text-decoration-none" href="mailto:hello@mediprospects.ai"><i class="fa fa-envelope"></i> &nbsp; hello@mediprospects.ai</a>
                                </div>
                            </div>
                            <div class="w-100 mt-2">
                                <ul class="list-unstyled social-links">
                                    <li class="text-start"><a class="fs-6" target="_blank" href=""><i class="fa fa-fw fa-facebook-square"></i></a></li>
                                    <li class="text-start"><a class="fs-6" target="_blank" href=""><i class="fa fa-fw fa-linkedin-square"></i></a></li>
                                    <li class="text-start"><a class="fs-6" target="_blank" href=""><i class="fa fa-fw fa-youtube-square"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="w-100 short-links mb-3">
                        <h4 class="mb-3 text-start"><strong>Quick Links</strong></h4>
                        <ul class="list-unstyled">
                            <li class="text-start"><router-link :to="{name: 'Faq'}">FAQ</router-link></li>
                            <li class="text-start"><router-link :to="{name: 'ContactUs'}">Contact Us</router-link></li>
                            <li class="text-start"><router-link :to="{name: 'HowItWorks'}">How It Works</router-link></li>
                            <li class="text-start"><router-link :to="{name: 'PrivacyPolicy'}">Privacy Policy</router-link></li>
                            <li class="text-start"><router-link :to="{name: 'TermsOfService'}">Terms of Service</router-link></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 our_partners certifications">
                    <div class="w-100 short-links mb-3">
                        <h4 class="mb-3 text-start"><strong>Social Media</strong></h4>
                        <ul class="list-unstyled social-links">
                            <li class="text-start"><a class="fs-6" target="_blank" href="https://www.facebook.com/mediprospectsai/"><i class="fa fa-fw fa-facebook-square"></i></a></li>
                            <li class="text-start"><a class="fs-6" target="_blank" href="https://bd.linkedin.com/company/mediprospectsai"><i class="fa fa-fw fa-linkedin-square"></i></a></li>
                            <li class="text-start"><a class="fs-6" target="_blank" href="https://www.youtube.com/channel/UCo-dLSOaxzo-RK5eObvuTqA"><i class="fa fa-fw fa-youtube-square"></i></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {}
</script>
