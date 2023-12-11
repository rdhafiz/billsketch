<template>
    <div class="header_content border rounded-15 px-3">

        <div class="dropdown float-end" v-if="UserInfo !== null">
            <a class="btn btn-outline-secondary rounded-10 shadow-none px-3 py-2" data-bs-toggle="dropdown" aria-expanded="false">
                <span id="user_name">{{ UserInfo.first_name+' '+UserInfo.last_name }}</span> <i class="fa fa-fw fa-chevron-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-3 shadow rounded-10" style="width: 250px;">
                <li><router-link :to="{name: 'Profile'}" class="dropdown-item px-3 py-2 rounded-10 text-center">Profile</router-link></li>
                <li><router-link :to="{name: 'UserLogs'}" class="dropdown-item px-3 py-2 rounded-10 text-center">User Logs</router-link></li>
                <li><a class="btn btn-danger w-100 rounded-10 mt-2" @click="logout">Logout</a></li>
            </ul>
        </div>

    </div>
</template>
<script>
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";
import AuthService from "../../services/AuthService";
export default {
    components: {},
    data(){
        return {
            UserInfo: null
        }
    },
    methods: {
        profile() {
            ApiService.GET(ApiRoutes.profile, (res) => {
                if(res.status === 200){
                    this.UserInfo = res.data;
                }
            })
        },
        logout() {
            ApiService.GET(ApiRoutes.profile_logout, (res) => {
                AuthService.logout(() => {
                    window.location.href = '/login';
                })
            })
        }
    },
    created() {
        this.profile();
    }
}
</script>
