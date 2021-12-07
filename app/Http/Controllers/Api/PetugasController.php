<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // tambahkan ini
use Validator; // import library untuk validasi
use App\Models\Petugas; // import model Petugas

class PetugasController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $petugass = Petugas::all(); // mengambil semua data petugas

        if (count($petugass) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $petugass
            ], 200);
        } // return data semua petugas dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data petugas kosong
    }

    // method untuk menampilkan 1 data petugas (search)
    public function show($id)
    {
        $petugas = Petugas::find($id); // mencari data petugas berdasarkan id

        if (!is_null($petugas)) {
            return response([
                'message' => "Retrieve Petugas Success",
                'data' => $petugas
            ], 200);
        } // return data petugas yang ditemukan dalam bentuk json

        return response([
            'message' => 'Petugas Not Found',
            'data' => null
        ], 404); // return message saat data petugas tidak ditemukan
    }

    // method untuk menambah 1 data petugas baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'imgUrl' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input 

        $petugas = Petugas::create($storeData);
        return response([
            'message' => 'Add Petugas Success',
            'data' => $petugas
        ], 200); // return data petugas baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $petugas = Petugas::find($id); // mencaari data product berdasarkan id

        if (is_null($petugas)) {
            return response ([
                'message' => 'Petugas Not Found',
                'data' => null
            ], 404);
        } // return message saat data petugas tidak ditemukan

        if ($petugas->delete()) {
            return response([
                'message' => 'Delete Petugas Success',
                'data' => $petugas
            ], 200);
        } // return message saat berhasil menghapus data petugas

        return response([
            'message' => 'Delete Petugas Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data petugas
    }

    //method untuk mengubah 1 data petugas (update)
    public function update(Request $request, $id)
    {
        $petugas = Petugas::find($id); // menbcaru data petugas berdasarkan id
        if (is_null($petugas)) {
            return response([
                'message' => 'Petugas Not Found',
                'data' => null
            ], 404);
        } // return message saat data petugas tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'imgUrl' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $petugas->nama = $updateData['nama'];
        $petugas->imgUrl = $updateData['imgUrl']; 

        if ($petugas->save()) {
            return response([
                'message' => 'Update Petugas Success',
                'data' => $petugas
            ], 200);
        } // return data petugas yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Petugas Failed',
            'data' => null,
        ], 400); // return message saat petugas gagal di edit
    }
}
