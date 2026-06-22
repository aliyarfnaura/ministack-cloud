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
        @php
            $usagePercent = $realData['storage_total'] > 0
                ? ($realData['storage_used'] / $realData['storage_total']) * 100
                : 0;

            $remainingMb = max(0, $realData['storage_total'] - $realData['storage_used']);
            $isOverQuota = $realData['storage_used'] > $realData['storage_total'];

            $barClass = $isOverQuota ? 'is-danger' : ($usagePercent >= 80 ? 'is-warning' : '');
        @endphp
        <div class="stat-card glass card-storage">
            <div class="stat-icon">
                <i class="fa fa-database"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Storage Terpakai</p>
                <p class="stat-value">{{ $realData['storage_used'] }} MB <span>/ {{ $realData['storage_total'] }} MB</span></p>
                <div class="progress-bar-wrap">
                    <div class="progress-bar {{ $barClass }}" style="width: {{ min(100, $usagePercent) }}%"></div>
                </div>
                @if ($isOverQuota)
                    <p class="stat-note is-danger"><i class="fa fa-triangle-exclamation"></i> Kuota terlampaui</p>
                @else
                    <p class="stat-note">{{ $remainingMb }} MB tersisa</p>
                @endif
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

    @if (session('success'))
        <div class="alert" style="background: rgba(0, 240, 255, 0.12); color:#00a8ba; border:1px solid rgba(0,240,255,0.25); margin-top: 20px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error" style="margin-top: 20px;">
            <i class="fa fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Kartu checkout --}}
    @if (!$blockCheckout || ($blockCheckout && $blockReason && str_contains($blockReason, 'menunggu')))
        <div class="info-panel glass" style="margin-top: 20px; width: 100%;">
            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Beli Paket IaaS</h3>

            @if ($blockCheckout)
                <div style="display:flex; align-items:flex-start; gap:0.75rem; background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.4); border-radius: 12px; padding: 0.9rem 1.1rem; margin-bottom: 1rem;">
                    <i class="fa fa-hourglass-half" style="color:#d97706; margin-top:2px;"></i>
                    <div>
                        <strong style="color:#92400e;">Menunggu Verifikasi Admin</strong>
                        <p style="margin:0.2rem 0 0; color:#78350f; font-size:0.88rem;">
                            {{ $blockReason }} Pantau statusnya di halaman
                            <a href="{{ route('storage.index') }}" style="color:#d97706; font-weight:700;">Storage</a>.
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-primary btn-full" disabled style="opacity:0.5; cursor:not-allowed;">
                    <i class="fa fa-lock"></i> Pengajuan Sedang Diproses
                </button>
            @else
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items:start;">
                    {{-- Kolom kiri: deskripsi + daftar keuntungan --}}
                    <div>
                        <p style="margin-bottom: 1rem; color: var(--text-light); font-size: 0.95rem;">
                            Anda belum memiliki paket. Silakan berlangganan untuk mulai membuat S3 Bucket dan mengakses infrastruktur IaaS kami.
                        </p>
                        <div class="plan-features" style="margin-top: 1.25rem;">
                            <div class="plan-feature">
                                <i class="fa fa-database"></i>
                                Storage sesuai paket yang dipilih
                            </div>
                            <div class="plan-feature">
                                <i class="fa fa-box-archive"></i>
                                Kredensial & bucket otomatis setelah ACC
                            </div>
                            <div class="plan-feature">
                                <i class="fa fa-shield-halved"></i>
                                Verifikasi admin untuk keamanan akun
                            </div>
                        </div>
                    </div>

                    {{-- Kolom kanan: form pemesanan --}}
                    <form method="POST" action="{{ route('storage.checkout') }}" class="auth-form" id="checkoutForm" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="plan_id">Pilih Paket:</label>
                            <select id="plan_id" name="plan_id" class="form-input" required>
                                <option value="" disabled selected>-- Pilih Paket --</option>
                                @forelse ($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->storage_quota_gb }} GB)</option>
                                @empty
                                    <option value="" disabled>Tidak ada paket tersedia</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="metode_bayar">Metode Pembayaran:</label>
                            <select id="metode_bayar" name="metode_bayar" class="form-input" required>
                                <option value="" disabled selected>-- Pilih Metode --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Virtual Account">Virtual Account</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary btn-full" id="btnSubmit">
                            <i class="fa fa-rocket"></i> Pesan Sekarang
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @endif

    <div class="coming-soon glass">
        <div class="cs-icon">🚀</div>
        <h3>MiniStack Integration — Coming Soon</h3>
        <p>Fase berikutnya: integrasi dengan MiniStack untuk manajemen S3 Bucket nyata.</p>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const plan = document.getElementById('plan_id').value;
        const method = document.getElementById('metode_bayar').value;

        // Validasi Kustom
        if (!plan || !method) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Mohon pilih paket dan metode pembayaran terlebih dahulu.',
                confirmButtonColor: '#ff2e93',
                customClass: { popup: 'glass-popup' }
            });
            return;
        }

        // Konfirmasi Pembelian
        Swal.fire({
            title: 'Lanjutkan Pembelian?',
            text: "Konfirmasi pesanan paket IaaS kamu sekarang.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#00a8ba',
            cancelButtonColor: '#ff2e93',
            confirmButtonText: 'Ya, Beli Sekarang',
            cancelButtonText: 'Batal',
            customClass: { popup: 'glass-popup' }
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // ── Validasi Konfirmasi Logout ──
    // Mendeteksi otomatis link/tombol logout di navbar (layouts.app),
    // lalu menampilkan konfirmasi SweetAlert2 sebelum benar-benar logout.
    (function () {
        // Cari elemen logout: link/button dengan href ke route('logout'),
        // atau yang memiliki atribut data-logout, atau teks "Logout".
        const logoutTrigger = document.querySelector('[data-logout]')
            || document.querySelector('a[href*="logout"]')
            || document.querySelector('form#logout-form button, form#logout-form a')
            || Array.from(document.querySelectorAll('a, button')).find(
                el => el.textContent.trim().toLowerCase() === 'logout'
            );

        if (!logoutTrigger) return;

        logoutTrigger.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Keluar dari Akun?',
                html: 'Kamu akan keluar dari sesi <b>ChromaStack Cloud</b> saat ini.<br><br><span style="font-size: 0.9em; color: #64748b;">Kamu perlu login kembali untuk mengakses dashboard.</span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff2e93',
                cancelButtonColor: '#00a8ba',
                confirmButtonText: '<i class="fa fa-right-from-bracket"></i> Ya, Logout',
                cancelButtonText: '<i class="fa fa-times"></i> Batal',
                customClass: { popup: 'glass-popup' }
            }).then((result) => {
                if (!result.isConfirmed) return;

                // Cek apakah trigger berada di dalam form (pola umum Laravel: form#logout-form)
                const parentForm = logoutTrigger.closest('form');
                if (parentForm) {
                    parentForm.submit();
                    return;
                }

                // Jika hanya link biasa, buat form POST dinamis ke route logout
                // menggunakan CSRF token dari meta tag (umum di layouts.app).
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = logoutTrigger.getAttribute('href') || '{{ route('logout') }}';
                form.style.display = 'none';

                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                }

                document.body.appendChild(form);
                form.submit();
            });
        });
    })();
</script>
@endpush

@push('styles')
<style>
    .swal2-popup.glass-popup {
        border-radius: 20px !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.95) !important;
    }
</style>
@endpush