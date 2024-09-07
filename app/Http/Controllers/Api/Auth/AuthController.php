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
use Illuminate\Support\Facades\Log;

class AuthController extends Controller{
public function __construct(private EmailVerificationService $service)
    {
    }

    /**
     * Login method
     */
    public function login(LoginRequest $request)
    {
        Log::info('Login request received', ['request' => $request->all()]);

        $token = auth()->attempt($request->validated());

        if ($token) {
            Log::info('Login successful', ['user' => auth()->user()]);
            return $this->responseWithToken($token, auth()->user());
        } else {
            Log::warning('Login failed: Invalid credentials', ['request' => $request->all()]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    /**
     * Resend verification link
     */
    public function resendEmailVerificatioLink(ResendEmailVerificatioLinkRequest $request)
    {
        Log::info('Resend email verification link request received', ['email' => $request->email]);
        return $this->service->resendLink($request->email);
    }

    /**
     * Verify user email
     */
    public function verifyUserEmail(VerifyEmailRequest $request)
    {
        Log::info('Verify email request received', ['email' => $request->email, 'token' => $request->token]);
        return $this->service->verifyEmail($request->email, $request->token);
    }

    /**
     * Register method
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        Log::info('Registration request received', ['request' => $request->all()]);

        // Assurez-vous de hacher le mot de passe avant de sauvegarder l'utilisateur
        $userData = $request->validated();
        $userData['password'] = bcrypt($userData['password']);

        $user = User::create($userData);

        if ($user) {
            Log::info('User registered successfully', ['user' => $user]);
            $this->service->sendVerificationLink($user);
            $token = JWTAuth::fromUser($user);
            return $this->responseWithToken($token, $user);
        } else {
            Log::error('Error occurred while trying to create user', ['userData' => $userData]);
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
        Log::info('Returning JWT token', ['user' => $user, 'token' => $token]);
        $roles = $user->getRoleNames();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'roles' => $roles,
            'access_token' => $token,
            'type' => 'bearer'
        ]);
    }

    public function logout()
    {
        Log::info('Logout request received', ['user' => Auth::user()]);
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'User has been logged out successfully'
        ]);
    }
}
