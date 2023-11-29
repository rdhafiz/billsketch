<?php

namespace App\Http\Controllers;

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
                'invoice_no' => 'required|integer|unique:invoices,invoice_no',
                'currency' => 'required|string',
                'recurring' => 'required|integer',
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
            $invoiceData = [
                'user_id' => $requestData['session_user']['id'],
                'client_id' => $requestData['client_id'] ?? null,
                'employee_id' => $requestData['employee_id'] ?? null,
                'category_id' => $requestData['category_id'],
                'invoice_no' => $requestData['invoice_no'],
                'invoice_date' => $requestData['invoice_date'] ?? null,
                'invoice_due_date' => $requestData['invoice_due_date'] ?? null,
                'invoice_status' => $requestData['invoice_status'] ?? 1,
                'cancel_reason' => $requestData['cancel_reason'] ?? null,
                'overdue_reason' => $requestData['overdue_reason'] ?? null,
                'currency' => $requestData['currency'],
                'recurring' => $requestData['recurring'] ?? 0,
                'recurring_frequency' => $requestData['recurring_frequency'] ?? null,
                'sub_total' => $requestData['sub_total'] ?? null,
                'tax' => $requestData['tax'] ?? null,
                'discount' => $requestData['discount'] ?? null,
                'bonus' => $requestData['bonus'] ?? null,
                'total' => $requestData['total'] ?? null,
                'note' => $requestData['note'] ?? null,
            ];
            $invoice = InvoiceRepository::save($invoiceData);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot save invoice'], 200);
            }
            if (!empty($requestData['invoice_items']) && count($requestData['invoice_items']) > 0) {
                $invoiceItems = [];
                foreach ($requestData['invoice_items'] as $item) {
                    $invoiceItems[] = [
                        'invoice_id' => $invoice->id,
                        'description' => $item['description'] ?? null,
                        'unit_frequency' => $item['unit_frequency'] ?? null,
                        'unit_value' => $item['unit_value'] ?? null,
                        'total' => $item['total'] ?? null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                if (!InvoiceItems::insert($invoiceItems)) {
                    DB::rollBack();
                    return response()->json(['status' => 500, 'message' => 'Cannot save invoice'], 200);
                }
            }
            DB::commit();
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Invoice_create);
            return response()->json(['status' => 200, 'message' => 'Invoice has been created successfully'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
