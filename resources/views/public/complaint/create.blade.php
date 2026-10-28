@extends('layouts.app')

@section('title', 'Tulis Pengaduan Baru')

@section('content')
<div class="form-container-wrap">
    <div class="gov-form-card">
        <!-- Header Form -->
        <div class="gov-form-header">
            <div class="header-badge"><i class="bi bi-shield-fill-check"></i> Formulir Pengaduan Resmi</div>
            <h1>Sampaikan Aspirasi & Pengaduan Anda</h1>
            <p>Mohon lengkapi formulir di bawah ini dengan data yang jelas dan valid untuk mempercepat tindak lanjut petugas DISPUSIP Bangkalan.</p>
        </div>

        <form action="{{ route('complaint.store') }}" method="POST" enctype="multipart/form-data" class="gov-form-body">
            @csrf

            <!-- Bagian 1: Kategori -->
            <div class="form-section">
                <label class="section-label">
                    <span>1. Pilih Kategori Pengaduan <span class="req">*</span></span>
                    <small>Pilih bidang layanan yang sesuai dengan permasalahan Anda</small>
                </label>
                
                <div class="category-selection-grid">
                    @foreach($categories as $cat)
                        <label class="cat-pill-label">
                            <input type="radio" name="category_id" value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'checked' : '' }} required>
                            <span class="cat-pill-box">
                                <i class="bi {{ $cat->icon ?: 'bi-tag-fill' }} cat-icon" style="color: {{ $cat->color ?: '#0e243f' }};"></i>
                                <span class="cat-name">{{ $cat->name }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('category_id')
                    <span class="field-error-msg">{{ $message }}</span>
                @enderror
            </div>

            <!-- Bagian 2: Pokok Pengaduan -->
            <div class="form-section">
                <label class="section-label">
                    <span>2. Pokok Permasalahan & Kronologi <span class="req">*</span></span>
                    <small>Uraikan inti permasalahan dan rincian kejadian</small>
                </label>

                <div class="form-group-field">
                    <label for="subject" class="sub-label">Judul Pengaduan <span class="req">*</span> <small style="font-weight:400;color:#64748b;">(Min. 10 karakter)</small></label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                           placeholder="Contoh: Lampu Ruang Baca Lantai 2 Berkedip dan Menyilaukan Pengunjung"
                           class="gov-input @error('subject') is-invalid @enderror">
                    @error('subject')
                        <span class="field-error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group-field">
                    <label for="description" class="sub-label">Deskripsi Lengkap Aduan <span class="req">*</span> <small style="font-weight:400;color:#64748b;">(Min. 20 karakter)</small></label>
                    <textarea id="description" name="description" rows="5" required
                              placeholder="Uraikan waktu kejadian, kronologi, dampak terhadap kenyamanan membaca, serta saran perbaikan..."
                              class="gov-input gov-textarea @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="field-error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group-field">
                    <label for="location" class="sub-label">Lokasi / Ruangan Spesifik</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}"
                           placeholder="Contoh: Lantai 2, Meja Baca Pojok Kiri"
                           class="gov-input @error('location') is-invalid @enderror">
                    @error('location')
                        <span class="field-error-msg">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Bagian 3: Identitas Pelapor -->
            <div class="form-section">
                <label class="section-label">
                    <span>3. Data Kontak Pelapor <span class="req">*</span></span>
                    <small>Digunakan untuk klarifikasi dan notifikasi tindak lanjut aduan</small>
                </label>

                <div class="form-row-2col">
                    <div class="form-group-field">
                        <label for="reporter_name" class="sub-label">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" id="reporter_name" name="reporter_name" value="{{ old('reporter_name', Auth::user()->name ?? '') }}" required
                               placeholder="Nama lengkap Anda"
                               class="gov-input @error('reporter_name') is-invalid @enderror">
                        @error('reporter_name')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group-field">
                        <label for="reporter_phone" class="sub-label">Nomor WhatsApp / HP Aktif <span class="req">*</span></label>
                        <input type="tel" id="reporter_phone" name="reporter_phone" value="{{ old('reporter_phone') }}" required
                               placeholder="081234567890"
                               class="gov-input @error('reporter_phone') is-invalid @enderror">
                        @error('reporter_phone')
                            <span class="field-error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group-field">
                    <label for="reporter_email" class="sub-label">Alamat Email Pelapor</label>
                    <input type="email" id="reporter_email" name="reporter_email" value="{{ old('reporter_email', Auth::user()->email ?? '') }}"
                           placeholder="nama@email.com"
                           class="gov-input @error('reporter_email') is-invalid @enderror">
                    @error('reporter_email')
                        <span class="field-error-msg">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Bagian 4: Lampiran Bukti -->
            <div class="form-section">
                <label class="section-label">
                    <span>4. Lampiran Bukti (Opsional)</span>
                    <small>Unggah foto atau dokumen bukti kendala (Maks. 5 file, format JPG/PNG/PDF, maks 5MB/file)</small>
                </label>

                <div class="upload-dropzone" onclick="document.getElementById('attachments').click()">
                    <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                    <strong>Pilih File dari Perangkat Anda</strong>
                    <span>Format: JPG, PNG, GIF, PDF (Maks. 5MB)</span>
                    <input type="file" id="attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf" style="display:none;" onchange="showFileNames(this)">
                </div>
                <div id="fileListContainer" class="file-list-preview"></div>
                @error('attachments')
                    <span class="field-error-msg">{{ $message }}</span>
                @enderror
                @error('attachments.*')
                    <span class="field-error-msg">{{ $message }}</span>
                @enderror
            </div>

            <!-- Bagian 5: Privasi Anonim -->
            <div class="form-section anon-box">
                <label class="anon-toggle">
                    <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                    <div class="anon-text">
                        <strong>Kirim sebagai Laporan Anonim</strong>
                        <p>Nama Anda tidak akan dipublikasikan secara terbuka di riwayat laporan publik demi kenyamanan dan perlindungan privasi.</p>
                    </div>
                </label>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-action-row">
                <a href="{{ route('home') }}" class="gov-btn gov-btn-outline">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="gov-btn gov-btn-primary btn-submit-laporan">
                    <i class="bi bi-send-fill"></i>
                    <span>Kirim Pengaduan Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-container-wrap {
    max-width: 820px;
    margin: 0 auto;
}

.gov-form-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 10px 30px -10px rgba(11, 33, 63, 0.08);
    overflow: hidden;
}

.gov-form-header {
    background: linear-gradient(135deg, #07172c 0%, #0e274b 60%, #173b6a 100%);
    color: #ffffff;
    padding: 38px 40px;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #fbbf24;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 12px;
}

.gov-form-header h1 {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 6px;
    letter-spacing: -0.3px;
    color: #ffffff;
}

.gov-form-header p {
    font-size: 14px;
    color: #cbd5e1;
    line-height: 1.55;
    margin: 0;
}

.gov-form-body {
    padding: 36px 40px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.section-label {
    display: flex;
    flex-direction: column;
    gap: 2px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 8px;
}

.section-label span {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}

.section-label small {
    font-size: 12.5px;
    color: #64748b;
}

.req {
    color: #ef4444;
}

/* Category Grid */
.category-selection-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.cat-pill-label {
    position: relative;
    cursor: pointer;
}

.cat-pill-label input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.cat-pill-box {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.15s ease;
}

.cat-pill-label:hover .cat-pill-box {
    border-color: #94a3b8;
    background: #ffffff;
}

.cat-pill-label input:checked + .cat-pill-box {
    background: #eff6ff;
    border-color: #1e3a5f;
    box-shadow: 0 0 0 1px #1e3a5f;
}

.cat-icon {
    font-size: 16px;
    color: #64748b;
}

.cat-pill-label input:checked + .cat-pill-box .cat-icon {
    color: #1e3a5f;
}

.cat-name {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}

.cat-pill-label input:checked + .cat-pill-box .cat-name {
    color: #1e3a5f;
    font-weight: 700;
}

/* Input Fields */
.form-group-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.sub-label {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
}

.gov-input {
    width: 100%;
    padding: 12px 16px;
    font-size: 14px;
    font-family: inherit;
    color: #0f172a;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    outline: none;
    transition: all 0.2s ease;
}

.gov-input:focus {
    background: #ffffff;
    border-color: #0b213f;
    box-shadow: 0 0 0 3px rgba(11, 33, 63, 0.08);
}

.gov-input.is-invalid {
    border-color: #ef4444;
    background: #ffffff;
}

.gov-textarea {
    resize: vertical;
    min-height: 120px;
    line-height: 1.6;
}

.form-row-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.field-error-msg {
    font-size: 12px;
    color: #dc2626;
    margin-top: 2px;
}

/* Dropzone */
.upload-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    padding: 24px 20px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.upload-dropzone:hover {
    border-color: #0b213f;
    background: #eff6ff;
}

.upload-icon {
    font-size: 30px;
    color: #0b213f;
    margin-bottom: 2px;
}

.upload-dropzone strong {
    font-size: 14px;
    color: #1e293b;
}

.upload-dropzone span {
    font-size: 12px;
    color: #64748b;
}

.file-list-preview {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: 6px;
}

.file-preview-item {
    font-size: 12.5px;
    color: #0f172a;
    background: #f1f5f9;
    padding: 7px 12px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Anon Box */
.anon-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
}

.anon-toggle {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
}

.anon-toggle input {
    width: 18px;
    height: 18px;
    accent-color: #0b213f;
    margin-top: 2px;
    cursor: pointer;
}

.anon-text strong {
    display: block;
    font-size: 13.5px;
    color: #0f172a;
    margin-bottom: 2px;
}

.anon-text p {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
}

/* Form Action Buttons */
.form-action-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.btn-submit-laporan {
    padding: 13px 28px;
    font-size: 14.5px;
}

@media (max-width: 768px) {
    .gov-form-header, .gov-form-body {
        padding: 26px 20px;
    }
    .category-selection-grid {
        grid-template-columns: 1fr;
    }
    .form-row-2col {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showFileNames(input) {
    const container = document.getElementById('fileListContainer');
    container.innerHTML = '';
    if (input.files.length > 0) {
        for (let i = 0; i < input.files.length; i++) {
            const item = document.createElement('div');
            item.className = 'file-preview-item';
            item.innerHTML = `<i class="bi bi-file-earmark-check-fill" style="color:#0b213f;"></i> ${input.files[i].name} <small style="color:#64748b;">(${(input.files[i].size / 1024).toFixed(1)} KB)</small>`;
            container.appendChild(item);
        }
    }
}
</script>
@endsection
