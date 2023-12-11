<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Employees;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Repositories\EmployeesRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
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
                'email' => 'required|email|unique:employees,email',
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

            $employeeData = [
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
                $employeeData['avatar'] = Helpers::fileUpload($request->file('avatar'));
            }
            $employeeInfo = EmployeesRepositories::save($employeeData);
            if (!$employeeInfo instanceof Employees) {
                return response()->json(['status' => 500, 'message' => 'Cannot save employee'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Employee_create, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' created a employee named: '.$employeeInfo['name']);
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
            $employee = Employees::where('email', $requestData['email'])->where('id', '!=', $requestData['id'])->first();
            if ($employee instanceof Employees) {
                return response()->json(['status' => 500, 'errors' => ['email' => ['Email already has been taken']]]);
            }
            $employee = Employees::find($requestData['id']);
            if (!$employee instanceof Employees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find employee'], 200);
            }

            preg_match_all('/(?<=\b)\w/iu', $requestData['name'], $matches);
            $invoice_prefix = mb_strtoupper(implode('', $matches[0]));

            $employeeData = [
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
                Helpers::fileRemove($employee, 'avatar');
                $employeeData['avatar'] = Helpers::fileUpload($request->file('avatar'));
            }
            $employeeInfo = EmployeesRepositories::update($employee, $employeeData);
            if (!$employeeInfo instanceof Employees) {
                return response()->json(['status' => 500, 'message' => 'Cannot update employee'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Employee_update, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' updated a employee named: '.$employeeInfo['name']);
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
            $employee = EmployeesRepositories::single($requestData['id']);
            if (!$employee instanceof Employees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find employee'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Employee_view, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' viewed a employee named: '.$employee['name']);
            return response()->json(['status' => 200, 'data' => $employee], 200);
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
            $employee = Employees::find($requestData['id']);
            if (!$employee instanceof Employees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find employee'], 200);
            }
            if (!$employee->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete employee'], 200);
            }
            Helpers::relationalDataAction($employee->id, 'employee_id', 'delete', new Invoices(), true, new InvoiceItems());
            Helpers::fileRemove($employee, 'logo');
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Employee_delete, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' deleted a employee named: '.$employee['name']);
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
            $result = EmployeesRepositories::list($filter, $paginatedData, $user);
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
            $employee = Employees::find($requestData['id']);
            if (!$employee instanceof Employees) {
                return response()->json(['status' => 500, 'message' => 'Cannot find employee'], 200);
            }
            $employee->is_active = $employee->is_active == 0 ? 1 : 0;
            if (!$employee->save()) {
                $message = 'Cannot restore employee';
                if ($employee->is_active == 0) {
                    $message = 'Cannot archive employee';
                }
                return response()->json(['status' => 500, 'message' => $message], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],$employee->is_active == 1 ? UserLogType::Employee_restore : UserLogType::Employee_archive, $requestData['session_user']['first_name'].' '.$requestData['session_user']['last_name']. ' '.$employee->is_active == 1 ? 'restored' : 'archived' .' a employee named: '.$employee['name']);
            $message = 'Employee archive successfully';
            Helpers::relationalDataAction($employee->id, 'employee_id', 'archive', new Invoices());
            if ($employee->is_active == 1) {
                Helpers::relationalDataAction($employee->id, 'employee_id', 'restore', new Invoices());
                $message = 'Employee restore successfully';
            }
            return response()->json(['status' => 200, 'message' => $message], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
