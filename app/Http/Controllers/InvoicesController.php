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
use App\Repositories\InvoiceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                'invoice_no' => 'required|numeric',
                'currency' => 'required|string',
                'recurring' => 'nullable|integer',
                'recurring_frequency' => 'nullable|required_if:recurring,1|integer|min:1',
                'invoice_items' => 'required|array',
                'invoice_items.*.description' => 'required',
                'invoice_items.*.unit_frequency' => 'required|numeric',
                'invoice_items.*.unit_value' => 'required|numeric',
                'client_id' => 'nullable|integer|required_without:employee_id|exists:clients,id',
                'employee_id' => 'nullable|integer|required_without:client_id|exists:employees,id',
                'tax' => 'nullable|numeric',
                'discount' => 'nullable|numeric',
                'bonus' => 'nullable|numeric',
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

            $invoiceNumber = '';
            if ($invoiceUserType === 'client_id') {
                $employeeOrClient = Clients::where('id', $requestData['client_id'])->first();
            } else {
                $employeeOrClient = Employees::where('id', $requestData['employee_id'])->first();
            }
            if ($employeeOrClient != null) {
                $invoiceNumber = Invoices::generateInvoiceNumber($employeeOrClient->invoice_prefix, $requestData['invoice_no']);
            }

            $invoiceData = [
                'user_id' => $requestData['session_user']['id'],
                'client_id' => $requestData['client_id'] ?? null,
                'employee_id' => $requestData['employee_id'] ?? null,
                'category_id' => $requestData['category_id'],
                'invoice_no' => $requestData['invoice_no'],
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $requestData['invoice_date'] ?? null,
                'invoice_due_date' => $requestData['invoice_due_date'] ?? null,
                'invoice_status' => $requestData['invoice_status'] ?? InvoiceStatus::Draft,
                'cancel_reason' => $requestData['cancel_reason'] ?? null,
                'overdue_reason' => $requestData['overdue_reason'] ?? null,
                'currency' => $requestData['currency'],
                'recurring' => $requestData['recurring'] ?? 0,
                'recurring_frequency' => $requestData['recurring_frequency'] ?? null,
                'recurring_end_date' => $requestData['recurring_end_date'] ?? null,
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
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Invoice_create, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' created a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Invoice has been created successfully'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
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
            DB::beginTransaction();
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'category_id' => 'required|integer',
                'currency' => 'required|string',
                'recurring' => 'nullable|integer',
                'recurring_frequency' => 'nullable|required_if:recurring,1|integer|min:1',
                'invoice_items' => 'required|array',
                'invoice_items.*.description' => 'required',
                'invoice_items.*.unit_frequency' => 'required|numeric',
                'invoice_items.*.unit_value' => 'required|numeric',
                'tax' => 'nullable|numeric',
                'discount' => 'nullable|numeric',
                'bonus' => 'nullable|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = Invoices::find($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            $invoiceData = [
                'category_id' => $requestData['category_id'],
                'invoice_date' => $requestData['invoice_date'] ?? null,
                'invoice_due_date' => $requestData['invoice_due_date'] ?? null,
                'invoice_status' => $requestData['invoice_status'] ?? InvoiceStatus::Draft,
                'currency' => $requestData['currency'],
                'recurring' => $requestData['recurring'] ?? 0,
                'recurring_frequency' => $requestData['recurring_frequency'] ?? null,
                'recurring_end_date' => $requestData['recurring_end_date'] ?? null,
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
            $invoiceResponse = InvoiceRepository::update($invoice, $invoiceData);
            if (!$invoiceResponse instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot update invoice'], 200);
            }
            InvoiceItems::where('invoice_id', $invoice->id)->forceDelete();
            foreach ($invoiceItems as &$item) {
                $item['invoice_id'] = $invoice->id;
            }
            if (!InvoiceItems::insert($invoiceItems)) {
                DB::rollBack();
                return response()->json(['status' => 500, 'message' => 'Cannot update invoice'], 200);
            }
            DB::commit();
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Invoice_update, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' updated a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Invoice has been updated successfully'], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
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

            $invoiceNumber = '';
            if ($invoiceUserType === 'client_id') {
                $employeeOrClient = Clients::where('id', $requestData['client_id'])->first();
            } else {
                $employeeOrClient = Employees::where('id', $requestData['employee_id'])->first();
            }
            if ($employeeOrClient != null) {
                $invoiceNumber = Invoices::generateInvoiceNumber($employeeOrClient->invoice_prefix, $latestNumber);
            }

            return response()->json(['status' => 200, 'invoice_no' => $latestNumber, 'invoice_number' => $invoiceNumber], 200);
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
            $invoice = InvoiceRepository::single($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Invoice_view, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' viewed a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'data' => $invoice], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'status_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = Invoices::find($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            $invoice->invoice_status = $requestData['status_id'];
            $invoice->cancel_reason = $requestData['cancel_reason'] ?? null;
            $invoice->overdue_reason = $requestData['overdue_reason'] ?? null;
            if (!$invoice->save()) {
                return response()->json(['status' => 500, 'message' => 'Cannot update invoice status'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Invoice_update, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' updated status of a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Invoice status updated successfully'], 200);
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
            $invoice = Invoices::find($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            if (!$invoice->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete invoice'], 200);
            }
            Helpers::relationalDataAction($invoice->id, 'invoice_id', 'delete', new InvoiceItems());
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Invoice_delete, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' deleted a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Invoice deleted successfully '], 200);
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
                'start_date' => $requestData['start_date'] ?? null,
                'end_date' => $requestData['end_date'] ?? null,
                'invoice_status' => $requestData['invoice_status'] ?? null,
                'category_id' => $requestData['category_id'] ?? null,
                'employee_id' => $requestData['employee_id'] ?? null,
                'client_id' => $requestData['client_id'] ?? null,
                'recurring' => $requestData['recurring'] ?? false,
            ];
            $paginatedData = [
                'limit' => $requestData['limit'] ?? 15,
                'order_by' => $requestData['order_by'] ?? 'id',
                'order_mode' => $requestData['order_mode'] ?? 'DESC',
                'pagination' => $requestData['pagination'] ?? true,
            ];
            $user = $requestData['session_user'];
            $result = InvoiceRepository::list($filter, $paginatedData, $user);
            return response()->json(['status' => 200, 'data' => $result]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function archiveOrRestore(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = Invoices::find($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            $invoice->is_active = $invoice->is_active == 0 ? 1 : 0;
            if (!$invoice->save()) {
                $message = 'Cannot restore invoice';
                if ($invoice->is_active == 0) {
                    $message = 'Cannot archive invoice';
                }
                return response()->json(['status' => 500, 'message' => $message], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'], $invoice->is_active == 1 ? UserLogType::Invoice_restore : UserLogType::Invoice_archive, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name'].' '.$invoice->is_active == 1 ? 'restored' : 'archive' .' updated a invoice named: '.$invoice['invoice_number']);
            $message = 'Invoice archive successfully';
            if ($invoice->is_active == 1) {
                $message = 'Invoice restore successfully';
            }
            return response()->json(['status' => 200, 'message' => $message], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function share(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = Invoices::find($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            $shareInfo = [];
            $shareInfo['link'] = env('APP_URL') . '/portal/share/invoice/' . base64_encode($invoice->id);
            $shareInfo['email'] = $requestData['email'];
            if (!empty($shareInfo['subject'])) {
                $shareInfo['subject'] = $requestData['subject'];
            } else {
                $shareInfo['subject'] = 'An invoice shared to you from ' . $requestData['session_user']['first_name'] . ' ' . $requestData['session_user']['last_name'] ?? '' . env('APP_NAME'); // todo: Please add a nice subject here... ridwan??
            }
            if (!empty($shareInfo['body'])) {
                $shareInfo['body'] = $requestData['body'];
            } else {
                $shareInfo['body'] = 'This a an invoice shared to you, To view the invoice please click the link bellow'; // todo: Please add a nice body here... ridwan??
            }
            Mail::send('email.share', ['shareInfo' => $shareInfo], function ($message) use ($shareInfo) {
                $message->to($shareInfo['email'])->subject($shareInfo['subject']);
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Share_invoice, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' shared a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Invoice shared successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function viewInvoice(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'invoice_code' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = InvoiceRepository::single(base64_decode($requestData['invoice_code']));
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            return response()->json(['status' => 200, 'data' => $invoice], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function generateQRCode(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = InvoiceRepository::single($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            // Generate QR code for a URL
            $url = env('APP_URL') . '/portal/share/invoice/' . base64_encode($invoice->id);
            QrCode::format('svg')->size(300)->generate($url, public_path('storage/uploads/qrcode_' . base64_encode($invoice->id) . '.svg'));
            $invoice->qrcode = 'qrcode_' . base64_encode($invoice->id) . '.svg';
            if (!$invoice->save()) {
                return response()->json(['status' => 500, 'message' => 'Cannot generate QR code'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Qrcode_invoice, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' created QR code of a invoice named: '.$invoice['invoice_number']);
            return response()->json(['status' => 200, 'message' => 'Qr code generated successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|string
     */
    public function download(Request $request): JsonResponse|string
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $invoice = InvoiceRepository::single($requestData['id']);
            if (!$invoice instanceof Invoices) {
                return response()->json(['status' => 500, 'message' => 'Cannot find invoice'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'], UserLogType::Download_invoice, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' downloaded a invoice named: '.$invoice['invoice_number']);
            $pdf = Pdf::loadView('download.invoice', ['invoice' => $invoice]);
            return $pdf->output();
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboardCount(Request $request): JsonResponse
    {
        try {
            $data = [
                'total_invoice'=> Invoices::count(),
                'pending_invoice'=> Invoices::where('invoice_status', InvoiceStatus::Pending)->count(),
                'paid_invoice'=> Invoices::where('invoice_status', InvoiceStatus::Paid)->count(),
                'recurring_invoice'=> Invoices::where('recurring', 1)->count(),
            ];
            return response()->json(['status' => 200, 'data' => $data], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboardChartByMonth(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'start_month' => 'required|integer',
                'end_month' => 'required|integer',
                'year' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $start_date = $requestData['year'].'-'.$requestData['start_month'].'-01';
            $end_date = $requestData['year'].'-'.$requestData['end_month'].'-31';
            $invoice = Invoices::select(DB::raw('COUNT(id) as total'), DB::raw('MONTH(created_at) as month'))
                ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
                ->groupBy('month')
                ->get()
                ->keyBy('month')
                ->toArray();
            $label = [];
            $data = [];
            for ($i = $requestData['start_month']; $i <= $requestData['end_month']; $i++) {
                $label[] = date('F', strtotime($requestData['year'].'-'.$i.'-01'));
                $data[] = isset($invoice[$i]) ? $invoice[$i]['total'] : 0;
            }
            return response()->json(['status' => 200, 'label' => $label, 'data'=>$data], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboardChartByStatus(Request $request): JsonResponse
    {
        try {
            $invoice = Invoices::select(DB::raw('COUNT(id) as total'), 'invoice_status')
                ->groupBy('invoice_status')
                ->get()
                ->keyBy('invoice_status')
                ->toArray();
            $label = [];
            $data = [];
            $invoiceStatus = self::getStatus();
            foreach ($invoiceStatus as $status) {
                $label[] = $status['name'];
                $data[] = isset($invoice[$status['value']]) ? $invoice[$status['value']]['total'] : 0;
            }
            return response()->json(['status' => 200, 'label' => $label, 'data'=>$data], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboardChartByCategory(Request $request): JsonResponse
    {
        try {
            $invoice = Invoices::select(DB::raw('COUNT(id) as total'), 'category_id')
                ->groupBy('category_id')
                ->get()
                ->keyBy('category_id')
                ->toArray();
            $categories = Categories::get()->toArray();
            $label = [];
            $data = [];
            foreach ($categories as $category) {
                $label[] = $category['name'];
                $data[] = isset($invoice[$category['id']]) ? $invoice[$category['id']]['total'] : 0;
            }
            return response()->json(['status' => 200, 'label' => $label, 'data'=>$data], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
