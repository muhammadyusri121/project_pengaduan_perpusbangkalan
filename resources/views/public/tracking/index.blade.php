@extends('layouts.app')

@section('title', 'Lacak Status Tiket')

@section('content')
<div class="tracking-container">
    <!-- Header Pencarian Cepat -->
    <div class="tracking-search-card">
        <div class="search-card-inner">
            <span class="badge-tag"><i class="bi bi-search"></i> Pelacakan Terbuka</span>
            <h2>Lacak Status Pengaduan</h2>
            <p>Masukkan kode tiket pelacakan Anda untuk melihat riwayat dan respon tindak lanjut petugas.</p>

            <form action="{{ route('tracking.show') }}" method="POST" class="tracking-search-form">
                @csrf
                <div class="search-input-wrap">
                    <i class="bi bi-ticket-perforated-fill input-ticket-icon"></i>
                    <input type="text" name="tracking_code" required
                           placeholder="Masukkan Nomor Tiket (Contoh: ADU-2410-001)"
                           value="{{ old('tracking_code') }}" class="ticket-input">
                    <button type="submit" class="btn-search-ticket">
                        <i class="bi bi-arrow-right-circle-fill"></i>
                        <span>Cari Tiket</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Pengaduan Saya -->
    <div class="my-reports-section">
        <div class="section-title-row">
            <div>
                <h3><i class="bi bi-clock-history"></i> Riwayat Pengaduan Saya</h3>
                <p>Daftar seluruh aduan yang pernah Anda ajukan melalui akun ini.</p>
            </div>
            <a href="{{ route('complaint.create') }}" class="gov-btn gov-btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Tulis Aduan Baru</span>
            </a>
        </div>

        @if($complaints->count() > 0)
            <div class="reports-table-card">
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>Nomor Tiket</th>
                            <th>Judul Pengaduan</th>
                            <th>Kategori</th>
                            <th>Tanggal Lapor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaints as $complaint)
                            <tr>
                                <td>
                                    <span class="ticket-code-pill">{{ $complaint->tracking_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($complaint->subject, 48) }}</strong>
                                </td>
                                <td>
                                    <span class="cat-pill-tiny">{{ $complaint->category->name ?? 'Umum' }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ $complaint->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td>
                                    <span class="status-pill {{ $complaint->status }}">
                                        {{ $complaint->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('tracking.show') }}?tracking_code={{ $complaint->tracking_code }}" class="btn-detail-link">
                                        <span>Lihat Detail</span>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $complaints->links() }}
            </div>
        @else
            <!-- State Kosong -->
            <div class="empty-reports-card">
                <div class="empty-icon-wrap">
                    <i class="bi bi-inbox"></i>
                </div>
                <h4>Belum Ada Riwayat Pengaduan</h4>
                <p>Anda belum pernah mengajukan laporan pengaduan. Sampaikan keluhan atau saran fasilitas perpustakaan sekarang.</p>
                <a href="{{ route('complaint.create') }}" class="gov-btn gov-btn-primary">
                    <i class="bi bi-pencil-square"></i>
                    <span>Mulai Buat Pengaduan</span>
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.tracking-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Search Card */
.tracking-search-card {
    background: linear-gradient(135deg, #07172c 0%, #0e274b 55%, #183e72 100%);
    border-radius: 20px;
    padding: 44px 40px;
    color: #ffffff;
    margin-bottom: 44px;
    box-shadow: 0 10px 30px -10px rgba(11, 33, 63, 0.2);
    text-align: center;
}

.search-card-inner {
    max-width: 640px;
    margin: 0 auto;
}

.badge-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #fbbf24;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    background: rgba(255, 255, 255, 0.1);
    padding: 5px 12px;
    border-radius: 999px;
    margin-bottom: 12px;
}

.search-card-inner h2 {
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.4px;
    margin-bottom: 8px;
    color: #ffffff;
}

.search-card-inner p {
    font-size: 14px;
    color: #cbd5e1;
    margin-bottom: 24px;
    line-height: 1.5;
}

.search-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 12px;
    padding: 6px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.15);
}

.input-ticket-icon {
    position: absolute;
    left: 18px;
    color: #94a3b8;
    font-size: 18px;
}

.ticket-input {
    flex: 1;
    border: none;
    outline: none;
    padding: 12px 14px 12px 46px;
    font-size: 14.5px;
    font-family: inherit;
    color: #0f172a;
    font-weight: 600;
}

.btn-search-ticket {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #0b213f;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.18s ease;
}

.btn-search-ticket:hover {
    background: #173864;
}

/* My Reports Section */
.my-reports-section {
    display: flex;
    flex-direction: column;
}

.section-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.section-title-row h3 {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.2px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title-row p {
    font-size: 13.5px;
    color: #64748b;
}

/* Table Card */
.reports-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
}

.reports-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.reports-table th {
    background: #f8fafc;
    padding: 14px 20px;
    font-weight: 700;
    color: #475569;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}

.reports-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    vertical-align: middle;
}

.reports-table tbody tr:hover {
    background: #f8fafd;
}

.ticket-code-pill {
    display: inline-block;
    font-family: monospace;
    font-size: 12.5px;
    font-weight: 700;
    color: #0b213f;
    background: #e0f2fe;
    padding: 4px 8px;
    border-radius: 6px;
}

.cat-pill-tiny {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 4px;
}

.date-text {
    font-size: 12.5px;
    color: #64748b;
}

.btn-detail-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12.5px;
    font-weight: 700;
    color: #0b213f;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 6px;
    background: #f1f5f9;
    transition: all 0.15s ease;
}

.btn-detail-link:hover {
    background: #0b213f;
    color: #ffffff;
}

/* Empty State */
.empty-reports-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 56px 24px;
    text-align: center;
}

.empty-icon-wrap {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}

.empty-reports-card h4 {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}

.empty-reports-card p {
    font-size: 13.5px;
    color: #64748b;
    margin-bottom: 22px;
    max-width: 440px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 768px) {
    .tracking-search-card {
        padding: 30px 20px;
    }
    .search-input-wrap {
        flex-direction: column;
        padding: 8px;
    }
    .btn-search-ticket {
        width: 100%;
        justify-content: center;
    }
    .reports-table-card {
        overflow-x: auto;
    }
}
</style>
@endsection
