<template>
    <Forgot_banner></Forgot_banner>

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

                                            <form class="w-100" @submit.prevent="Forgot">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email"><strong>Email</strong></label>
                                                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" v-model="formData.email">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button type="submit" class="btn btn-lg btn-success w-100" v-if="loading === false"><i class="fa fa-fw fa-send"></i> Submit</button>
                                                    <button type="button" disabled v-if="loading === true" class="btn btn-lg btn-success w-100">
                                                        <i class="fa fa-spinner spin" aria-hidden="true"></i>
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
import Forgot_banner from "./widgets/forgot_banner.vue";
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";
export default {
    components: {
        Forgot_banner
    },
    data(){
        return {
            formData: {
                email: '',
            },
            loading: false
        }
    },
    methods: {
        Forgot() {
            ApiService.ClearErrorHandler();
            this.loading = true;
            ApiService.POST(ApiRoutes.Forgot, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    this.$router.push({name: 'Reset'})
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
