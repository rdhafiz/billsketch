<template>
    <div class="row justify-content-center res">
        <div class="col-12">
            <form>
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <div class="d-flex justify-content-between flex-column flex-lg-row align-items-lg-center">
                            <div class="h1 mb-2 mb-lg-0">Invoice Edit</div>
                            <div class="text-end">
                                <router-link :to="{name: 'Invoices'}" class="btn btn-danger w-160 me-3">
                                    Cancel
                                </router-link>
                                <button type="submit" class="btn btn-theme w-160" v-if="loading === false">
                                    Update
                                </button>
                                <button type="button" disabled v-if="loading === true"
                                        class="btn btn-theme w-160">
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
                                            <div class="col-lg-5 col-xl-4">
                                                <div class="form-group mb-3">
                                                    <label for="invoice_type"><strong>Invoice As:</strong></label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="invoice_type"
                                                                   id="inlineCheckbox1" value="1" v-model="formData.invoice_type">
                                                            <label class="form-check-label"
                                                                   for="inlineCheckbox1">Bill To</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="invoice_type"
                                                                   id="inlineCheckbox2" value="2" v-model="formData.invoice_type">
                                                            <label class="form-check-label"
                                                                   for="inlineCheckbox2">Bill Pay</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3" v-if="formData.invoice_type == 1">
                                                    <label for="client"><strong>Client</strong></label>
                                                    <select name="client" id="client" class="form-select">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3" v-if="formData.invoice_type == 2">
                                                    <label for="employee"><strong>Employee</strong></label>
                                                    <select name="employee" id="employee" class="form-select">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="category"><strong>Category</strong></label>
                                                    <select name="category" id="category" class="form-select">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="invoice_type"><strong>Recurring</strong></label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="recurring"
                                                                   id="recurring" @change="changeValue($event)">
                                                            <label class="form-check-label"
                                                                   for="recurring">Toggle</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3" v-if="isRecurringPeriod">
                                                    <label for="recurring_period"><strong>Recurring Periods</strong></label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" class="form-control" name="Recurring" style="width: 120px">
                                                        <strong class="ms-3">Days</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-xl-4"></div>
                                            <div class="col-lg-5 col-xl-4">
                                                <div class="form-group mb-3">
                                                    <label for="invoice_no"><strong>Invoice No</strong></label>
                                                    <input type="text" class="form-control" name="invoice_no">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="invoice_date"><strong>Invoice Date</strong></label>
                                                    <flat-pickr v-model="date"
                                                                :config="config"
                                                                class="form-control"
                                                                placeholder="Select date" />
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="invoice_due_date"><strong>Invoice Due Date</strong></label>
                                                    <flat-pickr v-model="due_date"
                                                                :config="config"
                                                                class="form-control"
                                                                placeholder="Select date" />
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="invoice_status"><strong>Invoice Status</strong></label>
                                                    <select name="invoice_status" id="invoice_status" class="form-select">
                                                        <option value="1">Draft</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">Processing</option>
                                                        <option value="4">Partially paid</option>
                                                        <option value="5">Paid</option>
                                                        <option value="6">Overdue</option>
                                                        <option value="7">Canceled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div class="table-data table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width: 160px;">
                                                            <div class="form-group">
                                                                <select name="" class="form-select">
                                                                    <option value="">Services</option>
                                                                    <option value="">Products</option>
                                                                    <option value="">Description</option>
                                                                </select>
                                                            </div>
                                                        </th>
                                                        <th style="min-width: 160px;">
                                                            <div class="form-group">
                                                                <select name="" class="form-select">
                                                                    <option value="">Hours</option>
                                                                    <option value="">Days</option>
                                                                    <option value="">Months</option>
                                                                </select>
                                                            </div>
                                                        </th>
                                                        <th style="min-width: 160px;">
                                                            <div class="form-group">
                                                                <select name="" class="form-select">
                                                                    <option value="">Unit Payment</option>
                                                                    <option value="">Unit Price</option>
                                                                </select>
                                                            </div>
                                                        </th>
                                                        <th class="text-center" style="min-width: 160px;">Total</th>
                                                        <th v-if="tableData.length > 1"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(each, index) in tableData">
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" v-model="each.hours" @keypress="checkNumber($event)" @keyup="calculateTotal(index)">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" v-model="each.price" @keypress="checkNumber($event)" @keyup="calculateTotal(index)">
                                                            </div>
                                                        </td>
                                                        <td class="text-end">{{each.total}}</td>
                                                        <td class="text-center" v-if="tableData.length > 1">
                                                            <button type="button" class="btn btn-danger" @click="tableData.splice(index, 1)">
                                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-theme" @click="insertData" style="width: 160px;">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                        </button>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row mb-4">
                                            <div class="col-12 col-xl-5 col-xxl-4">
                                                <div class="form-group">
                                                    <label for="notes"><strong>Notes</strong></label>
                                                    <textarea name="" rows="7"
                                                              class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div
                                                class="col-12 col-xl-7 col-xxl-8 d-flex flex-column align-items-start align-items-sm-end  mt-3 mt-xl-0">
                                                <div class="d-flex flex-column flex-sm-row h5 mb-3 mb-sm-2"><strong class="text-start text-sm-end">Invoice
                                                    Subtotal: </strong> <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end" style="min-width: 100px;">600</span></div>
                                                <div class="d-flex flex-column flex-sm-row h5 mb-3 mb-sm-2"><strong class="text-start text-sm-end">Invoice
                                                    Tax: </strong> <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end" style="min-width: 100px;">10%</span></div>
                                                <div class="d-flex flex-column flex-sm-row h5 mb-3 mb-sm-2" v-if="formData.invoice_type == 1"><strong
                                                    class="text-start text-sm-end">Invoice Discount: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end" style="min-width: 100px;">10%</span></div>
                                                <div class="d-flex flex-column flex-sm-row h5 mb-3 mb-sm-2" v-if="formData.invoice_type == 2"><strong
                                                    class="text-start text-sm-end">Invoice Bonus: </strong>
                                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end" style="min-width: 100px;">10%</span></div>
                                                <div class="d-flex flex-column flex-sm-row h5 mb-3 mb-sm-2"><strong class="text-start text-sm-end">Invoice
                                                    Total: </strong> <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end" style="min-width: 100px;">800</span></div>
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
        changeValue(e){
            this.isRecurringPeriod = !this.isRecurringPeriod;
        },

        /*insert table data*/
        insertData(index){
            this.tableData.push({
                hours: 0,
                price: 0,
                total: 0,
            })
        },

        /*calculate total*/
        calculateTotal(index){
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
