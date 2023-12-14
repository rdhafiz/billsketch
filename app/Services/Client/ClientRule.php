<?php

namespace App\Services\Client;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\Clients;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use Illuminate\Support\Facades\Validator;

class ClientRule
{
    /**
     * Delete an existing client based on user-specified parameters.
     *
     * Functionality:
     * - Extracts data from the incoming request.
     * - Validates the incoming request data for the 'id' parameter.
     * - Finds the client by ID.
     * - Deletes the client.
     * - Performs related actions on associated data (invoices, invoice items).
     * - Removes the client's logo file.
     * - Saves user activity log for deleting a client.
     * - Returns a response array with a status code (200 for success, 500 for error) and a message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing client ID and session user data.
     *
     * @return array A response array containing the result of the client deletion attempt.
     */
    public static function destroy($request): array
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

            // Find the client by ID
            $client = Clients::find($requestData['id']);

            // Check if the client was found
            if (!$client instanceof Clients) {
                return ['status' => 500, 'message' => 'Cannot find client'];
            }

            // Delete the client
            if (!$client->delete()) {
                return ['status' => 500, 'message' => 'Cannot delete client'];
            }

            // Perform related actions on associated data (invoices, invoice items)
            Helpers::relationalDataAction($client->id, 'client_id', 'delete', new Invoices(), true, new InvoiceItems());

            // Remove the client's logo file
            Helpers::fileRemove($client, 'logo');

            // Save user activity log for deleting a client
            Helpers::saveUserActivity(
                $requestData['session_user']['id'],
                UserLogType::Client_delete,
                $requestData['session_user']['first_name'] . ' ' . $requestData['session_user']['last_name'] . ' deleted a client named: ' . $client['name']
            );

            // Return success status and message
            return ['status' => 200, 'message' => 'Client deleted successfully'];

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
     * Archive or restore a client based on user-specified parameters.
     *
     * Functionality:
     * - Extracts data from the incoming request.
     * - Validates the incoming request data for the 'id' parameter.
     * - Finds the client by ID.
     * - Toggles the 'is_active' status of the client (archive or restore).
     * - Saves the client's updated status.
     * - Saves user activity log for archiving or restoring a client.
     * - Performs related actions on associated data (invoices) based on the client's status.
     * - Returns a response array with a status code (200 for success, 500 for error) and a message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing client ID and session user data.
     *
     * @return array A response array containing the result of the archive or restore attempt.
     */
    public static function archiveOrRestore($request): array
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

            // Find the client by ID
            $client = Clients::find($requestData['id']);

            // Check if the client was found
            if (!$client instanceof Clients) {
                return ['status' => 500, 'message' => 'Cannot find client'];
            }

            // Toggle the 'is_active' status of the client (archive or restore)
            $client->is_active = $client->is_active == 0 ? 1 : 0;

            // Save the client's updated status
            if (!$client->save()) {
                $message = $client->is_active == 0 ? 'Cannot archive client' : 'Cannot restore client';
                return ['status' => 500, 'message' => $message];
            }

            // Save user activity log for archiving or restoring a client
            Helpers::saveUserActivity(
                $requestData['session_user']['id'],
                $client->is_active == 1 ? UserLogType::Client_restore : UserLogType::Client_archive,
                $requestData['session_user']['first_name'] . ' ' . $requestData['session_user']['last_name'] . ' ' .
                ($client->is_active == 1 ? 'restored' : 'archived') . ' a client named: ' . $client['name']
            );

            // Perform related actions on associated data (invoices) based on the client's status
            $message = $client->is_active == 1 ? 'Client restore successfully' : 'Client archive successfully';
            Helpers::relationalDataAction($client->id, 'client_id', $client->is_active == 1 ? 'restore' : 'archive', new Invoices());

            // Return success status and message
            return ['status' => 200, 'message' => $message];

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
