<?php

namespace App\Services\Recurring;


use App\Constants\InvoiceStatus;
use App\Http\Controllers\InvoicesController;
use App\Models\Clients;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Models\Payees;
use App\Models\RecurringInvoices;
use App\Models\User;
use App\Repositories\InvoiceRepository;
use Illuminate\Support\Facades\DB;

class Recurring
{
    public static function run()
    {
        $recurringInvoices = RecurringInvoices::where('is_active', 1)->where('status', 1)->where(function ($q){
            $q->whereNull('next_schedule_date');
            $q->orWhere(function ($qq){
                $qq->where('next_schedule_date', '>=', date('Y-m-d 00:00:00'));
                $qq->where('next_schedule_date', '<=', date('Y-m-d 11:59:59'));
            });
        })->get();
        print_r(PHP_EOL.'Total 3 Recurring Invoice(s) has been found'.PHP_EOL);
        foreach ($recurringInvoices as $recurringInvoice) {

            DB::beginTransaction();

            $start_date = date('Y-m-d', strtotime($recurringInvoice->start_date));
            $end_date = date('Y-m-d', strtotime($recurringInvoice->end_date));
            $today_date = date('Y-m-d');

            if ($recurringInvoice->next_schedule_date == null) {
                if (strtotime($today_date) > strtotime($end_date)) {
                    $recurringInvoice->status = 2;// Recurring Completed
                    $recurringInvoice->save();
                } elseif (strtotime($today_date) == strtotime($start_date)) {
                    $recurringInvoice->next_schedule_date = $today_date;
                    $recurringInvoice->save();
                } elseif (strtotime($today_date) < strtotime($start_date)) {
                    $recurringInvoice->next_schedule_date = $start_date;
                    $recurringInvoice->save();
                }
            }

            $next_schedule_date = date('Y-m-d', strtotime($recurringInvoice->next_schedule_date));
            if (strtotime($next_schedule_date) == strtotime($today_date)) {

                $invoiceNumber = '';
                $invoiceUserType = !empty($recurringInvoice->payee_id) ? 'payee_id' : 'client_id';
                $invoiceUserId = !empty($recurringInvoice->payee_id) ? $recurringInvoice->payee_id : $recurringInvoice->client_id;
                $user = User::find($recurringInvoice->user_id);
                $invoice_no = InvoiceRepository::getLatestNumber($user, $invoiceUserType, $invoiceUserId);
                if ($recurringInvoice->client_id > 0) {
                    $payeeOrClient = Clients::where('id', $recurringInvoice->client_id)->first();
                } else {
                    $payeeOrClient = Payees::where('id', $recurringInvoice->payee_id)->first();
                }
                if ($payeeOrClient != null) {
                    $invoiceNumber = Invoices::generateInvoiceNumber($payeeOrClient->invoice_prefix, $invoice_no);
                }

                $invoiceData = [
                    'user_id' => $recurringInvoice->user_id,
                    'client_id' => $recurringInvoice->client_id,
                    'payee_id' => $recurringInvoice->payee_id,
                    'recurring_id' => $recurringInvoice->id,
                    'category_id' => $recurringInvoice->category_id,
                    'invoice_no' => $invoice_no,
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => $today_date,
                    'invoice_due_date' => date('Y-m-d', strtotime('+ ' . $recurringInvoice->due_days . ' days', strtotime($today_date))),
                    'invoice_status' => InvoiceStatus::Pending,
                    'cancel_reason' => null,
                    'overdue_reason' => null,
                    'currency' => $recurringInvoice->currency,
                    'tax' => $recurringInvoice->tax,
                    'discount' => $recurringInvoice->discount,
                    'bonus' => $recurringInvoice->bonus,
                    'invoice_item_headings' => $recurringInvoice->invoice_item_headings,
                    'note' => $recurringInvoice->note,
                ];
                $invoiceItems = [];
                $totalItemsValue = 0;
                foreach ($recurringInvoice->invoice_items as $item) {
                    $totalItemValue = ($item->unit_frequency == null ? 0 : $item->unit_frequency) * ($item->unit_value == null ? 0 : $item->unit_value);
                    $invoiceItems[] = [
                        'description' => $item->description ?? null,
                        'unit_frequency' => $item->unit_frequency ?? null,
                        'unit_value' => $item->unit_value ?? null,
                        'total' => $totalItemValue,
                    ];
                    $totalItemsValue += $totalItemValue;
                }
                $invoiceData['sub_total'] = $totalItemsValue;
                $invoiceData['total'] = InvoicesController::calculateInvoiceTotal($invoiceData, $totalItemsValue);
                $invoice = InvoiceRepository::save($invoiceData);
                if (!$invoice instanceof Invoices) {
                    print_r(PHP_EOL . 'Cannot save invoice' . PHP_EOL);
                    DB::rollBack();
                    continue;
                }
                foreach ($invoiceItems as &$item) {
                    $item['invoice_id'] = $invoice->id;
                }
                if (!InvoiceItems::insert($invoiceItems)) {
                    print_r(PHP_EOL . 'Cannot save invoice' . PHP_EOL);
                    DB::rollBack();
                    continue;
                }

                $recurringInvoice->next_schedule_date = date('Y-m-d', strtotime('+ '.$recurringInvoice->frequency.' days', strtotime($today_date)));
                $recurringInvoice->save();
            }

            DB::commit();
        }
    }
}
