<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::Where('email', $request->input('email'))->first();
        if($user) {

            if (! $token = auth()->attempt($credentials)) {
                return $this->responseErrorMsg(401, 'خطا فى الحساب او كلمة المرور');
            }
            else {
                return $this->respondWithToken($token);
            }

        }
        else {
            return $this->responseErrorMsg(404, 'هذا الحساب غير مسجل');
        }
    }

    public function logout()
    {
        auth()->logout();

        return $this->responseSuccessMsg(201, 'تم تسجيل الخروج بنجاح');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 . ' S'
        ]);
    }
}
