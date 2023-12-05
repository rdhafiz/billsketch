<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Clients;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Repositories\ClientsRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
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
                'email' => 'required|email|unique:clients,email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $clientData = [
                'user_id' => $requestData['session_user']['id'],
                'invoice_prefix' => $requestData['invoice_prefix'] ?? preg_split("/[\s,_-]+/", $requestData['name']),
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'city' => $requestData['city'],
                'country' => $requestData['country'],
            ];
            if ($request->file('logo')) {
                $clientData['logo'] = Helpers::fileUpload($request->file('logo'));
            }
            $clientInfo = ClientsRepositories::save($clientData);
            if (!$clientInfo instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot save client'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Client_create);
            return response()->json(['status' => 200, 'message' => 'Client has been created successfully'], 200);
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
            $client = Clients::where('email', $requestData['email'])->where('id', '!=', $requestData['id'])->first();
            if ($client instanceof Clients) {
                return response()->json(['status' => 500, 'errors' => ['email' => ['Email already has been taken']]]);
            }
            $client = Clients::find($requestData['id']);
            if (!$client instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot find client'], 200);
            }
            $clientData = [
                'id' => $requestData['id'],
                'invoice_prefix' => $requestData['invoice_prefix'] ?? preg_split("/[\s,_-]+/", $requestData['name']),
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'city' => $requestData['city'],
                'country' => $requestData['country'],
            ];
            if ($request->file('logo')) {
                Helpers::fileRemove($client, 'logo');
                $clientData['logo'] = Helpers::fileUpload($request->file('logo'));
            }
            $clientInfo = ClientsRepositories::update($client, $clientData);
            if (!$clientInfo instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot update client'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Client_update);
            return response()->json(['status' => 200, 'message' => 'Client has been updated successfully'], 200);
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
            $client = ClientsRepositories::single($requestData['id']);
            if (!$client instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot find client'], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Client_view);
            return response()->json(['status' => 200, 'data' => $client], 200);
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
            $client = Clients::find($requestData['id']);
            if (!$client instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot find client'], 200);
            }
            if (!$client->delete()) {
                return response()->json(['status' => 500, 'message' => 'Cannot delete client'], 200);
            }

            Helpers::relationalDataAction($client->id, 'client_id', 'delete', new Invoices(), true, new InvoiceItems());
            Helpers::fileRemove($client, 'logo');
            Helpers::saveUserActivity($requestData['session_user']['id'],UserLogType::Client_delete);
            return response()->json(['status' => 200, 'message' => 'Client deleted successfully '], 200);
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
            $result = ClientsRepositories::list($filter, $paginatedData, $user);
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
            $client = Clients::find($requestData['id']);
            if (!$client instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot find client'], 200);
            }
            $client->is_active = $client->is_active == 0 ? 1 : 0;
            if (!$client->save()) {
                $message = 'Cannot restore client';
                if ($client->is_active == 0) {
                    $message = 'Cannot archive client';
                }
                return response()->json(['status' => 500, 'message' => $message], 200);
            }
            Helpers::saveUserActivity($requestData['session_user']['id'],$client->is_active == 1 ? UserLogType::Client_restore : UserLogType::Client_archive);
            $message = 'Client archive successfully';
            Helpers::relationalDataAction($client->id, 'client_id', 'archive', new Invoices());
            if ($client->is_active == 1) {
                Helpers::relationalDataAction($client->id, 'client_id', 'restore', new Invoices());
                $message = 'Client restore successfully';
            }
            return response()->json(['status' => 200, 'message' => $message], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode(), 'code_line' => $exception->getLine()], 200);
        }
    }
}
