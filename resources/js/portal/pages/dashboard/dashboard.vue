<template>

    <div class="row res">

        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Invoices</strong></h3>
                    <h1 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.total_invoice}}</strong></h1>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Pending Invoices</strong></h3>
                    <h1 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.pending_invoice}}</strong></h1>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Paid Invoices</strong></h3>
                    <h1 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.paid_invoice}}</strong></h1>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Recurring Invoices</strong></h3>
                    <h1 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.recurring_invoice}}</strong></h1>
                </div>
            </div>
        </div>

        <div class="cl col-lg-12">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 450px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by month (Current Year)</strong></h3>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 400px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by status</strong></h3>
                    <div>
                        <Bar
                            id="chart-status"
                            :options="chartOptions"
                            :data="chartStatusData"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 400px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by category</strong></h3>
                </div>
            </div>
        </div>


    </div>

</template>
<script>
import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    components: {Bar},
    data(){
        return {
            invoicesCount: {
                paid_invoice: 0,
                pending_invoice: 0,
                recurring_invoice: 0,
                total_invoice: 0,
            },
            chartStatusData: {
                labels: [],
                datasets: [ { data: [] } ]
            },
            chartOptions: {
                responsive: true
            }
        }
    },
    methods: {
        /*Get Dashboard Count*/
        getDashboardCount() {
            apiService.GET(apiRoutes.dashboardCount,(res) => {
                if (res.status === 200) {
                    this.invoicesCount = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Chart By Month*/
        getDashboardChartByMonth() {
            apiService.POST(apiRoutes.dashboardMonth,(res) => {
                if (res.status === 200) {
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Chart By Status*/
        getDashboardChartByStatus() {
            console.log(1)
            apiService.POST(apiRoutes.dashboardStatus, null,(res) => {
                console.log(2)
                if (res.status === 200) {
                    const {label, data} = res;
                    console.log(res,label, data)
                    this.chartStatusData = {
                        labels: label,
                        datasets: [{data}]
                    }
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Chart By Category*/
        getDashboardChartByCategory() {
            apiService.POST(apiRoutes.dashboardCategory,(res) => {
                if (res.status === 200) {
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },


    },
    mounted() {
        this.getDashboardCount();
        this.getDashboardChartByMonth();
        this.getDashboardChartByStatus();
        this.getDashboardChartByCategory();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
