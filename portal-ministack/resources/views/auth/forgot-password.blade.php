@extends('layouts.app')
@section('title', 'Lupa Password')

@section('content')
<div class="auth-wrapper">

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="auth-card glass">
        <div class="auth-logo">
            <span class="logo-icon">🔐</span>
            <h1 class="logo-title">Lupa Password?</h1>
            <p class="logo-subtitle">Jangan khawatir. Masukkan email kamu dan kami akan mengirimkan link untuk mereset password.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 1rem; border-radius: 0.5rem; color: #34d399; margin-bottom: 1.5rem; display: flex; gap: 0.5rem; align-items: center; font-size: 0.875rem;">
                <i class="fa fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
            @csrf

            <div class="form-group">
                <label for="email">
                    <i class="fa fa-envelope"></i> Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="kamu@chromastack.cloud"
                    class="form-input @error('email') is-error @enderror"
                    required
                    autofocus
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary btn-full">
                <i class="fa fa-paper-plane"></i> Kirim Link Reset Password
            </button>
        </form>

        <div class="auth-footer">
            <p>Ingat password kamu? <a href="{{ route('login') }}" class="link-candy">Kembali ke Login</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush