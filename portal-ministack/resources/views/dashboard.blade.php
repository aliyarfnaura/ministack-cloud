@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrapper">

    <div class="welcome-banner glass">
        <div class="welcome-left">
            <div class="avatar-circle">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="welcome-title">Halo, {{ $user->name }}! 👋</h2>
                <p class="welcome-sub">Selamat datang di <strong>ChromaStack Cloud</strong></p>
                <span class="badge-package">
                    <i class="fa fa-box"></i> {{ $realData['package'] }}
                </span>
            </div>
        </div>
        <div class="welcome-right">
            <div class="uptime-badge">
                <i class="fa fa-circle" style="color:#a8ff78;"></i>
                Uptime: 99.9%
            </div>
        </div>
    </div>

    <div class="stats-grid">

        <div class="stat-card glass card-storage">
            <div class="stat-icon">
                <i class="fa fa-database"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Storage Terpakai</p>
                <p class="stat-value">{{ $realData['storage_used'] }} MB <span>/ {{ $realData['storage_total'] }} MB</span></p>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width: {{ $realData['storage_total'] > 0 ? ($realData['storage_used'] / $realData['storage_total']) * 100 : 0 }}%"></div>
                </div>
                <p class="stat-note">{{ $realData['storage_total'] - $realData['storage_used'] }} MB tersisa</p>
            </div>
        </div>

        <div class="stat-card glass card-instance">
            <div class="stat-icon">
                <i class="fa fa-server"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">S3 Buckets Aktif</p>
                <p class="stat-value">{{ $realData['buckets_count'] }} <span>Buckets</span></p>
                <p class="stat-note">Jumlah bucket penyimpanan aktif</p>
            </div>
        </div>

    </div>

    <div class="info-panel glass">
        <h3 class="panel-title">
            <i class="fa fa-info-circle"></i> Info Akun
        </h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-key"><i class="fa fa-user"></i> Nama</span>
                <span class="info-val">{{ $user->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-key"><i class="fa fa-envelope"></i> Email</span>
                <span class="info-val">{{ $user->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-key"><i class="fa fa-calendar"></i> Bergabung</span>
                <span class="info-val">{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-key"><i class="fa fa-box"></i> Paket</span>
                <span class="info-val candy-text">{{ $realData['package'] }}</span>
            </div>
        </div>
    </div>

    <div class="coming-soon glass">
        <div class="cs-icon">🚀</div>
        <h3>MiniStack Integration — Coming Soon</h3>
        <p>Fase berikutnya: integrasi dengan MiniStack untuk manajemen S3 Bucket nyata.</p>
        <div class="cs-tags">
            <span class="cs-tag">OpenStack</span>
            <span class="cs-tag">MiniStack</span>
            <span class="cs-tag">REST API</span>
            <span class="cs-tag">S3 Storage</span>
        </div>
    </div>

</div>
@endsection