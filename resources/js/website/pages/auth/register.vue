<template>
    <div class="page_banner">

        <!-- Start Home Banner Video -->
        <div class="img-bg">
            <img class="w-100" :src="'/assets/images/banner/home-banner.jpg'" alt="" loading="lazy">
        </div>
        <!-- End Home Banner Video -->

        <!-- Start Home Banner Content -->
        <div class="auth_content">
            <div class="w-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow-lg">
                                <div class="card-body px-3 px-sm-5 py-4">
                                    <div class="row align-items-center">
                                        <div class="col-lg-7 d-none d-lg-block">
                                            <div class="auth_bg">
                                                <img :src="'/assets/images/auth_bg.jpg'" alt="auth images">
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="login_form">
                                                <div class="h2 text-center fw-bold mb-4">Sign Up</div>
                                                <div class="login-options">
                                                    <a href="" class="facebook mb-3">
                                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                                        <span>Sign Up with Facebook</span>
                                                    </a>
                                                    <a href="" class="google mb-4">
                                                        <i class="fa fa-google-plus" aria-hidden="true"></i>
                                                        <span>Sign Up with Google</span>
                                                    </a>
                                                </div>
                                                <form action="">
                                                   <div class="d-flex justify-content-between">
                                                       <div class="form-group mb-3 w-48">
                                                           <label for="first_name">First Name</label>
                                                           <input type="text" class="form-control mt-2" id="first_name" name="first_name">
                                                       </div>
                                                       <div class="form-group mb-3 w-48">
                                                           <label for="last_name">Last Name</label>
                                                           <input type="text" class="form-control mt-2" id="last_name" name="last_name">
                                                       </div>
                                                   </div>
                                                    <div class="form-group mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control mt-2" id="email" name="email">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="phone">Phone</label>
                                                        <input type="text" class="form-control mt-2" id="phone" name="phone">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="text">Address</label>
                                                        <input type="text" class="form-control mt-2" id="address" name="address">
                                                    </div>
                                                    <button class="btn btn-dark auth-btn w-100">Sign Up</button>
                                                    <div class="page_switch text-center mt-4">
                                                            Already have an account?
                                                            <router-link :to="{name: 'Login'}">Sign In</router-link>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
