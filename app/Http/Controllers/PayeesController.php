<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Payees;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Repositories\PayeesRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayeesController extends Controller
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
                'name' => 'required|string',
                'email' => 'required|email|unique:payees,email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }

            preg_match_all('/(?<=\b)\w/iu', $requestData['name'], $matches);
            $invoice_prefix = mb_strtoupper(implode('', $matches[0]));

            $payeeData = [
                'user_id' => $requestData['session_user']['id'],
                'invoice_prefix' => $requestData['invoice_prefix'] ?? $invoice_prefix,
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'city' => $requestData['city'],
                'country' => $requestData['country'],
            ];
            if ($request->file('avatar')) {
                $payeeData['avatar'] = Helpers::fileUpload($request->file('avatar'));
            }
            $payeeInfo = PayeesRepositories::save($payeeData);
            if (!$payeeInfo instanceof Payees) {
                return response()->json(['status' => 500, 'message' => 'Cannot save Payee'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Payee_create, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' created a Payee named: '.$payeeInfo['name']);
            return response()->json(['status' => 200, 'message' => 'Employee has been created successfully'], 200);
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
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            //check if email already has been taken
            $payee = Payees::where('email', $requestData['email'])->where('id', '!=', $requestData['id'])->first();
            if ($payee instanceof Payees) {
                return response()->json(['status' => 500, 'errors' => ['email' => ['Email already has been taken']]]);
            }
            $payee = Payees::find($requestData['id']);
            if (!$payee instanceof Payees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find payee'], 200);
            }

            preg_match_all('/(?<=\b)\w/iu', $requestData['name'], $matches);
            $invoice_prefix = mb_strtoupper(implode('', $matches[0]));

            $payeeData = [
                'id' => $requestData['id'],
                'invoice_prefix' => $requestData['invoice_prefix'] ?? $invoice_prefix,
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'city' => $requestData['city'],
                'country' => $requestData['country'],
            ];
            if ($request->file('avatar')) {
                Helpers::fileRemove($payee, 'avatar');
                $payeeData['avatar'] = Helpers::fileUpload($request->file('avatar'));
            }
            $payeeInfo = PayeesRepositories::update($payee, $payeeData);
            if (!$payeeInfo instanceof Payees) {
                return response()->json(['status' => 500, 'message' => 'Cannot update payee'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Payee_update, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' updated a payee named: '.$payeeInfo['name']);
            return response()->json(['status' => 200, 'message' => 'Employee has been updated successfully'], 200);
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
            $payee = PayeesRepositories::single($requestData['id']);
            if (!$payee instanceof Payees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find payee'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Payee_view, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' viewed a payee named: '.$payee['name']);
            return response()->json(['status' => 200, 'data' => $payee], 200);
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
            $payee = Payees::find($requestData['id']);
            if (!$payee instanceof Payees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find payee'], 200);
            }
            if (!$payee->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete payee'], 200);
            }
            Helpers::relationalDataAction($payee->id, 'payee_id', 'delete', new Invoices(), true, new InvoiceItems());
            Helpers::fileRemove($payee, 'logo');
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Payee_delete, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' deleted a payee named: '.$payee['name']);
            return response()->json(['status' => 200, 'message' => 'Employee deleted successfully '], 200);
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
            $result = PayeesRepositories::list($filter, $paginatedData, $user);
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
            $payee = Payees::find($requestData['id']);
            if (!$payee instanceof Payees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find payee'], 200);
            }
            $payee->is_active = $payee->is_active == 0 ? 1 : 0;
            if (!$payee->save()) {
                $message = 'Cannot restore payee';
                if ($payee->is_active == 0) {
                    $message = 'Cannot archive payee';
                }
                return response()->json(['status' => 500, 'message' => $message], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],$payee->is_active == 1 ? UserLogType::Payee_restore : UserLogType::Payee_archive, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' '.$payee->is_active == 1 ? 'restored' : 'archived' .' a payee named: '.$payee['name']);
            $message = 'Employee archive successfully';
            Helpers::relationalDataAction($payee->id, 'payee_id', 'archive', new Invoices());
            if ($payee->is_active == 1) {
                Helpers::relationalDataAction($payee->id, 'payee_id', 'restore', new Invoices());
                $message = 'Employee restore successfully';
            }
            return response()->json(['status' => 200, 'message' => $message], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
