<template>
<div class="w-100">
    <div class="container-lg">
        <div class="row justify-content-center res">
            <div class="w-100">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="d-flex justify-content-between flex-column flex-lg-row align-items-xl-center">
                            <div class="h2 mb-2 mb-xl-0 page-title-view">Recurring Invoice Preview</div>
                            <div class="text-end buttons">
                                <router-link :to="{name: 'RecurringInvoices'}" class="btn btn-danger w-160">
                                    Cancel
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row mb-4">
                                            <div class="col-sm-6 col-md-5 col-xl-4">
                                                <div class="mb-3">
                                                    <div><strong>{{ invoice?.client ? 'Client' : 'Payee'}}</strong></div>
                                                    <div>{{ invoice?.client ? invoice?.client.name : invoice?.payee.name}}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Category</strong></div>
                                                    <div>{{ invoice?.category.name }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Currency</strong></div>
                                                    <div>{{ invoice?.currency }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Frequency</strong></div>
                                                    <div>{{ invoice?.frequency }}</div>
                                                </div>
                                            </div>
                                            <div class="d-none d-md-block col-md-2 col-xl-4"></div>
                                            <div class="col-sm-6 col-md-5 col-xl-4 text-sm-end">
                                                <div class="mb-3">
                                                    <div><strong>Start Date</strong></div>
                                                    <div>{{ invoice?.start_date_formatted }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>End Date</strong></div>
                                                    <div>{{ invoice?.end_date_formatted ? invoice?.end_date_formatted : 'N/A'}}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Due Days</strong></div>
                                                    <div>{{ invoice?.due_days}}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Status</strong></div>
                                                    <div>{{ invoice?.status == 0 ? 'On-Hold' : 'Active'}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div class="table-data table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th style="min-width: 160px;">{{ invoice?.invoice_item_headings_formatted.description }}</th>
                                                    <th class="text-center" style="min-width: 160px;">{{ invoice?.invoice_item_headings_formatted.frequency }}</th>
                                                    <th class="text-center" style="min-width: 160px;">{{ invoice?.invoice_item_headings_formatted.value }}</th>
                                                    <th class="text-center" style="min-width: 160px;">Total</th>
                                                </tr>
                                                </thead>
                                                <tbody v-if="invoice?.invoice_items.length > 0">
                                                <tr v-for="(each, index) in invoice?.invoice_items">
                                                    <td>{{ each.description }}</td>
                                                    <td class="text-end">{{ each.unit_frequency }}</td>
                                                    <td class="text-end">{{ each.unit_value }}</td>
                                                    <td class="text-end">{{ each.total }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="preview-footer">
                                            <div class="notes">
                                                <div><strong>Notes</strong></div>
                                                <div>{{invoice?.note}}</div>
                                            </div>
                                            <div class="total">
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Subtotal: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 120px;">{{ subTotal }}</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Tax: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 120px;">{{ invoice?.tax }}</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"
                                                     v-if="invoice?.client_id"><strong
                                                    class="text-start text-sm-end">Invoice Discount: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                          style="min-width: 120px;">{{invoice?.discount}}</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"
                                                     v-if="invoice?.payee_id"><strong
                                                    class="text-start text-sm-end">Invoice Bonus: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                          style="min-width: 120px;">{{invoice?.bonus}}</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Total: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 120px;">{{ total }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>

import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import {createToaster} from "@meforma/vue-toaster";
import swal from "sweetalert";

const toaster = createToaster({
    position: 'top-right'
});

export default {
    components: {createToaster},
    data() {
        return {
            invoice: null,
            tableData: [],
            shareParam: {
                id: '',
                email: '',
                subject: '',
                message: ''
            },
            shareLoading: false,
            qrLoading: false,
            downloadLoading: false,
            statusParam: '',
            statusLoading: false,
            publicView: false,
            subTotal: 0,
            total: 0
        }
    },
    methods: {
        /*Get Invoice Data*/
        getInvoice(id){
            apiService.POST(apiRoutes.recurringInvoiceSingle, {id}, (res) => {
                if (res.status === 200) {
                    this.invoice = res.data;
                    this.calculateSubtotal();
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },
        getInvoicePublic(id){
            apiService.POST(apiRoutes.invoicePublicView, {invoice_code: id}, (res) => {
                if (res.status === 200) {
                    this.invoice = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },


        /*calculate total*/
        calculateTotal(){
            if(this.invoice.tax == ''){
                this.tax = 0;
            }else{
                this.tax = (this.subTotal * (parseFloat(this.invoice.tax) / 100)).toFixed(2);
            }
            this.total = (parseFloat(this.subTotal) - this.tax).toFixed(2);
            this.total = isNaN(this.total) ? 0 : this.total
            return this.total;
        },

        /*calculate subtotal*/
        calculateSubtotal(){
            this.subTotal = this.invoice.invoice_items.reduce((prev, current)=> (prev + parseFloat(current.total)),0).toFixed(2);

            this.calculateTotal();
        },
    },
    mounted() {
        if(this.$route.params){
            this.getInvoice(this.$route.params.id);
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
