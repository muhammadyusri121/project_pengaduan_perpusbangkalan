@extends('layouts.app')

@section('title', 'Masuk Akun')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Panel Samping: Identitas & Informasi -->
        <aside class="auth-editorial">
            <div class="editorial-header">
                <span class="badge-gov"><i class="bi bi-shield-check"></i> Layanan Publik Resmi</span>
                <h2>Dinas Perpustakaan & Kearsipan</h2>
                <p class="tagline">Kabupaten Bangkalan — Jawa Timur</p>
                <div class="editorial-points">
                    <div class="point-item">
                        <div class="point-icon"><i class="bi bi-chat-left-dots-fill"></i></div>
                        <div class="point-text">
                            <strong>Saluran Aspirasi Terpercaya</strong>
                            <p>Sampaikan keluhan dan saran demi peningkatan mutu layanan perpustakaan.</p>
                        </div>
                    </div>
                    <div class="point-item">
                        <div class="point-icon"><i class="bi bi-google"></i></div>
                        <div class="point-text">
                            <strong>Login Langsung dengan Google</strong>
                            <p>Akses akun secara instan tanpa perlu mengingat kata sandi tambahan.</p>
                        </div>
                    </div>
                    <div class="point-item">
                        <div class="point-icon"><i class="bi bi-lock-fill"></i></div>
                        <div class="point-text">
                            <strong>Privasi Terlindungi</strong>
                            <p>Data pribadi pelapor dijaga kerahasiaannya sesuai ketentuan yang berlaku.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="editorial-footer">
                <i class="bi bi-geo-alt-fill"></i> Jl. Soekarno-Hatta No. 35, Bangkalan
            </div>
        </aside>

        <!-- Panel Form Login -->
        <main class="auth-form-wrap">
            <div class="form-header">
                <h3>Selamat Datang</h3>
                <p>Silakan masuk untuk menyampaikan pengaduan atau melihat tindak lanjut tiket Anda.</p>
            </div>

            <!-- Tombol Google Sign-In Langsung -->
            <a href="{{ route('auth.google') }}" class="btn-google-action" id="btnGoogleLogin">
                <svg viewBox="0 0 24 24" width="18" height="18">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Lanjutkan dengan Google</span>
            </a>

            <div class="clean-divider">
                <span>atau masuk dengan email</span>
            </div>

            <!-- Form Autentikasi Manual -->
            <form action="{{ route('login') }}" method="POST" class="auth-form-body">
                @csrf

                <div class="input-block">
                    <label for="email">Alamat Email</label>
                    <div class="field-container">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="nama@email.com" class="@error('email') is-invalid @enderror">
                    </div>
                    @error('email')
                        <span class="err-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-block">
                    <label for="password">Kata Sandi</label>
                    <div class="field-container">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" id="password" name="password" required
                               placeholder="Masukkan kata sandi" class="@error('password') is-invalid @enderror">
                        <button type="button" class="btn-eye" onclick="togglePasswordVisibility('password', this)" title="Tampilkan/sembunyikan sandi">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="err-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-misc">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn-auth-submit">
                    <span>Masuk ke Akun</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-switch">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>

            <!-- Akun Cepat Pengujian (Untuk Penguji/Admin) -->
            <div class="test-creds-box">
                <div class="creds-title">
                    <i class="bi bi-person-fill-gear"></i> Akun Pengujian (Klik untuk Isi):
                </div>
                <div class="creds-list">
                    <button type="button" class="cred-btn" onclick="fillQuickAuth('admin@perpusbangkalan.go.id', 'Admin@Perpus2024!')">
                        <strong>Admin:</strong> admin@perpusbangkalan.go.id
                    </button>
                    <button type="button" class="cred-btn" onclick="fillQuickAuth('petugas@perpusbangkalan.go.id', 'Petugas@2024!')">
                        <strong>Petugas:</strong> petugas@perpusbangkalan.go.id
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function fillQuickAuth(email, pass) {
    const emailField = document.getElementById('email');
    const passField = document.getElementById('password');
    if (emailField && passField) {
        emailField.value = email;
        passField.value = pass;
        emailField.focus();
    }
}
</script>
@endpush
@endsection
