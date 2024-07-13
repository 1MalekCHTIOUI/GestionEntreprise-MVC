<?php

namespace App\Http\Controllers\Api\Auth;

use App\Customs\Services\EmailVerificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResendEmailVerificatioLinkRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function __construct(private EmailVerificationService $service)
    {
    }
    /**
     * Login method
     */
    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if ($token) {
            return $this->responseWithToken($token, auth()->user());
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }
    }
    /**
     * Resend verification lonk
     */
    public function resendEmailVerificatioLink(ResendEmailVerificatioLinkRequest $request)
    {
        return $this->service->resendLink($request->email);
    }
    /**
     * verify user email
     */
    public function verifyUserEmail(VerifyEmailRequest $request)
    {
        return $this->service->verifyEmail($request->email, $request->token);
    }


    /**
     * Register method
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        // Assurez-vous de hacher le mot de passe avant de sauvegarder l'utilisateur
        $userData = $request->validated();
        $userData['password'] = bcrypt($userData['password']);

        $user = User::create($userData);
        if ($user) {
            $this->service->sendVerificationLink($user);
            $token = JWTAuth::fromUser($user);
            return $this->responseWithToken($token, $user);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while trying to create user'
            ], 500);
        }
    }

    /**
     * Return Jwt access token
     */
    public function responseWithToken($token, $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer'
        ]);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
        'status'=>'sucess',
        'message'=>'User hasbeen logged out successufly'
    ]);
}
}
