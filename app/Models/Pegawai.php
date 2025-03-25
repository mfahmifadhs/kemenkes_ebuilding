<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pegawai extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_pegawai";
    protected $primaryKey = "id_pegawai";
    public $timestamps = false;

    protected $fillable = [
        'uker_id',
        'penempatan_id',
        'posisi_id',
        'nama_pegawai',
        'nik',
        'nip',
        'jenis_kelamin',
        'email',
        'no_telepon',
        'tanggal_masuk',
        'tanggal_keluar',
        'foto_pegawai'
    ];

    public function uker()
    {
        return $this->belongsTo(UnitKerja::class, 'uker_id');
    }

    public function penyedia()
    {
        return $this->belongsTo(Penyedia::class, 'penyedia_id');
    }

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id');
    }

    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class, 'penempatan_id');
    }

    public function area()
    {
        return $this->belongsTo(GedungArea::class, 'area_id');
    }

    public function penilaianHarian()
    {
        return $this->hasMany(Penilaian::class, 'pengawas_id')
            ->select('petugas_id', \DB::raw('COUNT(*) as total_penilaian'))
            ->whereDate('created_at', Carbon::today())
            ->groupBy('petugas_id');
    }

    public function totalReview()
    {
        return $this->hasMany(Review::class, 'petugas_id');
    }

    public function totalNilai()
    {
        return $this->hasMany(Penilaian::class, 'petugas_id');
    }

    public function totalTemuan()
    {
        return $this->hasMany(Review::class, 'petugas_id');
    }
}
