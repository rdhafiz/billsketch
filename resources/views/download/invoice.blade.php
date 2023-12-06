<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        .w-100{
            width: 100%;
        }
    </style>
</head>
<body>
<table class="100">
    <tr>

    </tr>
</table>
    <div class="row">
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
</div>
</body>
</html>
