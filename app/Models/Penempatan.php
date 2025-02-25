<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penempatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_penempatan";
    protected $primaryKey = "id_penempatan";
    public $timestamps = false;

    protected $fillable = [
        'nama_penempatan',
        'status'
    ];
}
