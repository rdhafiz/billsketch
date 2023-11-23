<template>
    <div class="header_content border rounded-pill px-3">

        <div class="dropdown float-end">
            <a class="btn btn-theme rounded-pill shadow-none px-3 py-2" data-bs-toggle="dropdown" aria-expanded="false">
                Ridwanul Hafiz <i class="fa fa-fw fa-chevron-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-3 shadow" style="width: 250px;">
                <li><a href="/portal/profile" class="dropdown-item px-3 py-2 rounded-pill text-center">Profile</a></li>
                <li><a class="btn btn-warning w-100 rounded-pill mt-2" href="/logout">Logout</a></li>
            </ul>
        </div>

    </div>
</template>
<script>
export default {
    components: {},
    created() {
    }
}
</script>
