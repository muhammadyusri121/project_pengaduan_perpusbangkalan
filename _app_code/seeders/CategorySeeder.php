<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name'        => 'Fasilitas & Sarana Prasarana',
                'icon'        => 'bi-building',
                'color'       => '#3498db',
                'description' => 'Pengaduan terkait kondisi gedung, meja, kursi, AC, toilet, dan fasilitas fisik lainnya.',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Pelayanan Petugas',
                'icon'        => 'bi-person-badge',
                'color'       => '#e74c3c',
                'description' => 'Pengaduan terkait sikap, profesionalitas, dan kualitas pelayanan petugas perpustakaan.',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Koleksi Buku & Referensi',
                'icon'        => 'bi-book',
                'color'       => '#2ecc71',
                'description' => 'Pengaduan tentang kelengkapan, kondisi, atau ketersediaan buku dan bahan referensi.',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Layanan Digital & Website',
                'icon'        => 'bi-laptop',
                'color'       => '#9b59b6',
                'description' => 'Pengaduan terkait sistem digital, website, e-library, atau layanan online perpustakaan.',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Kebersihan & Kenyamanan',
                'icon'        => 'bi-stars',
                'color'       => '#f39c12',
                'description' => 'Pengaduan mengenai kebersihan ruangan, kenyamanan, dan lingkungan perpustakaan.',
                'sort_order'  => 5,
            ],
            [
                'name'        => 'Jadwal & Jam Operasional',
                'icon'        => 'bi-clock',
                'color'       => '#1abc9c',
                'description' => 'Pengaduan terkait jam buka tutup, jadwal layanan, dan ketidaksesuaian operasional.',
                'sort_order'  => 6,
            ],
            [
                'name'        => 'Saran & Masukan',
                'icon'        => 'bi-lightbulb',
                'color'       => '#e67e22',
                'description' => 'Saran dan masukan untuk pengembangan dan peningkatan layanan perpustakaan.',
                'sort_order'  => 7,
            ],
            [
                'name'        => 'Lainnya',
                'icon'        => 'bi-chat-dots',
                'color'       => '#95a5a6',
                'description' => 'Pengaduan atau masukan yang tidak termasuk dalam kategori di atas.',
                'sort_order'  => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
