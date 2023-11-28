<template>

    <div class="row justify-content-center res">
        <div class="col-xxl-8">
            <form @submit.prevent="UpdateProfile" id="profile" enctype="multipart/form-data">
                <div class="row">
                    <div class="cl col-lg-12">
                        <div v-if="message" class="alert alert-success text-center">{{message}}</div>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="d-flex align-items-center mb-4 avatar">
                            <img :src="avatar" height="80" width="80" alt="avatar" class="rounded-circle">
                            <input type="file" id="uploadAvatar" class="form-control-custom d-none" name="avatar"
                                   @change="AttachFile($event)" accept="image/*"
                                   autocomplete="new-file_path">
                            <label for="uploadAvatar" class="btn btn-theme ms-4 w-160">Upload Photo</label>
                        </div>
                    </div>
                    <div class="cl col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input type="text" class="form-control form-control-lg"
                                           id="first_name" name="first_name" placeholder="First Name"
                                           v-model="formData.first_name">
                                    <div class="error-report text-danger "></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input type="text" class="form-control form-control-lg"
                                           id="last_name" name="last_name" placeholder="Last Name"
                                           v-model="formData.last_name">
                                    <div class="error-report text-danger "></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control form-control-lg"
                                           id="email" name="email" placeholder="Email"
                                           v-model="formData.email">
                                    <div class="error-report text-danger "></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <template v-if="formData.company_info">
                        <div class="cl col-lg-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="company_name">Company Name</label>
                                        <input type="text" class="form-control form-control-lg"
                                               id="company_name" name="company_name" placeholder="Company Name"
                                               v-model="formData.company_info.name">
                                        <div class="error-report text-danger "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cl col-lg-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="company_size">Company Size</label>
                                        <input type="text" class="form-control form-control-lg"
                                               id="company_size" name="company_size" placeholder="Company Size"
                                               v-model="formData.company_info.size">
                                        <div class="error-report text-danger "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cl col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="company_address">Address</label>
                                        <input type="text" class="form-control form-control-lg"
                                               id="company_address" name="company_address" placeholder="Address"
                                               v-model="formData.company_info.address">
                                        <div class="error-report text-danger "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cl col-lg-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="company_city">City</label>
                                        <input type="text" class="form-control form-control-lg"
                                               id="company_city" name="company_city" placeholder="City"
                                               v-model="formData.company_info.city">
                                        <div class="error-report text-danger "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cl col-lg-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="address">Country</label>
                                        <input type="text" class="form-control form-control-lg"
                                               id="company_country" name="company_country" placeholder="Country"
                                               v-model="formData.company_info.country">
                                        <div class="error-report text-danger "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="cl col-lg-12">
                        <div class="text-end">
                            <router-link :to="{name: 'Profile'}" class="btn btn-danger w-160 me-3">Cancel</router-link>
                            <button type="submit" class="btn btn-theme w-160" v-if="loading === false">Update</button>
                            <button type="button" disabled v-if="loading === true"
                                    class="btn btn-theme w-160">
                                <i class="fa fa-spinner spin" aria-hidden="true"></i>
                            </button>
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
                first_name: '',
                last_name: '',
                email: '',
                company_name: '',
                company_size: '',
                company_address: '',
                company_city: '',
                company_country: '',
            },
            message: '',
            loading: false,
            avatar: '/assets/images/profile.png'
        }
    },
    methods: {
        /*Get Profile Data*/
        profile() {
            apiService.GET(apiRoutes.profile, (res) => {
                if(res.status === 200){
                    if(res.data.company_info){
                        this.formData = {
                            ...res.data,
                            company_name: res.data.company_info.name,
                            company_size: res.data.company_info.size,
                            company_address: res.data.company_info.address,
                            company_city: res.data.company_info.city,
                            company_country: res.data.company_info.country,
                        };
                        this.avatar = res.data.avatar_path ?? this.avatar;
                    } else{
                      this.formData = res.data;
                    }
                }
            })
        },

        /*Update Profile*/
        UpdateProfile(){
            apiService.ClearErrorHandler();
            this.loading = true;
            const formData = new FormData(document.getElementById('profile'));
            formData.append('user_type', this.formData.user_type);
            apiService.POST_FORMDATA(apiRoutes.updateProfile, formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    document.querySelector('#user_name').textContent = this.formData.first_name+' '+this.formData.last_name;
                    toaster.info(res.message);
                    this.$router.push({name: 'Profile'})
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Upload Avatar*/
        AttachFile: function (event) {
            let file = event.target.files[0];
            this.avatar = URL.createObjectURL(file);
        },

    },
    mounted() {
        this.profile();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
