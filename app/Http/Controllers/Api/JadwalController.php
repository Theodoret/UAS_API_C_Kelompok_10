<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // tambahkan ini
use Validator; // import library untuk validasi
use App\Models\Jadwal; // import model Jadwal

class JadwalController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $jadwals = Jadwal::all(); // mengambil semua data jadwal

        if (count($jadwals) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwals
            ], 200);
        } // return data semua jadwal dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data jadwal kosong
    }

    // method untuk menampilkan 1 data jadwal (search)
    public function show($id)
    {
        $jadwal = Jadwal::find($id); // mencari data jadwal berdasarkan id

        if (!is_null($jadwal)) {
            return response([
                'message' => "Retrieve Jadwal Success",
                'data' => $jadwal
            ], 200);
        } // return data jadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ], 404); // return message saat data jadwal tidak ditemukan
    }

    // method untuk menambah 1 data jadwal baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'telp' => 'required',
            'tanggal' => 'required',
            'pelayanan' => 'required',
            'petugas' => 'required', 
            'imgUrl' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input 

        $jadwal = Jadwal::create($storeData);
        $arrayJadwal = [$jadwal];

        return response([
            'message' => 'Add Jadwal Success',
            'data' => $arrayJadwal
        ], 200); // return data jadwal baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id); // mencaari data product berdasarkan id

        if (is_null($jadwal)) {
            return response ([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404);
        } // return message saat data jadwal tidak ditemukan

        if ($jadwal->delete()) {
            $arrayJadwal = [$jadwal];
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $arrayJadwal
            ], 200);
        } // return message saat berhasil menghapus data jadwal

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data jadwal
    }

    //method untuk mengubah 1 data jadwal (update)
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id); // menbcaru data jadwal berdasarkan id
        if (is_null($jadwal)) {
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404);
        } // return message saat data jadwal tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'telp' => 'required',
            'tanggal' => 'required',
            'pelayanan' => 'required',
            'petugas' => 'required', 
            'imgUrl' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $jadwal->nama = $updateData['nama'];
        $jadwal->telp = $updateData['telp'];
        $jadwal->tanggal = $updateData['tanggal'];
        $jadwal->pelayanan = $updateData['pelayanan'];
        $jadwal->petugas = $updateData['petugas'];
        $jadwal->imgUrl = $updateData['imgUrl'];

        if ($jadwal->save()) {
            $arrayJadwal = [$jadwal];
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $arrayJadwal
            ], 200);
        } // return data jadwal yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Jadwal Failed',
            'data' => null,
        ], 400); // return message saat jadwal gagal di edit
    }
}
