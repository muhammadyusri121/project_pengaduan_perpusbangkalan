@extends('layouts.admin')

@section('title', 'Kategori Pengaduan')
@section('page_title', 'Master Kategori Pengaduan')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="bi bi-tags-fill"></i>
            <span>Daftar Kategori Layanan Perpustakaan</span>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="gov-btn gov-btn-primary" style="padding: 8px 16px; font-size: 13px;">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Kategori Baru</span>
        </a>
    </div>

    @if($categories->count() > 0)
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Urutan</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Laporan</th>
                        <th>Status</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                <strong style="color: #64748b;">#{{ $category->sort_order }}</strong>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 9px; background: {{ $category->color ? $category->color . '20' : '#f1f5f9' }}; color: {{ $category->color ?: '#0e243f' }}; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 1px solid {{ $category->color ? $category->color . '35' : '#e2e8f0' }};">
                                        <i class="bi {{ $category->icon ?: 'bi-tag-fill' }}"></i>
                                    </div>
                                    <strong style="color: #0f172a;">{{ $category->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: #64748b;">{{ $category->description ?: '-' }}</span>
                            </td>
                            <td>
                                <span class="badge-cat-count">{{ $category->complaints_count }} laporan</span>
                            </td>
                            <td>
                                @if($category->is_active)
                                    <span style="font-size: 11px; font-weight: 700; color: #166534; background: #dcfce7; padding: 3px 8px; border-radius: 999px;">
                                        <i class="bi bi-check2"></i> Aktif
                                    </span>
                                @else
                                    <span style="font-size: 11px; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 3px 8px; border-radius: 999px;">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-action-icon" title="Edit Kategori">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    @if($category->complaints_count == 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-icon" style="color: #dc2626;" title="Hapus Kategori">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #64748b;">
            Belum ada kategori pengaduan yang dibuat.
        </div>
    @endif
</div>
@endsection
