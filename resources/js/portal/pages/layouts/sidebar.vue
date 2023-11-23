<template>
    <div class="side_bar_content border">

        <div class="logo">
            <router-link :to="{name: 'Dashboard'}">
                <img :src="'/assets/images/bilify.png'" alt="">
            </router-link>
        </div>

        <div class="main_menu">
            <ul>
                <li><router-link :to="{name: 'Dashboard'}"><img :src="'/assets/images/portal/menu/dashboard.png'" alt=""> <span>Dashboard</span></router-link></li>
                <li><a href=""><img :src="'/assets/images/portal/menu/clients.png'" alt=""> <span>Clients</span></a></li>
                <li><a href=""><img :src="'/assets/images/portal/menu/employee.png'" alt=""> <span>Employees</span></a></li>
                <li><a href=""><img :src="'/assets/images/portal/menu/invoice.png'" alt="">  <span>Invoices</span></a></li>
                <li><a href=""><img :src="'/assets/images/portal/menu/recurring.png'" alt=""> <span>Recurring Invoices</span></a></li>
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
