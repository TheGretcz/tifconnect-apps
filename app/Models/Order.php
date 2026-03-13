<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'layanan',
        'teritory',
        'paket',
        'no_order',
        'olo',
        'sto',
        'nd',
        'status_order',
        'keterangan',
    ];

    public function areaInfo()
    {
        return $this->belongsTo(Area::class, 'sto', 'sto');
    }
}
