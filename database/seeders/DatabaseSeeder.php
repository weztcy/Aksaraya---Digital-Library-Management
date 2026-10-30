<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $settings = [
            'library_name' => 'Aksaraya',
            'library_tagline' => 'Perpustakaan Digital Nusantara Modern & Terpadu',
            'loan_period_days' => '7',
            'daily_fine_rate' => '1000',
            'max_borrow_limit' => '3',
            'library_address' => 'Gedung Aksaraya Lt. 3, Jl. Merdeka Literasi No. 45, Jakarta Pusat',
            'library_phone' => '+62 21 555-2026',
            'library_email' => 'layanan@aksaraya.id',
            'announcement' => 'Selamat datang di Aksaraya! Nikmati akses membaca ribuan e-book gratis serta peminjaman buku fisik secara online.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 2. Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@aksaraya.id'],
            [
                'name' => 'Administrator Aksaraya',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'member_number' => 'ADM-2026-0001',
                'phone' => '081234567890',
                'address' => 'Jl. Kebon Sirih No. 10, Jakarta',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@aksaraya.id'],
            [
                'name' => 'Sari Pustakawati, S.I.Pust',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'member_number' => 'STF-2026-0001',
                'phone' => '081234567891',
                'address' => 'Jl. Salemba Raya No. 25, Jakarta',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $anggota1 = User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0001',
                'phone' => '081298765432',
                'address' => 'Jl. Tebet Barat Dalam No. 12, Jakarta Selatan',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $anggota2 = User::updateOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0002',
                'phone' => '085711223344',
                'address' => 'Jl. Rawamangun Muka No. 8, Jakarta Timur',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $anggota3 = User::updateOrCreate(
            ['email' => 'dimas@gmail.com'],
            [
                'name' => 'Dimas Aditya',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0003',
                'phone' => '087855667788',
                'address' => 'Jl. Dago Asri No. 14, Bandung',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Categories
        $categoriesData = [
            [
                'name' => 'Teknologi & Pemrograman',
                'slug' => 'teknologi-pemrograman',
                'icon' => 'code',
                'color' => 'blue',
                'description' => 'Buku pemrograman web, mobile, artificial intelligence, cloud, dan arsitektur perangkat lunak modern.',
            ],
            [
                'name' => 'Fiksi & Sastra',
                'slug' => 'fiksi-sastra',
                'icon' => 'book-open',
                'color' => 'purple',
                'description' => 'Koleksi novel, sastra klasik, cerita fiksi inspiratif dari pengarang terbaik Indonesia dan dunia.',
            ],
            [
                'name' => 'Pengembangan Diri & Psikologi',
                'slug' => 'pengembangan-diri-psikologi',
                'icon' => 'sparkles',
                'color' => 'rose',
                'description' => 'Panduan motivasi, pembentukan kebiasaan, ketahanan mental, dan kepemimpinan efektif.',
            ],
            [
                'name' => 'Bisnis & Finansial',
                'slug' => 'bisnis-finansial',
                'icon' => 'trending-up',
                'color' => 'amber',
                'description' => 'Manajemen keuangan, investasi cerdas, strategi startup, dan kewirausahaan modern.',
            ],
            [
                'name' => 'Sains & Matematika',
                'slug' => 'sains-matematika',
                'icon' => 'atom',
                'color' => 'emerald',
                'description' => 'Eksplorasi ilmu alam, fisika, kosmologi, biologi, dan matematika untuk semua kalangan.',
            ],
            [
                'name' => 'Sejarah & Budaya Nusantara',
                'slug' => 'sejarah-budaya',
                'icon' => 'landmark',
                'color' => 'indigo',
                'description' => 'Perjalanan sejarah bangsa, arsip peradaban Nusantara, dan khazanah kebudayaan daerah.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::updateOrCreate(['slug' => $catData['slug']], $catData);
        }

        // Ensure sample ebook exists
        $ebookDir = storage_path('app/public/ebooks');
        if (! is_dir($ebookDir)) {
            mkdir($ebookDir, 0755, true);
        }
        $samplePdfPath = $ebookDir . '/sample-ebook.pdf';
        if (! file_exists($samplePdfPath)) {
            $pdfContent = "%PDF-1.4\n1 0 obj<< /Type /Catalog /Pages 2 0 R >>endobj\n2 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1 >>endobj\n3 0 obj<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>endobj\n4 0 obj<< /Length 200 >>stream\nBT /F1 20 Tf 50 720 Td (PUSTAKANESIA DIGITAL LIBRARY) Tj /F1 12 Tf 0 -35 Td (Selamat datang di e-Reader Aksaraya.) Tj 0 -25 Td (Buku digital ini dapat dibaca langsung melalui peramban Anda.) Tj ET\nendstream\nendobj\n5 0 obj<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>endobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000495 00000 n \ntrailer<< /Size 6 /Root 1 0 R >>\nstartxref\n565\n%%EOF";
            file_put_contents($samplePdfPath, $pdfContent);
        }

        // 4. Books
        $booksData = [
            [
                'category_slug' => 'fiksi-sastra',
                'title' => 'Laskar Pelangi',
                'slug' => 'laskar-pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'isbn' => '978-979-3062-79-2',
                'pages' => 529,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak A-01',
                'stock_total' => 6,
                'stock_available' => 4,
                'description' => 'Kisah inspiratif tentang sepuluh anak dari keluarga miskin di Pulau Belitung yang bersekolah di sebuah sekolah Muhammadiyah yang nyaris roboh. Penuh perjuangan, cinta ilmu, dan persahabatan yang mengharukan.',
                'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 1250,
            ],
            [
                'category_slug' => 'fiksi-sastra',
                'title' => 'Bumi Manusia',
                'slug' => 'bumi-manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra',
                'publication_year' => 1980,
                'isbn' => '978-979-97312-3-4',
                'pages' => 535,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak A-02',
                'stock_total' => 5,
                'stock_available' => 3,
                'description' => 'Karya agung Tetralogi Buru yang mengisahkan pergulatan Minke, seorang priyayi Jawa yang cerdas dan terdidik di masa kolonial Hindia Belanda, serta cintanya pada Annelies Mellema.',
                'cover_image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 980,
            ],
            [
                'category_slug' => 'fiksi-sastra',
                'title' => 'Laut Bercerita',
                'slug' => 'laut-bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2017,
                'isbn' => '978-602-424-694-5',
                'pages' => 379,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak A-03',
                'stock_total' => 4,
                'stock_available' => 2,
                'description' => 'Novel bertema sejarah pergerakan mahasiswa 1998, mengisahkan Biru Laut dan kawan-kawannya yang diculik dan dihilangkan secara paksa, serta duka mendalam keluarga yang mencari keadilan.',
                'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 1420,
            ],
            [
                'category_slug' => 'pengembangan-diri-psikologi',
                'title' => 'Filosofi Teras: Stoisisme Kuno untuk Mental Tangguh',
                'slug' => 'filosofi-teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Penerbit Buku Kompas',
                'publication_year' => 2019,
                'isbn' => '978-623-220-004-3',
                'pages' => 344,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak B-01',
                'stock_total' => 8,
                'stock_available' => 5,
                'description' => 'Penjelasan praktis tentang filsafat Stoisisme Yunani-Romawi kuno yang diadaptasi untuk kehidupan masa kini, mengatasi overthinking, kecemasan, dan emosi negatif.',
                'cover_image' => 'https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 2100,
            ],
            [
                'category_slug' => 'pengembangan-diri-psikologi',
                'title' => 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa',
                'slug' => 'atomic-habits-indonesia',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2019,
                'isbn' => '978-602-06-3317-6',
                'pages' => 356,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak B-02',
                'stock_total' => 7,
                'stock_available' => 6,
                'description' => 'Buku terlaris dunia yang mengajarkan cara membentuk kebiasaan baik, membuang kebiasaan buruk, dan menguasai perilaku-perilaku kecil yang menghasilkan lompatan besar.',
                'cover_image' => 'https://images.unsplash.com/photo-1499244571948-7ccddb3583f1?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 1890,
            ],
            [
                'category_slug' => 'teknologi-pemrograman',
                'title' => 'Clean Code: Panduan Desain & Arsitektur Perangkat Lunak',
                'slug' => 'clean-code-indonesia',
                'author' => 'Robert C. Martin (Uncle Bob)',
                'publisher' => 'Informatika Pratama',
                'publication_year' => 2021,
                'isbn' => '978-602-6232-15-1',
                'pages' => 464,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak C-01',
                'stock_total' => 5,
                'stock_available' => 3,
                'description' => 'Prinsip, pola desain, dan praktik terbaik penulisan kode bersih, refactoring, pengujian unit, serta pemeliharaan sistem skala besar bagi software engineer profesional.',
                'cover_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 1560,
            ],
            [
                'category_slug' => 'teknologi-pemrograman',
                'title' => 'Membangun Aplikasi Web Modern Berkinerja Tinggi dengan Laravel',
                'slug' => 'membangun-aplikasi-web-laravel',
                'author' => 'Tim Pengembang Aksaraya',
                'publisher' => 'Media Pustaka Edukasi',
                'publication_year' => 2024,
                'isbn' => '978-602-8888-22-9',
                'pages' => 420,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak C-02',
                'stock_total' => 10,
                'stock_available' => 9,
                'description' => 'Tutorial komprehensif mulai dari dasar MVC, Eloquent ORM canggih, Tailwind CSS, otentikasi role-based, hingga deployment production aplikasi Laravel modern.',
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => true,
                'views_count' => 2340,
            ],
            [
                'category_slug' => 'bisnis-finansial',
                'title' => 'The Psychology of Money: Pelajaran Abadi tentang Kekayaan & Kebahagiaan',
                'slug' => 'the-psychology-of-money-id',
                'author' => 'Morgan Housel',
                'publisher' => 'Penerbit Baca',
                'publication_year' => 2021,
                'isbn' => '978-602-6486-57-8',
                'pages' => 268,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak D-01',
                'stock_total' => 6,
                'stock_available' => 4,
                'description' => 'Mengapa kesuksesan finansial bukan hanya soal kepintaran matematika atau data keuangan, melainkan bagaimana perilaku psikologis kita memperlakukan uang.',
                'cover_image' => 'https://images.unsplash.com/photo-1553729459-efe14ef6055d?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => false,
                'ebook_file' => null,
                'is_featured' => true,
                'views_count' => 1740,
            ],
            [
                'category_slug' => 'sains-matematika',
                'title' => 'Kosmos: Menjelajahi Alam Semesta dan Asal-Usul Manusia',
                'slug' => 'kosmos-carl-sagan-id',
                'author' => 'Carl Sagan',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2018,
                'isbn' => '978-602-424-219-0',
                'pages' => 432,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak E-01',
                'stock_total' => 4,
                'stock_available' => 3,
                'description' => 'Perjalanan memukau selama 15 miliar tahun evolusi kosmik yang mengubah sains menjadi puisi, mengeksplorasi planet, galaksi, dan keberadaan manusia.',
                'cover_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => false,
                'views_count' => 840,
            ],
            [
                'category_slug' => 'sejarah-budaya',
                'title' => 'Madilog: Materialisme, Dialektika, dan Logika',
                'slug' => 'madilog-tan-malaka',
                'author' => 'Tan Malaka',
                'publisher' => 'Narasi Pustaka',
                'publication_year' => 2018,
                'isbn' => '978-979-168-450-7',
                'pages' => 568,
                'language' => 'Indonesia',
                'shelf_location' => 'Rak F-01',
                'stock_total' => 5,
                'stock_available' => 4,
                'description' => 'Masterpiece pemikiran filsafat Tan Malaka yang mengajak bangsa Indonesia keluar dari cara berpikir mistis menuju cara berpikir rasional, ilmiah, dan berdaya saing.',
                'cover_image' => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=800&q=80',
                'has_ebook' => true,
                'ebook_file' => 'ebooks/sample-ebook.pdf',
                'is_featured' => false,
                'views_count' => 1120,
            ],
        ];

        $books = [];
        foreach ($booksData as $bData) {
            $catSlug = $bData['category_slug'];
            unset($bData['category_slug']);
            $bData['category_id'] = $categories[$catSlug]->id;
            $books[] = Book::updateOrCreate(['slug' => $bData['slug']], $bData);
        }

        // 5. Sample Reviews
        $sampleReviews = [
            [
                'book_id' => $books[0]->id, // Laskar Pelangi
                'user_id' => $anggota1->id,
                'rating' => 5,
                'comment' => 'Buku yang sangat menginspirasi masa kecil saya. Karakter Lintang dan Bu Mus sangat membekas di hati!',
            ],
            [
                'book_id' => $books[0]->id,
                'user_id' => $anggota2->id,
                'rating' => 5,
                'comment' => 'Karya sastra legendaris yang wajib dibaca setiap anak Indonesia. Sangat direkomendasikan!',
            ],
            [
                'book_id' => $books[1]->id, // Bumi Manusia
                'user_id' => $anggota3->id,
                'rating' => 5,
                'comment' => 'Bahasa dan narasi Pram luar biasa tajam. Perspektif sejarah yang membuka mata tentang identitas kita.',
            ],
            [
                'book_id' => $books[3]->id, // Filosofi Teras
                'user_id' => $anggota1->id,
                'rating' => 5,
                'comment' => 'Sangat membantu saya mengelola stres kerja dan emosi. Stoisisme disajikan dengan bahasa santai tapi dalam.',
            ],
            [
                'book_id' => $books[5]->id, // Clean Code
                'user_id' => $anggota2->id,
                'rating' => 5,
                'comment' => 'Buku wajib untuk software engineer! Membaca kode sekarang jauh lebih menyenangkan setelah menerapkan prinsip Uncle Bob.',
            ],
        ];

        foreach ($sampleReviews as $rev) {
            Review::updateOrCreate(
                ['user_id' => $rev['user_id'], 'book_id' => $rev['book_id']],
                $rev
            );
        }

        // 6. Sample Bookmarks
        Bookmark::firstOrCreate(['user_id' => $anggota1->id, 'book_id' => $books[0]->id]);
        Bookmark::firstOrCreate(['user_id' => $anggota1->id, 'book_id' => $books[3]->id]);
        Bookmark::firstOrCreate(['user_id' => $anggota2->id, 'book_id' => $books[5]->id]);
        Bookmark::firstOrCreate(['user_id' => $anggota3->id, 'book_id' => $books[1]->id]);

        // 7. Sample Loans with various realistic statuses
        $sampleLoans = [
            [
                'loan_code' => 'PINJAM-202610-001',
                'user_id' => $anggota1->id,
                'book_id' => $books[0]->id, // Laskar Pelangi
                'loan_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(2),
                'return_date' => null,
                'status' => 'borrowed',
                'fine_amount' => 0,
                'fine_paid' => false,
                'officer_id' => $petugas->id,
                'notes' => 'Peminjaman reguler diambil di meja sirkulasi.',
            ],
            [
                'loan_code' => 'PINJAM-202610-002',
                'user_id' => $anggota2->id,
                'book_id' => $books[1]->id, // Bumi Manusia
                'loan_date' => Carbon::now()->subDays(12),
                'due_date' => Carbon::now()->subDays(5),
                'return_date' => null,
                'status' => 'overdue',
                'fine_amount' => 5000, // 5 days x 1000
                'fine_paid' => false,
                'officer_id' => $petugas->id,
                'notes' => 'Terlambat 5 hari. Denda berjalan Rp 5.000.',
            ],
            [
                'loan_code' => 'PINJAM-202610-003',
                'user_id' => $anggota3->id,
                'book_id' => $books[2]->id, // Laut Bercerita
                'loan_date' => Carbon::now()->subDays(2),
                'due_date' => Carbon::now()->addDays(5),
                'return_date' => null,
                'status' => 'pending',
                'fine_amount' => 0,
                'fine_paid' => false,
                'officer_id' => null,
                'notes' => 'Pengajuan pinjam online dari aplikasi.',
            ],
            [
                'loan_code' => 'PINJAM-202610-004',
                'user_id' => $anggota1->id,
                'book_id' => $books[3]->id, // Filosofi Teras
                'loan_date' => Carbon::now()->subDays(20),
                'due_date' => Carbon::now()->subDays(13),
                'return_date' => Carbon::now()->subDays(13),
                'status' => 'returned',
                'fine_amount' => 0,
                'fine_paid' => true,
                'officer_id' => $petugas->id,
                'notes' => 'Dikembalikan tepat waktu, kondisi buku sangat baik.',
            ],
        ];

        foreach ($sampleLoans as $l) {
            Loan::updateOrCreate(['loan_code' => $l['loan_code']], $l);
        }
    }
}
