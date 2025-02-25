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
        $user       = Auth::user()->pegawai;
        $reviewArr  = Review::orderBy('created_at', 'desc');

        if ($user->penyedia) {
            if ($user->penyedia_id == 1 && $user->posisi_id == 10) {
                $posisi = 3;
                $review = $reviewArr->whereHas('petugas', function ($query) use ($posisi) {
                    $query->where('posisi_id', $posisi);
                });
            }

            if ($user->penyedia_id == 1 && $user->posisi_id == 11) {
                $posisi = 5;
                $review = $reviewArr->whereHas('petugas', function ($query) use ($posisi) {
                    $query->where('posisi_id', $posisi);
                });
            }

            if ($user->penyedia_id == 2) {
                $posisi = [3, 5];
                $review = $reviewArr->whereHas('petugas', function ($query) use ($posisi) {
                    $query->whereNotIn('posisi_id', $posisi);
                });
            }

            $review = $review->get();
        } else {
            $review = $reviewArr->get();
        }

        $posisi    = Pegawai::get();
        $penilaian = new Penilaian();
        $totalTemuan = $penilaian->total();

        return view('pages.index', compact('review', 'posisi', 'totalTemuan'));
    }
}
