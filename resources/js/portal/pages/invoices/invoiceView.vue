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
                                                    <div><strong>Invoice As:</strong></div>
                                                    <div>Bill To</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Client</strong></div>
                                                    <div>Noyon Ahammed Sojon</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Category</strong></div>
                                                    <div>Laptop</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Recurring Periods</strong></div>
                                                    <div>10 Days</div>
                                                </div>
                                            </div>
                                            <div class="d-none d-md-block col-md-2 col-xl-4"></div>
                                            <div class="col-sm-6 col-md-5 col-xl-4">
                                                <div class="mb-3">
                                                    <div><strong>Invoice No</strong></div>
                                                    <div>121234</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Invoice Date</strong></div>
                                                    <div>01-12-23</div>
                                                </div>
                                                <div class="mb-3">
                                                    <div><strong>Invoice Due Date</strong></div>
                                                    <div>01-12-23</div>
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
                                                    <th style="min-width: 160px;">Services</th>
                                                    <th class="text-center" style="min-width: 160px;">Hours</th>
                                                    <th class="text-center" style="min-width: 160px;">Unit Price</th>
                                                    <th class="text-center" style="min-width: 160px;">Total</th>
                                                    <th v-if="tableData.length > 1"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(each, index) in tableData">
                                                    <td>HP</td>
                                                    <td class="text-end">{{ each.hours }}</td>
                                                    <td class="text-end">{{ each.price }}</td>
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
                                                <div>In publishing and graphic design, Lorem ipsum is a placeholder
                                                    text commonly used to demonstrate the visual form of a document
                                                    or a typeface without relying on meaningful content. Lorem ipsum
                                                    may be used as a placeholder before final copy is available.
                                                </div>
                                            </div>
                                            <div class="total">
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Subtotal: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 100px;">600</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Tax: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 100px;">10%</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"
                                                     v-if="formData.invoice_type == 1"><strong
                                                    class="text-start text-sm-end">Invoice Discount: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                          style="min-width: 100px;">10%</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"
                                                     v-if="formData.invoice_type == 2"><strong
                                                    class="text-start text-sm-end">Invoice Bonus: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                          style="min-width: 100px;">10%</span></div>
                                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                                    class="text-start text-sm-end">Invoice
                                                    Total: </strong> <span
                                                    class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                                    style="min-width: 100px;">800</span></div>
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
            loading: false,
            formData: {
                invoice_type: 1
            },
            tableData: [{
                hours: 0,
                price: 0,
                total: 0
            }],
            isRecurringPeriod: false,
            date: '',
            due_date: '',
            config: {
                altFormat: 'M j, Y',
                altInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: true
            }
        }
    },
    methods: {
        changeValue(e) {
            this.isRecurringPeriod = !this.isRecurringPeriod;
        },

        /*insert table data*/
        insertData(index) {
            this.tableData.push({
                hours: 0,
                price: 0,
                total: 0,
            })
        },

        /*calculate total*/
        calculateTotal(index) {
            const total = parseInt(this.tableData[index].hours) * parseInt(this.tableData[index].price);
            this.tableData[index].total = isNaN(total) ? 0 : total;
        },

        /*number validation*/
        checkNumber(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                // @ts-ignore
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /^\d*\.?\d*$/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        },
    },
    mounted() {

    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
