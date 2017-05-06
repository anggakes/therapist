<?php
/**
 * Created by PhpStorm.
 * User: anggakes
 * Date: 4/10/17
 * Time: 10:44 PM
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;

class UserController extends Controller
{

    public function add(){
//        $name = request('name');
//        $email = request('email');
//        $password = request('password');
//        $handphone = request('handphone');

        $data = request()->all();
        $data['password'] = Hash::make($data["password"]);

        $user = User::create($data);

        return $user;
    }

}