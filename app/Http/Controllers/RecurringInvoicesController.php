<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceRecurringStatus;
use App\Constants\InvoiceStatus;
use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Categories;
use App\Models\Clients;
use App\Models\Employees;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Models\RecurringInvoiceItems;
use App\Models\RecurringInvoices;
use App\Repositories\InvoiceRepository;
use App\Repositories\RecurringInvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RecurringInvoicesController extends Controller
{

    /**
     * @return array
     */
    public function getRecurringValue(): array
    {
        return InvoiceRecurringStatus::getArray();
    }

    public function save(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'client_id' => 'nullable|integer|required_without:employee_id|exists:clients,id',
                'employee_id' => 'nullable|integer|required_without:client_id|exists:employees,id',
                'category_id' => 'required|integer',
                'due_days' => 'required|numeric',
                'currency' => 'required|string',
                'tax' => 'nullable|numeric',
                'discount' => 'nullable|numeric',
                'bonus' => 'nullable|numeric',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'frequency' => 'required|integer',
                'status' => 'required|integer',
                'invoice_items' => 'required|array',
                'invoice_items.*.description' => 'required',
                'invoice_items.*.unit_frequency' => 'required|numeric',
                'invoice_items.*.unit_value' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            if (empty($requestData['client_id']) && empty($requestData['employee_id'])) {
                return response()->json(['status' => 500, 'message' => 'Invalid request']);
            }
            if (!empty($requestData['client_id']) && !empty($requestData['employee_id'])) {
                $requestData['employee_id'] = null;
            }
            $invoiceUserType = !empty($requestData['employee_id']) ? 'employee_id' : 'client_id';
            $invoiceUserId = !empty($requestData['employee_id']) ? $requestData['employee_id'] : $requestData['client_id'];
            $recurringUid = RecurringInvoiceRepository::RecurringUid($requestData['session_user'], $invoiceUserType, $invoiceUserId);


            $invoiceData = [
                'uid' => $recurringUid,
                'user_id' => $requestData['session_user']['id'],
                'client_id' => $requestData['client_id'] ?? null,
                'employee_id' => $requestData['employee_id'] ?? null,
                'category_id' => $requestData['category_id'],
                'due_days' => $requestData['due_days'],
                'currency' => $requestData['currency'],
                'tax' => $requestData['tax'] ?? 0,
                'discount' => $requestData['discount'] ?? 0,
                'bonus' => $requestData['bonus'] ?? 0,
                'invoice_item_headings' => !empty($requestData['invoice_item_headings']) ? json_encode($requestData['invoice_item_headings']) : null,
                'start_date' => $requestData['start_date'],
                'end_date' => $requestData['end_date'] ?? null,
                'frequency' => $requestData['frequency'],
                'status' => $requestData['status'],
                'note' => $requestData['note'] ?? null,
            ];
            $invoiceItems = [];
            $totalItemsValue = 0;
            foreach ($requestData['invoice_items'] as $item) {
                $totalItemValue = ($item['unit_frequency'] == null ? 0 : $item['unit_frequency']) * ($item['unit_value'] == null ? 0 : $item['unit_value']);
                $invoiceItems[] = [
                    'description' => $item['description'] ?? null,
                    'unit_frequency' => $item['unit_frequency'] ?? null,
                    'unit_value' => $item['unit_value'] ?? null,
                    'total' => $totalItemValue,
                ];
                $totalItemsValue += $totalItemValue;
            }
            $invoiceData['sub_total'] = $totalItemsValue;
            $invoiceData['total'] = self::calculateInvoiceTotal($invoiceData, $totalItemsValue);
            $invoice = RecurringInvoiceRepository::save($invoiceData);
            if (!$invoice instanceof RecurringInvoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot save invoice'], 200);
            }
            foreach ($invoiceItems as &$item) {
                $item['invoice_id'] = $invoice->id;
            }
            if (!RecurringInvoiceItems::insert($invoiceItems)) {
                DB::rollBack();
                return response()->json(['status' => 500, 'message' => 'Cannot save invoice'], 200);
            }
            DB::commit();
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Recurring_Invoice_create, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' created a recurring invoice: #'.$invoice['uid']);
            return response()->json(['status' => 200, 'message' => 'Recurring Invoice has been created successfully'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'category_id' => 'required|integer',
                'due_days' => 'required|numeric',
                'currency' => 'required|string',
                'tax' => 'nullable|numeric',
                'discount' => 'nullable|numeric',
                'bonus' => 'nullable|numeric',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'frequency' => 'required|integer',
                'status' => 'required|integer',
                'invoice_items' => 'required|array',
                'invoice_items.*.description' => 'required',
                'invoice_items.*.unit_frequency' => 'required|numeric',
                'invoice_items.*.unit_value' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = RecurringInvoices::find($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find recurring invoice'], 200);
            }
            $invoiceData = [
                'category_id' => $requestData['category_id'],
                'due_days' => $requestData['due_days'],
                'currency' => $requestData['currency'],
                'tax' => $requestData['tax'] ?? 0,
                'discount' => $requestData['discount'] ?? 0,
                'bonus' => $requestData['bonus'] ?? 0,
                'invoice_item_headings' => !empty($requestData['invoice_item_headings']) ? json_encode($requestData['invoice_item_headings']) : null,
                'start_date' => $requestData['start_date'],
                'end_date' => $requestData['end_date'] ?? null,
                'frequency' => $requestData['frequency'],
                'status' => $requestData['status'],
                'note' => $requestData['note'] ?? null,
            ];
            $invoiceItems = [];
            $totalItemsValue = 0;
            foreach ($requestData['invoice_items'] as $item) {
                $totalItemValue = ($item['unit_frequency'] == null ? 0 : $item['unit_frequency']) * ($item['unit_value'] == null ? 0 : $item['unit_value']);
                $invoiceItems[] = [
                    'description' => $item['description'] ?? null,
                    'unit_frequency' => $item['unit_frequency'] ?? null,
                    'unit_value' => $item['unit_value'] ?? null,
                    'total' => $totalItemValue,
                ];
                $totalItemsValue += $totalItemValue;
            }
            $invoiceData['sub_total'] = $totalItemsValue;
            $invoiceData['total'] = self::calculateInvoiceTotal($invoiceData, $totalItemsValue);
            $invoiceResponse = RecurringInvoiceRepository::update($invoice, $invoiceData);
            if (!$invoiceResponse instanceof RecurringInvoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot update recurring invoice'], 200);
            }
            InvoiceItems::where('invoice_id', $invoice->id)->forceDelete();
            foreach ($invoiceItems as &$item) {
                $item['invoice_id'] = $invoice->id;
            }
            if (!RecurringInvoices::insert($invoiceItems)) {
                DB::rollBack();
                return response()->json(['status' => 500, 'message' => 'Cannot update recurring invoice'], 200);
            }
            DB::commit();
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Recurring_Invoice_update, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' updated a recurring invoice: #'.$invoice['uid']);
            return response()->json(['status' => 200, 'message' => 'Recurring Invoice has been updated successfully'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    public function single(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = RecurringInvoiceRepository::single($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Invoice_view, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' viewed a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'data' => $invoice], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = RecurringInvoices::find($requestData['id']);
            if (!$invoice instanceof RecurringInvoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find recurring invoice'], 200);
            }
            if (!$invoice->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete recurring invoice'], 200);
            }
            Helpers::relationalDataAction($invoice->id, 'invoice_id', 'delete', new RecurringInvoiceItems());
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Recurring_Invoice_delete, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' deleted a recurring invoice: #'.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Recurring Invoice deleted successfully '], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $filter = [
                'keyword' => $requestData['keyword'] ?? '',
                'is_active' => $requestData['is_active'] ?? 1,
                'start_date' => $requestData['start_date'] ?? null,
                'end_date' => $requestData['end_date'] ?? null,
                'status' => $requestData['status'] ?? null,
                'category_id' => $requestData['category_id'] ?? null,
                'employee_id' => $requestData['employee_id'] ?? null,
                'client_id' => $requestData['client_id'] ?? null
            ];
            $paginatedData = [
                'limit' => $requestData['limit'] ?? 15,
                'sort_by' => $requestData['sort_by'] ?? 'id',
                'order_by' => $requestData['order_by'] ?? 'DESC'
            ];
            $user = $requestData['session_user'];
            $result = RecurringInvoiceRepository::list($filter, $paginatedData, $user);
            return response()->json(['status' => 200, 'data' => $result]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    public function calculateInvoiceTotal($invoiceData, $totalItemsValue)
    {
        if (!empty($invoiceData['tax'])) {
            $taxAmount = ($totalItemsValue * $invoiceData['tax']) / 100;
            $totalItemsValue = $totalItemsValue - $taxAmount;
        }
        if (!empty($invoiceData['discount'])) {
            $totalItemsValue -= $invoiceData['discount'];
        }
        if (!empty($invoiceData['bonus'])) {
            $totalItemsValue += $invoiceData['bonus'];
        }
        return $totalItemsValue;
    }
}
