@extends('layouts.admin')

@section('title', 'Manajemen Pengaduan')
@section('page_title', 'Daftar Pengaduan Masuk')

@section('content')
<!-- TABS FILTER STATUS -->
<div class="filter-tabs-bar">
    <a href="{{ route('admin.complaints.index') }}" class="tab-pill {{ empty($filters['status']) ? 'active' : '' }}">
        <span>Semua Laporan</span>
        <span class="count-badge">{{ $counts['all'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.complaints.index') }}?status=pending" class="tab-pill {{ ($filters['status'] ?? '') == 'pending' ? 'active' : '' }}">
        <span>Menunggu</span>
        <span class="count-badge amber">{{ $counts['pending'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.complaints.index') }}?status=in_progress" class="tab-pill {{ ($filters['status'] ?? '') == 'in_progress' ? 'active' : '' }}">
        <span>Diproses</span>
        <span class="count-badge blue">{{ $counts['in_progress'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.complaints.index') }}?status=resolved" class="tab-pill {{ ($filters['status'] ?? '') == 'resolved' ? 'active' : '' }}">
        <span>Selesai</span>
        <span class="count-badge green">{{ $counts['resolved'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.complaints.index') }}?status=rejected" class="tab-pill {{ ($filters['status'] ?? '') == 'rejected' ? 'active' : '' }}">
        <span>Ditolak</span>
        <span class="count-badge red">{{ $counts['rejected'] ?? 0 }}</span>
    </a>
</div>

<!-- FILTER SEARCH & KATEGORI -->
<div class="admin-card">
    <div style="padding: 16px 20px;">
        <form action="{{ route('admin.complaints.index') }}" method="GET" class="filter-search-form">
            @if(!empty($filters['status']))
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
            @endif

            <div class="search-input-field">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nomor tiket, judul, nama pelapor, atau nomor HP...">
            </div>

            <div class="select-field-wrap">
                <select name="category" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($filters['category'] ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="gov-btn gov-btn-primary" style="padding: 9px 18px; font-size: 13.5px;">
                <span>Filter</span>
            </button>

            @if(!empty($filters['search']) || !empty($filters['category']) || !empty($filters['status']))
                <a href="{{ route('admin.complaints.index') }}" class="gov-btn gov-btn-outline" style="padding: 9px 14px; font-size: 13px;">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABEL LAPORAN -->
    @if($complaints->count() > 0)
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nomor Tiket</th>
                        <th>Judul & Uraian</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                        <tr>
                            <td>
                                <span class="ticket-code-pill">{{ $complaint->tracking_code }}</span>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 3px;">
                                    {{ $complaint->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td>
                                <strong style="color: #0f172a;">{{ Str::limit($complaint->subject, 38) }}</strong>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                    {{ Str::limit($complaint->description, 55) }}
                                </div>
                                @if($complaint->location)
                                    <div style="font-size: 11px; color: #b45309; margin-top: 2px;">
                                        <i class="bi bi-geo-alt"></i> {{ $complaint->location }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; font-size: 13px;">{{ $complaint->display_name }}</div>
                                @if($complaint->reporter_phone)
                                    <div style="font-size: 11.5px; color: #64748b;">
                                        <i class="bi bi-telephone"></i> {{ $complaint->reporter_phone }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 9px; border-radius: 7px; background: {{ isset($complaint->category->color) ? $complaint->category->color . '18' : '#f1f5f9' }}; color: {{ $complaint->category->color ?? '#0e243f' }}; font-size: 12px; font-weight: 600; border: 1px solid {{ isset($complaint->category->color) ? $complaint->category->color . '30' : '#e2e8f0' }};">
                                    <i class="bi {{ $complaint->category->icon ?? 'bi-tag-fill' }}"></i>
                                    <span>{{ $complaint->category->name ?? 'Umum' }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $prioClass = match($complaint->priority) {
                                        'high' => 'background:#fee2e2; color:#991b1b;',
                                        'low'  => 'background:#f1f5f9; color:#475569;',
                                        default => 'background:#e0e7ff; color:#3730a3;'
                                    };
                                @endphp
                                <span style="font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 999px; {{ $prioClass }}">
                                    {{ $complaint->priority_label }}
                                </span>
                            </td>
                            <td>
                                <span class="status-pill {{ $complaint->status }}">
                                    {{ $complaint->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn-action-icon" title="Periksa Laporan & Tindak Lanjut">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
            {{ $complaints->links() }}
        </div>
    @else
        <div style="padding: 48px 24px; text-align: center; color: #64748b;">
            <i class="bi bi-inbox" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
            <strong>Tidak Ada Laporan yang Ditemukan</strong>
            <p style="font-size: 13px; margin-top: 4px;">Coba ubah kata kunci pencarian atau bersihkan filter.</p>
        </div>
    @endif
</div>
@endsection
