<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GedungArea extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_gedung_area";
    protected $primaryKey = "id_area";
    public $timestamps = false;

    protected $fillable = [
        'posisi_id',
        'gedung_id',
        'nama_area',
        'keterangan'
    ];

    public function gedung() {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }
}
