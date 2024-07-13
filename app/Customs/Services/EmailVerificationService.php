<?php

namespace App\Customs\Services;

use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EmailVerificationService
{
    /*
     * Create the method that will send the verification link to the user
     * send verification link to a user
     */
    public function sendVerificationLink(object $user): void
    {
        Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));
    }
    /**
     * resend link with token
     */
    public function resendLink($email){
        $user= User::where("email",$email)->first();
        if($user){
             $this->sendVerificationLink($user);
             return response()->json([
                'status' => 'Success',
                'message' => 'verification link sent sucessfully'
            ]);
        } else{
            return response()->json([
                'status' => 'failed',
                'message' => 'user not found' ]);


        }
    }
    /**
     * verify user email
     */
    public function verifyEmail(string $email, string $token)
    {
        $user = User::where('email', $email)->first();
        /**
         * checked if user exist
         */
        if (!$user) {
            response()->json([
                'status' => 'failed',
                'message' => 'user not found '
            ])->send();
            exit;
        }
        $this->checkIfEmailIsVerified($user);
        $verifiedToken = $this->verifyToken($email, $token);
        if ($user->markEmailAsVerified()) {
            $verifiedToken->delete();
            response()->json([
                'status' => 'success',
                'message' => 'Email has been verified successfully'
            ])->send();
            exit;
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Email verification failed, please try again '
            ])->send();
            exit;
        }
    }
    /**
     * check if user has already been verified
     */

    public function checkIfEmailIsVerified($user)
    {
        if ($user->email_verified_at) {
            response()->json([
                'status' => 'failed',
                'message' => 'Email has already been verified '
            ])->send();
            exit;
        }
    }
    /**
     * verify token
     */
    public function verifyToken(string $email, string $token)
    {
        $token = EmailVerificationToken::where('email', $email)->where('token', $token)->first();
        if ($token) {
            $token->delete();
            if ($token->expired_at >= now()) {
                return $token;
            } else {
                response()->json([
                    'status' => 'failed',
                    'message' => 'token expired'
                ])->send();
                exit;
            }
        } else {
            response()->json([
                'status' => 'failed',
                'message' => 'invalid token'
            ])->send();
            exit;
        }
    }

    /*
     * Generate verification link
     */
    public function generateVerificationLink($email): string
    {
        $checkIfTokenExists = EmailVerificationToken::where('email', $email)->first(); // Correction de la syntaxe de la requête where
        if ($checkIfTokenExists) {
            $checkIfTokenExists->delete();
        }

        $token = Str::uuid();
        $url = config('app.url') . "?token=" . $token . "&email=" . $email;

        $saveToken = EmailVerificationToken::create([
            "email" => $email,
            "token" => $token,
            "expired_at" => now()->addMinutes(60),
        ]);

        if ($saveToken) {
            return $url;
        }

        return ''; // Ajout d'un retour par défaut au cas où $saveToken échoue
    }
}
