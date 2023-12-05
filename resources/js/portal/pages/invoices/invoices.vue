<template>

    <h1>Invoices</h1>
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
                    <router-link :to="{name: 'InvoiceCreate'}" class="btn btn-theme" style="width: 120px;">Create
                    </router-link>
                </div>
            </div>
            <div class="table-data table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="min-width: 120px;">Invoice</th>
                        <th style="min-width: 180px;">Name</th>
                        <th style="min-width: 180px;">Invoice Date</th>
                        <th style="min-width: 180px;">Invoice Status</th>
                        <th style="min-width: 180px;">Invoice Total</th>
                        <th style="min-width: 220px;"></th>
                    </tr>
                    </thead>
                    <tbody v-if="tableData.length > 0 && loading === false">
                    <tr v-for="(each, index) in tableData">
                        <td>{{ each.invoice_number }}</td>
                        <td v-if="each.client"><i class="fa fa-fw fa-arrow-down text-success"></i> {{ each.client.name }}</td>
                        <td v-if="each.employee"><i class="fa fa-fw fa-arrow-up text-warning"></i> {{ each.employee.name }}</td>
                        <td>{{ each.invoice_date_formatted ? each.invoice_date_formatted : 'N/A' }}</td>
                        <td>
                            {{ each.invoice_status == 1 && 'Draft' || each.invoice_status == 2 && 'Pending' || each.invoice_status == 3 && 'Processing' || each.invoice_status == 4 && 'Partially paid' || each.invoice_status == 5 && 'Paid' || each.invoice_status == 6 && 'Overdue' || 'Canceled' }}
                        </td>
                        <td>{{ each.total }}</td>
                        <td class="text-end">
                            <router-link class="btn btn-warning text-white"
                                         :to="{name: 'InvoiceView', params: {id: each.id}}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </router-link>
                            <router-link :to="{name: 'InvoiceEdit', params: {id: each.id}}" class="btn btn-theme ms-2">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </router-link>
                            <button class="btn btn-secondary ms-2" @click="updateInvoiceStatus(each.id)">
                                <i class="fa fa-archive" aria-hidden="true" v-if="!param.list_type"></i>
                                <i class="fa fa-refresh" aria-hidden="true" v-if="param.list_type"></i>
                            </button>
                            <button class="btn btn-danger ms-2" @click="deleteInvoice(each.id)">
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
            </div>

            <!--  pagination start -->
            <div class="d-flex justify-content-center overflow-auto"
                 v-if="tableData.length > 0 && loading === false && last_page > 1" style="min-width: 400px;">
                <nav aria-label="...">
                    <ul class="pagination">
                        <li class="page-item" :class="{'disabled': this.current_page === 1}">
                            <a href="javascript:void(0)" class="page-link" @click="prevPage()">Previous</a>
                        </li>
                        <template v-if="buttons.length <= 6">
                            <li class="page-item" :class="{'active': current_page == page}" aria-current="page"
                                v-for="(page, index) in buttons">
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
                            <a class="page-link" href="#" @click="nextPage()">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!--  pagination end -->
        </div>
    </div>

</template>
<script>
import swal from "sweetalert";
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
                this.getInvoices()
            }
        },
        nextPage() {
            if (this.current_page < this.total_pages) {
                this.current_page = this.current_page + 1;
                this.getInvoices()
            }
        },
        pageChange(page) {
            this.current_page = page;
            this.getInvoices();
        },

        /*Search Invoices*/
        searchData() {
            clearTimeout(this.searchTimeout)
            this.searchTimeout = setTimeout(() => {
                this.getInvoices()
            }, 800)
        },

        /*Get Invoices*/
        getInvoices() {
            this.loading = true;
            this.param.page = this.current_page;
            apiService.POST(apiRoutes.invoiceList, this.param, (res) => {
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

        /*Change Status*/
        changeStatus() {
            this.current_page = 0;
            this.status = this.param.list_type;
            this.getInvoices();
        },

        /*Update Invoice Status*/
        updateInvoiceStatus(id) {
            swal({
                title: "Are you sure?",
                text: `Are you sure that you want to ${this.status === '' ? 'archive' : 'restore'} this invoice?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    console.log(1)
                    if (willDelete) {
                        apiService.POST(apiRoutes.invoiceStatus, {id}, (res) => {
                            if (res.status === 200) {
                                swal(`${!this.status ? 'Archived!' : 'Restored!'}`, `${!this.status ? 'Invoice has been archived!' : 'Invoice has been restored!!'}`, "success"
                                );
                                this.getInvoices();
                            } else {
                                swal("Error!", res.errors?.id[0], "error");
                            }
                        })
                    }
                });
        },

        /*Delete Invoice*/
        deleteInvoice(id) {
            swal({
                title: "Are you sure?",
                text: "If you delete a invoice, all associated data, such as invoices related to that invoice, will be permanently removed from the system.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    console.log(1)
                    if (willDelete) {
                        apiService.POST(apiRoutes.invoiceDelete, {id}, (res) => {
                            if (res.status === 200) {
                                swal("Deleted!", "Invoice has been deleted!", "success");
                                this.getInvoices();
                            } else if (res.message == 'Cannot find invoice') {
                                swal("Error!", res.message, "error");
                            } else {
                                swal("Error!", res.errors?.id[0], "error");
                            }
                        })
                    }
                });
        },
    },
    mounted() {
        this.getInvoices();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
