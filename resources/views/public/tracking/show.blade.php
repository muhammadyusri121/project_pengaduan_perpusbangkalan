@extends('layouts.app')

@section('title', 'Detail Tiket #' . $complaint->tracking_code)

@section('content')
<div class="tracking-detail-wrap">
    <!-- Tombol Kembali -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('tracking.index') }}" class="gov-btn gov-btn-outline" style="padding: 6px 14px; font-size: 13px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
        </a>
    </div>

    <!-- KARTU UTAMA DETAIL -->
    <div class="gov-card detail-main-card">
        <!-- Header Kartu -->
        <div class="detail-header-bar">
            <div class="header-left-meta">
                <span class="tracking-pill">{{ $complaint->tracking_code }}</span>
                <span class="status-pill {{ $complaint->status }}">
                    <i class="bi bi-dot"></i> {{ $complaint->status_label }}
                </span>
            </div>
            <div class="header-right-date">
                <i class="bi bi-calendar3"></i> Diajukan pada {{ $complaint->created_at->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>

        <div class="detail-card-body">
            <!-- JUDUL & KATEGORI -->
            <div class="detail-title-block">
                <span class="category-tag"><i class="bi bi-tag-fill"></i> {{ $complaint->category->name ?? 'Umum' }}</span>
                <h1>{{ $complaint->subject }}</h1>
                @if($complaint->location)
                    <div class="meta-location">
                        <i class="bi bi-geo-alt-fill text-amber"></i> Lokasi: {{ $complaint->location }}
                    </div>
                @endif
            </div>

            <!-- STEP PROGRESS TIMELINE -->
            <div class="progress-timeline-box">
                <h4 class="timeline-title"><i class="bi bi-diagram-3-fill"></i> Progres Penanganan</h4>
                <div class="timeline-steps">
                    <!-- Step 1: Laporan Masuk -->
                    <div class="step-point completed">
                        <div class="point-badge"><i class="bi bi-check-lg"></i></div>
                        <div class="point-label">Laporan Diterima</div>
                        <div class="point-time">{{ $complaint->created_at->format('d M, H:i') }}</div>
                    </div>

                    <!-- Step 2: Verifikasi & Proses -->
                    <div class="step-point {{ in_array($complaint->status, ['in_progress', 'resolved', 'rejected']) ? 'completed' : ($complaint->status == 'pending' ? 'current' : '') }}">
                        <div class="point-badge">
                            @if(in_array($complaint->status, ['in_progress', 'resolved', 'rejected']))
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi bi-hourglass-split"></i>
                            @endif
                        </div>
                        <div class="point-label">Sedang Diproses</div>
                        <div class="point-time">
                            {{ $complaint->status == 'pending' ? 'Antrean Petugas' : 'Dalam Tindak Lanjut' }}
                        </div>
                    </div>

                    <!-- Step 3: Selesai / Ditolak -->
                    <div class="step-point {{ in_array($complaint->status, ['resolved', 'rejected']) ? 'completed' : '' }}">
                        <div class="point-badge">
                            @if($complaint->status == 'resolved')
                                <i class="bi bi-check-all"></i>
                            @elseif($complaint->status == 'rejected')
                                <i class="bi bi-x-lg"></i>
                            @else
                                <i class="bi bi-flag"></i>
                            @endif
                        </div>
                        <div class="point-label">
                            {{ $complaint->status == 'rejected' ? 'Laporan Ditolak' : 'Tuntas Selesai' }}
                        </div>
                        <div class="point-time">
                            @if($complaint->resolved_at)
                                {{ $complaint->resolved_at->format('d M, H:i') }}
                            @else
                                Menunggu Hasil
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DESKRIPSI LENGKAP -->
            <div class="content-text-block">
                <h4>Uraian Pengaduan</h4>
                <div class="text-body-para">
                    {!! nl2br(e($complaint->description)) !!}
                </div>
            </div>

            <!-- LAMPIRAN BUKTI (JIKA ADA) -->
            @if($complaint->attachments->count() > 0)
                <div class="attachments-block">
                    <h4><i class="bi bi-paperclip"></i> Bukti Lampiran ({{ $complaint->attachments->count() }})</h4>
                    <div class="attachments-grid">
                        @foreach($complaint->attachments as $att)
                            <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="attachment-item-card">
                                <i class="bi bi-file-earmark-arrow-down-fill att-icon"></i>
                                <div class="att-meta">
                                    <strong>{{ Str::limit($att->file_name, 28) }}</strong>
                                    <span>{{ strtoupper($att->file_type) }} &bull; {{ round($att->file_size / 1024, 1) }} KB</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- ALASAN PENOLAKAN JIKA DITOLAK -->
            @if($complaint->status == 'rejected' && $complaint->rejection_reason)
                <div class="gov-alert gov-alert-danger" style="margin-top: 24px;">
                    <i class="bi bi-x-circle-fill" style="font-size: 20px;"></i>
                    <div>
                        <strong>Laporan Ini Ditolak dengan Alasan:</strong>
                        <p style="margin: 4px 0 0;">{{ $complaint->rejection_reason }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- TANGGAPAN RESMI PETUGAS -->
    <div class="responses-block-wrap">
        <div class="responses-header">
            <h3><i class="bi bi-chat-quote-fill"></i> Tanggapan Resmi Petugas Perpustakaan</h3>
            <span>{{ $complaint->publicResponses->count() }} tanggapan</span>
        </div>

        @if($complaint->publicResponses->count() > 0)
            <div class="responses-timeline">
                @foreach($complaint->publicResponses as $response)
                    <div class="response-card">
                        <div class="officer-header">
                            <img src="{{ $response->user->avatar_url ?? 'https://ui-avatars.com/api/?name=Petugas&background=0b213f&color=fff' }}" alt="Officer Avatar" class="officer-avatar">
                            <div class="officer-meta">
                                <strong>{{ $response->user->name ?? 'Petugas DISPUSIP' }}</strong>
                                <span class="officer-role"><i class="bi bi-patch-check-fill text-amber"></i> Petugas Resmi</span>
                            </div>
                            <span class="response-timestamp">{{ $response->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="response-content">
                            {!! nl2br(e($response->message)) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="gov-card empty-response-box">
                <i class="bi bi-hourglass-split empty-resp-icon"></i>
                <p>Belum ada tanggapan resmi dari petugas. Laporan Anda sedang dalam antrean verifikasi.</p>
            </div>
        @endif
    </div>
</div>

<style>
.tracking-detail-wrap {
    max-width: 860px;
    margin: 0 auto;
}

.detail-main-card {
    margin-bottom: 32px;
}

.detail-header-bar {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.header-left-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.tracking-pill {
    font-family: monospace;
    font-size: 13px;
    font-weight: 800;
    color: #0b213f;
    background: #e0f2fe;
    padding: 4px 10px;
    border-radius: 6px;
    border: 1px solid #bae6fd;
}

.header-right-date {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.detail-card-body {
    padding: 32px 30px;
    display: flex;
    flex-direction: column;
    gap: 28px;
}

.detail-title-block h1 {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.3px;
    line-height: 1.35;
    margin: 8px 0;
}

.category-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #b45309;
    background: #fef3c7;
    padding: 3px 10px;
    border-radius: 6px;
}

.meta-location {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
}

/* Progress Timeline */
.progress-timeline-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 22px 24px;
}

.timeline-title {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.timeline-steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    position: relative;
    text-align: center;
}

.timeline-steps::before {
    content: '';
    position: absolute;
    top: 18px;
    left: 15%;
    right: 15%;
    height: 3px;
    background: #e2e8f0;
    z-index: 1;
}

.step-point {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.point-badge {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #cbd5e1;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 8px;
}

.step-point.completed .point-badge {
    background: #0b213f;
    border-color: #0b213f;
    color: #ffffff;
}

.step-point.current .point-badge {
    background: #fffbeb;
    border-color: #f59e0b;
    color: #b45309;
}

.point-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 2px;
}

.point-time {
    font-size: 11.5px;
    color: #94a3b8;
}

/* Content Text */
.content-text-block h4, .attachments-block h4 {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 10px;
}

.text-body-para {
    font-size: 14.5px;
    color: #334155;
    line-height: 1.7;
    background: #ffffff;
    padding: 16px;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
}

/* Attachments */
.attachments-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.attachment-item-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.attachment-item-card:hover {
    border-color: #0b213f;
    background: #eff6ff;
}

.att-icon {
    font-size: 24px;
    color: #0b213f;
}

.att-meta strong {
    display: block;
    font-size: 13px;
    color: #0f172a;
}

.att-meta span {
    font-size: 11.5px;
    color: #64748b;
}

/* Responses */
.responses-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.responses-header h3 {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.responses-header span {
    font-size: 12.5px;
    color: #64748b;
}

.responses-timeline {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.response-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #0b213f;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.officer-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.officer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.officer-meta {
    flex: 1;
}

.officer-meta strong {
    font-size: 13.5px;
    color: #0f172a;
    display: block;
}

.officer-role {
    font-size: 11.5px;
    color: #b45309;
    font-weight: 600;
}

.response-timestamp {
    font-size: 12px;
    color: #94a3b8;
}

.response-content {
    font-size: 14px;
    color: #334155;
    line-height: 1.65;
    padding-left: 52px;
}

.empty-response-box {
    padding: 36px 20px;
    text-align: center;
    color: #64748b;
    font-size: 13.5px;
}

.empty-resp-icon {
    font-size: 28px;
    color: #94a3b8;
    margin-bottom: 8px;
    display: block;
}

@media (max-width: 640px) {
    .detail-card-body {
        padding: 20px 16px;
    }
    .attachments-grid {
        grid-template-columns: 1fr;
    }
    .response-content {
        padding-left: 0;
        margin-top: 10px;
    }
}
</style>
@endsection
