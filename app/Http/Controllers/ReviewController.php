<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($id, $token)
    {
        $data    = Pegawai::where('id_pegawai', $id)->first();
        $selesai = '';

        return view('review', compact('data', 'id', 'selesai'));
    }

    public function store(Request $request)
    {
        $id      = $request->petugas_id;
        $data    = Pegawai::where('id_pegawai', $id)->first();
        $selesai = 'true';

        $tambah = new Review();
        $tambah->petugas_id = $request->petugas_id;
        $tambah->area_id    = $data->area_id;
        $tambah->no_telepon = $request->no_telepon;
        $tambah->nilai      = $request->rating;
        $tambah->keterangan = $request->keterangan;
        $tambah->save();

        return view('review', compact('data', 'id', 'selesai'));
    }
}
