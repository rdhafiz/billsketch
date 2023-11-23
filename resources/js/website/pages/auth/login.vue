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
                                                <button class="btn btn-lg btn-primary w-100" @click="initLogin"
                                                        v-if="facebookLoading == false"><i
                                                    class="fa fa-fw fa-facebook"></i> Sign In with Facebook
                                                </button>
                                                <button type="button" disabled v-if="facebookLoading === true"
                                                        class="btn btn-lg btn-primary w-100">
                                                    <svg viewBox="0 0 24 24" width="16" height="16"
                                                         stroke="currentColor" stroke-width="2"
                                                         fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                         class="css-i6dzq1 la-spin">
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
                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-danger w-100"><i
                                                    class="fa fa-fw fa-google-plus"></i> Sign In with Google
                                                </button>
                                            </div>
                                            <div class="form-group my-4 text-center">
                                                --- OR ---
                                            </div>

                                            <form class="w-100" @submit.prevent="Login">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email"><strong>Email</strong></label>
                                                    <input type="email" class="form-control form-control-lg" id="email"
                                                           name="email" placeholder="Email" v-model="formData.email">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label
                                                        class="form-label d-flex justify-content-between align-items-center"
                                                        for="password">
                                                        <strong>Password</strong>
                                                        <router-link :to="{name: 'Forgot'}"><small>Forgot
                                                            Password?</small></router-link>
                                                    </label>
                                                    <input type="password" class="form-control form-control-lg"
                                                           id="password" name="password" placeholder="Password"
                                                           v-model="formData.password">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-lg btn-success w-100"
                                                            v-if="loading === false"><i class="fa fa-fw fa-send"></i>
                                                        Sign In
                                                    </button>
                                                    <button type="button" disabled v-if="loading === true"
                                                            class="btn btn-lg btn-success w-100">
                                                        <svg viewBox="0 0 24 24" width="16" height="16"
                                                             stroke="currentColor" stroke-width="2"
                                                             fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                             class="css-i6dzq1 la-spin">
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
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";

export default {
    components: {
        Login_banner
    },
    data() {
        return {
            formData: {
                email: '',
                password: ''
            },
            loading: false,
            facebookLoading: false,
        }
    },
    methods: {
        Login() {
            ApiService.ClearErrorHandler();
            this.loading = true;
            ApiService.POST(ApiRoutes.Login, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    window.location.href = '/portal';
                } else if (res.status === 300) {
                    this.$router.push({name: 'Verify', meta: {message: 'sdfghjkl'}})
                } else {
                    ApiService.ErrorHandler(res.errors)
                }
            })
        },

        loginFacebook(facebookInfo) {
            ApiService.ClearErrorHandler()
            this.loading = true;
            ApiService.POST(ApiRoutes.LoginFacebook, facebookInfo, (res) => {
                this.facebookLoading = false
                this.loading = false;
                if (parseInt(res.status) === 200) {
                    this.formData.provider = res.provider;
                    window.location.href = '/portal';
                } else {
                    ApiService.ErrorHandler(res.error)
                }
            })
        },

        initLogin: function () {
            let _this = this;
            this.facebookLoading = true
            FB.init({
                appId: '176091665574404',
                xfbml: true,
                version: 'v18.0',
                autoLogAppEvents: true,
            });
            FB.login(function (response) {
                if (response.authResponse) {
                    FB.api('/me', {fields: 'id, first_name, last_name, name, email'}, function (response) {
                        let param = {
                            first_name: response.first_name,
                            last_name: response.last_name,
                            social_provider: 'facebook',
                            social_provider_id: response.id
                        }
                        _this.loginFacebook(param)
                    });
                } else {
                    _this.facebookLoading = false
                    console.log('User cancelled login or did not fully authorize.');
                }
            });
        }
    },
    created() {
        window.scroll(0, 0);

        /*facebook login*/
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'))
    }
}
</script>
