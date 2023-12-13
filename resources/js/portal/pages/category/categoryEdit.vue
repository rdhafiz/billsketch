<template>
    <div class="row justify-content-center res">
        <div class="col-xxl-6">
            <form @submit.prevent="categoryEdit" id="categoryEdit" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Edit Category</h1>
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
                                                   id="name" name="name" placeholder="Name" v-model="formData.name">
                                            <div class="error-report text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label" for="color">Color</label>
                                            <input type="color" class="form-control form-control-lg"
                                                   id="color" name="color" placeholder="color" v-model="formData.color">
                                            <div class="error-report text-danger"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <router-link :to="{name: 'Categories'}" class="btn btn-outline-secondary px-3 me-2">Cancel</router-link>
                                <button type="submit" class="btn btn-theme px-3" v-if="loading === false">Update</button>
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
            formData: {
                id: '',
                name: '',
                color: ''
            },
            loading: false,
            icon: '/assets/images/categories.png'
        }
    },
    methods: {
        /*Get Category Data*/
        getSingle(id){
            apiService.POST(apiRoutes.categorySingle, {id} , (res) => {
                if (res.status === 200) {
                    this.formData = res.data;
                    this.icon = res.data.icon_path ?? this.icon;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Edit Category*/
        categoryEdit() {
            apiService.ClearErrorHandler();
            this.loading = true;
            const formData = new FormData(document.getElementById('categoryEdit'));
            formData.append('id', this.formData.id);
            apiService.POST_FORMDATA(apiRoutes.categoryUpdate, formData, (res) => {
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
