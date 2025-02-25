<?php

namespace App\Http\Controllers;

use App\Models\Penyedia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenyediaController extends Controller
{
    public function show()
    {
        $data = Penyedia::get();
        return view('pages.penyedia.show', compact('data'));
    }

    public function store(Request $request)
    {
        $id = Penyedia::withTrashed()->count() + 1;
        $tambah = new Penyedia();
        $tambah->id_penyedia   = $id;
        $tambah->nama_penyedia = $request->penyedia;
        $tambah->status        = $request->status;
        $tambah->created_at    = Carbon::now();
        $tambah->save();

        return redirect()->route('penyedia')->with('success', 'Berhasil Menambahkan');
    }

    public function update(Request $request, $id)
    {
        Penyedia::where('id_penyedia', $id)->update([
            'nama_penyedia' => $request->penyedia,
            'status'        => $request->status
        ]);

        return redirect()->route('penyedia')->with('success', 'Berhasil Memperbaharui');
    }
}
