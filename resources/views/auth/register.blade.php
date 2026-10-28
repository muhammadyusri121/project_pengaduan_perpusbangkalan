@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <!-- Panel Samping: Identitas & Informasi -->
        <aside class="auth-editorial">
            <div class="editorial-header">
                <span class="badge-gov"><i class="bi bi-person-badge-fill"></i> Partisipasi Publik</span>
                <h2>Buat Akun Pelapor</h2>
                <p class="tagline">Bergabung untuk mewujudkan layanan perpustakaan Bangkalan yang lebih baik.</p>
                <div class="editorial-points">
                    <div class="point-item">
                        <div class="point-icon"><i class="bi bi-google"></i></div>
                        <div class="point-text">
                            <strong>Daftar 1-Klik dengan Google</strong>
                            <p>Akun langsung aktif menggunakan data profil Google tanpa registrasi bertahap.</p>
                        </div>
                    </div>
                    <div class="point-item">
                        <div class="point-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="point-text">
                            <strong>Pantau Tiket Secara Real-Time</strong>
                            <p>Lacak status pengaduan, progres verifikasi, hingga respon petugas di satu tempat.</p>
                        </div>
                    </div>
                    <div class="point-item">
                        <div class="point-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="point-text">
                            <strong>Layanan Bebas Biaya (Gratis)</strong>
                            <p>Seluruh masyarakat berhak menyampaikan aspirasi tanpa dipungut biaya apapun.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="editorial-footer">
                <i class="bi bi-building-check"></i> DISPUSIP Kabupaten Bangkalan
            </div>
        </aside>

        <!-- Panel Form Pendaftaran -->
        <main class="auth-form-wrap">
            <div class="form-header">
                <h3>Pendaftaran Akun</h3>
                <p>Silakan pilih pendaftaran instan lewat Google atau isi formulir di bawah ini.</p>
            </div>

            <!-- Tombol Google Sign-Up Langsung -->
            <a href="{{ route('auth.google') }}" class="btn-google-action" id="btnGoogleRegister">
                <svg viewBox="0 0 24 24" width="18" height="18">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Daftar Cepat dengan Google</span>
            </a>

            <div class="clean-divider">
                <span>atau isi formulir pendaftaran</span>
            </div>

            <!-- Form Manual -->
            <form action="{{ route('register') }}" method="POST" class="auth-form-body">
                @csrf

                <div class="input-block">
                    <label for="name">Nama Lengkap <span class="req">*</span></label>
                    <div class="field-container">
                        <i class="bi bi-person field-icon"></i>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="Nama lengkap sesuai identitas" class="@error('name') is-invalid @enderror">
                    </div>
                    @error('name')
                        <span class="err-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-block">
                    <label for="email">Alamat Email <span class="req">*</span></label>
                    <div class="field-container">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="nama@email.com" class="@error('email') is-invalid @enderror">
                    </div>
                    @error('email')
                        <span class="err-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-block">
                    <label for="password">Kata Sandi <span class="req">*</span> <span class="opt-hint">(Min. 8 karakter)</span></label>
                    <div class="field-container">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" id="password" name="password" required
                               placeholder="Minimal 8 karakter" class="@error('password') is-invalid @enderror">
                        <button type="button" class="btn-eye" onclick="togglePasswordVisibility('password', this)" title="Tampilkan/sembunyikan sandi">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="err-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-block">
                    <label for="password_confirmation">Ulangi Kata Sandi <span class="req">*</span></label>
                    <div class="field-container">
                        <i class="bi bi-shield-lock field-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="Ketik ulang kata sandi Anda">
                        <button type="button" class="btn-eye" onclick="togglePasswordVisibility('password_confirmation', this)" title="Tampilkan/sembunyikan sandi">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-auth-submit">
                    <span>Selesaikan Pendaftaran</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-switch">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a>
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
</script>
@endpush
@endsection
