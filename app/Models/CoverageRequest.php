<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverageRequest extends Model
{
    protected $primaryKey = 'req_id';

    protected $fillable = [
        'user_id',
        'brand',
        'isp_name',
        'area',
        'layanan',
        'paket',
        'kode_pra',
        'phone',
        'cust_name',
        'cust_add',
        'longlat',
        'bandwidth',
        'odp',
        'gpon',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class, 'coverage_request_id', 'req_id');
    }
}
