<template>

    <div class="row justify-content-center res">
        <div class="col-xxl-8">
            <form @submit.prevent="updateEmployee" id="employeeEdit" enctype="multipart/form-data">
                <div class="row">
                    <div class="cl col-lg-12">
                        <div class="d-flex align-items-center mb-4 avatar">
                            <img :src="avatar" height="80" width="80" alt="avatar" class="rounded-circle">
                            <input type="file" id="uploadAvatar" class="form-control-custom d-none" name="avatar"
                                   @change="AttachFile($event)" accept="image/*"
                                   autocomplete="new-file_path">
                            <label for="uploadAvatar" class="btn btn-theme ms-4 w-160">Upload Photo</label>
                        </div>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="name" name="name" placeholder="Name"
                                                           v-model="formData.name">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
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
                                    <div class="col-lg-6">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email">Phone</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="phone" name="phone" placeholder="Phone"
                                                           autocomplete="new-phone" @keypress="checkNumber($event)" v-model="formData.phone">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email">Address</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="address" name="address" placeholder="Address"
                                                           v-model="formData.address">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email">City</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="city" name="city" placeholder="city"
                                                           v-model="formData.city">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email">Country</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="country" name="country" placeholder="Country"
                                                           v-model="formData.country">
                                                    <div class="error-report text-danger "></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <router-link :to="{name: 'Employees'}" class="btn btn-danger w-160 me-3">Cancel</router-link>
                                            <button type="submit" class="btn btn-theme w-160" v-if="loading === false">Update</button>
                                            <button type="button" disabled v-if="loading === true"
                                                    class="btn btn-theme w-160">
                                                <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                id: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                country: '',
            },
            avatar: '/assets/images/profile.png',
            loading: false
        }
    },
    methods: {
        /*Get Employee Data*/
        getSingle(id){
            apiService.POST(apiRoutes.employeeSingle, {id} , (res) => {
                if (res.status === 200) {
                    this.formData = res.data;
                    this.avatar = res.data.avatar_path ?? this.avatar;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Update Employee*/
        updateEmployee(){
            apiService.ClearErrorHandler();
            this.loading = true;
            const formData = new FormData(document.getElementById('employeeEdit'));
            formData.append('id', this.formData.id);
            apiService.POST_FORMDATA(apiRoutes.employeeUpdate, formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.$router.push({name: 'Employees'})
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
    mounted() {
        if(this.$route.params){
            const {id} = this.$route.params;
            this.getSingle(id)
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
