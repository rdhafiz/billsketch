<template>
    <Verify_banner></Verify_banner>

    <div class="floating-section w-100">
        <div class="container-lg">
            <div class="floating-section-content w-100">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card shadow-lg border border-2 border-primary">
                            <div class="card-body px-3 px-sm-5 py-5">
                                <div class="row align-items-center">
                                    <div class="col-xl-7 d-none d-xl-block">
                                        <div class="auth_bg">
                                            <img :src="'/assets/images/auth_bg.jpg'" alt="auth images">
                                        </div>
                                    </div>
                                    <div class="col-xl-5">
                                        <div class="w-100">
                                            <h1>Verify Account</h1>
                                            <p>Verify your account to avoid account getting disabled</p>
                                            <form class="w-100 mt-5" @submit.prevent="Verify">
                                                <div v-if="message" class="alert alert-success">{{message}}</div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="code">Code</label>
                                                    <input type="text" class="form-control form-control-lg" id="code" name="code" placeholder="Code"  v-model="formData.code">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button class="btn btn-lg btn-success w-100"  v-if="loading === false" ><i class="fa fa-fw fa-send"></i> Submit</button>
                                                    <button type="button" disabled v-if="loading === true" class="btn btn-lg btn-success w-100">
                                                        <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                                <div class="form-group">
                                                    <div class="w-100 text-center">
                                                        Back to Register?
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
import Verify_banner from "./widgets/verify_banner.vue";
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";
export default {
    components: {
        Verify_banner
    },
    data() {
      return {
          formData: {
              email: '',
              code: ''
          },
          message: '',
          loading: false
      }
    },
    methods: {
        Verify() {
            ApiService.ClearErrorHandler();
            this.loading = true;
            ApiService.POST(ApiRoutes.Verify, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    ApiService.setAuthentication(res.access_token, res.user, (auth) => {
                        if (auth) {
                            window.location.href = '/portal';
                        }
                    })
                } else {
                    ApiService.ErrorHandler(res.errors)
                }
            })
        },
    },
    mounted() {
        if(window.history.state){
            this.message = window.history.state.message;
            this.formData.email = window.history.state.email;
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
