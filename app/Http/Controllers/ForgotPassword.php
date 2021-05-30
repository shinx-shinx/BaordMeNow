<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        if(User::where('email', $request->email)->doesntExist())
        {
            return response(['message' => 'User do not exists!'], 400);
        }

        $token = Str::random(10);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token
                ]);

            Mail::to($request->email)->send(new ResetPassword($token));

            return response(['message' => 'Check your email. We sent an email for you to change/reset your password']);
    }

    public function resetPassword(Request $request)
    {
        if(DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first())
            {
                $user = User::where('email', $request->email)->update([
                    'password' => Hash::make($request->password)
                ]);
                return response(['message' => 'Password changed successfully!']);
            }
        return response(['message' => 'Invalid Token or email'], 400);
    }
}
