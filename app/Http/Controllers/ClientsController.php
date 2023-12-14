<?php

namespace App\Http\Controllers;

use App\Services\Client\ClientGet;
use App\Services\Client\ClientRule;
use App\Services\Client\ClientUpsert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Handles the list endpoint for retrieving a paginated list of clients.
     *
     * This function invokes the 'list' method of the 'ClientGet' class to process
     * the request for fetching a paginated list of clients and returns a JSON response based on the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing parameters for client listing.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of fetching the client list.
     */
    public function index(Request $request): JsonResponse
    {
        $rv = ClientGet::list($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the store endpoint for creating a new client.
     *
     * This function invokes the 'store' method of the 'ClientUpsert' class to process
     * the request for creating a new client and returns a JSON response based on the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing client data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of the client creation attempt.
     */
    public function store(Request $request): JsonResponse
    {
        $rv = ClientUpsert::store($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the update endpoint for modifying an existing client.
     *
     * This function invokes the 'update' method of the 'ClientUpsert' class to process
     * the request for updating an existing client and returns a JSON response based on the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing updated client data.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of the client update attempt.
     */
    public function update(Request $request): JsonResponse
    {
        $rv = ClientUpsert::update($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the show endpoint for retrieving information about a single client.
     *
     * This function invokes the 'single' method of the 'ClientGet' class to process
     * the request for fetching details of a single client and returns a JSON response based on the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing the client ID.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of fetching client details.
     */
    public function show(Request $request): JsonResponse
    {
        $rv = ClientGet::single($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles delete endpoint for removing an existing client.
     *
     * This function invokes the 'destroy' method of the 'ClientRule' class to process
     * the request for deleting an existing client and returns a JSON response based on the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing the client ID for deletion.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of the client deletion attempt.
     */
    public function destroy(Request $request): JsonResponse
    {
        $rv = ClientRule::destroy($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the archive or restore endpoint for changing the active status of a client.
     *
     * This function invokes the 'archiveOrRestore' method of the 'ClientRule' class to process
     * the request for archiving or restoring a client and returns a JSON response based on the result.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing the client ID.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the result of the archive or restore attempt.
     */
    public function archiveOrRestore(Request $request): JsonResponse
    {
        $rv = ClientRule::archiveOrRestore($request);
        return response()->json($rv, 200);
    }

}
