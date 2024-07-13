<?php

namespace App\Customs\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class PasswordService
{
    private function validateCurrentPassword($current_password)
    {
        if (!Hash::check($current_password, auth()->user()->password)) {
            return Response::json([
                'status' => 'failed',
                'message' => "Password didn't match the current password",
            ], 400);
        }
    }

    public function changePassword($data)
    {
        $this->validateCurrentPassword($data['current_password']);

        $updatePassword = auth()->user()->update([
            'password' => Hash::make($data['password'])
        ]);

        if ($updatePassword) {
            return Response::json([
                'status' => 'success',
                'message' => 'Password updated successfully'
            ]);
        } else {
            return Response::json([
                'status' => 'failed',
                'message' => 'An error occurred while updating password'
            ], 500);
        }
    }



}
