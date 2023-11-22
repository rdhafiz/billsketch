<template>
    <Reset_banner></Reset_banner>

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
                                        <div class="w-100 h-100 d-flex align-items-center">

                                            <form class="w-100" action="">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="reset_code">
                                                        <strong>Reset Code</strong>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg" id="reset_code"
                                                           name="reset_code" placeholder="Reset Code"
                                                           required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="new_password">
                                                        <strong>New Password</strong>
                                                    </label>
                                                    <input type="password" class="form-control form-control-lg" id="new_password"
                                                           name="new_password" placeholder="Password" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="password_confirmation">
                                                        <strong>Confirm Password</strong>
                                                    </label>
                                                    <input type="password" class="form-control form-control-lg" id="password_confirmation"
                                                           name="password_confirmation" placeholder="Confirm Password"
                                                           required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button class="btn btn-lg btn-success w-100"><i
                                                        class="fa fa-fw fa-send"></i> Update
                                                    </button>
                                                </div>
                                                <div class="form-group">
                                                    <div class="w-100 text-center">
                                                        Back to Login?
                                                        <router-link :to="{name: 'Login'}">Sign In</router-link>
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
import Reset_banner from "./widgets/reset_banner.vue";

export default {
    components: {
        Reset_banner
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
