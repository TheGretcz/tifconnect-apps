<?php

namespace App\Exports;

use App\Models\User;

class UserExport
{
    public static function download()
    {
        $filename = 'all_users_'.date('Y-m-d_His').'.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $handle = fopen('php://output', 'w');

        // CSV Headers
        fputcsv($handle, [
            'User ID',
            'Username',
            'Role',
            'PIC ISP',
            'ISP Brand',
            'ISP Name',
            'Area',
            'Created At',
        ]);

        $users = User::orderBy('created_at', 'desc')->get();

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->user_id,
                $user->username,
                $user->role,
                $user->pic_isp,
                $user->isp_brand,
                $user->isp_name,
                $user->area,
                $user->created_at,
            ]);
        }

        fclose($handle);
        exit;
    }
}
