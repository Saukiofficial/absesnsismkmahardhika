<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SystemController extends Controller
{
    public function toggle()
    {

        $currentStatus = Cache::get('attendance_system_status', 'open');

        $newStatus = $currentStatus === 'open' ? 'closed' : 'open';
        Cache::forever('attendance_system_status', $newStatus);

        $statusText = $newStatus === 'open' ? 'DIBUKA' : 'DITUTUP';

        return back()->with('success', "Sistem Absensi Berhasil {$statusText}.");
    }
}
