<template>
    <h1>Clients</h1>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <input type="text" class="form-control form-control-lg" placeholder="Search">
                </div>
                <div class="col-lg-8 text-end">
                    <router-link :to="{name: 'ClientCreate'}" class="btn btn-theme" style="width: 120px;">Create</router-link>
                </div>
            </div>
            <div class="table-data table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th style="min-width: 180px;">Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="avatar">
                                    <img :src="'/assets/images/profile.png'" height="40" width="40" class="rounded-circle" alt="avatar">
                                </div>
                            </td>
                            <td>Noyon Ahammed</td>
                            <td>noyon@gmail.com</td>
                            <td>01700000000</td>
                            <td class="text-end" style="min-width: 120px;">
                                <router-link :to="{name: 'ClientEdit'}" class="btn btn-theme">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </router-link>
                                <button class="btn btn-danger ms-2" @click="deleteClient">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>
<script>
import swal from 'sweetalert';

export default {
    components: {},
    methods: {
      deleteClient(){
          swal({
              title: "Are you sure?",
              text: "Are you sure that you want to delete this client?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
              .then(willDelete => {
                  if (willDelete) {
                      swal("Deleted!", "Client has been deleted!", "success");
                  }
              });
      }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
