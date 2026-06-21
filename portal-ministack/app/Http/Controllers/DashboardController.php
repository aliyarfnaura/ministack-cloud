<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\UserSubscription;
use App\Models\Bucket;
use App\Services\MiniStackService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // $this->syncStorageUsage($user);

        // --- Sinkronkan pemakaian storage asli dari MiniStack ---
        //$this->syncStorageUsage($user);

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

        $realData = [
            'storage_used'  => round($storageUsedMb, 2),  // tampilkan MB langsung
            'storage_total' => $totalStorageGb * 1024, 
            'package'       => $paketName,
            'buckets_count' => Bucket::where('user_id', $user->id)->count(),
        ];

        // kirim data ke tampilan dashboard
        return view('dashboard', compact('user', 'realData'));
    }

    /**
     * Tarik pemakaian storage asli dari MiniStack, lalu simpan ke DB.
     */
    protected function syncStorageUsage($user): void
    {
        $credential = $user->credential;
        if (!$credential) {
            return; // user belum punya credential, skip
        }

        try {
            $secretKey = Crypt::decryptString($credential->secret_access_key);
            $ministack = new MiniStackService();

            foreach ($user->buckets as $bucket) {
                $usageMb = $ministack->getBucketUsageMb(
                    $credential->ministack_account_id,
                    $credential->access_key_id,
                    $secretKey,
                    $bucket->bucket_name
                );

                $bucket->update(['used_storage_mb' => $usageMb]);
            }
        } catch (\Exception $e) {
            // kalau MiniStack lagi mati/error, dashboard tetap tampil pakai data DB terakhir
            Log::warning('Gagal sinkronisasi storage MiniStack: ' . $e->getMessage());
        }
    }
}