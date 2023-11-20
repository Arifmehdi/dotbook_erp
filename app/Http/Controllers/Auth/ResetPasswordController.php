<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function resetCurrentPassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'current_password' => 'required',
                'password' => 'required|confirmed',
            ]
        );

        $adminUserHashedPassword = auth()->user()->password;
        $checkHashedPasswordWithOldPassword = Hash::check($request->current_password, $adminUserHashedPassword);
        if ($checkHashedPasswordWithOldPassword) {
            if (! Hash::check($request->password, $adminUserHashedPassword)) {
                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::logout();

                return response()->json(['successMsg' => 'Password changed successfully!']);
            } else {
                return response()->json(['errorMsg' => 'You entered your current password, insert new password to change!']);
            }
        } else {
            return response()->json(['errorMsg' => 'Current password does not matched']);
        }
    }
}
