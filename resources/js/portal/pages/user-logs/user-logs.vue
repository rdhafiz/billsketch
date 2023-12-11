<template>
    <h1>User Logs</h1>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-6 col-lg-4 col-xxl-3 mb-3 mb-lg-0">
                    <input type="text" class="form-control" placeholder="Search" v-model="param.keyword"
                           @keyup="searchData">
                </div>
                <div class="col-sm-6 col-lg-4 col-xxl-2 mb-3 mb-lg-0">
                    <select name="status" class="form-select" v-model="param.log_type" @change="getUserLogs">
                        <option value="" disabled>Select</option>
                        <template v-if="logTypes.length > 0">
                            <option :value="each.value" v-for="(each, index) in logTypes">{{each.name}}</option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="table-data table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="min-width: 200px;">Log Type</th>
                        <th style="min-width: 200px;">Browser</th>
                        <th style="min-width: 200px;">Device</th>
                        <th style="min-width: 120px;">IP</th>
                        <th style="min-width: 200px;">Message</th>
                        <th>Created at</th>
                    </tr>
                    </thead>
                    <tbody v-if="tableData.length > 0 && loading === false">
                    <tr v-for="(each, index) in tableData">
                        <td>{{ each.log_type }}</td>
                        <td>{{ each.browser }}</td>
                        <td>{{ each.device }}</td>
                        <td>{{ each.ip_address }}</td>
                        <td>{{ each.message }}</td>
                        <td>{{ each.created_at_formatted }}</td>
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
                <div class="d-flex justify-content-center overflow-auto" v-if="tableData.length > 0 && loading === false && last_page > 1" style="min-width: 400px;">
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
                log_type: '',
            },
            tableData: [],
            loading: false,
            searchTimeout: null,


            /*Pagination Variables*/
            total_pages: 0,
            current_page: 0,
            last_page: 0,
            buttons: [],

            logTypes: []
        }
    },
    methods: {
        prevPage() {
            if (this.current_page > 1) {
                this.current_page = this.current_page - 1;
                this.getUserLogs()
            }
        },
        nextPage() {
            if (this.current_page < this.total_pages) {
                this.current_page = this.current_page + 1;
                this.getUserLogs()
            }
        },
        pageChange(page) {
            this.current_page = page;
            this.getUserLogs();
        },

        /*Search User Logs*/
        searchData() {
            clearTimeout(this.searchTimeout)
            this.searchTimeout = setTimeout(() => {
                this.getUserLogs()
            }, 800)
        },

        /*Get User Logs*/
        getUserLogs() {
            this.loading = true;
            this.param.page = this.current_page;
            apiService.POST(apiRoutes.userLogsList, this.param, (res) => {
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

        /*Get Log Types*/
        getLogTypes() {
            apiService.GET(apiRoutes.userLogsTypes, (res) => {
                this.logTypes = res;
            })
        }
    },
    mounted() {
        this.getUserLogs();
        this.getLogTypes();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
