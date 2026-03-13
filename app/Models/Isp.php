<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Isp extends Model
{
    protected $primaryKey = 'isp_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'isp_code',
        'isp_brand',
        'isp_name',
        'area',
        'ba',
        'ca',
        'sid',
        'vlan',
        'layanan',
        'created_date',
    ];

    public static function generateIspCode(): string
    {
        $lastIsp = self::orderBy('isp_code', 'desc')->first();
        if (! $lastIsp) {
            return 'ISP001';
        }

        $lastCode = $lastIsp->isp_code;
        $number = (int) substr($lastCode, 3);

        return 'ISP'.str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }

    protected function casts(): array
    {
        return [
            'created_date' => 'date',
        ];
    }
}
