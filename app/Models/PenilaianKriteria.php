<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianKriteria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_penilaian_kriteria";
    protected $primaryKey = "id_kriteria";
    public $timestamps = false;

    protected $fillable = [
        'nama_kriteria',
        'posisi_id',
        'status'
    ];


    public function posisi() {
        return $this->belongsTo(Posisi::class, 'posisi_id');
    }
}
