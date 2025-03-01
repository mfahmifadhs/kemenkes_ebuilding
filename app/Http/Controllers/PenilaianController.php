<?php

namespace App\Http\Controllers;

use App\Models\Posisi;
use App\Models\Pegawai;
use App\Models\Penyedia;
use App\Models\Penempatan;
use App\Models\GedungArea;
use App\Models\Penilaian;
use App\Models\PenilaianDetail;
use App\Models\PenilaianFoto;
use App\Models\PenilaianKriteria;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class PenilaianController extends Controller
{
    public function show(Request $request)
    {
        $data       = Penilaian::count();
        $penyedia   = Penyedia::get();
        $penempatan = Penempatan::get();
        $posisi     = Posisi::get();

        $penyediaSelected   = $request->penyedia;
        $penempatanSelected = $request->penempatan;
        $posisiSelected     = $request->posisi;
        $statusSelected     = $request->status;

        return view('pages.penilaian.show', compact('data', 'penyedia', 'penempatan', 'posisi', 'penyediaSelected', 'penempatanSelected', 'posisiSelected', 'statusSelected'));
    }

    public function detail($id)
    {
        $data = Penilaian::where('id_penilaian', $id)->first();
        return view('pages.penilaian.detail', compact('data'));
    }

    public function select(Request $request)
    {
        $role       = Auth::user()->role_id;
        $user       = Auth::user()->pegawai;
        $pengawas   = $request->pengawas;
        $petugas    = $request->petugas;
        $penempatan = $request->penempatan;
        $posisi     = $request->posisi;
        $status     = $request->status;

        $dataArr  = Penilaian::orderBy('status', 'asc')->orderBy('created_at', 'desc');
        $no       = 1;
        $response = [];

        if ($role == 4) {
            $data = $dataArr->where('pengawas_id', $user->id_pegawai);
        } else {
            $data = $dataArr;
        }

        if ($pengawas || $petugas || $penempatan || $posisi || $status) {

            if ($pengawas) {
                $res = $data->where('pengawas_id', $pengawas);
            }

            if ($petugas) {
                $res = $data->where('petugas_id', $petugas);
            }

            if ($penempatan) {
                $res = $data->where('penempatan_id', $penempatan);
            }

            if ($posisi) {
                $res = $data->where('posisi_id', $posisi);
            }

            if ($status) {
                $res = $data->where('status', $status);
            }

            $result = $res->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($row->petugas->foto_pegawai) {
                $foto = '<img src="' . asset('dist/img/foto_pegawai/' . $row->petugas->foto_pegawai) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/128/149/149071.png" class="img-fluid" alt="">';
            }

            if (!$row->status) {
                $aksi .= '
                    <a href="' . route('penilaian.approval', $row->id_penilaian) . '" class="btn btn-default btn-xs bg-warning rounded border-dark my-1">
                        <i class="fas fa-file-signature p-1" style="font-size: 12px;"></i>
                    </a>
                ';

                if ($role != 3) {
                    $aksi .= '
                        <a href="' . route('penilaian.edit', $row->id_penilaian) . '" class="btn btn-default btn-xs bg-warning rounded border-dark my-1">
                            <i class="fas fa-edit p-1" style="font-size: 12px;"></i>
                        </a>
                    ';
                }

                $status .= '<i class="fas fa-clock text-warning"></i>';
            } else if ($row->status) {
                $aksi .= '
                    <a href="' . route('penilaian.detail', $row->id_penilaian) . '" class="btn btn-default btn-xs bg-primary rounded border-dark">
                        <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                    </a>
                ';
            }

            if ($row->status == 'true') {
                $status .= '<i class="fas fa-check-circle text-success"></i>';
            } else if ($row->status == 'false') {
                $status .= '<i class="fas fa-times-circle text-danger"></i>';
            }

            $response[] = [
                'no'            => $no,
                'id'            => $row->id_penilaian,
                'aksi'          => $aksi,
                'foto'          => $foto,
                'tanggal'       => $row->created_at,
                'kode'          => $row->kode_penilaian,
                'penyedia'      => $row->penyedia?->nama_penyedia,
                'posisi'        => $row->posisi?->nama_posisi,
                'pengawas'      => $row->pengawas?->nama_pegawai,
                'petugas'       => $row->petugas?->nama_pegawai,
                'penempatan'    => $row->penempatan?->nama_penempatan,
                'temuan'        => $row->temuan->count(),
                'nilai'         => '<i class="fas fa-star text-warning"></i>'.$row->nilai,
                'keterangan'    => $row->keterangan ?? null,
                'status'        => $row->status == 'true' ? 'Diterima' : ($row->status == 'false' ? 'Ditolak ' . $row->keterangan_tolak : 'Pending'),
                'statusIcon'    => $status,
                'detailTemuan'  => $row->temuan->where('status', 'true')->map(function ($item) {
                    return optional($item->kriteria)->nama_kriteria;
                })->filter()->implode(', ')
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $proses     = '';
        $pegawai    = '';
        $area       = '';
        $kriteria   = '';
        $user       = Auth::user()->pegawai;
        $posisiArr  = Posisi::orderBy('nama_posisi', 'asc');

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                $posisi = $posisiArr->where('id_posisi', 3)->get();
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                $posisi = $posisiArr->where('id_posisi', 5)->get();
            }

            if ($user->penyedia_id == 2) {
                $posisi = $posisiArr->whereNotIn('id_posisi', [3, 5])->get();
            }
        } else {
            $posisi = $posisiArr->get();
        }

        if ($request->proses == 'proses') {
            $proses     = $request->proses;
            $posisiArea = $request->posisi == 5 ? 5 : 0;
            $posisi     = Posisi::where('id_posisi', $request->posisi)->get();
            $pegawai    = Pegawai::where('posisi_id', $request->posisi)->get();
            $area       = GedungArea::where('posisi_id', $posisiArea)->get();
            $kriteria   = PenilaianKriteria::where('posisi_id', $request->posisi)->get();
            $posisiSelected = $request->posisi;
        }

        return view('pages.penilaian.create', compact('posisi', 'proses', 'pegawai', 'area', 'kriteria'));
    }

    public function store(Request $request)
    {
        $pegawai = Pegawai::where('id_pegawai', $request->pegawai)->first();
        $id_penilaian = Penilaian::withTrashed()->count() + 1;
        $tambah = new Penilaian();
        $tambah->id_penilaian   = $id_penilaian;
        $tambah->penyedia_id    = $pegawai->penyedia_id;
        $tambah->pengawas_id    = Auth::user()->pegawai_id;
        $tambah->petugas_id     = $pegawai->id_pegawai;
        $tambah->penempatan_id  = $pegawai->penempatan_id;
        $tambah->posisi_id      = $pegawai->posisi_id;
        $tambah->kode_penilaian = time();
        $tambah->area_id        = $request->area;
        $tambah->nilai          = $request->rating;
        $tambah->keterangan     = $request->keterangan;
        $tambah->created_at     = Carbon::now();
        $tambah->save();

        if ($request->temuan) {
            $temuan = $request->temuan;
            foreach ($temuan as $kriteria_id) {
                $id_detail = PenilaianDetail::withTrashed()->count() + 1;
                $detail = new PenilaianDetail();
                $detail->id_detail      = $id_detail;
                $detail->penilaian_id   = $id_penilaian;
                $detail->kriteria_id    = $kriteria_id;
                $detail->created_at     = Carbon::now();
                $detail->save();
            }
        }

        if ($request->file('foto')) {
            $foto = $request->file('foto');
            foreach ($foto as $i => $foto_temuan) {

                if ($foto_temuan) {
                    $fileName = time() . '_' . $foto_temuan->getClientOriginalName();
                    $request->foto[$i]->move(public_path('dist/img/foto_temuan'), $fileName);
                }

                $id_foto = PenilaianFoto::withTrashed()->count() + 1;
                $fotoTemuan = new PenilaianFoto();
                $fotoTemuan->id_foto        = $id_foto;
                $fotoTemuan->penilaian_id   = $id_penilaian;
                $fotoTemuan->foto_temuan    = $fileName;
                $fotoTemuan->created_at     = Carbon::now();
                $fotoTemuan->save();
            }
        }

        return redirect()->route('penilaian.approval', $id_penilaian)->with('success', 'Berhasil Menambahkan');
    }

    public function edit($id)
    {
        $data = Penilaian::where('id_penilaian', $id)->first();
        $user = Auth::user()->pegawai;

        if (!$data) {
            return back()->with('failed', 'Data Tidak Ditemukan');
        }

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                if ($data->petugas->posisi_id == 3 && $user->posisi_id != 10) {
                    return back();
                }
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                if ($data->petugas->posisi_id == 5 && $user->posisi_id != 11) {
                    return back();
                }
            }
        }

        $kriteria   = PenilaianKriteria::where('posisi_id', $data->posisi_id)->get();
        $posisiArea = $data->posisi_id == 5 ? 5 : 0;
        $area     = GedungArea::where('posisi_id', $posisiArea)->get();

        return view('pages.penilaian.edit', compact('kriteria', 'data', 'area'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::where('id_pegawai', $request->pegawai)->first();

        Penilaian::where('id_penilaian', $id)->update([
            'penyedia_id'    => $pegawai->penyedia_id,
            'pengawas_id'    => Auth::user()->pegawai_id,
            'petugas_id'     => $pegawai->id_pegawai,
            'penempatan_id'  => $pegawai->penempatan_id,
            'posisi_id'      => $pegawai->posisi_id,
            'kode_penilaian' => time(),
            'area_id'        => $request->area,
            'keterangan'     => $request->keterangan,
            'created_at'     => Carbon::now(),
        ]);

        $kriteria = $request->kriteria;

        foreach ($kriteria as $i => $kriteria_id) {
            $cekDetail = $request->id_detail[$kriteria_id];
            $cekTemuan = $request->temuan[$kriteria_id];

            if ($cekDetail) {
                $penilaianDetail = PenilaianDetail::where('id_detail', $cekDetail)->where('kriteria_id', $kriteria_id)->first();

                if ($penilaianDetail) {
                    PenilaianDetail::where('id_detail', $cekDetail)->update([
                        'status' => $cekTemuan
                    ]);
                }
            } else {
                $cekPenilaian = PenilaianDetail::where('penilaian_id', $id)->where('kriteria_id', $kriteria_id)->first();

                if ($cekPenilaian) {
                    PenilaianDetail::where('id_detail', $cekPenilaian->id_detail)->update([
                        'status' => $cekTemuan
                    ]);
                } else {
                    if ($cekTemuan == 'true') {
                        $idDetailBaru = PenilaianDetail::withTrashed()->count() + 1;
                        $tambahBaru = new PenilaianDetail();
                        $tambahBaru->id_detail      = $idDetailBaru;
                        $tambahBaru->penilaian_id   = $id;
                        $tambahBaru->kriteria_id    = $kriteria_id;
                        $tambahBaru->created_at     = Carbon::now();
                        $tambahBaru->save();
                    }
                }
            }
        }

        if ($request->file('foto')) {
            $foto = $request->file('foto');
            foreach ($foto as $i => $foto_temuan) {

                if ($foto_temuan) {
                    $fileName = time() . '_' . $foto_temuan->getClientOriginalName();
                    $request->foto[$i]->move(public_path('dist/img/foto_temuan'), $fileName);
                }

                $id_foto = PenilaianFoto::withTrashed()->count() + 1;
                $fotoTemuan = new PenilaianFoto();
                $fotoTemuan->id_foto        = $id_foto;
                $fotoTemuan->penilaian_id   = $id;
                $fotoTemuan->foto_temuan    = $fileName;
                $fotoTemuan->created_at     = Carbon::now();
                $fotoTemuan->save();
            }
        }

        return redirect()->route('penilaian.detail', $id)->with('success', 'Berhasil Menyimpan');
    }

    public function delete($id)
    {
        PenilaianDetail::where('penilaian_id', $id)->delete();
        Penilaian::where('id_penilaian', $id)->delete();

        return redirect()->route('penilaian')->with('success', 'Berhasil Menghapus');
    }


    public function approval($id)
    {
        $data       = Penilaian::where('id_penilaian', $id)->first();

        return view('pages.penilaian.approval', compact('data'));
    }

    public function approvalStore(Request $request, $id)
    {
        if ($request->input('signature')) {
            $base64Signature = $request->input('signature');
            $imageData = explode(',', $base64Signature)[1];
            $decodedImage = base64_decode($imageData);
            $fileName = 'ttd_' . uniqid() . '.png';
            $destinationPath = public_path('dist/img/foto_ttd');
            $filePath = $destinationPath . '/' . $fileName;
            file_put_contents($filePath, $decodedImage);
        }

        if ($request->status == 'true') {
            Penilaian::where('id_penilaian', $id)->update([
                'status'   => 'true',
                'foto_ttd' => $fileName ?? null
            ]);
        } else {
            Penilaian::where('id_penilaian', $id)->update([
                'status'           => 'false',
                'keterangan_tolak' => $request->keterangan_tolak ?? null
            ]);
        }

        return redirect()->route('penilaian.detail', $id)->with('success', 'Berhasil Menyimpan');
    }
}
