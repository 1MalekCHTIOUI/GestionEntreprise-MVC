<?php

namespace App\Customs\Services;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function sendResetLink(string $email): void
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = $this->generateResetToken($email);
            Notification::send($user, new ForgetPasswordNotification($token));
        }
    }

    public function generateResetToken(string $email): string
    {
        $token = Str::uuid();
        PasswordResetToken::updateOrCreate(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        return $token;
    }

    public function resetPassword(string $email, string $token, string $password)
    {
        $tokenRecord = PasswordResetToken::where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$tokenRecord || $tokenRecord->created_at->addMinutes(60) < now()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token is invalid or expired'
            ], 400);
        }

        $user = User::where('email', $email)->first();
        $user->password = bcrypt($password);
        $user->save();

        $tokenRecord->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully'
        ]);
    }
}
