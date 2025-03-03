<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Pegawai;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user           = Auth::user()->pegawai;
        $pengawas       = Pegawai::where('posisi_id', 10)->get();
        $totalReview    = $this->totalReview();
        $totalReviewer  = $this->totalReviewer();
        $totalPenilaian = $this->totalPenilaian();

        $posisi    = Pegawai::get();
        $penilaian = new Penilaian();
        $totalTemuan = $penilaian->total();

        return view('pages.index', compact('pengawas','posisi', 'totalTemuan', 'totalReview', 'totalReviewer', 'totalPenilaian'));
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

    public function totalReviewer()
    {
        $user = Auth::user()->pegawai;
        $data = Review::select('no_telepon', db::raw('sum(nilai) as total'))->groupBy('no_telepon')->orderBy('total', 'desc')->take(10);

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

    public function totalPenilaian()
    {
        $user = Auth::user();
        $data = Penilaian::select('petugas_id', db::raw('sum(nilai) as total'))->groupBy('petugas_id')->orderBy('total', 'desc')->take(10);

        if ($user->role_id == 4) {
            $result = $data->where('pengawas_id', $user->pegawai_id)->get();
        } else {
            $result = $data->get();
        }
        return $result;
    }
}
