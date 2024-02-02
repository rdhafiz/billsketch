<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        @vite('resources/scss/portal/app.scss')
    </style>
</head>
<body>

<div class="">
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
                        <td class="fs-5 fw-bold text-secondary text-end">{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td class="fs-5 fw-bold text-secondary text-start">Invoice Date</td>
                        <td class="fs-5 fw-bold text-secondary text-end">{{ $invoice->invoice_date_formatted }}</td>
                    </tr>
                    <tr>
                        <td class="fs-5 fw-bold text-secondary text-start">Due Date</td>
                        <td class="fs-5 fw-bold text-secondary text-end">{{ $invoice->invoice_due_date_formatted }}</td>
                    </tr>
                    <tr>
                        <td class="fs-5 fw-bold text-secondary text-start">Status</td>
                        <td class="fs-5 fw-bold text-secondary text-end">{{ $invoice->invoice_status_name }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="w-100 bg-success px-5">
        <div class="row">
            <div class="col-lg-3 offset-lg-9">
                <h1 class="w-100 fs-1 fw-bold m-0 p-0 bg-white text-center py-2">{{ $invoice->invoice_title }}</h1>
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
                    <th class="bg-secondary text-white p-3" style="min-width: 160px;">{{ $invoice->invoice_item_headings_formatted->description }}</th>
                    <th class="bg-secondary text-white p-3 text-center" style="min-width: 160px;">{{ $invoice->invoice_item_headings_formatted->frequency }}</th>
                    <th class="bg-secondary text-white p-3 text-center" style="min-width: 160px;">{{ $invoice->invoice_item_headings_formatted->value }}</th>
                    <th class="bg-secondary text-white p-3 text-end" style="min-width: 160px;">Total</th>
                </tr>
                </thead>
                @if(count($invoice->invoice_items) > 0)
                    <tbody>
                    @foreach($invoice->invoice_items as $each)
                        <tr>
                            <td class="p-3 fw-bold text-secondary">{{ $each['description'] }}</td>
                            <td class="p-3 fw-bold text-secondary text-center">{{ $each['unit_frequency'] }}</td>
                            <td class="p-3 fw-bold text-secondary text-center">{{ $each['unit_value'] }}</td>
                            <td class="p-3 fw-bold text-secondary text-end">{{ $each['total'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>
    <div class="w-100 px-5">
        <div class="row">
            <div class="col-lg-6">
                <div class="w-100">
                    <div><strong>Notes</strong></div>
                    <div>{{ $invoice->note }}</div>
                </div>
                <div class="w-100" v-if="invoice?.qrcode_path != null">
                    <img :src="invoice?.qrcode_path" height="100" width="100" alt="">
                </div>
            </div>
            <div class="col-lg-3 offset-lg-3">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="text-start fs-5 fw-bold text-secondary">Sub Total:</td>
                        <td class="text-end fs-5 fw-bold text-secondary">{{ $invoice->sub_total }}</td>
                    </tr>
                    <tr v-if="invoice?.tax > 0">
                        <td class="text-start fs-5 fw-bold text-secondary">Tax:</td>
                        <td class="text-end fs-5 fw-bold text-secondary">{{ $invoice->tax }}</td>
                    </tr>
                    <tr v-if="invoice?.discount > 0">
                        <td class="text-start fs-5 fw-bold text-secondary">Discount:</td>
                        <td class="text-end fs-5 fw-bold text-secondary">{{ $invoice->discount }}</td>
                    </tr>
                    <tr v-if="invoice?.bonus > 0">
                        <td class="text-start fs-5 fw-bold text-secondary">Bonus:</td>
                        <td class="text-end fs-5 fw-bold text-secondary">{{ $invoice->bonus }}</td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td class="text-start fs-4 fw-bold text-white bg-success">Total:</td>
                        <td class="text-end fs-4 fw-bold text-white bg-success">{{ $invoice->total }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


</body>
</html>
