<template>
    <div class="w-100">
        <div class="container-lg">
            <div class="row justify-content-center res" :class="{'mt-5' : publicView}">
                <div class="w-100">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="d-flex justify-content-between flex-column flex-xl-row align-items-xl-center">
                                <div class="h2 mb-2 mb-xl-0 page-title-view">Invoice Preview</div>
                                <div class="text-end buttons" v-if="!publicView">
                                    <router-link :to="{name: 'Invoices'}" class="btn btn-danger w-160 me-3">
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
                                <div class="card-body p-0" v-if="invoice != null">

                                    <!--Invoice Info-->
                                    <div class="w-100 p-5">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="w-100 d-flex align-items-center border p-5 d-inline-block rounded rounded-3 mb-2">
                                                    <div class="w-100 text-center">
                                                        <span class="fs-4 text-secondary fw-bold">Brand Logo</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 offset-lg-5">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td class="fs-5 fw-bold text-secondary text-start">Invoice</td>
                                                        <td class="fs-5 fw-bold text-secondary text-end">{{ invoice?.invoice_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fs-5 fw-bold text-secondary text-start">Invoice Date</td>
                                                        <td class="fs-5 fw-bold text-secondary text-end">{{ invoice?.invoice_date_formatted }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fs-5 fw-bold text-secondary text-start">Due Date</td>
                                                        <td class="fs-5 fw-bold text-secondary text-end">{{ invoice?.invoice_due_date_formatted }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fs-5 fw-bold text-secondary text-start">Status</td>
                                                        <td class="fs-5 fw-bold text-secondary text-end">{{ invoice?.invoice_status_name }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 bg-success px-5">
                                        <div class="row">
                                            <div class="col-lg-3 offset-lg-9">
                                                <h1 class="w-100 fs-1 fw-bold m-0 p-0 bg-white text-center py-2">{{ invoice?.invoice_title }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 p-5">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <h3 class="fs-3 fw-bold">Invoice From</h3>
                                                <h5 class="m-0 p-0">Equidesk Team</h5>
                                                <p class="m-0 p-0">
                                                    West Barandipara, Kotwali
                                                    Jessore-7400, Khulna, Bangladesh
                                                </p>
                                                <p class="m-0 p-0"><a href="mailto:ridwanul.hafiz@gmail.com">ridwanul.hafiz@gmail.com</a></p>
                                                <p class="m-0 p-0"><a href="tel:880 1717 863 320">880 1717 863 320</a></p>
                                            </div>
                                            <div class="col-lg-4 offset-lg-4">
                                                <h3 class="fs-3 fw-bold">Bill To</h3>
                                                <h5 class="m-0 p-0">Omar Fareda</h5>
                                                <p class="m-0 p-0">
                                                    Sydney Olympic Park
                                                </p>
                                                <p class="m-0 p-0"><a href="mailto:ridwanul.hafiz@gmail.com">omar@mybos.com</a></p>
                                                <p class="m-0 p-0"><a href="tel:880 1717 863 320">+612 8378 1096</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 p-5">
                                        <div class="table-data table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th class="bg-secondary text-white p-3" style="min-width: 160px;">{{ invoice?.invoice_item_headings_formatted.description }}</th>
                                                    <th class="bg-secondary text-white p-3 text-center" style="min-width: 160px;">{{ invoice?.invoice_item_headings_formatted.frequency }}</th>
                                                    <th class="bg-secondary text-white p-3 text-center" style="min-width: 160px;">{{ invoice?.invoice_item_headings_formatted.value }}</th>
                                                    <th class="bg-secondary text-white p-3 text-end" style="min-width: 160px;">Total</th>
                                                </tr>
                                                </thead>
                                                <tbody v-if="invoice?.invoice_items.length > 0">
                                                <tr v-for="(each, index) in invoice?.invoice_items">
                                                    <td class="p-3 fw-bold text-secondary">{{ each.description }}</td>
                                                    <td class="p-3 fw-bold text-secondary text-center">{{ each.unit_frequency }}</td>
                                                    <td class="p-3 fw-bold text-secondary text-center">{{ each.unit_value }}</td>
                                                    <td class="p-3 fw-bold text-secondary text-end">{{ each.total }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="w-100 px-5">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="w-100">
                                                    <div><strong>Notes</strong></div>
                                                    <div>{{ invoice?.note }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 offset-lg-3">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-start fs-5 fw-bold text-secondary">Sub Total:</td>
                                                        <td class="text-end fs-5 fw-bold text-secondary">{{ invoice?.sub_total }}</td>
                                                    </tr>
                                                    <tr v-if="invoice?.tax > 0">
                                                        <td class="text-start fs-5 fw-bold text-secondary">Tax:</td>
                                                        <td class="text-end fs-5 fw-bold text-secondary">{{ invoice?.tax }}</td>
                                                    </tr>
                                                    <tr v-if="invoice?.discount > 0">
                                                        <td class="text-start fs-5 fw-bold text-secondary">Discount:</td>
                                                        <td class="text-end fs-5 fw-bold text-secondary">{{ invoice?.discount }}</td>
                                                    </tr>
                                                    <tr v-if="invoice?.bonus > 0">
                                                        <td class="text-start fs-5 fw-bold text-secondary">Bonus:</td>
                                                        <td class="text-end fs-5 fw-bold text-secondary">{{ invoice?.bonus }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-start fs-4 fw-bold text-white bg-success">Total:</td>
                                                        <td class="text-end fs-4 fw-bold text-white bg-success">{{ invoice?.total }}</td>
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
        </div>
    </div>
</template>
<script>

import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import {createToaster} from "@meforma/vue-toaster";
import swal from "sweetalert";
import flatPickr from "vue-flatpickr-component";

const toaster = createToaster({
    position: 'top-right'
});

export default {
    components: {flatPickr, createToaster},
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
            publicView: false
        }
    },
    methods: {
        /*Get Invoice Data*/
        getInvoice(id) {
            apiService.POST(apiRoutes.invoiceSingle, {id}, (res) => {
                if (res.status === 200) {
                    this.invoice = res.data;
                    console.log(this.invoice)
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },
        getInvoicePublic(id) {
            apiService.POST(apiRoutes.invoicePublicView, {invoice_code: id}, (res) => {
                if (res.status === 200) {
                    this.invoice = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Update Status*/
        updateStatus() {
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
        generatePdf() {
            this.downloadLoading = true;
            apiService.DOWNLOAD(apiRoutes.invoiceDownload, {id: this.invoice?.id}, '', (res) => {
                this.downloadLoading = false;
                // Create a Blob object from the PDF data
                const blob = new Blob([res], {type: 'application/pdf'});

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
        generateQRCode() {
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
        shareInvoice() {
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
        shareModalOpen() {
            let myModal = new bootstrap.Modal(document.getElementById('shareModal'));
            myModal.show();
        },

        /*share modal open*/
        shareModalClose() {
            let myModalEl = document.getElementById('shareModal');
            let modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        }
    },
    mounted() {
        if (this.$route.params) {
            if (window.location.pathname.includes('share')) {
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
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
