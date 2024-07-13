<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Customs\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $this->passwordResetService->sendResetLink($request->email);

        return response()->json([
            'status' => 'success',
            'message' => 'Reset link sent successfully'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $response = $this->passwordResetService->resetPassword(
            $request->email,
            $request->token,
            $request->password
        );

        return $response;
    }
}
