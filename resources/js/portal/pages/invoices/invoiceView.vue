<template>
    <div class="row justify-content-center res" :class="{'mt-5' : publicView}">
        <div class="col-lg-10 col-xl-10 col-xxl-7">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <div class="d-flex justify-content-between flex-column flex-xl-row align-items-xl-center" :class="{'mw-1450': isRecurring}">
                        <div class="h2 mb-2 mb-xl-0 page-title-view">{{isRecurring ? 'Recurring Invoice' : 'Invoice'}} Preview</div>
                        <div class="text-end buttons" v-if="!publicView">
                            <router-link :to="{name: 'Invoices'}" class="btn btn-danger w-160 me-3" v-if="!isRecurring">
                                Cancel
                            </router-link>
                            <router-link :to="{name: 'RecurringInvoices'}" class="btn btn-danger w-160 me-2" v-if="isRecurring">
                                Cancel
                            </router-link>
                            <button class="btn btn-theme w-160" @click="updateStatus">Change Status</button>
                        </div>
                        <div v-else class="text-end buttons">
                            <button class="btn btn-theme me-2" @click="generatePdf" v-if="!downloadLoading">
                                <i class="fa fa-arrow-down" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-theme me-2" disabled v-if="downloadLoading">
                                <i class="fa fa-spinner spin" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mb-4" v-if="!publicView">
                    <div class="text-end">
                        <button class="btn btn-theme me-2" @click="generatePdf" v-if="!downloadLoading">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-theme me-2" disabled v-if="downloadLoading">
                            <i class="fa fa-spinner spin" aria-hidden="true"></i>
                        </button>
                        <template v-if="invoice?.qrcode_path == null">
                            <button class="btn btn-primary me-2" @click="generateQRCode" v-if="!qrLoading">
                                <i class="fa fa-qrcode" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-primary me-2" disabled v-if="qrLoading">
                                <i class="fa fa-spinner spin" aria-hidden="true"></i>
                            </button>
                        </template>
                        <button class="btn btn-secondary" @click="shareModalOpen">
                            <i class="fa fa-share" aria-hidden="true"></i>
                        </button>
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
                                        <div class="col-sm-6 col-md-5 col-xl-4 text-sm-end">
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
                                                <div>{{ invoice?.invoice_status_name }}</div>
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
                                <div class="col-lg-12 mt-3" v-if="invoice?.qrcode_path">
                                    <div class="qr-img text-end">
                                        <img :src="invoice?.qrcode_path" height="100" width="100" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-3" id="exampleModalLabel">Share Invoice</h1>
                    </div>
                    <form @submit.prevent="shareInvoice">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email" v-model="shareParam.email">
                                <div class="error-report text-danger"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="subject">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" v-model="shareParam.subject">
                            </div>
                            <div class="form-group mb-3">
                                <label for="message">Message</label>
                                <textarea name="message" id="message" class="form-control" placeholder="Message" v-model="shareParam.message" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="shareModalClose" style="width: 120px;">Close</button>
                            <button type="submit" class="btn btn-theme" v-if="shareLoading === false" style="width: 120px;">
                                Send
                            </button>
                            <button type="button" disabled v-if="shareLoading === true"
                                    class="btn btn-theme" style="width: 120px;">
                                <i class="fa fa-spinner spin" aria-hidden="true"></i>
                            </button>
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
            isRecurring: false,
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
            publicView: false
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

        /*Update Status*/
        updateStatus(){
            this.statusLoading = true;
            swal({
                title: "Are you sure?",
                text: `Are you sure that you want to change status?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    console.log(1)
                    if (willDelete) {
                        const param = {
                            id: this.invoice?.id,
                            status_id: this.invoice?.invoice_status
                        }
                        apiService.POST(apiRoutes.invoiceStatusUpdate, param, (res) => {
                            if (res.status === 200) {
                                swal('Updated!', 'Invoice status has been updated successfully.', "success");
                                this.getInvoice(this.invoice?.id);
                            }
                        })
                    }
                });
        },

        /*generate pdf*/
        generatePdf(){
            this.downloadLoading = true;
            apiService.DOWNLOAD(apiRoutes.invoiceDownload, {id: this.invoice?.id}, '' , (res) => {
                this.downloadLoading = false;
                // Create a Blob object from the PDF data
                const blob = new Blob([res], { type: 'application/pdf' });

                // Create a URL for the Blob object
                const url = URL.createObjectURL(blob);

                // Create a link element
                const a = document.createElement('a');
                a.href = url;
                a.download = 'invoice'; // Set the filename for the downloaded file

                // Append the link to the body
                document.body.appendChild(a);

                // Click the link programmatically to trigger the download
                a.click();

                // Clean up - remove the link and revoke the URL
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            })
        },

        /*generate qrcode*/
        generateQRCode(){
            apiService.ClearErrorHandler();
            this.qrLoading = true;
            apiService.POST(apiRoutes.invoiceQRCode, {id: this.invoice?.id}, (res) => {
                this.qrLoading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.getInvoice(this.invoice?.id);
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*share invoice*/
        shareInvoice(){
            apiService.ClearErrorHandler();
            this.shareLoading = true;
            apiService.POST(apiRoutes.invoiceShare, this.shareParam, (res) => {
                this.shareLoading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.shareModalClose();
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*share modal open*/
        shareModalOpen(){
            let myModal = new bootstrap.Modal(document.getElementById('shareModal'));
            myModal.show();
        },

        /*share modal open*/
        shareModalClose(){
            let myModalEl = document.getElementById('shareModal');
            let modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        }
    },
    mounted() {
        if(this.$route.params){
            if(window.location.pathname.includes('share')){
                this.publicView = true;
                this.getInvoicePublic(this.$route.params.id);
            } else {
                this.getInvoice(this.$route.params.id);
                this.shareParam = {
                    ...this.shareParam,
                    id: this.$route.params.id
                }
            }
        }

        if(window.location.pathname.includes('recurring')){
            this.isRecurring = true;
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
