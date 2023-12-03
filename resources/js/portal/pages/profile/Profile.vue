<template>

    <div class="row justify-content-center res" v-if="UserInfo">
        <div class="col-xxl-8">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center flex-column flex-sm-row mb-4">
                        <div class="avatar">
                            <img :src="avatar" height="80" width="80" alt="avatar" class="rounded-circle">
                        </div>
                        <div class="profile-buttons mt-3 mt-sm-0">
                            <router-link :to="{name: 'UpdateProfile'}" class="btn btn-theme mx-2 mx-lg-4 w-160">Edit Profile</router-link>
                            <router-link :to="{name: 'ChangePassword'}" class="btn btn-theme w-160">Change Password</router-link>
                        </div>
                    </div>
                </div>
                <div class="cl col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-2">First Name</div>
                            <div class="h5">
                                <strong>{{ UserInfo.first_name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cl col-lg-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-2">Last Name</div>
                            <div class="h5">
                                <strong>{{ UserInfo.last_name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cl col-lg-12">
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
                    <div class="cl col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Company Name</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.name }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Company Size</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.size }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">Address</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.address }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="mb-2">City</div>
                                <div class="h5">
                                    <strong>{{ UserInfo.company_info.city }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-6">
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
            UserInfo: null,
            avatar: '/assets/images/profile.png',
        }
    },
    methods: {
        profile() {
            apiService.GET(apiRoutes.profile, (res) => {
                if(res.status === 200){
                    this.UserInfo = res.data;
                    this.avatar = res.data.avatar_path ?? this.avatar;
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
