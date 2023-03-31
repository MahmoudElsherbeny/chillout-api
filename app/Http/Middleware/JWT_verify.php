<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\ApiResponseTrait;
use Exception;

class JWT_verify
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            //check if not authinticated and pass token
            if(! JWTAuth::parseToken()->authenticate()) {
                return $this->responseErrorMsg(401, 'unauthinticated');
            }
        } catch(Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->responseErrorMsg(401, 'invalid token: '.$e->getMessage());
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->responseErrorMsg(402, 'expired token: '.$e->getMessage());
            } else if ( $e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return $this->responseErrorMsg(404, 'invalid token');
            }else{
                return $this->responseErrorMsg(404, 'something is wrong');
            }
        }

        return $next($request);
    }
}
