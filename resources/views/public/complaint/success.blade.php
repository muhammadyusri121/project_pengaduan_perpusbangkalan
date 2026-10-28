@extends('layouts.app')

@section('title', 'Laporan Berhasil Dikirim')

@section('content')
<div class="success-page-wrap">
    <div class="success-card">
        <div class="success-badge-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <h1 class="success-title">Laporan Anda Berhasil Terkirim</h1>
        <p class="success-sub">
            Terima kasih atas kepedulian Anda. Petugas piket DISPUSIP Kabupaten Bangkalan akan segera memverifikasi dan menindaklanjuti laporan Anda.
        </p>

        <!-- KOTAK KODE TRACKING -->
        <div class="tracking-ticket-box">
            <span class="ticket-header-label"><i class="bi bi-ticket-perforated-fill"></i> Nomor Tiket Pelacakan Anda</span>
            <div class="ticket-code-display">{{ $complaint->tracking_code }}</div>
            <p class="ticket-hint">Simpan atau salin kode ini untuk mengecek progres tindak lanjut kapan saja.</p>
            <button type="button" class="btn-copy-code" onclick="copyTrackingCode()" id="btnCopyCode">
                <i class="bi bi-clipboard"></i>
                <span>Salin Nomor Tiket</span>
            </button>
        </div>

        <!-- RINGKASAN DATA LAPORAN -->
        <div class="summary-card">
            <h3><i class="bi bi-card-text"></i> Ringkasan Pengaduan</h3>
            <div class="summary-list">
                <div class="summary-item">
                    <span class="label">Kategori</span>
                    <span class="val"><strong>{{ $complaint->category->name ?? 'Umum' }}</strong></span>
                </div>
                <div class="summary-item">
                    <span class="label">Judul Aduan</span>
                    <span class="val">{{ $complaint->subject }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Status Saat Ini</span>
                    <span class="val">
                        <span class="status-pill pending"><i class="bi bi-hourglass-split"></i> {{ $complaint->status_label }}</span>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="label">Waktu Pengiriman</span>
                    <span class="val">{{ $complaint->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                @if($complaint->location)
                <div class="summary-item">
                    <span class="label">Lokasi</span>
                    <span class="val">{{ $complaint->location }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- TAHAPAN BERIKUTNYA -->
        <div class="next-steps-card">
            <h4><i class="bi bi-info-circle-fill"></i> Langkah Selanjutnya</h4>
            <ul>
                <li>Laporan akan diperiksa oleh petugas dalam waktu maksimal <strong>1×24 jam kerja</strong>.</li>
                <li>Petugas akan memberikan tanggapan resmi dan memperbarui status tiket.</li>
                <li>Anda dapat memantau status setiap saat di menu <strong>Lacak Status Tiket</strong>.</li>
            </ul>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="success-actions">
            <a href="{{ route('tracking.show') }}?tracking_code={{ $complaint->tracking_code }}" class="gov-btn gov-btn-primary">
                <i class="bi bi-search"></i>
                <span>Lacak Status Tiket Ini</span>
            </a>
            <a href="{{ route('complaint.create') }}" class="gov-btn gov-btn-outline">
                <i class="bi bi-plus-circle"></i>
                <span>Tulis Laporan Lain</span>
            </a>
            <a href="{{ route('home') }}" class="gov-btn gov-btn-outline">
                <i class="bi bi-house"></i>
                <span>Ke Beranda</span>
            </a>
        </div>
    </div>
</div>

<style>
.success-page-wrap {
    max-width: 660px;
    margin: 20px auto 40px;
}

.success-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 44px 38px;
    box-shadow: 0 10px 30px -10px rgba(11, 33, 63, 0.08);
    text-align: center;
}

.success-badge-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #ecfdf5;
    border: 2px solid #a7f3d0;
    color: #059669;
    font-size: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
}

.success-title {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.3px;
    margin-bottom: 8px;
}

.success-sub {
    font-size: 14px;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 28px;
}

/* Tracking Ticket Box */
.tracking-ticket-box {
    background: linear-gradient(135deg, #0b213f 0%, #173864 100%);
    color: #ffffff;
    border-radius: 16px;
    padding: 28px 24px;
    margin-bottom: 28px;
    box-shadow: 0 8px 24px rgba(11, 33, 63, 0.18);
}

.ticket-header-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    color: #fbbf24;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 10px;
}

.ticket-code-display {
    font-size: 34px;
    font-weight: 800;
    letter-spacing: 4px;
    font-family: monospace;
    color: #ffffff;
    margin-bottom: 8px;
}

.ticket-hint {
    font-size: 12.5px;
    color: #cbd5e1;
    margin-bottom: 16px;
}

.btn-copy-code {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 18px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.15s ease;
}

.btn-copy-code:hover {
    background: rgba(255, 255, 255, 0.25);
}

/* Detail Summary */
.summary-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px;
    text-align: left;
    margin-bottom: 24px;
}

.summary-card h3 {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 7px;
}

.summary-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    font-size: 13.5px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f1f5f9;
}

.summary-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.summary-item .label {
    color: #64748b;
}

.summary-item .val {
    color: #0f172a;
    text-align: right;
    max-width: 60%;
}

/* Next Steps */
.next-steps-card {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 14px;
    padding: 20px;
    text-align: left;
    margin-bottom: 28px;
}

.next-steps-card h4 {
    font-size: 13.5px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.next-steps-card ul {
    margin: 0;
    padding-left: 18px;
    font-size: 13px;
    color: #78350f;
    line-height: 1.7;
}

/* Action Buttons */
.success-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

@media (max-width: 640px) {
    .success-card {
        padding: 30px 20px;
    }
    .ticket-code-display {
        font-size: 26px;
    }
    .success-actions {
        flex-direction: column;
        width: 100%;
    }
    .success-actions .gov-btn {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
function copyTrackingCode() {
    const code = '{{ $complaint->tracking_code }}';
    navigator.clipboard.writeText(code).then(() => {
        const btn = document.getElementById('btnCopyCode');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> <span>Nomor Tiket Tersalin!</span>';
        btn.style.background = '#059669';
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-clipboard"></i> <span>Salin Nomor Tiket</span>';
            btn.style.background = 'rgba(255, 255, 255, 0.15)';
        }, 2200);
    });
}
</script>
@endpush
@endsection
