@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('page_title', $category->exists ? 'Ubah Data Kategori' : 'Tambah Kategori Pengaduan')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.categories.index') }}" class="gov-btn gov-btn-outline" style="padding: 6px 14px; font-size: 13px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kategori
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="bi bi-tag-fill"></i>
                <span>{{ $category->exists ? 'Form Edit Kategori' : 'Form Kategori Baru' }}</span>
            </div>
        </div>

        <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" style="padding: 24px 28px;">
            @csrf
            @if($category->exists)
                @method('PUT')
            @endif

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Nama Kategori <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       placeholder="Contoh: Koleksi Buku & Majalah" class="gov-input">
                @error('name')
                    <span style="font-size: 12px; color: #dc2626;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Ikon Bootstrap (Icon Class) <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon ?: 'bi-tag-fill') }}" required
                           placeholder="bi-book, bi-laptop, bi-wifi, dll" class="gov-input">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Kode Warna (Color HEX) <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="color" value="{{ old('color', $category->color ?: '#1e3a5f') }}" required
                           placeholder="#1e3a5f" class="gov-input">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Deskripsi Kategori
                </label>
                <textarea name="description" rows="3" placeholder="Jelaskan jenis aduan apa saja yang masuk kategori ini..." class="gov-input">{{ old('description', $category->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 22px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Nomor Urut Tampil
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?: 0) }}" min="0" class="gov-input">
                </div>
                <div style="display: flex; align-items: center; padding-top: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: #334155; cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#0b213f;">
                        <span>Kategori Aktif & Tersedia untuk Publik</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                <a href="{{ route('admin.categories.index') }}" class="gov-btn gov-btn-outline">
                    Batal
                </a>
                <button type="submit" class="gov-btn gov-btn-primary">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
