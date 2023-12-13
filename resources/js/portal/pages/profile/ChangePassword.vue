<template>

    <div class="row justify-content-center res">
        <div class="col-xxl-6">
            <form @submit.prevent="ChangePassword">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="fs-24">Change Password</h1>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="old_password">Current Password</label>
                                            <input type="password" class="form-control form-control-lg"
                                                   id="old_password" name="old_password" placeholder="Current Password"
                                                   v-model="formData.old_password">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="password">New Password</label>
                                            <input type="password" class="form-control form-control-lg"
                                                   id="password" name="password" placeholder="New Password"
                                                   v-model="formData.password">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                                            <input type="password" class="form-control form-control-lg"
                                                   id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                                                   v-model="formData.password_confirmation">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <router-link :to="{name: 'Profile'}" class="btn btn-outline-secondary px-3 me-2">Cancel</router-link>
                                <button type="submit" class="btn btn-theme px-3" v-if="loading === false">Update</button>
                                <button type="button" disabled v-if="loading === true"
                                        class="btn btn-theme px-3" style="width: 87px;">
                                    <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</template>
<script>

import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";
import {createToaster} from "@meforma/vue-toaster";
const toaster = createToaster({
    position: 'top-right'
});


export default {
    components: {createToaster},
    data() {
        return {
            formData: {
                old_password: '',
                password: '',
                password_confirmation: '',
            },
            loading: false
        }
    },
    methods: {
        ChangePassword(){
            apiService.ClearErrorHandler();
            this.loading = true;
            apiService.POST(apiRoutes.changePassword, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    this.formData = {
                        old_password: '',
                        new_password: '',
                        password_confirmation: '',
                    }
                    toaster.info(res.message);
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        }
    },
    mounted() {},
    created() {
        window.scroll(0, 0);
    }
}
</script>
