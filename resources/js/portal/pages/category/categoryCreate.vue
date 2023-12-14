<template>
    <div class="row justify-content-center res">
        <div class="col-xxl-6">
            <form @submit.prevent="categoryCreate" id="categoryCreate" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="fs-24">New Category</h1>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card">
                            <div class="card-body p-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-4 avatar">
                                            <div class="w-100">
                                                <img :src="icon" height="130" width="130" alt="icon" class="rounded-circle">
                                            </div>
                                            <div class="w-100">
                                                <input type="file" id="uploadIcon" class="form-control-custom d-none" name="icon"
                                                       @change="AttachFile($event)" accept="image/*"
                                                       autocomplete="new-file_path">
                                            </div>
                                            <div class="w-100 mt-2">
                                                <label for="uploadIcon" class="btn btn-sm btn-theme py-1 px-3 rounded-pill"><i class="fa fa-fw fa-upload"></i> Upload Photo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   id="name" name="name" placeholder="Name">
                                            <div class="error-report text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label" for="color">Color</label>
                                            <input type="color" class="form-control form-control-lg"
                                                   id="color" name="color" placeholder="color">
                                            <div class="error-report text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <router-link :to="{name: 'Categories'}" class="btn btn-outline-secondary px-3 me-2">Cancel</router-link>
                                <button type="submit" class="btn btn-theme px-3" v-if="loading === false">Submit</button>
                                <button type="button" disabled v-if="loading === true"
                                        class="btn btn-theme px-3" style="width: 86px;">
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
            loading: false,
            icon: '/assets/images/categories.png',
        }
    },
    methods: {
        /*Create Category*/
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
