@extends('layouts.admin')

@section('title', 'Dashboard Petugas & Admin')
@section('page_title', 'Ringkasan Statistik & Operasional')

@section('content')
<!-- BANNER SAMBUTAN -->
<div class="gov-card admin-welcome-banner">
    <div class="welcome-text">
        <span class="officer-badge"><i class="bi bi-shield-check"></i> Petugas Piket Aktif</span>
        <h2>Selamat Bertugas, {{ Auth::user()->name }}!</h2>
        <p>{{ now()->translatedFormat('l, d F Y') }} &bull; Pengaduan DISPUSIP Bangkalan siap melayani masyarakat.</p>
    </div>
    <div class="welcome-action">
        <a href="{{ route('admin.complaints.index') }}?status=pending" class="btn-review-pending">
            <i class="bi bi-bell-fill"></i>
            <span>{{ $stats['pending'] ?? 0 }} Aduan Menunggu</span>
        </a>
    </div>
</div>

<!-- METRIC STATS ROW -->
<div class="admin-stats-row">
    <div class="metric-card">
        <div>
            <div class="metric-label">Total Laporan Masuk</div>
            <div class="metric-value">{{ number_format($stats['total'] ?? 0) }}</div>
        </div>
        <div class="metric-icon-box icon-blue">
            <i class="bi bi-inbox-fill"></i>
        </div>
    </div>

    <div class="metric-card">
        <div>
            <div class="metric-label">Menunggu Verifikasi</div>
            <div class="metric-value" style="color: #b45309;">{{ number_format($stats['pending'] ?? 0) }}</div>
        </div>
        <div class="metric-icon-box icon-amber">
            <i class="bi bi-hourglass-split"></i>
        </div>
    </div>

    <div class="metric-card">
        <div>
            <div class="metric-label">Sedang Diproses</div>
            <div class="metric-value" style="color: #1d4ed8;">{{ number_format($stats['in_progress'] ?? 0) }}</div>
        </div>
        <div class="metric-icon-box icon-purple">
            <i class="bi bi-gear-wide-connected"></i>
        </div>
    </div>

    <div class="metric-card">
        <div>
            <div class="metric-label">Selesai Ditangani</div>
            <div class="metric-value" style="color: #15803d;">{{ number_format($stats['resolved'] ?? 0) }}</div>
        </div>
        <div class="metric-icon-box icon-green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
    </div>
</div>

<!-- GRAFIK & KATEGORI -->
<div class="dashboard-two-col">
    <!-- Kolom Kiri: Tabel Laporan Terbaru -->
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="bi bi-clock-history"></i>
                <span>Pengaduan Terbaru Masuk</span>
            </div>
            <a href="{{ route('admin.complaints.index') }}" class="btn-card-link">
                <span>Lihat Semua</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($latestComplaints->count() > 0)
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tiket</th>
                            <th>Judul & Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestComplaints as $complaint)
                            <tr>
                                <td>
                                    <span class="ticket-code-pill">{{ $complaint->tracking_code }}</span>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($complaint->subject, 34) }}</strong>
                                    <div style="font-size: 11.5px; color: #64748b;">
                                        Oleh: {{ $complaint->display_name }} &bull; {{ $complaint->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; border-radius: 6px; background: {{ isset($complaint->category->color) ? $complaint->category->color . '18' : '#f1f5f9' }}; color: {{ $complaint->category->color ?? '#0e243f' }}; font-size: 11.5px; font-weight: 600;">
                                        <i class="bi {{ $complaint->category->icon ?? 'bi-tag-fill' }}"></i>
                                        <span>{{ $complaint->category->name ?? 'Umum' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $complaint->status }}">
                                        {{ $complaint->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn-action-icon" title="Lihat Detail & Beri Tanggapan">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding: 36px; text-align: center; color: #64748b;">
                <i class="bi bi-inbox" style="font-size: 32px; color: #cbd5e1; display:block; margin-bottom: 8px;"></i>
                Belum ada pengaduan yang diajukan.
            </div>
        @endif
    </div>

    <!-- Kolom Kanan: Rekap Kategori & Grafik -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Distribusi Kategori -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    <span>Sebaran Kategori</span>
                </div>
            </div>
            <div style="padding: 20px 24px;">
                @if(isset($categoryStats) && $categoryStats->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($categoryStats as $cat)
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 26px; height: 26px; border-radius: 7px; background: {{ $cat->color ? $cat->color . '20' : '#eff6ff' }}; color: {{ $cat->color ?: '#0e243f' }}; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                            <i class="bi {{ $cat->icon ?: 'bi-tag-fill' }}"></i>
                                        </div>
                                        <span style="color: #1e293b;">{{ $cat->name }}</span>
                                    </div>
                                    <span style="color: #64748b; font-size: 12px; font-weight: 700;">{{ $cat->total }} aduan</span>
                                </div>
                                <div style="width: 100%; height: 7px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                                    @php
                                        $percent = $stats['total'] > 0 ? round(($cat->total / $stats['total']) * 100) : 0;
                                    @endphp
                                    <div style="width: {{ $percent }}%; height: 100%; background: {{ $cat->color ?: '#0e243f' }}; border-radius: 999px;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; color: #64748b; font-size: 13px;">Belum ada data kategori.</div>
                @endif
            </div>
        </div>

        <!-- Trend Bulanan -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-graph-up"></i>
                    <span>Grafik Laporan Bulanan</span>
                </div>
                <span style="font-size: 11.5px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 3px 10px; border-radius: 999px; border: 1px solid #bae6fd;">
                    Mulai Okt 2026
                </span>
            </div>
            <div style="padding: 20px 24px;">
                <canvas id="monthlyTrendChart" height="175"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
.admin-welcome-banner {
    background: linear-gradient(135deg, #07172c 0%, #0e274b 60%, #173b6a 100%);
    color: #ffffff;
    padding: 30px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    border: none;
}

.officer-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #fbbf24;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 8px;
}

.welcome-text h2 {
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 4px;
    color: #ffffff;
    letter-spacing: -0.3px;
}

.welcome-text p {
    font-size: 13px;
    color: #cbd5e1;
    margin: 0;
}

.btn-review-pending {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    background: rgba(245, 158, 11, 0.2);
    border: 1px solid rgba(245, 158, 11, 0.4);
    color: #fbbf24;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-review-pending:hover {
    background: #f59e0b;
    color: #0b213f;
    border-color: #f59e0b;
}

.dashboard-two-col {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 24px;
}

.btn-card-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #0b213f;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-card-link:hover {
    text-decoration: underline;
}

@media (max-width: 980px) {
    .admin-welcome-banner {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    .dashboard-two-col {
        grid-template-columns: 1fr;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('monthlyTrendChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels'] ?? []) !!},
                datasets: [
                    {
                        label: 'Total Masuk',
                        data: {!! json_encode($chartData['totals'] ?? []) !!},
                        borderColor: '#0b213f',
                        backgroundColor: 'rgba(11, 33, 63, 0.08)',
                        pointBackgroundColor: '#0b213f',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Diproses',
                        data: {!! json_encode($chartData['in_progress'] ?? []) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: false
                    },
                    {
                        label: 'Selesai',
                        data: {!! json_encode($chartData['resolved'] ?? []) !!},
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.08)',
                        pointBackgroundColor: '#16a34a',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11, weight: '600', family: 'Plus Jakarta Sans' },
                            padding: 14
                        }
                    },
                    tooltip: {
                        backgroundColor: '#07172c',
                        titleFont: { size: 12, weight: '700', family: 'Plus Jakarta Sans' },
                        bodyFont: { size: 11, family: 'Plus Jakarta Sans' },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': ' + context.parsed.y + ' aduan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            font: { size: 10, family: 'Plus Jakarta Sans' }
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10, family: 'Plus Jakarta Sans' },
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
