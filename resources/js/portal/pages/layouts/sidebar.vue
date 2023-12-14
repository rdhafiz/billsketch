<template>
    <div class="side_bar_content border rounded-15">

        <div class="logo">
            <router-link :to="{name: 'Dashboard'}">
                <img :src="'/assets/images/bilify.png'" alt="">
            </router-link>
            <a href="javascript:void(0)" class="close-btn" @click="hideMenu">
                <img :src="'/assets/images/close.png'" alt="close button">
            </a>
        </div>
        <a href="javascript:void(0)" class="hamburger" @click="showMenu">
            <img :src="'/assets/images/more.png'" alt="hamburger button">
        </a>

        <div class="main_menu">
            <ul>
                <li><router-link :to="{name: 'Dashboard'}"><img :src="'/assets/images/portal/menu/dashboard.png'" alt=""> <span>Dashboard</span></router-link></li>
                <li><router-link :to="{name: 'Clients'}"><img :src="'/assets/images/portal/menu/clients.png'" alt=""> <span>Clients</span></router-link></li>
                <li><router-link :to="{name: 'Payees'}"><img :src="'/assets/images/portal/menu/payee.png'" alt=""> <span>Payees</span></router-link></li>
                <li><router-link :to="{name: 'Categories'}"><img :src="'/assets/images/portal/menu/category.png'" alt=""> <span>Categories</span></router-link></li>
                <li><router-link :to="{name: 'Invoices'}"><img :src="'/assets/images/portal/menu/invoice.png'" alt="">  <span>Invoices</span></router-link></li>
                <li><router-link :to="{name: 'RecurringInvoices'}"><img :src="'/assets/images/portal/menu/recurring.png'" alt=""> <span>Recurring Invoices</span></router-link></li>
            </ul>
        </div>

    </div>
</template>
<script>
export default {
    components: {},
    methods: {
      showMenu(){
          document.querySelector('.side_bar').classList.add('active');
          document.querySelector('.main_body').classList.add('active');
      },
      hideMenu(){
          document.querySelector('.side_bar').classList.remove('active');
          document.querySelector('.main_body').classList.remove('active');
      },
        outsideClick(){
          document.addEventListener('click', (e) => {
              const sideBar = document.querySelector('.side_bar');
              if(!sideBar.contains(e.target)){
                  this.hideMenu();
              }
          })
        }
    },
    mounted() {
        this.outsideClick();
    },
    created() {
    }
}
</script>
