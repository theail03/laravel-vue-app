<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    public function googleAuthRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthCallback(Request $request)
    {
        $user_google = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['google_id' => $user_google->id],
            [
                'name' => $user_google->name,
                'email' => $user_google->email,
            ]
        );

        Auth::login($user);
    
        return redirect(env('CLIENT_URL') . '/matrices/dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return response([
            'success' => true
        ]);
    }

    public function getUser()
    {
        return Auth::user();
    }

}
