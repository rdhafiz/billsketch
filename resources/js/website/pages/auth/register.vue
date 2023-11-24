<template>
    <Register_banner></Register_banner>

    <div class="floating-section w-100">
        <div class="container-lg">
            <div class="floating-section-content w-100">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card auth_content shadow-lg border border-2 border-primary">
                            <div class="card-body px-3 px-sm-5 py-5">
                                <div class="row">
                                    <div class="col-xl-6 d-none d-xl-block">
                                        <div class="auth_bg">
                                            <img :src="'/assets/images/auth_bg.jpg'" alt="auth images">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="w-100">
                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-primary w-100"  @click="initLogin" v-if="facebookLoading == false"><i
                                                    class="fa fa-fw fa-facebook"></i> Sign Up with Facebook
                                                </button>
                                                <button type="button" disabled v-if="facebookLoading === true" class="btn btn-lg btn-primary w-100">
                                                    <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-danger w-100"><i
                                                    class="fa fa-fw fa-google-plus"></i> Sign Up with Google
                                                </button>
                                            </div>
                                            <div class="form-group my-4 text-center">
                                                --- OR ---
                                            </div>

                                            <form class="w-100" @submit.prevent="Register">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="first_name"><strong>First
                                                                Name</strong></label>
                                                            <input type="text" class="form-control form-control-lg"
                                                                   id="first_name" name="first_name"
                                                                   placeholder="First Name" v-model="formData.first_name">
                                                            <div class="error-report text-danger "></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="last_name"><strong>Last
                                                                Name</strong></label>
                                                            <input type="text" class="form-control form-control-lg"
                                                                   id="last_name" name="last_name"
                                                                   placeholder="Last Name" v-model="formData.last_name">
                                                            <div class="error-report text-danger "></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email"><strong>Email</strong></label>
                                                    <input type="email" class="form-control form-control-lg" id="email"
                                                           name="email" placeholder="Email" v-model="formData.email">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="password">
                                                                <strong>Password</strong>
                                                            </label>
                                                            <input type="password" autocomplete="new-password" class="form-control form-control-lg"
                                                                   id="password" name="password" placeholder="Password"
                                                                   v-model="formData.password">
                                                            <div class="error-report text-danger "></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="password_confirmation">
                                                                <strong>Confirm Password</strong>
                                                            </label>
                                                            <input type="password" class="form-control form-control-lg"
                                                                   id="password_confirmation"
                                                                   name="password_confirmation"
                                                                   placeholder="Confirm Password" v-model="formData.password_confirmation">
                                                            <div class="error-report text-danger "></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="user_type"><strong>Join as</strong></label>
                                                    <div class="w-100">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="inlineCheckbox1" value="1" @change="formData.user_type = 1" :checked="formData.user_type == 1">
                                                            <label class="form-check-label" for="inlineCheckbox1">Individual</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="inlineCheckbox2" value="2" @change="formData.user_type = 2" :checked="formData.user_type == 2">
                                                            <label class="form-check-label" for="inlineCheckbox2">Company</label>
                                                        </div>
                                                    </div>
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                                <template v-if="formData.user_type == 2">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group mb-3">
<!--                                                                <label class="form-label" for="company_name"><strong>Company Name</strong></label>-->
                                                                <input type="text" class="form-control form-control-lg"
                                                                       id="company_name" name="company_name"
                                                                       placeholder="Company Name" v-model="formData.company_name">
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group mb-3">
<!--                                                                <label class="form-label" for="company_size"><strong>Company Size</strong></label>-->
                                                                <input type="text" class="form-control form-control-lg"
                                                                       id="company_size" name="company_size" autocomplete="new-company-size"
                                                                       placeholder="Company Size" v-model="formData.company_size" @keypress="checkNumber($event)">
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group mb-3">
<!--                                                                <label class="form-label" for="company_address"><strong>Company Address</strong></label>-->
                                                                <input type="text" class="form-control form-control-lg"
                                                                       id="company_address" name="company_address"
                                                                       placeholder="Company Address" v-model="formData.company_address">
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group mb-3">
<!--                                                                <label class="form-label" for="company_city"><strong>Company City</strong></label>-->
                                                                <input type="text" class="form-control form-control-lg"
                                                                       id="company_city" name="company_city"
                                                                       placeholder="Company City" v-model="formData.company_city">
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group mb-3">
<!--                                                                <label class="form-label" for="company_country"><strong>Company Country</strong></label>-->
                                                                <input type="text" class="form-control form-control-lg"
                                                                       id="company_country" name="company_country"
                                                                       placeholder="Company Country" v-model="formData.company_country">
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-lg btn-success w-100" v-if="loading === false"><i
                                                        class="fa fa-fw fa-send"></i> Sign Up
                                                    </button>
                                                    <button type="button" disabled v-if="loading === true" class="btn btn-lg btn-success w-100">
                                                        <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                                <div class="form-group">
                                                    <div class="w-100 text-center">
                                                        Already have an account?
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
import Register_banner from "./widgets/register_banner.vue";
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";
export default {
    components: {
        Register_banner
    },
    data() {
        return {
            formData: {
                first_name: '',
                last_name: '',
                email: '',
                company_name: '',
                company_size: '',
                company_address: '',
                company_city: '',
                company_country: '',
                user_type: 1,
                password: '',
                password_confirmation: ''
            },
            loading: false,
            facebookLoading: false,
        }
    },
    methods: {
        Register() {
            ApiService.ClearErrorHandler();
            this.loading = true;
            ApiService.POST(ApiRoutes.Register, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    this.$router.push({name: 'Verify', state: {message: res.message, email: this.formData.email}})
                } else {
                    ApiService.ErrorHandler(res.errors)
                }
            })
        },

        loginFacebook(facebookInfo){
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

        initLogin: function() {
            let _this = this;
            this.facebookLoading = true
            FB.init({
                appId: '176091665574404',
                xfbml: true,
                version: 'v18.0',
                autoLogAppEvents : true,
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
        },

        /*number validation*/
        checkNumber(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                // @ts-ignore
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /^\d*\.?\d*$/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }

        },
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
