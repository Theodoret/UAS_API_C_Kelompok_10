<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // tambahkan ini
use Validator; // import library untuk validasi
use App\Models\User; // import model User

class UserController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $users = User::all(); // mengambil semua data user
        // $passwords = User::with('password')->get();

        if (count($users) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $users,
                // 'password' => $passwords
            ], 200);
        } // return data semua user dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data user kosong
    }

    // method untuk menampilkan 1 data user (search)
    public function show($id, Request $request)
    {
        $user = User::find($id); // mencari data user berdasarkan id

        if (!is_null($user)) {
            return response([
                'message' => "Retrieve User Success",
                'data' => $user,
            ], 200);
        } // return data user yang ditemukan dalam bentuk json

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404); // return message saat data user tidak ditemukan
    }

    // method untuk menambah 1 data user baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'name' => 'required|unique:users',
            'password' => 'required',
            'imgUrl' => ''
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error validasi input

        $storeData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($storeData); // membuat user baru
        $arrayUser = [$user];

        return response([
            'message' => 'Add User Success',
            'user' => $arrayUser
        ], 200); // return data user dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $user = User::find($id); // mencaari data product berdasarkan id

        if (is_null($user)) {
            return response ([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        } // return message saat data user tidak ditemukan

        if ($user->delete()) {
            $arrayUser = [$user];
            return response([
                'message' => 'Delete User Success',
                'data' => $arrayUser
            ], 200);
        } // return message saat berhasil menghapus data user

        return response([
            'message' => 'Delete User Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data user
    }

    //method untuk mengubah 1 data user (update)
    public function update(Request $request, $id)
    {
        $user = User::find($id); // menbcaru data user berdasarkan id
        if (is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        } // return message saat data user tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'name' => ['max:60', 'required', Rule::unique('users')->ignore($user)],
            'password' => 'required',
            'imgUrl' => ''
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $updateData['password'] = bcrypt($request->password); // enkripsi password

        $user->name = $updateData['name'];
        $user->password = $updateData['password'];
        $user->imgUrl = $updateData['imgUrl'];

        if ($user->save()) {
            $arrayUser = [$user];
            return response([
                'message' => 'Update User Success',
                'data' => $arrayUser
            ], 200);
        } // return data user yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update User Failed',
            'data' => null,
        ], 400); // return message saat user gagal di edit
    }
}
