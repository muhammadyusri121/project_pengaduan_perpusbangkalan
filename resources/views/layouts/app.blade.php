<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Pengaduan DISPUSIP Bangkalan (Dinas Perpustakaan dan Kearsipan). Sampaikan aspirasi, saran, dan pengaduan layanan secara mudah dan transparan.">
    <title>@yield('title', 'Beranda') — Pengaduan DISPUSIP Bangkalan</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Main Design System -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    
    @stack('styles')
</head>
<body>

<!-- TOP GOVERNMENT EMBLEM BAR -->
<div class="gov-topbar">
    <div class="gov-topbar-inner">
        <div class="gov-badge-text">
            <span class="dot"></span>
            <span>Pemerintah Kabupaten Bangkalan &bull; Portal Resmi Aspirasi & Pengaduan DISPUSIP</span>
        </div>
        <div class="gov-links">
            <a href="https://bangkalankab.go.id" target="_blank" rel="noopener"><i class="bi bi-globe2"></i> bangkalankab.go.id</a>
            <span>&bull;</span>
            <a href="tel:0313095000"><i class="bi bi-telephone-fill"></i> (031) 3095000</a>
        </div>
    </div>
</div>

<!-- NAVBAR UTAMA -->
<nav class="main-navbar">
    <div class="navbar-container">
        <!-- Logo & Identitas -->
        <a href="{{ route('home') }}" class="brand-wrapper">
            <div class="brand-emblem">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div class="brand-titles">
                <span class="brand-title-main">PENGADUAN DISPUSIP BANGKALAN</span>
                <span class="brand-title-sub">Dinas Perpustakaan & Kearsipan Kabupaten Bangkalan</span>
            </div>
        </a>

        <!-- Tombol Menu Mobile -->
        <button class="mobile-nav-btn" onclick="toggleMobileNav()" aria-label="Buka Menu">
            <i class="bi bi-list"></i>
        </button>

        <!-- Navigasi -->
        <ul class="nav-menu" id="navMenu">
            <li>
                <a href="{{ route('home') }}" class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> Beranda
                </a>
            </li>
            <li>
                <a href="{{ route('tracking.index') }}" class="nav-link-item {{ request()->routeIs('tracking.*') ? 'active' : '' }}">
                    <i class="bi bi-search"></i> Lacak Status Tiket
                </a>
            </li>

            @guest
                <li>
                    <a href="{{ route('login') }}" class="nav-link-item {{ request()->routeIs('login') ? 'active' : '' }}">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="nav-btn-lapor">
                        <i class="bi bi-pencil-square"></i> Buat Pengaduan
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('complaint.create') }}" class="nav-btn-lapor {{ request()->routeIs('complaint.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle-fill"></i> Tulis Aduan Baru
                    </a>
                </li>

                @if(Auth::user()->isPetugas())
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link-item" style="color: #b45309; background: #fffbeb;">
                            <i class="bi bi-speedometer2"></i> Panel Petugas
                        </a>
                    </li>
                @endif

                <!-- User Dropdown -->
                <li class="nav-user-dropdown">
                    <a href="#" class="user-toggle">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="user-avatar-tiny">
                        <span>{{ Str::limit(Auth::user()->name, 16) }}</span>
                        <i class="bi bi-chevron-down" style="font-size: 11px;"></i>
                    </a>
                    <div class="user-dropdown-menu">
                        <a href="{{ route('tracking.index') }}">
                            <i class="bi bi-ticket-perforated"></i> Riwayat Laporan Saya
                        </a>
                        @if(Auth::user()->isPetugas())
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Panel Petugas & Admin
                            </a>
                        @endif
                        <div class="menu-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();" style="color: #dc2626;">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                        <form id="global-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>

<!-- ALERT NOTIFIKASI GLOBAL -->
@if(session('success') || session('error') || session('warning') || session('info'))
    <div class="site-alerts">
        @if(session('success'))
            <div class="gov-alert gov-alert-success">
                <i class="bi bi-check-circle-fill" style="font-size: 18px; margin-top: 1px;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="gov-alert gov-alert-danger">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px; margin-top: 1px;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if(session('warning'))
            <div class="gov-alert gov-alert-warning">
                <i class="bi bi-exclamation-circle-fill" style="font-size: 18px; margin-top: 1px;"></i>
                <div>{{ session('warning') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="gov-alert gov-alert-info">
                <i class="bi bi-info-circle-fill" style="font-size: 18px; margin-top: 1px;"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif
    </div>
@endif

<!-- MAIN CONTENT -->
<main class="page-wrapper">
    @yield('content')
</main>

<!-- FOOTER RESMI -->
<footer class="site-footer">
    <div class="footer-top">
        <div class="footer-col">
            <h4>Dinas Perpustakaan & Kearsipan Kabupaten Bangkalan</h4>
            <p>
                Lembaga pengelola perpustakaan daerah dan kearsipan yang berkomitmen menyediakan layanan informasi, literasi, dan pelestarian arsip berbasis kebutuhan masyarakat Bangkalan.
            </p>
            <p style="font-size: 13px; color: #64748b;">
                <i class="bi bi-geo-alt-fill text-amber"></i> Jl. Soekarno-Hatta No. 35, Wr 08, Mlajah, Kec. Bangkalan, Kabupaten Bangkalan, Jawa Timur 69116
            </p>
        </div>
        <div class="footer-col">
            <h4>Jam Pelayanan Aduan</h4>
            <p><strong>Senin — Kamis:</strong> 08.00 - 15.30 WIB</p>
            <p><strong>Jumat:</strong> 08.00 - 14.30 WIB</p>
            <p><strong>Sabtu — Minggu:</strong> Pengaduan daring tetap diterima & diproses pada hari kerja berikutnya.</p>
        </div>
        <div class="footer-col">
            <h4>Tautan Cepat</h4>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right"></i> Beranda Publik</a></li>
                <li><a href="{{ route('tracking.index') }}"><i class="bi bi-chevron-right"></i> Lacak Status Aduan</a></li>
                <li><a href="{{ route('complaint.create') }}"><i class="bi bi-chevron-right"></i> Buat Laporan Baru</a></li>
                <li><a href="{{ route('login') }}"><i class="bi bi-chevron-right"></i> Masuk Akun</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} Pengaduan DISPUSIP Bangkalan &bull; Dinas Perpustakaan dan Kearsipan Kabupaten Bangkalan
    </div>
</footer>

<script>
function toggleMobileNav() {
    const nav = document.getElementById('navMenu');
    nav.classList.toggle('active');
}
</script>

@stack('scripts')
</body>
</html>
