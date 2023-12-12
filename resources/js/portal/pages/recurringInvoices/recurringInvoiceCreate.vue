<template>
    <div class="w-100">
        <div class="container-lg">
            <div class="row justify-content-center res">
                <div class="col-12">
                    <form @submit.prevent="recurringInvoiceCreate">
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="d-flex justify-content-between flex-column flex-lg-row align-items-lg-center">
                                    <div class="h1 mb-2 mb-lg-0 page-title">Recurring Invoice Create</div>
                                    <div class="text-end">
                                        <router-link :to="{name: 'RecurringInvoices'}" class="btn btn-danger w-160 me-3">Cancel</router-link>
                                        <button type="submit" class="btn btn-theme w-160" v-if="loading === false"> Save </button>
                                        <button type="button" disabled v-if="loading === true" class="btn btn-theme w-160">
                                            <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="cl col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row mb-4">
                                                    <div class="col-lg-6 col-xxl-4">
                                                        <div class="form-group mb-3">
                                                            <div class="invoice-type-btn">
                                                                <div class="btn btn-light me-2 border">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                               name="invoice_type"
                                                                               id="inlineCheckbox1" value="1"
                                                                               v-model="invoice_type" @change="changeInvoiceType">
                                                                        <label class="form-check-label"
                                                                               for="inlineCheckbox1">Bill To</label>
                                                                    </div>
                                                                </div>
                                                                <div class="btn btn-light me-2 border">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                               name="invoice_type"
                                                                               id="inlineCheckbox2" value="2"
                                                                               v-model="invoice_type" @change="changeInvoiceType">
                                                                        <label class="form-check-label"
                                                                               for="inlineCheckbox2">Bill Pay</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group bg-light border p-3">
                                                            <div class="form-group mb-3" v-if="invoice_type == 1">
                                                                <label for="client"><strong>Client</strong></label>
                                                                <select name="client_id" id="client" class="form-select"
                                                                        v-model="formData.client_id">
                                                                    <option value="" disabled>Select</option>
                                                                    <template v-if="clients.length > 0">
                                                                        <option :value="each.id" v-for="(each) in clients">
                                                                            {{ each.name }}
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                            <div class="form-group mb-3" v-if="invoice_type == 2">
                                                                <label for="employee"><strong>Employee</strong></label>
                                                                <select name="employee_id" id="employee" class="form-select"
                                                                        v-model="formData.employee_id">
                                                                    <option value="" disabled>Select</option>
                                                                    <template v-if="employees.length > 0">
                                                                        <option :value="each.id" v-for="(each) in employees">
                                                                            {{ each.name }}
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="category"><strong>Category</strong></label>
                                                                <select name="category_id" id="category" class="form-select"
                                                                        v-model="formData.category_id">
                                                                    <option value="" disabled>Select</option>
                                                                    <template v-if="categories.length > 0">
                                                                        <option :value="each.id" v-for="(each) in categories">
                                                                            {{ each.name }}
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="currency"><strong>Currency</strong></label>
                                                                <select name="currency" id="currency"
                                                                        class="form-select" v-model="formData.currency">
                                                                    <option value="BDT">BDT</option>
                                                                    <option value="USA">USA</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="frequency"><strong>Frequency</strong></label>
                                                                <select name="frequency" id="frequency"
                                                                        class="form-select" v-model="formData.frequency">
                                                                    <option value="" disabled>Select</option>
                                                                    <option :value="each.value" v-for="(each) in frequency">{{ each.name }}</option>
                                                                </select>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-lg-none d-xxl-block col-xxl-4">&nbsp;</div>
                                                    <div class="col-lg-6 col-xxl-4">
                                                        <div class="form-group bg-light border p-3">
                                                            <div class="form-group mb-3">
                                                                <label for="start_date"><strong>Start Date</strong></label>
                                                                <flat-pickr v-model="formData.start_date"
                                                                            :config="config"
                                                                            class="form-control"
                                                                            name="start_date"
                                                                            id="start_date"
                                                                            placeholder="Select date"/>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="end_date"><strong>End Date</strong></label>
                                                                <flat-pickr v-model="formData.end_date"
                                                                            :config="config"
                                                                            class="form-control"
                                                                            name="end_date"
                                                                            id="end_date"
                                                                            placeholder="Select date"/>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="due_days"><strong>Due Days</strong></label>
                                                                <input type="text" class="form-control" id="due_days" name="due_days" placeholder="Due Days" v-model="formData.due_days" @keypress="checkNumber($event)">
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="status"><strong>Status</strong></label>
                                                                <select name="status" id="status"
                                                                        class="form-select" v-model="formData.status">
                                                                    <option value="0">On Hold</option>
                                                                    <option value="1">Active</option>
                                                                </select>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 my-3">
                                                <div class="table-data table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th style="min-width: 160px;" class="p-0">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none" v-model="formData.invoice_item_headings.description">
                                                                        <option value="Services">Services</option>
                                                                        <option value="Products">Products</option>
                                                                        <option value="Description">Description</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th style="min-width: 160px;" class="p-0">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none text-center" v-model="formData.invoice_item_headings.frequency">
                                                                        <option value="Hours">Hours</option>
                                                                        <option value="Days">Days</option>
                                                                        <option value="Months">Months</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th style="min-width: 160px;" class="p-0">
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
                                                                           v-bind:name="'invoice_items.' + index + '.unit_value'"
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
                                                            <textarea name="note" rows="3" class="form-control" v-model="formData.note"></textarea>
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
            invoice_type: 1,
            formData: {
                client_id: '',
                employee_id: '',
                category_id: '',
                start_date: '',
                end_date: '',
                due_days: '',
                frequency: '',
                status: 0,
                sub_total: '',
                tax: 0,
                note: '',
                currency: 'BDT',
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
            },
            config: {
                altFormat: 'M j, Y',
                altInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: true
            },
            employees: [],
            clients: [],
            categories: [],
            status: [],
            frequency: [],
            subTotal: 0,
            tax: 0,
            total: 0
        }
    },
    methods: {

        /*Get Employees*/
        getEmployees() {
            apiService.POST(apiRoutes.employeeList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.employees = res.data;
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

        /*Get Frequency*/
        getFrequency() {
            apiService.GET(apiRoutes.recurringInvoiceFrequency, (res) => {
                this.frequency = res;
            })
        },

        /*Create Recurring Invoice*/
        recurringInvoiceCreate() {
            apiService.ClearErrorHandler();
            this.loading = true;
            if(this.invoice_type === 1){
                this.formData.discount = 0;
            } else {
                this.formData.bonus = 0;
            }
            apiService.POST(apiRoutes.recurringInvoiceCreate, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.$router.push({name: 'RecurringInvoices'})
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*change invoice type*/
        changeInvoiceType(){
            this.formData.invoice_no = '';
            this.formData.client_id = '';
            this.formData.employee_id = '';
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
        calculateTotal(){
            if(this.formData.tax == ''){
                this.tax = 0;
            }else{
                this.tax = this.subTotal * (parseFloat(this.formData.tax) / 100);
            }
            this.total = (parseFloat(this.subTotal) - this.tax).toFixed(2);
            this.total = isNaN(this.total) ? 0 : this.total
            return this.total;
        },

        /*calculate subtotal*/
        calculateSubtotal(){
            this.subTotal = this.formData.invoice_items.reduce((prev, current)=> (prev + parseFloat(current.total)),0).toFixed(2);
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
        this.getEmployees();
        this.getClients();
        this.getCategories();
        this.getFrequency();
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
