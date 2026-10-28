@extends('layouts.admin')

@section('title', $user->id ? 'Edit Akun Pengguna' : 'Tambah Pengguna Baru')
@section('page_title', $user->id ? 'Edit Akun Pengguna' : 'Tambah Pengguna Baru')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="margin-bottom: 16px;">
        <a href="{{ route('admin.users.index') }}" class="gov-btn gov-btn-outline" style="padding: 6px 14px; font-size: 13px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="bi {{ $user->id ? 'bi-person-gear' : 'bi-person-plus-fill' }}"></i>
                <span>{{ $user->id ? 'Perbarui Profil Akun' : 'Informasi Akun Baru' }}</span>
            </div>
        </div>

        <form action="{{ $user->id ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" style="padding: 24px 28px;">
            @csrf
            @if($user->id)
                @method('PUT')
            @endif

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Nama Lengkap <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="100"
                       placeholder="Contoh: Budi Santoso, S.Kom" class="gov-input @error('name') is-invalid @enderror">
                @error('name')
                    <div style="font-size: 12px; color: #dc2626; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Alamat Email Kedinasan / Akun <span style="color: #ef4444;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       placeholder="nama@perpusbangkalan.go.id" class="gov-input @error('email') is-invalid @enderror">
                @error('email')
                    <div style="font-size: 12px; color: #dc2626; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Kata Sandi (Password)
                    @if(!$user->id)
                        <span style="color: #ef4444;">*</span>
                    @else
                        <span style="font-size: 12px; color: #64748b; font-weight: 400;">(Kosongkan jika tidak ingin mengubah password)</span>
                    @endif
                </label>
                <input type="password" name="password" {{ !$user->id ? 'required' : '' }}
                       placeholder="Min. 8 karakter (huruf besar, kecil, dan angka)"
                       class="gov-input @error('password') is-invalid @enderror">
                <span style="display: block; font-size: 11.5px; color: #64748b; margin-top: 5px;">
                    <i class="bi bi-info-circle"></i> Password harus terdiri minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka.
                </span>
                @error('password')
                    <div style="font-size: 12px; color: #dc2626; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                    Peran & Hak Akses (Role) <span style="color: #ef4444;">*</span>
                </label>
                <select name="role" class="gov-input @error('role') is-invalid @enderror" required>
                    <option value="petugas" {{ old('role', $user->role ?? 'petugas') === 'petugas' ? 'selected' : '' }}>
                        Petugas (Verifikasi, Tanggapi Aduan, dan Update Status)
                    </option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                        Administrator (Akses Penuh: Manajemen Pengguna, Kategori & Sistem)
                    </option>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                        Masyarakat / Pelapor (Hanya dapat membuat & melacak pengaduan publik)
                    </option>
                </select>
                @error('role')
                    <div style="font-size: 12px; color: #dc2626; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            @if($user->id)
                <div style="margin-bottom: 24px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 600; color: #1e293b; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            style="width: 18px; height: 18px; accent-color: #0b213f;">
                        <span>Status Akun Aktif (Dapat masuk ke dashboard)</span>
                    </label>
                </div>
            @endif

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                <a href="{{ route('admin.users.index') }}" class="gov-btn gov-btn-outline">
                    Batal
                </a>
                <button type="submit" class="gov-btn gov-btn-primary">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ $user->id ? 'Perbarui Pengguna' : 'Simpan Pengguna' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
