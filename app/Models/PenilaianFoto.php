<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianFoto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_penilaian_foto";
    protected $primaryKey = "id_foto";
    public $timestamps = false;

    protected $fillable = [
        'penilaian_id',
        'foto_temuan'
    ];


    public function penilaian() {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }
}
