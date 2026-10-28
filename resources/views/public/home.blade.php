@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- HERO SECTION -->
<section class="home-hero">
    <div class="hero-content">
        <div class="hero-badge-pill">
            <span class="live-indicator"></span>
            <span>Portal Resmi Pengaduan Masyarakat Kabupaten Bangkalan</span>
        </div>
        <h1 class="hero-title">
            Wujudkan Layanan Perpustakaan Daerah yang Lebih Prima
        </h1>
        <p class="hero-lead">
            Punya keluhan terkait fasilitas ruang baca, ketersediaan buku, kartu anggota, atau layanan kearsipan? Sampaikan laporan Anda secara langsung, transparan, dan dapat dipantau perkembangannya.
        </p>

        <div class="hero-cta-group">
            <a href="{{ route('complaint.create') }}" class="btn-hero-primary">
                <i class="bi bi-pencil-square"></i>
                <span>Tulis Pengaduan Baru</span>
            </a>
            <a href="{{ route('tracking.index') }}" class="btn-hero-secondary">
                <i class="bi bi-search"></i>
                <span>Lacak Status Tiket Anda</span>
            </a>
        </div>

        <div class="hero-trust-list">
            <div class="trust-item"><i class="bi bi-shield-check"></i> Kerahasiaan Terjamin</div>
            <div class="trust-item"><i class="bi bi-stopwatch-fill"></i> Verifikasi Cepat</div>
            <div class="trust-item"><i class="bi bi-cash-coin"></i> Bebas Biaya / Gratis</div>
        </div>
    </div>

    <div class="hero-visual">
        <div class="visual-card">
            <div class="card-top-chip"><i class="bi bi-building-check"></i> DISPUSIP Bangkalan</div>
            <div class="visual-icon-wrap">
                <i class="bi bi-book-half"></i>
            </div>
            <h3>Perpustakaan Terakreditasi</h3>
            <p>Melayani dengan sepenuh hati untuk mencerdaskan generasi penerus Bangkalan.</p>
            <div class="visual-stats-row">
                <div>
                    <strong>{{ $stats['resolved'] ?? 0 }}</strong>
                    <span>Aduan Tuntas</span>
                </div>
                <div>
                    <strong>{{ $stats['total'] ?? 0 }}</strong>
                    <span>Total Laporan</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATISTIK SECTION -->
<section class="section-block">
    <div class="section-header">
        <span class="section-tag">Transparansi Publik</span>
        <h2>Statistik Penanganan Pengaduan</h2>
        <p>Rekapitulasi berkala penanganan aduan masyarakat di DISPUSIP Kabupaten Bangkalan.</p>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon-circle blue"><i class="bi bi-collection-fill"></i></div>
            <div class="stat-number">{{ number_format($stats['total'] ?? 0) }}</div>
            <div class="stat-title">Total Laporan Diterima</div>
            <div class="stat-note">Semua aduan terarsip secara digital</div>
        </div>

        <div class="stat-box">
            <div class="stat-icon-circle amber"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-number">{{ number_format($stats['pending'] ?? 0) }}</div>
            <div class="stat-title">Menunggu Verifikasi</div>
            <div class="stat-note">Menunggu antrean petugas piket</div>
        </div>

        <div class="stat-box">
            <div class="stat-icon-circle indigo"><i class="bi bi-gear-wide-connected"></i></div>
            <div class="stat-number">{{ number_format($stats['in_progress'] ?? 0) }}</div>
            <div class="stat-title">Sedang Ditindaklanjuti</div>
            <div class="stat-note">Dalam proses perbaikan & tindak lanjut</div>
        </div>

        <div class="stat-box">
            <div class="stat-icon-circle green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-number">{{ number_format($stats['resolved'] ?? 0) }}</div>
            <div class="stat-title">Selesai Ditangani</div>
            <div class="stat-note">Tuntas dengan respon resmi petugas</div>
        </div>
    </div>
</section>

<!-- ALUR PENGADUAN -->
<section class="section-block bg-subtle">
    <div class="section-header">
        <span class="section-tag">Tata Cara</span>
        <h2>4 Langkah Mudah Menyampaikan Aduan</h2>
        <p>Prosedur sederhana agar keluhan Anda dapat segera ditindaklanjuti secara tepat sasaran.</p>
    </div>

    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">01</div>
            <h4>Tulis Laporan</h4>
            <p>Uraikan keluhan Anda secara jelas dan sertakan bukti pendukung jika ada (foto fasilitas, bukti layanan, dll).</p>
        </div>
        <div class="step-card">
            <div class="step-num">02</div>
            <h4>Dapat Nomor Tiket</h4>
            <p>Sistem akan memberikan kode pelacakan unik (misal: <code>ADU-2410-001</code>) untuk memantau status secara langsung.</p>
        </div>
        <div class="step-card">
            <div class="step-num">03</div>
            <h4>Verifikasi & Tindak Lanjut</h4>
            <p>Petugas piket DISPUSIP Bangkalan memeriksa kebenaran laporan dan melakukan tindakan di lapangan.</p>
        </div>
        <div class="step-card">
            <div class="step-num">04</div>
            <h4>Penyelesaian & Respon</h4>
            <p>Anda menerima penjelasan resmi dan solusi konkret dari pihak perpustakaan melalui sistem ini.</p>
        </div>
    </div>
</section>

<!-- KATEGORI LAYANAN -->
@if(isset($categories) && $categories->count() > 0)
<section class="section-block">
    <div class="section-header">
        <span class="section-tag">Klasifikasi</span>
        <h2>Kategori Layanan yang Tersedia</h2>
        <p>Pilih kategori yang paling sesuai dengan kendala yang Anda temui.</p>
    </div>

    <div class="categories-grid">
        @foreach($categories as $category)
            <div class="category-box">
                <div class="category-icon-wrap" style="background: {{ $category->color ? $category->color . '18' : '#eff6ff' }}; color: {{ $category->color ?: '#0e243f' }}; border: 1px solid {{ $category->color ? $category->color . '30' : '#bfdbfe' }};">
                    <i class="bi {{ $category->icon ?: 'bi-tag-fill' }}"></i>
                </div>
                <h3>{{ $category->name }}</h3>
                <p>{{ Str::limit($category->description, 90) ?: 'Layanan pengaduan seputar bidang ini.' }}</p>
                <span class="badge-cat-count">{{ $category->complaints_count ?? 0 }} laporan masuk</span>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- ADUAN TERAKHIR DISELESAIKAN -->
@if(isset($recentResolved) && $recentResolved->count() > 0)
<section class="section-block bg-subtle">
    <div class="section-header">
        <span class="section-tag">Bukti Nyata</span>
        <h2>Pengaduan Terbaru yang Telah Selesai</h2>
        <p>Komitmen kami dalam merespon dan menuntaskan aspirasi masyarakat.</p>
    </div>

    <div class="resolved-grid">
        @foreach($recentResolved as $item)
            <div class="resolved-card">
                <div class="resolved-card-header">
                    <span class="ticket-code">{{ $item->tracking_code }}</span>
                    <span class="status-pill resolved"><i class="bi bi-check2"></i> Selesai</span>
                </div>
                <h4>{{ $item->subject }}</h4>
                <p class="resolved-excerpt">{{ Str::limit($item->description, 110) }}</p>
                <div class="resolved-footer">
                    <span><i class="bi bi-folder"></i> {{ $item->category->name ?? 'Umum' }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ $item->resolved_at ? $item->resolved_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- CTA BANNER -->
<section class="home-cta-banner">
    <div class="cta-inner">
        <h2>Siap Menyampaikan Aspirasi Anda?</h2>
        <p>Mari bersama-sama membangun Perpustakaan Umum Kabupaten Bangkalan menjadi pusat literasi kebanggaan daerah.</p>
        <a href="{{ route('complaint.create') }}" class="btn-hero-primary" style="background:#fbbf24; color:#0b213f;">
            <i class="bi bi-pencil-square"></i>
            <span>Mulai Buat Pengaduan Sekarang</span>
        </a>
    </div>
</section>

<style>
/* HERO */
.home-hero {
    background: linear-gradient(145deg, #07172c 0%, #0e274b 55%, #183e72 100%);
    border-radius: 24px;
    padding: 64px 54px;
    color: #ffffff;
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    gap: 48px;
    align-items: center;
    box-shadow: 0 16px 40px -10px rgba(7, 23, 44, 0.25);
    margin-bottom: 56px;
    position: relative;
    overflow: hidden;
}

.home-hero::after {
    content: '';
    position: absolute;
    right: -100px;
    top: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%);
    pointer-events: none;
}

.hero-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: #fbbf24;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 22px;
}

.live-indicator {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
}

.hero-title {
    font-size: 38px;
    font-weight: 800;
    line-height: 1.25;
    letter-spacing: -0.8px;
    margin-bottom: 18px;
    color: #ffffff;
}

.hero-lead {
    font-size: 15.5px;
    color: #cbd5e1;
    line-height: 1.7;
    margin-bottom: 32px;
    max-width: 540px;
}

.hero-cta-group {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 32px;
}

.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: #fbbf24;
    color: #0b213f;
    font-size: 14.5px;
    font-weight: 700;
    padding: 13px 26px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35);
}

.btn-hero-primary:hover {
    background: #f59e0b;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(245, 158, 11, 0.45);
}

.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.22);
    font-size: 14.5px;
    font-weight: 600;
    padding: 13px 22px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-hero-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

.hero-trust-list {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 13px;
    color: #94a3b8;
    flex-wrap: wrap;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.trust-item i {
    color: #fbbf24;
}

/* Hero Visual Box */
.hero-visual {
    display: flex;
    justify-content: center;
}

.visual-card {
    width: 100%;
    max-width: 360px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 20px;
    padding: 32px 28px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.card-top-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #fbbf24;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
}

.visual-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    color: #fbbf24;
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    border: 1px solid rgba(255, 255, 255, 0.22);
}

.visual-card h3 {
    font-size: 19px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
}

.visual-card p {
    font-size: 13px;
    color: #cbd5e1;
    line-height: 1.5;
    margin-bottom: 24px;
}

.visual-stats-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding-top: 18px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
}

.visual-stats-row strong {
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
    display: block;
}

.visual-stats-row span {
    font-size: 11.5px;
    color: #94a3b8;
    display: block;
}

/* SECTION BLOCKS */
.section-block {
    margin-bottom: 64px;
}

.section-block.bg-subtle {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 48px 40px;
}

.section-header {
    text-align: center;
    max-width: 620px;
    margin: 0 auto 38px;
}

.section-tag {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #b45309;
    background: #fef3c7;
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 10px;
}

.section-header h2 {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.4px;
    margin-bottom: 8px;
}

.section-header p {
    font-size: 14.5px;
    color: #64748b;
    line-height: 1.6;
}

/* STATS ROW */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.stat-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px 22px;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
    text-align: left;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.08);
}

.stat-icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 16px;
}

.stat-icon-circle.blue   { background: #eff6ff; color: #1d4ed8; }
.stat-icon-circle.amber  { background: #fffbeb; color: #b45309; }
.stat-icon-circle.indigo { background: #eef2ff; color: #4338ca; }
.stat-icon-circle.green  { background: #f0fdf4; color: #15803d; }

.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.6px;
    line-height: 1.1;
    margin-bottom: 6px;
}

.stat-title {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
}

.stat-note {
    font-size: 12px;
    color: #94a3b8;
}

/* STEPS GRID */
.steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

.step-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 24px 20px;
    position: relative;
}

.step-num {
    font-size: 24px;
    font-weight: 800;
    color: #cbd5e1;
    margin-bottom: 12px;
}

.step-card h4 {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.step-card p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.55;
}

/* CATEGORIES */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.category-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
}

.category-icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #fef3c7;
    color: #b45309;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-bottom: 14px;
}

.category-box h3 {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

.category-box p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 16px;
    flex: 1;
}

.badge-cat-count {
    align-self: flex-start;
    font-size: 11.5px;
    font-weight: 600;
    color: #1e3a5f;
    background: #eff6ff;
    padding: 4px 10px;
    border-radius: 6px;
}

/* RESOLVED GRID */
.resolved-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.resolved-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px;
    display: flex;
    flex-direction: column;
}

.resolved-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.ticket-code {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 6px;
    font-family: monospace;
}

.resolved-card h4 {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
    line-height: 1.35;
}

.resolved-excerpt {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 18px;
    flex: 1;
}

.resolved-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    color: #94a3b8;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
}

/* CTA BANNER */
.home-cta-banner {
    background: linear-gradient(135deg, #0b213f 0%, #163660 100%);
    border-radius: 20px;
    padding: 56px 40px;
    text-align: center;
    color: #ffffff;
}

.cta-inner {
    max-width: 640px;
    margin: 0 auto;
}

.cta-inner h2 {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 12px;
    letter-spacing: -0.4px;
}

.cta-inner p {
    font-size: 15px;
    color: #cbd5e1;
    line-height: 1.6;
    margin-bottom: 28px;
}

/* Responsif */
@media (max-width: 980px) {
    .home-hero {
        grid-template-columns: 1fr;
        padding: 40px 28px;
    }
    .hero-visual {
        display: none;
    }
    .stats-row, .steps-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .categories-grid, .resolved-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .stats-row, .steps-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
