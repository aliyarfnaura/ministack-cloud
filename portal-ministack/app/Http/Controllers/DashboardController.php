<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSubscription;
use App\Models\Bucket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // mengencek apakah user punya langganan yang sedang aktif
        $activeSub = UserSubscription::where('user_id', $user->id)
                        ->where('status', 'active')
                        ->first();

        // jika ada maka ambil data nama paket dan kuotanya dari tabel subscription
        $paketName = 'Belum Berlangganan';
        $totalStorageGb = 0;
        
        if ($activeSub) {
            $paketName = $activeSub->plan->name;
            $totalStorageGb = $activeSub->plan->storage_quota_gb;
        }

        // menghitung total penggunaan storage dari tabel buckets
        $storageUsedMb = Bucket::where('user_id', $user->id)->sum('used_storage_mb');
        $storageUsedGb = $storageUsedMb / 1024;

        // data asli yang akan dikirim ke halaman view
        $realData = [
            'storage_used'  => round($storageUsedGb, 2),
            'storage_total' => $totalStorageGb,
            'package'       => $paketName,
            'buckets_count' => Bucket::where('user_id', $user->id)->count(),
        ];

        // kirim data ke tampilan dashboard
        return view('dashboard', compact('user', 'realData'));
    }
}