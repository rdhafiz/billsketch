<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Clients;
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
                'email' => 'required|unique',
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
            return response()->json(['status' => 200, 'message' => 'Client has been created successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
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
                'email' => 'required|unique',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 500, 'errors' => $validator->errors()]);
            }
            $client = Clients::find($requestData['id']);
            if (!$client instanceof Clients) {
                return response()->json(['status' => 500, 'message' => 'Cannot find client'], 200);
            }
            $clientData = [
                'id' => $requestData['id'],
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
            return response()->json(['status' => 200, 'message' => 'Client has been updated successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
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
            return response()->json(['status' => 200, 'data' => $client], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
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
            Helpers::fileRemove($client, 'logo');
            return response()->json(['status' => 200, 'message' => 'Client deleted successfully '], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => 500, 'message' => $exception->getMessage(), 'error_code' => $exception->getCode()], 200);
        }
    }
    public function list()
    {

    }
}
