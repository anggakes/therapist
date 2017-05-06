<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Validator;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return array
     */
    public function login(Request $request){
        if($request->has("email")){
            $credentials = $request->only('email', 'password');
        }elseif($request->has("handphone")){
            $credentials = $request->only('handphone', 'password');
        }else{
            throw new BadRequestHttpException("Email/ponsel tidak boleh Kosong");
        }


        $auth = Auth::attempt($credentials);

        if (!$auth) {
            throw new UnauthorizedHttpException("",'Email/ponsel atau password anda salah');
        }

        $user  = Auth::user();
        $token = JWTAuth::fromUser($user);

        return [
            "token" => $token,
            "user"  => $user
        ];

    }

    public function refreshToken(){

        $token = JWTAuth::getToken();

        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{

            $token = JWTAuth::refresh($token);

        }catch(TokenInvalidException $e){
            throw new UnauthorizedHttpException("",'The token is invalid');
        }

        return ['token'=>$token];
    }

    /**
     * @param $data
     * @param string $type
     * @return mixed
     */

    public function validator($data, $type='login'){
        if($type == 'register'){
            return Validator::make($data, [
                'name'     => 'required|between:3,255',
                'email'    => 'required|between:3,255|email|unique:users',
                'password' => 'required|between:4,255',
                'handphone'=> 'required|between:3,255|unique:users',

            ]);
        }else{
            return Validator::make($data, [
                'email'    => 'between:3,255|email',
                'password' => 'required|between:4,255',
                'handphone'=> 'between:3,255',

            ]);
        }

    }
}
