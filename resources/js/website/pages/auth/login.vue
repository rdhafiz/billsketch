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
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card shadow-lg">
                                <div class="card-body px-3 px-sm-5 py-5">
                                    <div class="row align-items-center">
                                        <div class="col-lg-7 d-none d-lg-block">
                                            <div class="auth_bg">
                                                <img src="/assets/images/auth_bg.jpg" alt="auth images">
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="login_form">
                                                <div class="title h2 fw-bold mb-5">Sign In</div>
                                                <div class="login-options">
                                                    <a href="" class="facebook mb-3">
                                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                                        <span>Sign In with Facebook</span>
                                                    </a>
                                                    <a href="" class="google mb-4">
                                                        <i class="fa fa-google-plus" aria-hidden="true"></i>
                                                        <span>Sign In with Google</span>
                                                    </a>
                                                </div>
                                                <form action="">
                                                    <div class="form-group mb-3">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control mt-2" id="email" name="email">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="password">Password</label>
                                                        <input type="password" class="form-control mt-2" id="password" name="password">
                                                    </div>
                                                    <button class="btn btn-dark auth-btn w-100">Sign In</button>
                                                    <div class="page_switch text-center">
                                                        <router-link :to="{name: 'Forgot'}" class="mt-4 mb-2">Forgot Password?</router-link>
                                                        <div>
                                                            Don't have an account?
                                                            <router-link :to="{name: 'Register'}">Sign Up</router-link>
                                                        </div>
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
