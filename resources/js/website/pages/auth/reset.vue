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

                                            <form class="w-100" @submit.prevent="Reset">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email">
                                                        <strong>Email</strong>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg" id="email"
                                                           name="email" placeholder="Email"
                                                           v-model="formData.email">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="reset_code">
                                                        <strong>Reset Code</strong>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg" id="reset_code"
                                                           name="reset_code" placeholder="Reset Code"
                                                           v-model="formData.reset_code">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="password">
                                                        <strong>New Password</strong>
                                                    </label>
                                                    <input type="password" class="form-control form-control-lg" id="password"
                                                           name="password" placeholder="Password" v-model="formData.password">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="password_confirmation">
                                                        <strong>Confirm Password</strong>
                                                    </label>
                                                    <input type="password" class="form-control form-control-lg" id="password_confirmation"
                                                           name="password_confirmation" placeholder="Confirm Password"
                                                           v-model="formData.password_confirmation">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-lg btn-success w-100" v-if="loading === false"><i class="fa fa-fw fa-send"></i> Update</button>
                                                    <button type="button" disabled v-if="loading === true" class="btn btn-lg btn-success w-100">
                                                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2"
                                                             fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1 la-spin">
                                                            <line x1="12" y1="2" x2="12" y2="6"></line>
                                                            <line x1="12" y1="18" x2="12" y2="22"></line>
                                                            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                                            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                                            <line x1="2" y1="12" x2="6" y2="12"></line>
                                                            <line x1="18" y1="12" x2="22" y2="12"></line>
                                                            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                                            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                                        </svg>
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
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";

export default {
    components: {
        Reset_banner
    },
    data(){
        return {
            formData: {
                email: '',
                reset_code: '',
                password: '',
                password_confirmation: ''
            },
            loading: false
        }
    },
    methods: {
        Reset() {
            ApiService.ClearErrorHandler();
            this.loading = true;
            ApiService.POST(ApiRoutes.Reset, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    this.formData = {
                        email: '',
                        reset_code: '',
                        password: '',
                        password_confirmation: ''
                    }
                } else {
                    ApiService.ErrorHandler(res.errors)
                }
            })
        },
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
