<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => '000001',
            'password' => 'TIFConnect2026',
            'role' => 'Super Admin',
        ]);

        User::create([
            'username' => '000002',
            'password' => 'TIFConnect2026',
            'role' => 'Admin',
        ]);

        User::create([
            'username' => '100001',
            'password' => 'TIFConnect2026',
            'role' => 'ISP',
            'pic_isp' => 'John Doe',
            'isp_brand' => 'ISP Provider A',
            'isp_name' => 'ISP Provider A',
            'area' => 'JABODETABEK',
        ]);
    }
}
