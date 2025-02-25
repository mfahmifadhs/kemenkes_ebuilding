<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gedung extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_gedung";
    protected $primaryKey = "id_gedung";
    public $timestamps = false;

    protected $fillable = [
        'nama_gedung',
        'status'
    ];
}
