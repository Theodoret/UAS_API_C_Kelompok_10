<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // tambahkan ini
use App\Models\User; // import model user
use Validator; // import library untuk validasi

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|unique:users',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error validasi input

        $registrationData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($registrationData); // membuat user baru
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); // return data user dalam bentuk json
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'name' => 'required',
            'password' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input
        
        if (!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401); // return error gagal login

        $user = Auth::user();

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'password' => $request->password
        ]); // return data user dalam bentuk json
    }
}
