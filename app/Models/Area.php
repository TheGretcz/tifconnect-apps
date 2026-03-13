<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'sto',
        'nama_sto',
        'area',
        'regional',
        'branch',
    ];
}
