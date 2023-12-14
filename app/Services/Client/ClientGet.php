<?php

namespace App\Services\Client;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Clients;
use App\Repositories\ClientsRepositories;
use Illuminate\Support\Facades\Validator;

class ClientGet
{
    /**
     * Retrieve details of a single client based on user-specified parameters.
     *
     * Functionality:
     * - Extracts data from the incoming request.
     * - Validates the incoming request data for the 'id' parameter.
     * - Retrieves the client details by ID using the 'ClientsRepositories::single' method.
     * - Saves user activity log for viewing a client.
     * - Returns a response array with a status code (200 for success, 500 for error) and client data.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing the client ID and session user data.
     *
     * @return array A response array containing the result of fetching details of a single client.
     */
    public static function single($request): array
    {
        try {
            // Extract all data from the incoming request
            $requestData = $request->all();

            // Validate the incoming request data
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }

            // Retrieve the client details by ID
            $client = ClientsRepositories::single($requestData['id']);

            // Check if the client was found
            if (!$client instanceof Clients) {
                return ['status' => 500, 'message' => 'Cannot find client'];
            }

            // Save user activity log for viewing a client
            Helpers::saveUserActivity(
                $requestData['session_user']['id'],
                UserLogType::Client_view,
                $requestData['session_user']['first_name'] . ' ' . $requestData['session_user']['last_name'] . ' viewed a client named: ' . $client['name']
            );

            // Return success status and client data
            return ['status' => 200, 'data' => $client];

        } catch (\Exception $exception) {
            // Return an error message with details in case of an exception
            return [
                'status' => 500,
                'message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'code_line' => $exception->getLine()
            ];
        }
    }

    /**
     * Retrieve a paginated list of clients based on user-specified parameters.
     *
     * Functionality:
     * - Extracts data from the incoming request.
     * - Prepares filter parameters for the client list, including keyword and list type.
     * - Prepares paginated data parameters for the client list, including limit, order by, order mode, and pagination.
     * - Gets the current user from the session data.
     * - Retrieves the list of clients from the 'ClientsRepositories::list' method based on specified parameters.
     * - Returns a response array with a status code (200 for success, 500 for error) and client data.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing parameters for client listing.
     *
     * @return array A response array containing the result of fetching the client list.
     */
    public static function list($request): array
    {
        try {
            // Extract all data from the incoming request
            $requestData = $request->all();

            // Prepare filter parameters for the client list
            $filter = [
                'keyword' => $requestData['keyword'] ?? '',
                'list_type' => $requestData['list_type'] ?? 'active',
            ];

            // Prepare paginated data parameters for the client list
            $paginatedData = [
                'limit' => $requestData['limit'] ?? 15,
                'order_by' => $requestData['order_by'] ?? 'id',
                'order_mode' => $requestData['order_mode'] ?? 'DESC',
                'pagination' => $requestData['pagination'] ?? true,
            ];

            // Get the current user
            $user = $requestData['session_user'];

            // Retrieve the list of clients based on specified parameters
            $result = ClientsRepositories::list($filter, $paginatedData, $user);

            // Return success status and client data
            return ['status' => 200, 'data' => $result];

        } catch (\Exception $exception) {
            // Return an error message with details in case of an exception
            return [
                'status' => 500,
                'message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'code_line' => $exception->getLine()
            ];
        }
    }

}
