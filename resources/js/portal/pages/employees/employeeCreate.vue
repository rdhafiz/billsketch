<template>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form>
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
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="email">Phone</label>
                                    <input type="text" class="form-control form-control-lg"
                                           id="phone" name="phone" placeholder="Phone"
                                           v-model="formData.phone">
                                    <div class="error-report text-danger "></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
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
                    <div class="col-lg-12">
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
                    <div class="col-lg-12">
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
                            <router-link :to="{name: 'Clients'}" class="btn btn-danger w-25 me-3">Cancel</router-link>
                            <button type="submit" class="btn btn-theme w-25" v-if="loading === false">Save</button>
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

export default {
    components: {},
    data() {
        return {
            formData: {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                country: '',
            },
            message: '',
            loading: false
        }
    },
    methods: {
    },
    mounted() {

    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
