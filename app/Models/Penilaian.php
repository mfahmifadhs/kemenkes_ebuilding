<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth;

class Penilaian extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_penilaian";
    protected $primaryKey = "id_penilaian";
    public $timestamps = false;

    protected $fillable = [
        'kode_penilaian',
        'penyedia_id',
        'pengawas_id',
        'petugas_id',
        'penempatan_id',
        'posisi_id',
        'area_id',
        'keterangan'
    ];

    public function penyedia() {
        return $this->belongsTo(Penyedia::class, 'penyedia_id');
    }

    public function pengawas() {
        return $this->belongsTo(Pegawai::class, 'pengawas_id');
    }

    public function petugas() {
        return $this->belongsTo(Pegawai::class, 'petugas_id');
    }

    public function penempatan() {
        return $this->belongsTo(Penempatan::class, 'penempatan_id');
    }

    public function posisi() {
        return $this->belongsTo(Posisi::class, 'posisi_id');
    }

    public function area() {
        return $this->belongsTo(GedungArea::class, 'area_id');
    }

    public function temuan() {
        return $this->hasMany(PenilaianDetail::class, 'penilaian_id');
    }

    public function foto() {
        return $this->hasMany(PenilaianFoto::class, 'penilaian_id');
    }

    public function total() {
        $data = self::orderBy('created_at', 'desc');
        $user = Auth::user()->pegawai;


        if ($user->penyedia) {
            $data = $data->where('pengawas_id', $user->id_pegawai);
        }

        $result = $data->get();

        return $result;
    }
}
