<template>

    <!----------------------->
    <!-- Start Contact Form -->
    <!----------------------->
    <div class="page_contact_form">

        <!-- Start Contact Form Content -->
        <div class="page_contact_form_content">
            <div class="w-100">
                <div class="container">
                    <div class="content px-2 py-5 p-lg-0">
                        <div class="row">
                            <div class="col-lg-7 order-1 order-lg-0">
                                <div class="contact_form px-0 pb-lg-0 p-lg-5">
                                    <div class="title">Send Us a Message</div>
                                    <div class="desc">Use the form below to send us a message. We'll get back to you as
                                        soon as possible.
                                    </div>
                                    <form action="" class="mt-4">
                                        <div class="form-group mb-3">
                                            <label for="name">Your Name</label>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="subject">Subject</label>
                                            <input type="text" class="form-control" id="subject" name="subject">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="subject">Message</label>
                                            <textarea name="message" id="message" cols="30" rows="5"
                                                      class="form-control"></textarea>
                                        </div>
                                        <button class="btn contact-btn">Send</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-5  mb-4 mb-lg-0 order-0 order-lg-1">
                                <div class="contact_info px-3 px-sm-5 py-5">
                                    <div class="title mb-5">Our Contact Information</div>
                                    <div class="address mb-4 mt-5">
                                        <span class="icon me-3">
                                            <i class="fa fa-home"></i>
                                        </span>
                                        <span class="text">5-7 High Street, London, E13 0AD</span>
                                    </div>
                                    <a href="tel:01700000000" class="phone mb-4">
                                        <span class="icon me-3">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">01700000000</span>
                                    </a>
                                    <a href="mailto:hello@mediprospects.ai" class="email mb-4">
                                        <span class="icon me-3">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                        <span class="text">hello@mediprospects.ai</span>
                                    </a>
                                    <div class="map pt-4">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.0203922280502!2d0.017110276911066244!3d51.531185808965006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a7ebd4aa364f%3A0xb268e69019c23683!2s5-7%20High%20St%2C%20London%20E13%200AD%2C%20UK!5e0!3m2!1sen!2sbd!4v1700555592488!5m2!1sen!2sbd"
                                            width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Contact Form Content -->

    </div>
    <!--------------------->
    <!-- End Contact Form -->
    <!--------------------->

</template>
<script>
export default {
    methods: {}
}
</script>
