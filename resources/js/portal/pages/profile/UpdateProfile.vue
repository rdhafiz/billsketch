<template>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form @submit.prevent="UpdateProfile">
                <div class="row">
                    <div class="col-lg-12">
                        <div v-if="message" class="alert alert-success text-center">{{message}}</div>
                    </div>
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar">
                                <img :src="'/assets/images/profile.png'" height="80" width="80" alt="avatar">
                            </div>
                            <button type="button" class="btn btn-theme ms-4 w-25">Upload Photo</button>
                        </div>
                    </div>
                    <div class="col-lg-6">
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
                    <div class="col-lg-6">
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
                    <div class="col-lg-12">
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
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
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
                        <div class="col-lg-12">
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
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
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
                    <div class="col-lg-12">
                        <div class="text-end">
                            <router-link :to="{name: 'Profile'}" class="btn btn-danger w-25 me-3">Cancel</router-link>
                            <button type="submit" class="btn btn-theme w-25" v-if="loading === false">Update</button>
                            <button type="button" disabled v-if="loading === true"
                                    class="btn btn-theme w-25">
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

export default {
    components: {},
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
            loading: false
        }
    },
    methods: {
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
                    } else{
                      this.formData = res.data;
                    }
                }
            })
        },
        UpdateProfile(){
            apiService.ClearErrorHandler();
            this.loading = true;
            apiService.POST(apiRoutes.updateProfile, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    document.querySelector('#user_name').textContent = this.formData.first_name+' '+this.formData.last_name;
                    this.message  = res.message;
                    setTimeout(()=> {
                        this.message = '';
                    }, 3000)
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        }
    },
    mounted() {
        this.profile();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
