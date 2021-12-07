<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // tambahkan ini
use Validator; // import library untuk validasi
use App\Models\Produk; // import model Produk

class ProdukController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $produks = Produk::all(); // mengambil semua data produk

        if (count($produks) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $produks
            ], 200);
        } // return data semua produk dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data produk kosong
    }

    // method untuk menampilkan 1 data produk (search)
    public function show($id)
    {
        $produk = Produk::find($id); // mencari data produk berdasarkan id

        if (!is_null($produk)) {
            return response([
                'message' => "Retrieve Produk Success",
                'data' => $produk
            ], 200);
        } // return data produk yang ditemukan dalam bentuk json

        return response([
            'message' => 'Produk Not Found',
            'data' => null
        ], 404); // return message saat data produk tidak ditemukan
    }

    // method untuk menambah 1 data produk baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'imgUrl' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input 

        $produk = Produk::create($storeData);
        return response([
            'message' => 'Add Produk Success',
            'data' => $produk
        ], 200); // return data produk baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $produk = Produk::find($id); // mencaari data product berdasarkan id

        if (is_null($produk)) {
            return response ([
                'message' => 'Produk Not Found',
                'data' => null
            ], 404);
        } // return message saat data produk tidak ditemukan

        if ($produk->delete()) {
            return response([
                'message' => 'Delete Produk Success',
                'data' => $produk
            ], 200);
        } // return message saat berhasil menghapus data produk

        return response([
            'message' => 'Delete Produk Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data produk
    }

    //method untuk mengubah 1 data produk (update)
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id); // menbcaru data produk berdasarkan id
        if (is_null($produk)) {
            return response([
                'message' => 'Produk Not Found',
                'data' => null
            ], 404);
        } // return message saat data produk tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'imgUrl' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $produk->nama = $updateData['nama']; 
        $produk->deskripsi = $updateData['deskripsi']; 
        $produk->harga = $updateData['harga'];
        $produk->imgUrl = $updateData['imgUrl'];

        if ($produk->save()) {
            return response([
                'message' => 'Update Produk Success',
                'data' => $produk
            ], 200);
        } // return data produk yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Produk Failed',
            'data' => null,
        ], 400); // return message saat produk gagal di edit
    }
}
