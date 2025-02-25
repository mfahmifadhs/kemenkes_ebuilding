<?php

namespace App\Http\Controllers;

use App\Models\PenilaianDetail;
use Illuminate\Http\Request;
use Auth;

class TemuanController extends Controller
{
    public function show(Request $request)
    {
        $data       = PenilaianDetail::count();

        $penyediaSelected   = '';

        return view('pages.penilaian.temuan.show', compact('data'));
    }

    public function detail($id)
    {
        $data = PenilaianDetail::where('id_detail', $id)->first();
        return view('pages.penilaian.temuan.detail', compact('data'));
    }

    public function select(Request $request)
    {
        $role       = Auth::user()->role_id;
        $user       = Auth::user()->pegawai;

        $dataArr  = PenilaianDetail::orderBy('status', 'asc')->orderBy('created_at', 'desc');
        $no       = 1;
        $response = [];

        if ($role == 4) {
            $pengawas = $user->id_pegawai;
            $data     = $dataArr->whereHas('penilaian', function ($query) use ($pengawas) {
                $query->where('pengawas_id', $pengawas);
            });
        } else {
            $data = $dataArr;
        }

        $result = $data->get();
        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($row->penilaian->petugas->foto_pegawai) {
                $foto = '<img src="' . asset('dist/img/foto_pegawai/' . $row->penilaian->petugas->foto_pegawai) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/128/149/149071.png" class="img-fluid" alt="">';
            }

            if ($row->status == 'true') {
                $status .= '<i class="fas fa-check-circle text-success"></i>';
            } else {
                $status .= '<i class="fas fa-times-circle text-danger"></i>';
            }

            $response[] = [
                'no'            => $no,
                'id'            => $row->id_penilaian,
                'aksi'          => $aksi,
                'foto'          => $foto,
                'tanggal'       => $row->penilaian->created_at,
                'kode'          => $row->penilaian->kode_penilaian,
                'penyedia'      => $row->penilaian->penyedia?->nama_penyedia,
                'posisi'        => $row->penilaian->posisi?->nama_posisi,
                'pengawas'      => $row->penilaian->pengawas?->nama_pegawai,
                'petugas'       => $row->penilaian->petugas?->nama_pegawai,
                'temuan'        => $row->kriteria->nama_kriteria,
                'penempatan'    => $row->penilaian->area?->nama_area.' '.$row->penilaian->area?->gedung->nama_gedung,
                'status'        => $row->status == 'true' ? 'Diterima' : 'Ditolak, ' . $row->keterangan_tolak,
                'statusIcon'    => $status
            ];

            $no++;
        }

        return response()->json($response);
    }
}
