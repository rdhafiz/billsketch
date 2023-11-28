<template>
    <h1>Clients</h1>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6 col-lg-4 col-xxl-3 mb-3 mb-lg-0">
                    <input type="text" class="form-control" placeholder="Search" v-model="param.keyword"
                           @keyup="searchData">
                </div>
                <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3 mb-lg-0">
                    <select name="status" class="form-select" v-model="param.list_type" @change="changeStatus">
                        <option value="">Active</option>
                        <option value="archive">Archive</option>
                    </select>
                </div>
                <div class="col-lg-4 col-xxl-7 text-end">
                    <router-link :to="{name: 'ClientCreate'}" class="btn btn-theme" style="width: 120px;">Create
                    </router-link>
                </div>
            </div>
            <div class="table-data table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Country</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody v-if="tableData.length > 0 && loading === false">
                    <tr v-for="(each, index) in tableData">
                        <td>
                            <div class="avatar">
                                <img :src="each.logo_path" height="40" width="40" class="rounded-circle" alt="avatar"
                                     v-if="each.logo_path">
                                <img :src="'/assets/images/profile.png'" height="40" width="40" class="rounded-circle"
                                     alt="avatar" v-if="!each.logo_path">
                            </div>
                        </td>
                        <td style="min-width: 200px;">{{ each.name }}</td>
                        <td>{{ each.email }}</td>
                        <td>{{ each.phone }}</td>
                        <td>{{ each.city }}</td>
                        <td>{{ each.country }}</td>
                        <td class="text-end" style="min-width: 180px;">
                            <router-link :to="{name: 'ClientEdit', params: {id: each.id}}" class="btn btn-theme">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </router-link>
                            <button class="btn btn-secondary ms-2" @click="updateClientStatus(each.id)">
                                <i class="fa fa-archive" aria-hidden="true" v-if="!param.list_type"></i>
                                <i class="fa fa-refresh" aria-hidden="true" v-if="param.list_type"></i>
                            </button>
                            <button class="btn btn-danger ms-2" @click="deleteClient(each.id)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tbody v-if="tableData.length === 0 && loading === false">
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-warning text-center mb-0">No data found</div>
                        </td>
                    </tr>
                    </tbody>
                    <tbody v-if="loading === true">
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-primary text-center mb-0">Loading...</div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!--  pagination start -->
                <div class="d-flex justify-content-center overflow-auto" v-if="tableData.length > 0 && loading === false" style="min-width: 400px;">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item" :class="{'disabled': this.current_page === 1}">
                                <a href="javascript:void(0)" class="page-link" @click="prevPage()">Previous</a>
                            </li>
                            <template v-if="buttons.length <= 6">
                                <li class="page-item" :class="{'active': current_page == page}" aria-current="page" v-for="(page, index) in buttons" >
                                    <a href="javascript:void(0)" class="page-link" @click="pageChange(page)">{{ page }}</a>
                                </li>
                            </template>
                            <template v-if="buttons.length > 6">
                                <li class="page-item" :class="{'active': current_page == 1}">
                                    <a class="page-link" @click="pageChange(1)"
                                       href="javascript:void(0)">1</a>
                                </li>

                                <li v-if="current_page > 3" class="page-item">
                                    <a class="page-link" @click="pageChange(current_page - 2)"
                                       href="javascript:void(0)">...</a>
                                </li>

                                <li v-if="current_page == buttons.length" class="page-item"
                                     :class="{'active': current_page == (current_page - 2)}">
                                    <a class="page-link" @click="pageChange(current_page - 2)"
                                       href="javascript:void(0)" v-text="current_page - 2"></a>
                                </li>

                                <li v-if="current_page > 2" class="page-item"
                                     :class="{'active': current_page == (current_page - 1)}">
                                    <a class="page-link" @click="pageChange(current_page - 1)"
                                       href="javascript:void(0)" v-text="current_page - 1"></a>
                                </li>

                                <li v-if="current_page != 1 && current_page != buttons.length"
                                     class="page-item active">
                                    <a class="page-link" @click="pageChange(current_page)"
                                       href="javascript:void(0)"
                                       v-text="current_page"></a>
                                </li>

                                <li v-if="current_page < buttons.length - 1" class="page-item"
                                     :class="{'active': current_page == (current_page + 1)}">
                                    <a class="page-link" @click="pageChange(current_page + 1)"
                                       href="javascript:void(0)" v-text="current_page + 1"></a>
                                </li>

                                <li v-if="current_page == 1" class="page-item"
                                     :class="{'active': current_page == (current_page + 2)}">
                                    <a class="page-link" @click="pageChange(current_page + 2)"
                                       href="javascript:void(0)" v-text="current_page + 2"></a>
                                </li>

                                <li v-if="current_page < buttons.length - 2" class="page-item">
                                    <a class="page-link" @click="pageChange(current_page + 2)"
                                       href="javascript:void(0)">...</a>
                                </li>

                                <li class="page-item"
                                     :class="{'active': current_page == buttons.length}">
                                    <a class="page-link" @click="pageChange(buttons.length)"
                                       href="javascript:void(0)" v-text="buttons.length"></a>
                                </li>
                            </template>
                            <li class="page-item" :class="{'disabled': this.current_page === this.last_page}">
                                <a class="page-link" href="#"  @click="nextPage()">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!--  pagination end -->
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
    data() {
        return {
            param: {
                keyword: '',
                list_type: ''
            },
            tableData: [],
            loading: false,
            searchTimeout: null,


            /*Pagination Variables*/
            total_pages: 0,
            current_page: 0,
            last_page: 0,
            buttons: [],

            status: ''
        }
    },
    methods: {
        prevPage() {
            if (this.current_page > 1) {
                this.current_page = this.current_page - 1;
                this.getClients()
            }
        },
        nextPage() {
            if (this.current_page < this.total_pages) {
                this.current_page = this.current_page + 1;
                this.getClients()
            }
        },
        pageChange(page) {
            this.current_page = page;
            this.getClients();
        },

        /*Search Clients*/
        searchData() {
            clearTimeout(this.searchTimeout)
            this.searchTimeout = setTimeout(() => {
                this.getClients()
            }, 800)
        },

        /*Get Clients*/
        getClients() {
            this.loading = true;
            this.param.page = this.current_page;
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

        /*Delete Client*/
        deleteClient(id) {
            swal({
                title: "Are you sure?",
                text: "If you delete a client, all associated data, such as invoices related to that client, will be permanently removed from the system.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    console.log(1)
                    if (willDelete) {
                        apiService.POST(apiRoutes.clientDelete, {id}, (res) => {
                            if (res.status === 200) {
                                swal("Deleted!", "Client has been deleted!", "success");
                                this.getClients();
                            } else {
                                swal("Error!", res.errors?.id[0], "error");
                            }
                        })
                    }
                });
        },

        /*Change Status*/
        changeStatus(){
            this.current_page = 0;
            this.status = this.param.list_type;
            this.getClients();
        },

        /*Update Client Status*/
        updateClientStatus(id) {
            swal({
                title: "Are you sure?",
                text: `Are you sure that you want to ${this.status === '' ? 'archive' : 'restore'} this client?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    console.log(1)
                    if (willDelete) {
                        apiService.POST(apiRoutes.clientStatus, {id}, (res) => {
                            if (res.status === 200) {
                                swal(`${!this.status ? 'Archived!' : 'Restored!'}`, `${!this.status ? 'Client has been archived!' : 'Client has been restored!!'}`, "success"
                                );
                                this.getClients();
                            } else {
                                swal("Error!", res.errors?.id[0], "error");
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
