<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posisi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_posisi";
    protected $primaryKey = "id_posisi";
    public $timestamps = false;

    protected $fillable = [
        'nama_posisi'
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'posisi_id');
    }
}
