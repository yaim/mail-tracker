<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class TokenLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $info = $request->header('origin').' ('.$request->header('user-agent').') - '.now();
        $token = $this->guard()->user()->createToken($info)->accessToken;

        return $this->authenticated($request, $this->guard()->user())
                ?: response()->json(['data' => ['token' => $token]], 201);
    }

    public function user()
    {
        $user = $this->guard()->user();

        return new UserResource($user);
    }

    public function logout()
    {
        $this->guard()->user()->token()->delete();

        return redirect('/');
    }
}
