<template>
    <Login_banner></Login_banner>

    <div class="floating-section w-100">
        <div class="container-lg">
            <div class="floating-section-content w-100">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card shadow-lg border border-2 border-primary">
                            <div class="card-body px-3 px-sm-5 py-5">
                                <div class="row">
                                    <div class="col-xl-7 d-none d-xl-block">
                                        <div class="auth_bg">
                                            <img :src="'/assets/images/auth_bg.jpg'" alt="auth images">
                                        </div>
                                    </div>
                                    <div class="col-xl-5">
                                        <div class="w-100">


                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-primary w-100"><i class="fa fa-fw fa-facebook"></i> Sign In with Facebook</button>
                                            </div>
                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-danger w-100"><i class="fa fa-fw fa-google-plus"></i> Sign In with Google</button>
                                            </div>
                                            <div class="form-group my-4 text-center">
                                                --- OR ---
                                            </div>

                                            <form class="w-100" action="">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email"><strong>Email</strong></label>
                                                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label d-flex justify-content-between align-items-center" for="password">
                                                        <strong>Password</strong>
                                                        <router-link :to="{name: 'Forgot'}"><small>Forgot Password?</small></router-link>
                                                    </label>
                                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button class="btn btn-lg btn-success w-100"><i class="fa fa-fw fa-send"></i> Sign In</button>
                                                </div>
                                                <div class="form-group">
                                                    <div class="w-100 text-center">
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


</template>
<script>
import Login_banner from "./widgets/login_banner.vue";
export default {
    components: {
        Login_banner
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
