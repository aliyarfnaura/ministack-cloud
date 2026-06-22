<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IaasTransactionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rute di bawah ini secara otomatis dimuat oleh RouteServiceProvider dan
| semuanya akan dimasukkan ke dalam grup "api".
|
*/

// ==========================================
// Autentikasi API (bebas CSRF, untuk Postman)
// ==========================================
Route::post('/auth/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    if (! Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Email atau password salah.'
        ], 401);
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Hapus token lama agar tidak menumpuk
    $user->tokens()->delete();
    $token = $user->createToken('PostmanToken')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil.',
        'token'   => $token,
        'user'    => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ],
    ]);
});

// Grup rute yang dilindungi oleh autentikasi token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // ==========================================
    // API Read Data (Sesuai Jobdesk Log Penyewaan)
    // ==========================================
    Route::get('/iaas/subscriptions', [IaasTransactionController::class, 'getUserSubscriptions']);
    Route::get('/iaas/logs', [IaasTransactionController::class, 'getUserActivityLogs']);

    // ==========================================
    // API Transaksi IaaS (Sisi Pelanggan/Customer)
    // ==========================================
    Route::post('/iaas/checkout', [IaasTransactionController::class, 'checkout']);
    Route::post('/iaas/upgrade',  [IaasTransactionController::class, 'upgrade']);

    // ==========================================
    // API Kontrol (Sisi Administrator)
    // ==========================================
    Route::patch('/admin/payments/{id}/verify', [IaasTransactionController::class, 'verifyPayment']);
    Route::patch('/admin/credentials/{id}/toggle', [IaasTransactionController::class, 'toggleCredentialStatus']);
    
    // ==========================================
    // API CRUD Manajemen Pengguna (Sisi Administrator)
    // ==========================================
    Route::apiResource('users', UserController::class);
});