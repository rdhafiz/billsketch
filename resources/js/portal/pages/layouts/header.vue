<template>
    <div class="header_content border rounded-15 px-3">

        <div class="dropdown float-end">
            <a class="btn btn-theme rounded-10 shadow-none px-3 py-2" data-bs-toggle="dropdown" aria-expanded="false">
                Ridwanul Hafiz <i class="fa fa-fw fa-chevron-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-3 shadow rounded-10" style="width: 250px;">
                <li><a href="#" class="dropdown-item px-3 py-2 rounded-10 text-center">Profile</a></li>
                <li><a class="btn btn-danger w-100 rounded-10 mt-2" href="#">Logout</a></li>
            </ul>
        </div>

    </div>
</template>
<script>
import ApiService from "../../services/ApiService";
import ApiRoutes from "../../services/ApiRoutes";
export default {
    components: {},
    methods: {
        profile() {
            ApiService.GET(ApiRoutes.profile, (res) => {
                console.log(res);
            })
        }
    },
    created() {
        this.profile();
    }
}
</script>
