<?php

namespace App\Http\Controllers;

use App\Constants\UserLogType;
use App\Constants\UserType;
use App\Helpers\Helpers;
use App\Models\Companies;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Repositories\CompaniesRepository;
use App\Services\Auth\Login;
use App\Services\Auth\Profile;
use App\Services\Auth\Register;
use App\Services\Auth\SocialLogin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{
    /**
     * Handles the login endpoint.
     *
     * This function invokes the static 'make' method of the 'Login' class to process
     * the user login request and then returns a JSON response based on the result.
     *
     * @param Request $request The HTTP request object containing user login details.
     *
     * @return JsonResponse A JSON response containing the result of the login attempt.
     */
    public function login(Request $request): JsonResponse
    {
        $rv = Login::make($request);
        return response()->json($rv, 200);
    }



    /**
     * Handles the social login endpoint.
     *
     * This function invokes the static 'make' method of the 'SocialLogin' class to process
     * the social login request and then returns a JSON response based on the result.
     *
     * @param Request $request The HTTP request object containing social login details.
     *
     * @return JsonResponse A JSON response containing the result of the social login attempt.
     */
    public function socialLogin(Request $request): JsonResponse
    {
        $rv = SocialLogin::make($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the user registration endpoint.
     *
     * This function invokes the static 'make' method of the 'Register' class to process
     * the user registration request and then returns a JSON response based on the result.
     *
     * @param Request $request The HTTP request object containing user registration details.
     *
     * @return JsonResponse A JSON response containing the result of the user registration attempt.
     */
    public function registration(Request $request): JsonResponse
    {
        $rv = Register::make($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the user account verification endpoint.
     *
     * This function invokes the static 'verifyAccount' method of the 'Register' class to process
     * the user account verification request and then returns a JSON response based on the result.
     *
     * @param Request $request The HTTP request object containing account verification details.
     *
     * @return JsonResponse A JSON response containing the result of the account verification attempt.
     */
    public function verifyAccount(Request $request): JsonResponse
    {
        $rv = Register::verifyAccount($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the forgot password endpoint.
     *
     * This function invokes the static 'forgotPassword' method of the 'Profile' class to initiate
     * the password reset process based on the provided email. It then returns a JSON response based
     * on the result of the password reset operation.
     *
     * @param Request $request The HTTP request object containing the email for password reset.
     *
     * @return JsonResponse A JSON response containing the result of the password reset operation.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $rv = Profile::forgotPassword($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the reset password endpoint.
     *
     * This function invokes the static 'resetPassword' method of the 'Profile' class to reset
     * the user's password based on the provided email, reset code, and new password. It then
     * returns a JSON response based on the result of the password reset operation.
     *
     * @param Request $request The HTTP request object containing email, reset code, and new password.
     *
     * @return JsonResponse A JSON response containing the result of the password reset operation.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $rv = Profile::resetPassword($request);
        return response()->json($rv, 200);
    }

    /**
     * Handles the logout endpoint.
     *
     * This function invokes the static 'logout' method of the 'Profile' class to log out the user.
     * It revokes the access token, records the user's logout activity, and returns a JSON response
     * based on the result of the logout operation.
     *
     * @param Request $request The HTTP request object containing the access token and session user information.
     *
     * @return JsonResponse A JSON response containing the result of the logout operation.
     */
    public function logout(Request $request): JsonResponse
    {
        $rv = Profile::logout($request);
        return response()->json($rv, 200);
    }

}
