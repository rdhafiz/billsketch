<template>
    <div class="row justify-content-center res">
        <div class="col-xxl-6">
            <form @submit.prevent="categoryCreate" id="categoryCreate" enctype="multipart/form-data">
                <div class="row">
                    <div class="cl col-lg-12">
                        <div class="d-flex align-items-center mb-4 avatar">
                            <img :src="icon" height="80" width="80" alt="icon" class="rounded-circle">
                            <input type="file" id="uploadIcon" class="form-control-custom d-none" name="icon"
                                   @change="AttachFile($event)" accept="image/*"
                                   autocomplete="new-file_path">
                            <label for="uploadIcon" class="btn btn-theme ms-4 w-160">Upload Photo</label>
                        </div>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="name" name="name" placeholder="Name">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="name">Color</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                           id="color" name="color" placeholder="color">
                                                    <div class="error-report text-danger"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <router-link :to="{name: 'Categories'}" class="btn btn-danger w-160 me-3">Cancel</router-link>
                                            <button type="submit" class="btn btn-theme w-160" v-if="loading === false">Save</button>
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
            loading: false,
            icon: '/assets/images/circle.png'
        }
    },
    methods: {
        /*Create Client*/
        categoryCreate() {
            apiService.ClearErrorHandler();
            this.loading = true;
            const formData = new FormData(document.getElementById('categoryCreate'));
            apiService.POST_FORMDATA(apiRoutes.categoryCreate, formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.$router.push({name: 'Categories'})
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Upload Icon*/
        AttachFile: function (event) {
            let file = event.target.files[0];
            this.icon = URL.createObjectURL(file);
        },
    },
    mounted() {

    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
