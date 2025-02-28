<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengawasController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenilaianKriteriaController;
use App\Http\Controllers\PenyediaController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('login-post', [AuthController::class, 'post'])->name('login-post');

Route::get('pegawai/review/{id}/{token}', [ReviewController::class, 'index']);
Route::post('review', [ReviewController::class, 'store'])->name('review');


Route::group(['middleware' => 'auth'], function () {
    Route::get('temuan', [TemuanController::class, 'show'])->name('temuan');
    Route::get('temuan/select', [TemuanController::class, 'select'])->name('temuan.select');

    Route::get('penilaian', [PenilaianController::class, 'show'])->name('penilaian');
    Route::get('penilaian/select', [PenilaianController::class, 'select'])->name('penilaian.select');
    Route::get('penilaian/detail/{id}', [PenilaianController::class, 'detail'])->name('penilaian.detail');

    Route::get('penilaian-temuan', [PenilaianController::class, 'show'])->name('penilaian-temuan');
    Route::get('penilaian-temuan/select', [PenilaianController::class, 'select'])->name('penilaian-temuan.select');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => ['access:manage']], function () {

        Route::get('penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::get('penilaian/edit/{id}', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::get('penilaian/persetujuan/{id}', [PenilaianController::class, 'approval'])->name('penilaian.approval');
        Route::get('penilaian/hapus/{id}', [PenilaianController::class, 'delete'])->name('penilaian.delete');
        Route::post('penilaian/persetujuan-store/{id}', [PenilaianController::class, 'approvalStore'])->name('penilaian.approval-store');
        Route::post('penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::post('penilaian/update/{id}', [PenilaianController::class, 'update'])->name('penilaian.update');

        Route::get('pegawai', [PegawaiController::class, 'show'])->name('pegawai');
        Route::get('pegawai/select', [PegawaiController::class, 'select'])->name('pegawai.select');
        Route::get('pegawai/qrcode/{id}', [PegawaiController::class, 'qrcode'])->name('pegawai.qrcode');
        Route::get('pegawai/detail/{id}', [PegawaiController::class, 'detail'])->name('pegawai.detail');
        Route::get('pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::get('pegawai/delete/{id}', [PegawaiController::class, 'delete'])->name('pegawai.delete');
        Route::post('pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::post('pegawai/update/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

    });

    Route::group(['middleware' => ['access:monitor']], function () {

        Route::get('kriteria', [PenilaianKriteriaController::class, 'show'])->name('kriteria');

    });

    Route::group(['middleware' => ['access:admin']], function () {

        Route::get('kriteria/store', [PenilaianKriteriaController::class, 'store'])->name('kriteria.store');
        Route::post('kriteria/update/{id}', [PenilaianKriteriaController::class, 'update'])->name('kriteria.update');

    });

    Route::group(['middleware' => ['access:master']], function () {

        Route::get('penyedia', [PenyediaController::class, 'show'])->name('penyedia');
        Route::post('penyedia/store', [PenyediaController::class, 'store'])->name('penyedia.store');
        Route::post('penyedia/update/{id}', [PenyediaController::class, 'update'])->name('penyedia.update');

        Route::get('user', [UserController::class, 'show'])->name('user');
        Route::post('user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');

    });
});
