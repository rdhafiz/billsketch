<?php

namespace App\Http\Controllers;

use App\Constants\InvoiceRecurringStatus;
use App\Constants\InvoiceStatus;
use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function Symfony\Component\Translation\t;

class InvoicesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'category_id' => 'required|integer',
                'invoice_no' => 'required|integer',
                'currency' => 'required|string',
                'recurring' => 'nullable|integer',
                'recurring_frequency' => 'nullable|required_if:recurring,1|integer|min:1',
                'invoice_items' => 'required|array',
                'invoice_items.*.description' => 'required',
                'invoice_items.*.unit_frequency' => 'required|integer',
                'invoice_items.*.unit_value' => 'required|integer',
                'client_id' => 'integer|required_without:employee_id|exists:clients,id',
                'employee_id' => 'integer|required_without:client_id|exists:employees,id',
                'tax' => 'nullable|integer',
                'discount' => 'nullable|integer',
                'bonus' => 'nullable|integer',
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
            $isInvoiceNoExist = InvoiceRepository::checkIfInvoiceNoExist($requestData['session_user'], $invoiceUserType, $invoiceUserId, $requestData['invoice_no']);
            if ($isInvoiceNoExist == true) {
                return response()->json(['status' => 500, 'errors' => ['invoice_no' => ['Invoice no already exist']]]);
            }
            $invoiceData = [
                'user_id' => $requestData['session_user']['id'],
                'client_id' => $requestData['client_id'] ?? null,
                'employee_id' => $requestData['employee_id'] ?? null,
                'category_id' => $requestData['category_id'],
                'invoice_no' => $requestData['invoice_no'],
                'invoice_date' => $requestData['invoice_date'] ?? null,
                'invoice_due_date' => $requestData['invoice_due_date'] ?? null,
                'invoice_status' => $requestData['invoice_status'] ?? InvoiceStatus::Draft,
                'cancel_reason' => $requestData['cancel_reason'] ?? null,
                'overdue_reason' => $requestData['overdue_reason'] ?? null,
                'currency' => $requestData['currency'],
                'recurring' => $requestData['recurring'] ?? 0,
                'recurring_frequency' => $requestData['recurring_frequency'] ?? null,
                'tax' => $requestData['tax'] ?? null,
                'discount' => $requestData['discount'] ?? null,
                'bonus' => $requestData['bonus'] ?? null,
                'invoice_item_headings' => !empty($requestData['invoice_item_headings']) ? json_encode($requestData['invoice_item_headings']) : null,
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
            $invoice = InvoiceRepository::save($invoiceData);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot save invoice'], 200);
            }
            foreach ($invoiceItems as &$item) {
                $item['invoice_id'] = $invoice->id;
            }
            if (!InvoiceItems::insert($invoiceItems)) {
                DB::rollBack();
                return response()->json(['status' => 500, 'message' => 'Cannot save invoice'], 200);
            }
            DB::commit();
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Invoice_create);
            return response()->json(['status' => 200, 'message' => 'Invoice has been created successfully'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    public function calculateInvoiceTotal($invoiceData, $totalItemsValue)
    {
        $taxAmount = 0;
        if (!empty($invoiceData['tax'])) {
            $taxAmount = ($totalItemsValue * $invoiceData['tax']) / 100;
        }
        if (!empty($invoiceData['bonus'])) {
            $totalItemsValue += $invoiceData['bonus'];
        }
        if (!empty($invoiceData['discount'])) {
            $totalItemsValue -= $invoiceData['discount'];
        }
        return $totalItemsValue - $taxAmount;
    }

    /**
     * @return array
     */
    public function getStatus(): array
    {
        return InvoiceStatus::getArray();
    }
    /**
     * @return array
     */
    public function getRecurringValue(): array
    {
        return InvoiceRecurringStatus::getArray();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLatestNumber(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'client_id' => 'integer|required_without:employee_id',
                'employee_id' => 'integer|required_without:client_id',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            if (!empty($requestData['client_id']) && !empty($requestData['employee_id'])) {
                $requestData['employee_id'] = null;
            }
            $invoiceUserType = !empty($requestData['employee_id']) ? 'employee_id' : 'client_id';
            $invoiceUserId = !empty($requestData['employee_id']) ? $requestData['employee_id'] : $requestData['client_id'];
            $latestNumber = InvoiceRepository::getLatestNumber($requestData['session_user'], $invoiceUserType, $invoiceUserId);
            return response()->json(['status' => 200, 'invoice_number' => $latestNumber], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
