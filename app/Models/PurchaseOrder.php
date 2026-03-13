<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $primaryKey = 'po_id';

    public function getRouteKeyName()
    {
        return 'po_id';
    }

    protected $fillable = [
        'coverage_request_id',
        'user_id',
        'po_number',
        'no_order',
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
        'sto',
        'branch',
        'regional',
        'admin_no_order',
        'admin_no_order_input',
        'reason_cancel',
        'category_cancel',
        'po_document',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function coverageRequest()
    {
        return $this->belongsTo(CoverageRequest::class, 'coverage_request_id', 'req_id');
    }

    public function areaInfo()
    {
        return $this->belongsTo(Area::class, 'sto', 'sto');
    }

    public static function generateNoOrder()
    {
        do {
            $randomId = 'PO'.str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $exists = self::where('no_order', $randomId)->exists();
        } while ($exists);

        return $randomId;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->odp && (empty($model->sto) || $model->isDirty('odp'))) {
                $parts = explode('-', $model->odp);
                if (count($parts) >= 2) {
                    $sto = strtoupper(trim($parts[1]));

                    // Specific mapping exceptions
                    $exceptions = [
                        'CKN' => 'GBC',
                        'MBS' => 'CID',
                        'KBB' => 'KBY',
                        'GBR' => 'GBI',
                    ];

                    $model->sto = $exceptions[$sto] ?? $sto;
                }
            }
        });
    }
}
