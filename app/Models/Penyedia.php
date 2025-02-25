<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penyedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_penyedia";
    protected $primaryKey = "id_penyedia";
    public $timestamps = false;

    protected $fillable = [
        'nama_penyedia',
        'status'
    ];
}
