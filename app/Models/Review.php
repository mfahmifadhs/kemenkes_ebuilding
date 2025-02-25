<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_review";
    protected $primaryKey = "id_review";
    public $timestamps = false;

    protected $fillable = [
        'petugas_id',
        'no_telepon',
        'nilai',
        'keterangan',
    ];

    public function petugas() {
        return $this->belongsTo(Pegawai::class, 'petugas_id');
    }

    public function area() {
        return $this->belongsTo(GedungArea::class, 'area_id');
    }
}
