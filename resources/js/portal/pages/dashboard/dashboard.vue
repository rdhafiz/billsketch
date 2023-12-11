<template>

    <div class="row res dashboard">

        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Invoices</strong></h3>
                    <h2 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.total_invoice}}</strong></h2>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Pending Invoices</strong></h3>
                    <h2 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.pending_invoice}}</strong></h2>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Paid Invoices</strong></h3>
                    <h2 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.paid_invoice}}</strong></h2>
                </div>
            </div>
        </div>
        <div class="cl col-lg-6 col-xxl-3">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 200px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Total Recurring Invoices</strong></h3>
                    <h2 class="m-0 mt-3 p-0 text-secondary"><strong>{{invoicesCount.recurring_invoice}}</strong></h2>
                </div>
            </div>
        </div>

        <div class="cl col-lg-12">
            <div class="card mb-4">
                <div class="card-body overflow-auto" style="height: 450px">
                    <div class="row align-items-center">
                        <div class="col-sm-8 col-xl-10">
                            <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by month (Current Year)</strong></h3>
                        </div>
                        <div class="col-sm-4 col-xl-2 mt-3 mt-sm-0">
                            <div class="text-end ps-0 ps-sm-3">
                                <select name="year" class="form-select w-100" v-model="year" @change="getDashboardChartByMonth">
                                    <option :value="each" v-for="(each) in years">{{each}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <Line id="chart-month" :data="chartMonthData" :options="chartOptions" />
                    </div>
                </div>
            </div>
        </div>
        <div class="cl col-xl-6">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 400px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by status</strong></h3>
                    <div class="mt-3">
                        <Bar
                            id="chart-status"
                            :options="chartOptions"
                            :data="chartStatusData"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="cl col-xl-6">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center justify-content-center flex-column" style="height: 400px">
                    <h3 class="m-0 p-0 text-secondary"><strong>Chart - Invoices by category</strong></h3>
                    <div class="mt-3">
                        <Bar
                            id="chart-category"
                            :options="chartOptions"
                            :data="chartCategoryData"
                        />
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>
<script>
import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import { Bar } from 'vue-chartjs'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale ,
        PointElement,
        LineElement,} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement,)

export default {
    components: {Bar, Line},
    data(){
        return {
            invoicesCount: {
                paid_invoice: 0,
                pending_invoice: 0,
                recurring_invoice: 0,
                total_invoice: 0,
            },
            chartMonthParam: {
                startDate: ''
            },
            chartMonthData: {
                labels: [],
                datasets: [
                    {
                        label: 'Month',
                        backgroundColor: '#44bd32',
                        data: []
                    }
                ]
            },
            chartStatusData: {
                labels: [],
                datasets: [
                        {
                            label: 'Status',
                            backgroundColor: '#44bd32',
                            data: []
                        }
                    ]
            },
            chartCategoryData: {
                labels: [],
                datasets: [
                        {
                            label: 'Category',
                            backgroundColor: '#44bd32',
                            data: []
                        }
                    ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            years: ['2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033', '2034', '2035', '2036', '2037', '2038', '2039', '2040', '2041', '2042', '2043'],
            year: new Date().getFullYear().toString()
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
            apiService.POST(apiRoutes.dashboardMonth, {year: this.year},(res) => {
                if (res.status === 200) {
                    const {label, data} = res;
                    this.chartMonthData = {
                        labels: label,
                        datasets: [{label: 'Month', backgroundColor: '#44bd32',data}]
                    }
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Chart By Status*/
        getDashboardChartByStatus() {
            apiService.POST(apiRoutes.dashboardStatus, null,(res) => {
                if (res.status === 200) {
                    const {label, data} = res;
                    this.chartStatusData = {
                        labels: label,
                        datasets: [{label: 'Status', backgroundColor: '#44bd32',data}]
                    }
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Chart By Category*/
        getDashboardChartByCategory() {
            apiService.POST(apiRoutes.dashboardCategory,null,(res) => {
                if (res.status === 200) {
                    const {label, data} = res;
                    this.chartCategoryData = {
                        labels: label,
                        datasets: [{label: 'Category', backgroundColor: '#44bd32',data}]
                    }
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
        const myChart = document.getElementById('chart-status')
        window.addEventListener('beforeprint', () => {
            myChart.resize(600, 600);
        });
        window.addEventListener('afterprint', () => {
            myChart.resize();
        });

    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
