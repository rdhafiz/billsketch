<template>
    <div class="row justify-content-center res">
        <div class="col-lg-10 col-xl-8 col-xxl-6">
            <form>
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <div class="d-flex justify-content-between flex-column flex-sm-row align-items-lg-center">
                            <div class="h2 mb-2 mb-lg-0">Invoice Preview</div>
                            <div class="text-end">
                                <router-link :to="{name: 'Invoices'}" class="btn btn-danger w-160 me-3">
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
                                                    <div><strong>{{ invoice?.client ? 'Client' : 'Employee'}}</strong></div>
                                                    <div>{{ invoice?.client ? invoice?.client.name : invoice?.employee.name}}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Category</strong></div>
                                                    <div>{{ invoice?.category.name }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Recurring Periods</strong></div>
                                                    <div>{{ invoice?.recurring_frequency ? invoice.recurring_frequency : 'N/A' }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Currency</strong></div>
                                                    <div>{{ invoice?.currency }}</div>
                                                </div>
                                            </div>
                                            <div class="d-none d-md-block col-md-2 col-xl-4"></div>
                                            <div class="col-sm-6 col-md-5 col-xl-4 text-end">
                                                <div class="mb-3">
                                                    <div><strong>Invoice No</strong></div>
                                                    <div>{{ invoice?.invoice_number }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Invoice Date</strong></div>
                                                    <div>{{ invoice?.invoice_date_formatted }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Invoice Due Date</strong></div>
                                                    <div>{{ invoice?.invoice_due_date_formatted }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Invoice Status</strong></div>
                                                    <div>Draft</div>
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
                                                    style="min-width: 120px;">{{ invoice?.sub_total }}</span></div>
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
                                                     v-if="invoice?.employee_id"><strong
                                                    class="text-start text-sm-end">Invoice Bonus: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                          style="min-width: 120px;">{{invoice?.bonus}}</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Total: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 120px;">{{ invoice?.total }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</template>
<script>

import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

import {createToaster} from "@meforma/vue-toaster";

const toaster = createToaster({
    position: 'top-right'
});

export default {
    components: {createToaster, flatPickr},
    data() {
        return {
            invoice: null,
            tableData: []
        }
    },
    methods: {
        /*Get Invoice Data*/
        getInvoice(id){
            apiService.POST(apiRoutes.invoiceSingle, {id}, (res) => {
                if (res.status === 200) {
                    this.invoice = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        }
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
