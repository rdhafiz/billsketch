<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Categories;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Repositories\CategoriesRepositories;
use App\Repositories\InvoiceItemsRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;

class InvoiceItemsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'invoice_id' => 'required|integer',
                'description' => 'required|string',
                'unit_frequency' => 'required|integer',
                'unit_value' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $itemData = [
                'invoice_id' => $requestData['invoice_id'],
                'description' => $requestData['description'],
                'unit_frequency' => $requestData['unit_frequency'],
                'unit_value' => $requestData['unit_value'],
                'total' => $requestData['unit_value'] * $requestData['unit_frequency'],
            ];
            $invoiceItem = InvoiceItemsRepositories::save($itemData);
            if (!$invoiceItem instanceof InvoiceItems) {
                return response()->json(['status' => 500, 'message' => 'Cannot save invoice item'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Invoice_item_create);
            return response()->json(['status' => 200, 'message' => 'Invoice Item has been created successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'description' => 'required|string',
                'unit_frequency' => 'required|integer',
                'unit_value' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = InvoiceItems::find($requestData['id']);
            if (!$invoice instanceof InvoiceItems) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice item'], 200);
            }
            $itemData = [
                'description' => $requestData['description'],
                'unit_frequency' => $requestData['unit_frequency'],
                'unit_value' => $requestData['unit_value'],
                'total' => $requestData['unit_value'] * $requestData['unit_frequency'],
            ];
            $invoiceItem = InvoiceItemsRepositories::update($invoice, $itemData);
            if (!$invoiceItem instanceof InvoiceItems) {
                return response()->json(['status' => 500, 'message' => 'Cannot update Invoice Item'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Invoice_item_update);
            return response()->json(['status' => 200, 'message' => 'Invoice item has been updated successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
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
            $invoiceItem = InvoiceItemsRepositories::single($requestData['id']);
            if (!$invoiceItem instanceof InvoiceItems) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice item'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Invoice_item_view);
            return response()->json(['status' => 200, 'data' => $invoiceItem], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
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
            $invoiceItem = InvoiceItems::find($requestData['id']);
            if (!$invoiceItem instanceof InvoiceItems) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice item'], 200);
            }
            if (!$invoiceItem->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete invoice item'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Invoice_item_delete);
            return response()->json(['status' => 200, 'message' => 'Invoice item deleted successfully '], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $filter = [
                'keyword' => $requestData['keyword'] ?? '',
                'list_type' => $requestData['list_type'] ?? 'active',
            ];
            $paginatedData = [
                'limit' => $requestData['limit'] ?? 15,
                'order_by' => $requestData['order_by'] ?? 'id',
                'order_mode' => $requestData['order_mode'] ?? 'DESC',
                'pagination' => $requestData['pagination'] ?? true,
            ];
            $user = $requestData['session_user'];
            $result = InvoiceItemsRepositories::list($filter, $paginatedData, $user);
            return response()->json(['status' => 200, 'data' => $result]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
