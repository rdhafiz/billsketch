<template>

    <div class="row justify-content-center" v-if="UserInfo">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar">
                            <img :src="'/assets/images/profile.png'" height="80" width="80" alt="avatar">
                        </div>
                        <router-link :to="{name: 'UpdateProfile'}" class="btn btn-theme mx-4 w-25">Edit Profile</router-link>
                        <router-link :to="{name: 'ChangePassword'}" class="btn btn-theme w-25">Change Password</router-link>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-2">First Name</div>
                            <div class="h5">
                                <strong>{{ UserInfo.first_name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-2">Last Name</div>
                            <div class="h5">
                                <strong>{{ UserInfo.last_name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-2">Email</div>
                            <div class="h5">
                                <strong>{{ UserInfo.email }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <template v-if="UserInfo.company_info">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Company Name</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.name }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Company Size</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.size }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Address</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.address }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">City</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.city }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Country</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.country }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
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
            UserInfo: null
        }
    },
    methods: {
        profile() {
            apiService.GET(apiRoutes.profile, (res) => {
                if(res.status === 200){
                    this.UserInfo = res.data;
                }
            })
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
