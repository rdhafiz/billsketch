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
<!--                                            <div class="form-group mb-3">-->
<!--                                                <button class="btn btn-lg btn-primary w-100" @click="initLogin"-->
<!--                                                        v-if="facebookLoading == false"><i-->
<!--                                                    class="fa fa-fw fa-facebook"></i> Sign In with Facebook-->
<!--                                                </button>-->
<!--                                                <button type="button" disabled v-if="facebookLoading === true"-->
<!--                                                        class="btn btn-lg btn-primary w-100">-->
<!--                                                    <i class="fa fa-spinner spin" aria-hidden="true"></i>-->
<!--                                                </button>-->
<!--                                            </div>-->
<!--                                            <div class="form-group mb-3">-->
<!--                                                <button class="btn btn-lg btn-danger w-100"><i-->
<!--                                                    class="fa fa-fw fa-google-plus"></i> Sign In with Google-->
<!--                                                </button>-->
<!--                                            </div>-->
<!--                                            <div class="form-group my-4 text-center">-->
<!--                                                -&#45;&#45; OR -&#45;&#45;-->
<!--                                            </div>-->
                                            <h1>Sign In</h1>
                                            <p>Explore and Manage your invoices</p>

                                            <form class="w-100 mt-5" @submit.prevent="Login">
                                                <div v-if="message" class="alert alert-success">{{message}}</div>
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
                                                        <i class="fa fa-spinner spin" aria-hidden="true"></i>
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
            message: ''
        }
    },
    methods: {
        Login() {
            ApiService.ClearErrorHandler();
            this.loading = true;
            ApiService.POST(ApiRoutes.Login, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    ApiService.setAuthentication(res.access_token, res.user, (auth) => {
                        if (auth) {
                            window.location.href = '/portal';
                        }
                    })
                } else if (res.status === 300) {
                    this.$router.push({name: 'Verify', state: {message: res.message, email: this.formData.email}})
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
                    ApiService.setAuthentication(res.access_token, res.user, (auth) => {
                        if (auth) {
                            window.location.href = '/portal';
                        }
                    })
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

    mounted() {
        if(window.history.state){
            this.message = window.history.state.message;
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
