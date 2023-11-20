<template>
    <div class="page_banner">

        <!-- Start Home Banner Video -->
        <div class="img-bg">
            <img class="w-100" :src="'/assets/images/banner/home-banner.jpg'" alt="" loading="lazy">
        </div>
        <!-- End Home Banner Video -->

        <!-- Start Home Banner Content -->
        <div class="page_banner_content">
            <div class="w-100">
                <div class="container">

                    <div class="col-lg-8 offset-lg-2">
                        <div class="card shadow-lg">
                            <div class="card-body p-5">
                                <div class="title h3 text-center fw-bold mb-4">Forgot</div>
                                <div class="forgot_form">
                                    <form action="">
                                        <div class="form-group mb-4">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control mt-2" id="email" name="email">
                                        </div>
                                        <button class="btn btn-dark w-25">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Home Banner Content -->

    </div>
</template>
<script>

export default {
    components: {
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
