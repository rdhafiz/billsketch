<?php

namespace App\Services\Client;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Clients;
use App\Repositories\ClientsRepositories;
use Illuminate\Support\Facades\Validator;

class ClientUpsert
{
    /**
     * Store a new client based on user-specified parameters.
     *
     * Functionality:
     * - Extracts data from the incoming request.
     * - Validates the incoming request data for client creation, including name, email, phone, address, city, and country.
     * - Generates an invoice prefix from the initials of the client's name.
     * - Prepares data for the new client, including user ID, invoice prefix, and client details.
     * - Uploads the client logo if provided in the request.
     * - Saves the client information using the 'ClientsRepositories::save' method.
     * - Saves user activity log for creating a new client.
     * - Returns a response array with a status code (200 for success, 500 for error) and a message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing client creation data.
     *
     * @return array A response array containing the result of creating a new client.
     */
    public static function store($request): array
    {
        try {
            // Extract all data from the incoming request
            $requestData = $request->all();

            // Validate the incoming request data
            $validator = Validator::make($requestData, [
                'name' => 'required|string',
                'email' => 'required|email|unique:clients,email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }

            // Generate an invoice prefix from the initials of the client's name
            preg_match_all('/(?<=\b)\w/iu', $requestData['name'], $matches);
            $invoice_prefix = mb_strtoupper(implode('', $matches[0]));

            // Prepare data for the new client
            $clientData = [
                'user_id' => $requestData['session_user']['id'],
                'invoice_prefix' => $requestData['invoice_prefix'] ?? $invoice_prefix,
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'city' => $requestData['city'],
                'country' => $requestData['country'],
            ];

            // Upload client logo if provided in the request
            if ($request->file('logo')) {
                $clientData['logo'] = Helpers::fileUpload($request->file('logo'));
            }

            // Save the client information
            $clientInfo = ClientsRepositories::save($clientData);

            // Check if the client information was successfully saved
            if (!$clientInfo instanceof Clients) {
                return ['status' => 500, 'message' => 'Cannot save client'];
            }

            // Save user activity log for creating a new client
            Helpers::saveUserActivity(
                $requestData['session_user']['id'],
                UserLogType::Client_create,
                $requestData['session_user']['first_name'] . ' ' . $requestData['session_user']['last_name'] . ' created a client named: ' . $clientInfo['name']
            );

            // Return success message
            return ['status' => 200, 'message' => 'Client has been created successfully'];

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
     * Update an existing client based on user-specified parameters.
     *
     * Functionality:
     * - Extracts data from the incoming request.
     * - Validates the incoming request data for client update, including ID, name, email, phone, address, city, and country.
     * - Checks if the provided email is already taken by another client.
     * - Finds the client by ID.
     * - Generates an invoice prefix from the initials of the client's name.
     * - Prepares data for updating the client, including ID, invoice prefix, and client details.
     * - Removes the existing logo and uploads a new one if provided in the request.
     * - Updates the client information using the 'ClientsRepositories::update' method.
     * - Saves user activity log for updating an existing client.
     * - Returns a response array with a status code (200 for success, 500 for error) and a message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing client update data.
     *
     * @return array A response array containing the result of updating an existing client.
     */
    public static function update($request): array
    {
        try {
            // Extract all data from the incoming request
            $requestData = $request->all();

            // Validate the incoming request data
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }

            // Check if the provided email is already taken by another client
            $client = Clients::where('email', $requestData['email'])->where('id', '!=', $requestData['id'])->first();
            if ($client instanceof Clients) {
                return ['status' => 500, 'errors' => ['email' => ['Email already has been taken']]];
            }

            // Find the client by ID
            $client = Clients::find($requestData['id']);
            if (!$client instanceof Clients) {
                return ['status' => 500, 'message' => 'Cannot find client'];
            }

            // Generate an invoice prefix from the initials of the client's name
            preg_match_all('/(?<=\b)\w/iu', $requestData['name'], $matches);
            $invoice_prefix = mb_strtoupper(implode('', $matches[0]));

            // Prepare data for updating the client
            $clientData = [
                'id' => $requestData['id'],
                'invoice_prefix' => $requestData['invoice_prefix'] ?? $invoice_prefix,
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'phone' => $requestData['phone'],
                'address' => $requestData['address'],
                'city' => $requestData['city'],
                'country' => $requestData['country'],
            ];

            // Remove the existing logo and upload a new one if provided in the request
            if ($request->file('logo')) {
                Helpers::fileRemove($client, 'logo');
                $clientData['logo'] = Helpers::fileUpload($request->file('logo'));
            }

            // Update the client information
            $clientInfo = ClientsRepositories::update($client, $clientData);

            // Check if the client information was successfully updated
            if (!$clientInfo instanceof Clients) {
                return ['status' => 500, 'message' => 'Cannot update client'];
            }

            // Save user activity log for updating an existing client
            Helpers::saveUserActivity(
                $requestData['session_user']['id'],
                UserLogType::Client_update,
                $requestData['session_user']['first_name'] . ' ' . $requestData['session_user']['last_name'] . ' updated a client named: ' . $clientInfo['name']
            );

            // Return success message
            return ['status' => 200, 'message' => 'Client has been updated successfully'];

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
