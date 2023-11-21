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
                                            <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">
                                            Shahi Tower, New Market <br> Jashore, Khulna, Bangladesh
                                        </span>
                                    </div>
                                    <a class="phone mb-4">
                                        <span class="icon me-3">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">01700000000</span>
                                    </a>
                                    <a class="email mb-4">
                                        <span class="icon me-3">
                                            <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">redishketch@gmail.com</span>
                                    </a>
                                    <div class="map pt-4">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3667.8152213900376!2d89.21354308577313!3d23.17694315658789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ff115afe36f205%3A0x6914a3d55e81646a!2sJashore%2C%20High%20Court%20Mor!5e0!3m2!1sen!2sbd!4v1700546882238!5m2!1sen!2sbd"
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
