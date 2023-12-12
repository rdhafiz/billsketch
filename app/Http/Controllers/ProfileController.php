<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Helpers\Helpers;
use App\Models\User;
use App\Services\Auth\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Handles the user data retrieval endpoint.
     *
     * This function invokes the static 'get' method of the 'Profile' class to retrieve user data
     * based on the session user's ID. It then returns a JSON response containing the result of the
     * user data retrieval operation.
     *
     * @param Request $request The HTTP request object containing the session user information.
     *
     * @return JsonResponse A JSON response containing the result of the user data retrieval operation.
     */
    public function get(Request $request): JsonResponse
    {
        $rv = Profile::get($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the user profile update endpoint and returns a JsonResponse.
     *
     * @param Request $request The HTTP request object containing the updated profile information.
     *
     * @return JsonResponse The JSON response containing the status and a message indicating the success or failure of the profile update operation.
     */
    public function update(Request $request): JsonResponse
    {
        $rv = Profile::update($request);
        return response()->json($rv, 200);
    }

    /**
     * Update the user's password.
     *
     * @param Request $request The HTTP request containing user data.
     * @return JsonResponse The JSON response indicating the status of the password update.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $response = Profile::updatePassword($request);
        return response()->json($response, $response['status']);
    }
}
