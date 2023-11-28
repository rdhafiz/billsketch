<template>
    <h1>Clients</h1>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <input type="text" class="form-control form-control-lg" placeholder="Search" v-model="param.keyword" @keyup="searchData">
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
                    <tbody v-if="tableData.length > 0 && loading === false">
                        <tr v-for="(each, index) in tableData">
                            <td>
                                <div class="avatar">
                                    <img :src="'/assets/images/profile.png'" height="40" width="40" class="rounded-circle" alt="avatar">
                                </div>
                            </td>
                            <td>{{each.name}}</td>
                            <td>{{each.email}}</td>
                            <td>{{ each.phone }}</td>
                            <td class="text-end" style="min-width: 120px;">
                                <router-link :to="{name: 'ClientEdit', params: {name: each.name}}" class="btn btn-theme">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </router-link>
                                <button class="btn btn-danger ms-2" @click="deleteClient">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="tableData.length === 0 && loading === false">
                        <tr>
                            <td colspan="5">
                                <div class="alert alert-warning text-center mb-0">No data found</div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="loading === true">
                        <tr>
                            <td colspan="5">
                                <div class="alert alert-primary text-center mb-0">Loading...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import swal from 'sweetalert';
import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

export default {
    components: {},
    data () {
      return {
          param: {
              keyword: '',
              limit: 1
          },
          tableData: [],
          loading: false,
          searchTimeout: null,


          /*Pagination Variables*/
          total_pages: 0,
          current_page: 0,
          last_page: 0,
          buttons: [],
      }
    },
    methods: {
        /*Search Clients*/
        searchData() {
            clearTimeout(this.searchTimeout)
            this.searchTimeout = setTimeout(() => {
                this.getClients()
            }, 800)
        },

        /*Get Clients*/
        getClients(){
            this.loading = true;
            apiService.POST(apiRoutes.clientList, this.param, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    this.tableData = res.data.data;
                    this.last_page = res.data.last_page
                    this.total_pages = res.data.total < res.data.per_page ? 1 : Math.ceil((res.data.total / res.data.per_page))
                    this.current_page = res.data.current_page;
                    this.buttons = [...Array(this.total_pages).keys()].map(i => i + 1);
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },
        deleteClient(id){
          swal({
              title: "Are you sure?",
              text: "Are you sure that you want to delete this client?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
              .then(willDelete => {
                  console.log(1)
                  if (willDelete) {
                      apiService.POST(apiRoutes.clientDelete, {id} , (res) => {
                          if (res.status === 200) {
                              swal("Deleted!", "Client has been deleted!", "success");
                              this.getClients();
                          } else {
                              swal("Error!",res.errors?.id[0], "error");
                          }
                      })
                  }
              });
      }
    },
    mounted() {
        this.getClients();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
