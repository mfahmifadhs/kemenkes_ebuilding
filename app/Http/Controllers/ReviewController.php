<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Posisi;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;

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


    public function show(Request $request)
    {
        $data    = Review::count();
        $bulan   = $request->get('bulan');
        $tahun   = $request->get('tahun');
        $posisi  = $request->get('posisi');
        $petugas = $request->get('petugas');

        $posisiList  = $this->posisi();
        $totalReview = $this->totalReview();

        return view('pages.review.show', compact('data', 'bulan', 'tahun', 'posisi', 'petugas', 'posisiList', 'totalReview'));
    }

    public function select(Request $request)
    {
        $role    = Auth::user()->role_id;
        $user    = Auth::user()->pegawai;
        $bulan   = $request->bulan;
        $tahun   = $request->tahun;
        $posisi  = $request->posisi;
        $petugas = $request->petugas;

        $dataArr  = Review::orderBy('created_at', 'desc');
        $no       = 1;
        $response = [];


        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                $posisiId = 3;
                $data = $dataArr->whereHas('petugas', function ($query) use ($posisiId) {
                    $query->where('posisi_id', $posisiId);
                });
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                $posisiId = 5;
                $data = $dataArr->whereHas('petugas', function ($query) use ($posisiId) {
                    $query->where('posisi_id', $posisiId);
                });
            }

            if ($user->penyedia_id == 2) {
                $posisiId = [3,5];
                $data = $dataArr->whereHas('petugas', function ($query) use ($posisiId) {
                    $query->whereNotIn('posisi_id', $posisiId);
                });
            }
        }

        if ($bulan || $tahun || $posisi || $petugas) {

            if ($request->bulan) {
                $result = $data->whereMonth('created_at', $request->bulan);
            }

            if ($request->tahun) {
                $result = $data->whereYear('created_at', $request->tahun);
            }

            if ($posisi) {
                $result = $data->whereHas('petugas', function ($query) use ($posisi) {
                    $query->where('posisi_id', $posisi);
                });
            }

            if ($petugas) {
                $result = $data->whereHas('petugas', function ($query) use ($petugas) {
                    $query->where('id_pegawai', $petugas);
                });
            }

            $result = $result->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {
            $aksi   = '';

            if ($row->petugas->foto_pegawai) {
                $foto = '<img src="' . asset('dist/img/foto_pegawai/' . $row->petugas->foto_pegawai) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/128/149/149071.png" class="img-fluid" alt="">';
            }

            $response[] = [
                'no'            => $no,
                'id'            => $row->id_penilaian,
                'aksi'          => $aksi,
                'foto'          => $foto,
                'tanggal'       => Carbon::parse($row->created_at)->isoFormat('DD MMMM Y'),
                'posisi'        => $row->petugas->posisi->nama_posisi,
                'notelp'        => $row->no_telepon,
                'petugas'       => $row->petugas?->nama_pegawai,
                'area'          => $row->area?->gedung->nama_gedung.' '.$row->area?->nama_area,
                'nilai'         => '<i class="fas fa-star text-warning"></i>'.$row->nilai,
                'keterangan'    => $row->keterangan ?? ''
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function posisi()
    {
        $user = Auth::user()->pegawai;
        $data = Posisi::orderBy('id_posisi', 'asc');

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                $result = $data->where('id_posisi', 3)->get();
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                $result = $data->where('id_posisi', 5)->get();
            }

            if ($user->penyedia_id == 2) {
                $result = $data->whereNotIn('id_posisi', [3, 5])->get();
            }
        } else {
            $result = $data->get();
        }
        return $result;
    }


    public function totalReview()
    {
        $user = Auth::user()->pegawai;
        $data = Review::select('petugas_id', db::raw('sum(nilai) as total'))->groupBy('petugas_id')->orderBy('total', 'desc')->take(10);

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                $posisi = 3;
                $result = $data->whereHas('petugas', function ($query) use ($posisi) {
                    $query->where('posisi_id', $posisi);
                });
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                $posisi = 5;
                $result = $data->whereHas('petugas', function ($query) use ($posisi) {
                    $query->where('posisi_id', $posisi);
                });
            }

            if ($user->penyedia_id == 2) {
                $posisi = [3,5];
                $result = $data->whereHas('petugas', function ($query) use ($posisi) {
                    $query->whereNotIn('posisi_id', $posisi);
                });
            }
            $result = $result->get();
        } else {
            $result = $data->get();
        }

        return $result;
    }
}
