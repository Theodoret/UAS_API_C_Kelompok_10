<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // tambahkan ini
use App\Models\User; // import model user
use Validator; // import library untuk validasi
use Mail;
use App\Mail\UserMail;

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

        $registrationData['token'] = rand(100000,999999);

        $registrationData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($registrationData); // membuat user baru

        try{
            $detail = [
                'body' => $registrationData['token']
            ];
            Mail::to($registrationData['email'])->send(new UserMail($detail));
            return response([
                'message' => 'Register Success',
                'user' => $user
            ], 200); // return data user dalam bentuk json
        }catch (Exception $e) {
            return response([
                'message' => 'Register Success but Email was not sent'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        //$loginData = $request->all();
        $loginData['name'] = $request->name;
        $loginData['password'] = $request->password;
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
            'user' => $user
        ]); // return data user dalam bentuk json
    }

    public function verify(Request $request, $id){
        $user = User::find($id); // menbcaru data user berdasarkan id
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        } // return message saat data user tidak ditemukan

        $verifyData = $request->all();
        $validate = Validator::make($verifyData, [
            'token' => 'required|digits:6'
        ]);
        
        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input
        
        if ($user->token == $verifyData['token']) {
            $user->token = '1';
            if ($user->save()) {
                $arrayUser = [$user];
                return response([
                    'message' => 'Verify Success',
                    'data' => $arrayUser
                ], 200);
            } // return data user yang telah di edit dalam bentuk json
        }
        return response([
            'message' => 'Verify Failed',
            'data' => null,
        ], 400); // return message saat user gagal di edit
    }
}
