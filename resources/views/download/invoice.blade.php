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
            <div>{{ $invoice->invoice_status ?? '' }}</div>
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
</table>
<!--    <div class="row">
    <div class="cl col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mb-4">
                            <div class="col-sm-6 col-md-5 col-xl-4">
                                <div class="mb-3">
                                    <div><strong>{{ $invoice->client ? 'Client' : 'Employee'}}</strong></div>
                                    <div>{{ $invoice->client ? $invoice->client->name : $invoice->employee->name}}</div>
                                </div>
                                <div class="mb-3">
                                    <div><strong>Category</strong></div>
                                    <div>{{ $invoice->category->name ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <div><strong>Recurring Periods</strong></div>
                                    <div>{{ $invoice->recurring_frequency ?? 'N/A' }}</div>
                                </div>
                                <div class="mb-3">
                                    <div><strong>Currency</strong></div>
                                    <div>{{ $invoice->currency }}</div>
                                </div>
                            </div>
                            <div class="d-none d-md-block col-md-2 col-xl-4"></div>
                            <div class="col-sm-6 col-md-5 col-xl-4">
                                <div class="mb-3">
                                    <div><strong>Invoice No</strong></div>
                                    <div>{{ $invoice->invoice_number ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <div><strong>Invoice Date</strong></div>
                                    <div>{{ $invoice->invoice_date_formatted ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <div><strong>Invoice Due Date</strong></div>
                                    <div>{{ $invoice->invoice_due_date_formatted ?? '' }}</div>
                                </div>
                                <div class="mb-3">
                                    <div><strong>Invoice Status</strong></div>
                                    <div>{{$invoice->status}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-4">
                        <div class="table-data table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="min-width: 160px;">{{ $invoice->invoice_item_headings_formatted->description ?? '' }}</th>
                                    <th class="text-center" style="min-width: 160px;">{{ $invoice->invoice_item_headings_formatted->frequency ?? '' }}</th>
                                    <th class="text-center" style="min-width: 160px;">{{ $invoice->invoice_item_headings_formatted->value ?? '' }}</th>
                                    <th class="text-center" style="min-width: 160px;">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($invoice->invoice_items) > 0)
    @foreach($invoice->invoice_items as $item)
        <tr>
            <td>{{ $item->description }}</td>
                                            <td class="text-end">{{ $item->unit_frequency }}</td>
                                            <td class="text-end">{{ $item->unit_value }}</td>
                                            <td class="text-end">{{ $item->total }}</td>
                                        </tr>


    @endforeach
@endif
</tbody>
</table>
</div>
</div>
<div class="col-lg-12">
<div class="preview-footer">
<div class="notes">
<div><strong>Notes</strong></div>
<div>{{$invoice->note ?? ''}}</div>
                            </div>
                            <div class="total">
                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                        class="text-start text-sm-end">Invoice
                                        Subtotal: </strong> <span
                                        class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                        style="min-width: 120px;">{{ $invoice->sub_total ?? '' }}</span></div>
                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                        class="text-start text-sm-end">Invoice
                                        Tax: </strong> <span
                                        class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                        style="min-width: 120px;">{{ $invoice->tax ?? '' }}</span></div>
                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2">
                                    <strong class="text-start text-sm-end">Invoice Discount: </strong>
                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                          style="min-width: 120px;">{{$invoice->discount ?? '' }}</span></div>
                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                        class="text-start text-sm-end">Invoice Bonus: </strong>
                                    <span class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                          style="min-width: 120px;">{{$invoice->bonus ?? '' }}</span></div>
                                <div class="d-flex flex-column flex-sm-row justify-content-end h6 mb-3 mb-sm-2"><strong
                                        class="text-start text-sm-end">Invoice
                                        Total: </strong> <span
                                        class="ms-0 ms-sm-3 mt-1 mt-sm-0 text-start text-sm-end"
                                        style="min-width: 120px;">{{ $invoice->total ?? ''}}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
</body>
</html>
