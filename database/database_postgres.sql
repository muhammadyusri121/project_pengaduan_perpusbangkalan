-- ============================================================
-- SQL Script Schema & Seeder untuk PostgreSQL
-- Aplikasi Pengaduan Perpustakaan Bangkalan
-- ============================================================

-- 1. Buat Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user' CHECK (role IN ('admin', 'petugas', 'user')),
    avatar VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. Buat Tabel Password Resets
CREATE TABLE IF NOT EXISTS password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);
CREATE INDEX IF NOT EXISTS idx_password_resets_email ON password_resets(email);

-- 3. Buat Tabel Failed Jobs
CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 4. Buat Tabel Personal Access Tokens
CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_tokenable ON personal_access_tokens(tokenable_type, tokenable_id);

-- 5. Buat Tabel Categories
CREATE TABLE IF NOT EXISTS categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL DEFAULT 'bi-tag',
    color VARCHAR(50) NOT NULL DEFAULT '#3498db',
    description TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- 6. Buat Tabel Complaints
CREATE TABLE IF NOT EXISTS complaints (
    id BIGSERIAL PRIMARY KEY,
    tracking_code VARCHAR(10) NOT NULL UNIQUE,
    category_id BIGINT NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    reporter_name VARCHAR(255) NOT NULL,
    reporter_phone VARCHAR(20) NOT NULL,
    reporter_email VARCHAR(255) NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'in_progress', 'resolved', 'rejected')),
    priority VARCHAR(20) NOT NULL DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
    is_anonymous BOOLEAN NOT NULL DEFAULT FALSE,
    rejection_reason TEXT NULL,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- 7. Buat Tabel Complaint Attachments
CREATE TABLE IF NOT EXISTS complaint_attachments (
    id BIGSERIAL PRIMARY KEY,
    complaint_id BIGINT NOT NULL REFERENCES complaints(id) ON DELETE CASCADE,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size BIGINT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- 8. Buat Tabel Complaint Responses
CREATE TABLE IF NOT EXISTS complaint_responses (
    id BIGSERIAL PRIMARY KEY,
    complaint_id BIGINT NOT NULL REFERENCES complaints(id) ON DELETE CASCADE,
    user_id BIGINT NULL REFERENCES users(id) ON DELETE SET NULL,
    message TEXT NOT NULL,
    status_change VARCHAR(20) NULL CHECK (status_change IS NULL OR status_change IN ('pending', 'in_progress', 'resolved', 'rejected')),
    is_public BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

-- 9. Pencatatan Migrations (Agar sinkron dengan Laravel)
CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
);

INSERT INTO migrations (migration, batch) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2024_01_01_000001_create_categories_table', 1),
('2024_01_01_000002_create_complaints_table', 1),
('2024_01_01_000003_create_complaint_attachments_table', 1),
('2024_01_01_000004_create_complaint_responses_table', 1)
ON CONFLICT DO NOTHING;

-- ============================================================
-- SEED DATA AWAL (Kategori & Akun Admin/Petugas)
-- ============================================================

-- Data Users Default (Admin & Petugas)
-- Password Admin: Admin@Perpus2024!
-- Password Petugas: Petugas@2024!
INSERT INTO users (name, email, password, role, is_active, created_at, updated_at)
VALUES 
(
    'Administrator', 
    'admin@perpusbangkalan.go.id', 
    '$2y$10$ReCxwCY1xC/m1eA2RRVI1.VNDxW6dD//CnHX5/E8xnZMmK1FtXp/K', 
    'admin', 
    TRUE, 
    CURRENT_TIMESTAMP, 
    CURRENT_TIMESTAMP
),
(
    'Petugas Perpustakaan', 
    'petugas@perpusbangkalan.go.id', 
    '$2y$10$oYM1bDC.88264t33rBtKe.dJKmQ.JhRxuKRDCJfe3Gj.ezlAGNAWq', 
    'petugas', 
    TRUE, 
    CURRENT_TIMESTAMP, 
    CURRENT_TIMESTAMP
)
ON CONFLICT (email) DO NOTHING;

-- Data Kategori Awal
INSERT INTO categories (name, icon, color, description, is_active, sort_order, created_at, updated_at)
VALUES
('Fasilitas & Sarana Prasarana', 'bi-building', '#3498db', 'Pengaduan terkait kondisi gedung, meja, kursi, AC, toilet, dan fasilitas fisik lainnya.', TRUE, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Pelayanan Petugas', 'bi-person-badge', '#e74c3c', 'Pengaduan terkait sikap, profesionalitas, dan kualitas pelayanan petugas perpustakaan.', TRUE, 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Koleksi Buku & Referensi', 'bi-book', '#2ecc71', 'Pengaduan tentang kelengkapan, kondisi, atau ketersediaan buku dan bahan referensi.', TRUE, 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Layanan Digital & Website', 'bi-laptop', '#9b59b6', 'Pengaduan terkait sistem digital, website, e-library, atau layanan online perpustakaan.', TRUE, 4, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Kebersihan & Kenyamanan', 'bi-stars', '#f39c12', 'Pengaduan mengenai kebersihan ruangan, kenyamanan, dan lingkungan perpustakaan.', TRUE, 5, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Jadwal & Jam Operasional', 'bi-clock', '#1abc9c', 'Pengaduan terkait jam buka tutup, jadwal layanan, dan ketidaksesuaian operasional.', TRUE, 6, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Saran & Masukan', 'bi-lightbulb', '#e67e22', 'Saran dan masukan untuk pengembangan dan peningkatan layanan perpustakaan.', TRUE, 7, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
('Lainnya', 'bi-chat-dots', '#95a5a6', 'Pengaduan atau masukan yang tidak termasuk dalam kategori di atas.', TRUE, 8, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
