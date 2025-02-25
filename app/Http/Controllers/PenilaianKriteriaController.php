<?php

namespace App\Http\Controllers;

use App\Models\PenilaianKriteria;
use App\Models\Posisi;
use Illuminate\Http\Request;

class PenilaianKriteriaController extends Controller
{
    public function show()
    {
        $posisi = Posisi::get();
        $data   = PenilaianKriteria::get();
        return view('pages.penilaian.kriteria.show', compact('posisi','data'));
    }

    public function store(Request $request)
    {
        $id = PenilaianKriteria::withTrashed()->count() + 1;
        $tambah = new PenilaianKriteria();
        $tambah->id_kriteria   = $id;
        $tambah->nama_kriteria = $request->kriteria;
        $tambah->posisi_id     = $request->posisi;
        $tambah->status        = $request->status;
        $tambah->created_at    = Carbon::now();
        $tambah->save();

        return redirect()->route('kriteria')->with('success', 'Berhasil Menambahkan');
    }

    public function update(Request $request, $id)
    {
        PenilaianKriteria::where('id_kriteria', $id)->update([
            'nama_kriteria' => $request->kriteria,
            'posisi_id'     => $request->posisi,
            'status'        => $request->status
        ]);

        return redirect()->route('kriteria')->with('success', 'Berhasil Memperbaharui');
    }
}
