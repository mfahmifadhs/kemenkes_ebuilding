<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_penilaian_detail";
    protected $primaryKey = "id_detail";
    public $timestamps = false;

    protected $fillable = [
        'penilaian_id',
        'kriteria_id',
        'keterangan',
        'foto_temuan'
    ];


    public function kriteria() {
        return $this->belongsTo(PenilaianKriteria::class, 'kriteria_id');
    }

    public function penilaian() {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }
}
