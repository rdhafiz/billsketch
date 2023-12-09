<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        .w-100 {
            width: 100%;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .border {
            border: 1px solid #dee2e6;
        }

        .border-top-0 {
            border-top: 0 !important;
        }

        .border-right-0 {
            border-right: 0 !important;
        }

        .p-8 {
            padding: 8px;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .d-inline-block {
            display: inline-block
        }
        .mb-40{
            margin-bottom: 40px;
        }
        .mw-120{
            min-width: 120px;
        }
    </style>
</head>
<body>
<table class="w-100">
    <tr>
        <td class="w-100 text-center">
            <img src="{{public_path('assets/images/bilify.png')}}" height="150px" alt="">
        </td>
        <td class="w-100">
            <div class="fw-bold">{{ $invoice->client ? 'Client' : 'Employee'}}</div>
            <div>{{ $invoice->client ? $invoice->client->name : $invoice->employee->name}}</div>
        </td>
        <td class="w-100"></td>
        <td class="w-100"></td>
        <td class="w-100 text-end">
            <div class="fw-bold">Invoice No</div>
            <div>{{ $invoice->invoice_number ?? '' }}</div>
        </td>
    </tr>
    <tr>
        <td class="w-100">
            <div class="fw-bold">Category</div>
            <div>{{ $invoice->category->name ?? '' }}</div>
        </td>
        <td class="w-100"></td>
        <td class="w-100"></td>
        <td class="w-100 text-end">
            <div class="fw-bold">Invoice Date</div>
            <div>{{ $invoice->invoice_date_formatted ?? '' }}</div>
        </td>
    </tr>
    <tr>
        <td class="w-100">
            <div class="fw-bold">Recurring Periods</div>
            <div{{ $invoice->recurring_frequency ?? 'N/A' }}</div>
        </td>
        <td class="w-100"></td>
        <td class="w-100"></td>
        <td class="w-100 text-end">
            <div class="fw-bold">Invoice Due Date</div>
            <div>{{ $invoice->invoice_due_date_formatted ?? '' }}</div>
        </td>
    </tr>
    <tr>
        <td class="w-100">
            <div class="fw-bold">Currency</div>
            <div>{{ $invoice->currency }}</div>
        </td>
        <td class="w-100"></td>
        <td class="w-100"></td>
        <td class="w-100 text-end">
            <div class="fw-bold">Invoice Status</div>
            <div>{{ $invoice->invoice_status_name ?? '' }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="mb-40"></div>
        </td>
    </tr>
    <tr>
        <td class="w-100 border border-right-0">
            <div class="fw-bold p-8">{{ $invoice->invoice_item_headings_formatted->description ?? '' }}</div>
        </td>
        <td class="w-100 border border-right-0">
            <div class="fw-bold p-8 text-center">{{ $invoice->invoice_item_headings_formatted->frequency ?? '' }}</div>
        </td>
        <td class="w-100 border border-right-0">
            <div class="fw-bold p-8 text-center">{{ $invoice->invoice_item_headings_formatted->value ?? '' }}</div>
        </td>
        <td class="w-100 border">
            <div class="fw-bold p-8 text-center">Total</div>
        </td>
    </tr>
    @if(count($invoice->invoice_items) > 0)
        @foreach($invoice->invoice_items as $item)
            <tr>
                <td class="w-100 border border-top-0 border-right-0">
                    <div class="p-8">{{ $item->description }}</div>
                </td>
                <td class="w-100 border border-top-0 border-right-0">
                    <div class="p-8 text-end">{{ $item->unit_frequency }}</div>
                </td>
                <td class="w-100 border border-top-0 border-right-0">
                    <div class="p-8 text-end">{{ $item->unit_value }}</div>
                </td>
                <td class="w-100 border border-top-0">
                    <div class="p-8 text-end">{{ $item->total }}</div>
                </td>
            </tr>

        @endforeach
    @endif

    <tr>
        <td>
            <div class="mb-40"></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="vertical-align: top;">
            <div class="fw-bold">Notes</div>
            <div>{{$invoice->note ?? ''}}</div>
        </td>
        <td colspan="2" class="text-end" style="vertical-align: top;">
            <div class="w-100">
                <div class="fw-bold d-inline-block">Invoice Subtotal:</div>
                <div class="d-inline-block text-end mw-120">{{ $invoice->sub_total ?? '' }}</div>
            </div>
            <div class="mb-10"></div>
            <div class="w-100">
                <div class="fw-bold d-inline-block">Invoice Tax:</div>
                <div class="d-inline-block text-end mw-120">{{ $invoice->tax ?? '' }}</div>
            </div>
            <div class="mb-10"></div>
            <div class="w-100">
                <div class="fw-bold d-inline-block">{{ $invoice->client ? 'Discount:' : 'Bonus:'}}</div>
                @if($invoice->client)
                <div class="d-inline-block text-end mw-120">{{ $invoice->discount ?? '' }}</div>
                @else
                <div class="d-inline-block text-end mw-120">{{ $invoice->bonus ?? '' }}</div>
                @endif
            </div>
            <div class="mb-10"></div>
            <div class="w-100">
                <div class="fw-bold d-inline-block">Invoice Total:</div>
                <div class="text-end d-inline-block mw-120">{{ $invoice->total ?? '' }}</div>
            </div>
        </td>
    </tr>
    @if($invoice->qrcode_path)
        <tr>
            <td>
                <div class="mb-40"></div>
            </td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="1" class="text-end">
                <img src="{{$invoice->qrcode_path}}" height="100" width="100" alt="qr code">
            </td>
        </tr>
    @endif
</table>
</body>
</html>
