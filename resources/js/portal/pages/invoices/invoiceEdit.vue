<template>
    <div class="w-100">
        <div class="container-lg">
            <div class="row justify-content-center res">
                <div class="col-12">
                    <form @submit.prevent="invoiceUpdate">
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="d-flex justify-content-between flex-column flex-lg-row align-items-lg-center">
                                    <div class="h1 mb-2 mb-lg-0 page-title">Invoice Edit</div>
                                    <div class="text-end">
                                        <router-link :to="{name: 'Invoices'}" class="btn btn-danger w-160 me-3">Cancel</router-link>
                                        <button type="submit" class="btn btn-theme w-160" v-if="loading === false"> Save Changes</button>
                                        <button type="button" disabled v-if="loading === true" class="btn btn-theme w-160">
                                            <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="cl col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <!--Invoice Info-->
                                        <div class="row mb-5">
                                            <div class="col-lg-5">
                                                <div class="w-100 d-flex align-items-center border p-5 d-inline-block rounded rounded-3 mb-2">
                                                    <div class="w-100 text-center">
                                                        <i class="fa fa-fw fa-2x fa-cloud-upload text-secondary"></i>
                                                        <span class="fs-4 text-secondary fw-bold">Upload Logo</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-5">
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control form-control-lg fs-4 fw-bold text-theme" placeholder="Invoice" v-model="formData.invoice_title">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <flat-pickr v-model="formData.invoice_date"
                                                                :config="date_config"
                                                                class="form-control"
                                                                name="invoice_date"
                                                                placeholder="Invoice Date"/>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <flat-pickr v-model="formData.invoice_due_date"
                                                                :config="due_date_config"
                                                                class="form-control"
                                                                name="invoice_due_date"
                                                                placeholder="Invoice Due Date"/>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <select name="invoice_status" class="form-select" v-model="formData.invoice_status">
                                                        <optgroup label="Invoice Status">
                                                            <option :value="each.value" v-for="(each) in status">{{ each.name }}</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Invoice From To-->
                                        <div class="row mb-5">
                                            <div class="col-lg-5">
                                                <h4 class="fs-4 fw-bold">Invoice From</h4>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" placeholder="Name" v-model="formData.invoice_from.name">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" placeholder="Email" v-model="formData.invoice_from.email">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" placeholder="Phone" v-model="formData.invoice_from.phone">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <textarea class="form-control" placeholder="Address" rows="2" v-model="formData.invoice_from.address"></textarea>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <select name="invoice_currency" class="form-select" v-model="formData.invoice_currency">
                                                        <optgroup label="Currency">
                                                            <option value="BDT">BDT</option>
                                                            <option value="USD">USD</option>
                                                            <option value="AUD">AUD</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                            </div>
                                            <div class="col-lg-5">
                                                <h4 class="fs-4 fw-bold" v-if="formData.invoice_type == 1">Bill To</h4>
                                                <h4 class="fs-4 fw-bold" v-if="formData.invoice_type == 2">Pay To</h4>

                                                <div class="form-group mb-2" v-if="formData.invoice_type == 1">
                                                    <select name="client_id" id="client" class="form-select"
                                                            v-model="formData.client_id" @change="getInvoiceNumber('client', formData.client_id)">
                                                        <option value="" disabled>Select whom you want to bill</option>
                                                        <template v-if="clients.length > 0">
                                                            <option :value="each.id" v-for="(each) in clients">
                                                                {{ each.name }}
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2" v-if="formData.invoice_type == 2">
                                                    <select name="payee_id" id="payee" class="form-select"
                                                            v-model="formData.payee_id"  @change="getInvoiceNumber('payee', formData.payee_id)">
                                                        <option value="" disabled>Select whom you want to pay</option>
                                                        <template v-if="payees.length > 0">
                                                            <option :value="each.id" v-for="(each) in payees">
                                                                {{ each.name }}
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" placeholder="Name" v-model="formData.invoice_to.name">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" placeholder="Email" v-model="formData.invoice_to.email">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" placeholder="Phone" v-model="formData.invoice_to.phone">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <textarea class="form-control" placeholder="Address" rows="2" v-model="formData.invoice_to.address"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Invoice Items-->
                                        <div class="row">
                                            <div class="col-lg-12 my-3">
                                                <div class="table-data table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="p-0" style="min-width: 160px;">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none" v-model="formData.invoice_item_headings.description">
                                                                        <option value="Services">Services</option>
                                                                        <option value="Products">Products</option>
                                                                        <option value="Description">Description</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th class="p-0" style="min-width: 160px;">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none text-center" v-model="formData.invoice_item_headings.frequency">
                                                                        <option value="Hours">Hours</option>
                                                                        <option value="Days">Days</option>
                                                                        <option value="Months">Months</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th class="p-0" style="min-width: 160px;">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none text-center" v-model="formData.invoice_item_headings.value">
                                                                        <option value="Unit Payment">Unit Payment</option>
                                                                        <option value="Unit Price">Unit Price</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th class="py-0 text-end" style="min-width: 160px;">Total</th>
                                                            <th class="py-0" v-if="formData.invoice_items.length > 1"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(each, index) in formData.invoice_items">
                                                            <td class="p-0">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control border-0 shadow-none"
                                                                           :name="'invoice_items.' + index + '.description'"
                                                                           v-model="formData.invoice_items[index]['description']">
                                                                    <div class="error-report text-danger "></div>
                                                                </div>
                                                            </td>
                                                            <td class="p-0">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control border-0 shadow-none text-center"
                                                                           :name="'invoice_items.' + index + '.unit_frequency'"
                                                                           v-model="formData.invoice_items[index]['unit_frequency']"
                                                                           @keypress="checkNumber($event)"
                                                                           @keyup="calculateInvoiceItemTotal(index)">
                                                                    <div class="error-report text-danger "></div>
                                                                </div>
                                                            </td>
                                                            <td class="p-0">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control border-0 shadow-none text-center"
                                                                           :name="'invoice_items.' + index + '.unit_value'"
                                                                           v-model="formData.invoice_items[index]['unit_value']"
                                                                           @keypress="checkNumber($event)"
                                                                           @keyup="calculateInvoiceItemTotal(index)">
                                                                    <div class="error-report text-danger "></div>
                                                                </div>
                                                            </td>
                                                            <td class="py-0 text-end">{{ each.total }}</td>
                                                            <td class="py-0 text-center" v-if="formData.invoice_items.length > 1">
                                                                <button type="button" class="btn btn-danger btn-sm px-1 py-0"
                                                                        @click="formData.invoice_items.splice(index, 1);calculateSubtotal();">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-theme btn-sm" @click="insertData">
                                                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                                </button>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="row my-3">
                                                    <div class="col-lg-5 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="notes"><strong>Notes</strong></label>
                                                            <textarea name="note" rows="7"
                                                                      class="form-control" v-model="formData.note"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-xl-4">&nbsp;</div>
                                                    <div class="col-lg-5 col-xl-4">
                                                        <table class="table">
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-end"><strong>Subtotal:</strong></td>
                                                                <td class="text-end">{{ this.subTotal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="d-flex justify-content-end align-items-center">
                                                                    <strong>Tax:</strong>
                                                                    <select class="form-select ms-2 border-0 p-0" style="width: 60px;" v-model="formData.tax" @change="checkTax">
                                                                        <option value="0">0%</option>
                                                                        <option value="5">5%</option>
                                                                        <option value="10">10%</option>
                                                                        <option value="15">15%</option>
                                                                        <option value="20">20%</option>
                                                                        <option value="25">25%</option>
                                                                    </select>
                                                                </td>
                                                                <td class="text-end">{{ this.tax }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end"><strong>Total:</strong></td>
                                                                <td class="text-end">{{ this.total }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
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
                invoice_type: 1,
                invoice_logo: "",
                invoice_title: "",
                invoice_date: '',
                invoice_due_date: '',
                invoice_status: 1,
                invoice_from: {
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                },
                client_id: '',
                payee_id: '',
                invoice_to: {
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                },
                invoice_currency: 'BDT',
                invoice_item_headings: {
                    description: 'Services',
                    frequency: 'Hours',
                    value: 'Unit Payment',
                },
                invoice_items: [{
                    description: '',
                    unit_frequency: '',
                    unit_value: '',
                    total: 0,
                }],
                tax: 0,
                discount: '',
                bonus: '',
                note: '',
            },
            date_config: {
                altFormat: "F j, Y",
                altInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: true
            },
            due_date_config: {
                altFormat: "F j, Y",
                altInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: true
            },
            payees: [],
            clients: [],
            categories: [],
            status: [],
            subTotal: 0,
            tax: 0,
            total: 0
        }
    },
    methods: {

        /*Get Payees*/
        getPayees() {
            apiService.POST(apiRoutes.payeeList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.payees = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Clients*/
        getClients() {
            apiService.POST(apiRoutes.clientList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.clients = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Categories*/
        getCategories() {
            apiService.POST(apiRoutes.categoryList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.categories = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Status*/
        getStatus() {
            apiService.GET(apiRoutes.invoiceStatusList, (res) => {
                this.status = res;
            })
        },

        /*Get Invoice Number*/
        getInvoiceNumber(type, id) {
            const param = type == 'client' ? {client_id: id} : {payee_id: id};
            apiService.POST(apiRoutes.invoiceNumber, param, (res) => {
                this.formData.invoice_no = res.invoice_number;
            })
        },

        /*Get Invoice Data*/
        getInvoice(id) {
            apiService.POST(apiRoutes.invoiceSingle, {id}, (res) => {
                if (res.status === 200) {
                    this.formData = {
                        ...res.data,
                        invoice_status: res.data.invoice_status,
                        invoice_item_headings: res.data.invoice_item_headings_formatted
                    };
                    this.subTotal = res.data.sub_total;
                    this.total = res.data.sub_total;
                    this.invoice_type = res.data.invoice_type;
                    this.calculateTotal();
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Create Invoice*/
        invoiceUpdate() {
            apiService.ClearErrorHandler();
            this.loading = true;
            apiService.POST(apiRoutes.invoiceUpdate, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.$router.push({name: 'Invoices'})
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*change invoice type*/
        changeInvoiceType() {
            this.formData.invoice_no = '';
            this.formData.client_id = '';
            this.formData.payee_id = '';
            this.invoice_number = '';
            this.formData.discount = '';
            this.formData.bonus = '';
            this.calculateTotal();
        },

        /*insert table data*/
        insertData() {
            this.formData.invoice_items.push({
                description: '',
                unit_frequency: '',
                unit_value: '',
                total: '',
            })
        },

        /* Check Tax */
        checkTax() {
            this.calculateTotal();
        },

        /*calculate invoice item total*/
        calculateInvoiceItemTotal(index) {
            const total = parseFloat(this.formData.invoice_items[index]['unit_frequency']) * parseFloat(this.formData.invoice_items[index]['unit_value']);
            this.formData.invoice_items[index]['total'] = isNaN(total) ? 0 : total.toFixed(2);
            this.calculateSubtotal();
        },

        /*calculate total*/
        calculateTotal() {
            if (this.formData.tax == '' || this.formData.tax == null) {
                this.tax = 0;
            } else {
                this.tax = this.subTotal * (parseFloat(this.formData.tax) / 100);
            }
            const discount = isNaN(parseFloat(this.formData.discount)) ? 0 : parseFloat(this.formData.discount);
            const bonus = isNaN(parseFloat(this.formData.bonus)) ? 0 : parseFloat(this.formData.bonus);
            this.total = (parseFloat(this.subTotal) - this.tax - discount + bonus).toFixed(2);
            this.total = isNaN(this.total) ? 0 : this.total
            return this.total;
        },

        /*calculate subtotal*/
        calculateSubtotal() {
            console.log(this.formData.invoice_items)
            this.subTotal = this.formData.invoice_items.reduce((prev, current) => (prev + parseFloat(current.total)), 0).toFixed(2);
            this.calculateTotal();
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
        this.getPayees();
        this.getClients();
        this.getCategories();
        this.getStatus();
        if (this.$route.params) {
            this.getInvoice(this.$route.params.id);
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
