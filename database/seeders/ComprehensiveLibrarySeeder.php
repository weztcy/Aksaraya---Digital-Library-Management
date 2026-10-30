<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\CirculationNotification;
use App\Models\Loan;
use App\Models\ReadingChallenge;
use App\Models\ReadingHistory;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserShelf;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ComprehensiveLibrarySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tambahan Kategori Lengkap
        $extraCategories = [
            [
                'name' => 'Kesehatan & Gaya Hidup',
                'slug' => 'kesehatan-gaya-hidup',
                'icon' => 'heart-pulse',
                'color' => 'rose',
                'description' => 'Nutrisi seimbang, kebugaran jasmani, kesehatan mental, medis populer, dan panduan pola hidup sehat.',
            ],
            [
                'name' => 'Pendidikan & Pembelajaran',
                'slug' => 'pendidikan-pembelajaran',
                'icon' => 'graduation-cap',
                'color' => 'blue',
                'description' => 'Metodologi pedagogi, literasi sekolah, riset pendidikan, psikologi perkembangan, dan buku pegangan guru.',
            ],
            [
                'name' => 'Seni, Desain & Arsitektur',
                'slug' => 'seni-desain-arsitektur',
                'icon' => 'palette',
                'color' => 'purple',
                'description' => 'Estetika visual, tipografi, desain antarmuka (UI/UX), sejarah seni rupa, dan arsitektur Nusantara.',
            ],
            [
                'name' => 'Agama & Spiritualitas',
                'slug' => 'agama-spiritualitas',
                'icon' => 'moon',
                'color' => 'amber',
                'description' => 'Khazanah keislaman, etika moral, filsafat ketuhanan, tafsir modern, dan kerukunan beragama.',
            ],
        ];

        foreach ($extraCategories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $allCategories = Category::all()->keyBy('slug');

        // 2. Tambahan Pengguna & Anggota (Petugas & Mahasiswa/Masyarakat)
        $extraUsers = [
            [
                'email' => 'librarian2@aksaraya.id',
                'name' => 'Rian Hidayat, S.Sos',
                'role' => 'petugas',
                'member_number' => 'STF-2026-0002',
                'phone' => '081234567892',
                'address' => 'Jl. Percetakan Negara No. 18, Jakarta Pusat',
            ],
            [
                'email' => 'anita.wijaya@gmail.com',
                'name' => 'Anita Kusuma Wijaya',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0004',
                'phone' => '081377889900',
                'address' => 'Jl. Boulevard Kelapa Gading Blok M, Jakarta Utara',
            ],
            [
                'email' => 'fajar.pratama@gmail.com',
                'name' => 'Fajar Pratama Putra',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0005',
                'phone' => '082199001122',
                'address' => 'Jl. Margonda Raya No. 102, Depok',
            ],
            [
                'email' => 'nadia.safira@gmail.com',
                'name' => 'Nadia Safira Putri',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0006',
                'phone' => '085233445566',
                'address' => 'Jl. Kaliurang KM 5, Sleman, Yogyakarta',
            ],
            [
                'email' => 'reza.aditya@gmail.com',
                'name' => 'M. Reza Aditya',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0007',
                'phone' => '087788990011',
                'address' => 'Jl. Dipati Ukur No. 35, Bandung',
            ],
            [
                'email' => 'maya.lestari@gmail.com',
                'name' => 'Maya Tri Lestari',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0008',
                'phone' => '089611223344',
                'address' => 'Jl. Manyar Kertoarjo No. 20, Surabaya',
            ],
            [
                'email' => 'ilham.ramadhan@gmail.com',
                'name' => 'Ilham Ramadhan',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0009',
                'phone' => '081822334455',
                'address' => 'Jl. Pettarani Blok B No. 4, Makassar',
            ],
            [
                'email' => 'clara.siregar@gmail.com',
                'name' => 'Clara Yosephine Siregar',
                'role' => 'anggota',
                'member_number' => 'AKS-202610-0010',
                'phone' => '081266778899',
                'address' => 'Jl. Jamin Ginting No. 88, Medan',
            ],
        ];

        foreach ($extraUsers as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                array_merge($u, [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ])
            );
        }

        $allMembers = User::where('role', 'anggota')->get();
        $staffUser = User::where('role', 'petugas')->first();

        // 3. Koleksi Buku Baru Berkualitas Tinggi
        $newBooks = [
            // Fiksi & Sastra
            [
                'title' => 'Ronggeng Dukuh Paruk',
                'author' => 'Ahmad Tohari',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2018,
                'isbn' => '978-979-22-7724-1',
                'category_id' => $allCategories['fiksi-sastra']->id,
                'pages' => 408,
                'shelf_location' => 'RAK-B04',
                'stock_total' => 5,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Trilogi legendaris Ahmad Tohari yang mengisahkan Srintil dan Rasus dengan latar pedukuhan terpencil di Banyumas serta gejolak pergolakan politik 1965.',
                'cover_image' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2021,
                'isbn' => '978-979-22-8141-5',
                'category_id' => $allCategories['fiksi-sastra']->id,
                'pages' => 280,
                'shelf_location' => 'RAK-B05',
                'stock_total' => 6,
                'stock_available' => 5,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Kisah epik pencarian cinta masa lalu, rahasia saus tembakau kretek, persaingan bisnis keluarga, dan sejarah industri rokok kretek di Jawa Tengah tempo dulu.',
                'cover_image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2020,
                'isbn' => '978-602-03-1258-3',
                'category_id' => $allCategories['fiksi-sastra']->id,
                'pages' => 536,
                'shelf_location' => 'RAK-B06',
                'stock_total' => 4,
                'stock_available' => 3,
                'has_ebook' => false,
                'is_featured' => true,
                'description' => 'Mahakarya realisme magis Indonesia yang diakui dunia internasional. Mengisahkan Dewi Ayu di kota fiksi Halimunda melewati masa kolonial Belanda, pendudukan Jepang, hingga kemerdekaan.',
                'cover_image' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Saman',
                'author' => 'Ayu Utami',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2019,
                'isbn' => '978-602-481-224-9',
                'category_id' => $allCategories['fiksi-sastra']->id,
                'pages' => 216,
                'shelf_location' => 'RAK-B07',
                'stock_total' => 5,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Novel pendobrak sastra Indonesia pemenang Sayembara DKJ 1998 yang mengangkat keterbukaan ekspresi, pergulatan nurani aktivis, dan hak-hak kemanusiaan.',
                'cover_image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80',
            ],

            // Teknologi & Pemrograman
            [
                'title' => 'Designing Data-Intensive Applications',
                'author' => 'Martin Kleppmann',
                'publisher' => "O'Reilly Media",
                'publication_year' => 2022,
                'isbn' => '978-144-937-332-0',
                'category_id' => $allCategories['teknologi-pemrograman']->id,
                'pages' => 616,
                'shelf_location' => 'RAK-T04',
                'stock_total' => 4,
                'stock_available' => 2,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Kitab panduan fundamental arsitektur data terdistribusi modern: replikasi, partisi data, transaksi ACID, konsensus terdistribusi, batch & stream processing.',
                'cover_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'The Pragmatic Programmer: 20th Anniversary Edition',
                'author' => 'David Thomas & Andrew Hunt',
                'publisher' => 'Addison-Wesley Professional',
                'publication_year' => 2020,
                'isbn' => '978-013-595-705-9',
                'category_id' => $allCategories['teknologi-pemrograman']->id,
                'pages' => 352,
                'shelf_location' => 'RAK-T05',
                'stock_total' => 5,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Prinsip ketrampilan praktis software craftsmanship: DRY, refactoring, decoupling kebiasaan debugging efektif, dan etos profesional seorang insinyur perangkat lunak.',
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Building Microservices: Designing Fine-Grained Systems',
                'author' => 'Sam Newman',
                'publisher' => "O'Reilly Media",
                'publication_year' => 2021,
                'isbn' => '978-149-203-402-5',
                'category_id' => $allCategories['teknologi-pemrograman']->id,
                'pages' => 612,
                'shelf_location' => 'RAK-T06',
                'stock_total' => 3,
                'stock_available' => 3,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Panduan komprehensif merancang arsitektur microservices yang handal, pemecahan monolith, komunikasi inter-service, keamanan, deployment CI/CD, dan observabilitas.',
                'cover_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
            ],

            // Pengembangan Diri & Psikologi
            [
                'title' => 'Berani Tidak Disukai',
                'author' => 'Ichiro Kishimi & Fumitake Koga',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2022,
                'isbn' => '978-602-06-3321-3',
                'category_id' => $allCategories['pengembangan-diri-psikologi']->id,
                'pages' => 350,
                'shelf_location' => 'RAK-P04',
                'stock_total' => 8,
                'stock_available' => 6,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Dialog filosofis berlandaskan teori Psikologi Adlerian tentang keberanian menentukan kebahagiaan sejati, memisahkan tugas pribadi dari ekspektasi sosial orang lain.',
                'cover_image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Deep Work: Rules for Focused Success',
                'author' => 'Cal Newport',
                'publisher' => 'Grand Central Publishing',
                'publication_year' => 2019,
                'isbn' => '978-145-558-669-1',
                'category_id' => $allCategories['pengembangan-diri-psikologi']->id,
                'pages' => 304,
                'shelf_location' => 'RAK-P05',
                'stock_total' => 6,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Strategi meraih konsentrasi tingkat tinggi tanpa distraksi media sosial dan pesan instan guna menghasilkan karya bernilai tinggi dalam waktu efisien.',
                'cover_image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Grit: Kekuatan Passion dan Kegigihan',
                'author' => 'Angela Duckworth',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2020,
                'isbn' => '978-602-06-2184-5',
                'category_id' => $allCategories['pengembangan-diri-psikologi']->id,
                'pages' => 440,
                'shelf_location' => 'RAK-P06',
                'stock_total' => 5,
                'stock_available' => 5,
                'has_ebook' => false,
                'is_featured' => false,
                'description' => 'Penelitian psikologi terkemuka membuktikan bahwa bakat alami hanyalah permulaan. Yang sesungguhnya memprediksi keberhasilan jangka panjang adalah kombinasi ketekunan dan dedikasi tinggi.',
                'cover_image' => 'https://images.unsplash.com/photo-1517842645767-c639042777db?auto=format&fit=crop&w=600&q=80',
            ],

            // Bisnis & Finansial
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'publisher' => 'Crown Business',
                'publication_year' => 2021,
                'isbn' => '978-030-788-789-4',
                'category_id' => $allCategories['bisnis-finansial']->id,
                'pages' => 336,
                'shelf_location' => 'RAK-M03',
                'stock_total' => 6,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Metodologi inovasi bisnis berbasis Build-Measure-Learn dan Minimum Viable Product (MVP) untuk menguji ide bisnis secara cepat di bawah ketidakpastian ekstrem.',
                'cover_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Zero to One: Notes on Startups',
                'author' => 'Peter Thiel & Blake Masters',
                'publisher' => 'Currency',
                'publication_year' => 2020,
                'isbn' => '978-080-413-929-7',
                'category_id' => $allCategories['bisnis-finansial']->id,
                'pages' => 224,
                'shelf_location' => 'RAK-M04',
                'stock_total' => 5,
                'stock_available' => 3,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Filosofi membangun monopoli kreatif dan menciptakan hal-hal baru yang belum pernah ada di dunia daripada sekadar meniru inovasi yang sudah ada.',
                'cover_image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=600&q=80',
            ],

            // Sains & Matematika
            [
                'title' => 'Cosmos: Gagasan tentang Alam Semesta',
                'author' => 'Carl Sagan',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2021,
                'isbn' => '978-602-424-219-0',
                'category_id' => $allCategories['sains-matematika']->id,
                'pages' => 448,
                'shelf_location' => 'RAK-S03',
                'stock_total' => 4,
                'stock_available' => 3,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Perjalanan puitis dan sains mengarungi 15 miliar tahun evolusi kosmik, misteri galaksi, asal-usul materi kehidupan, dan masa depan eksplorasi luar angkasa umat manusia.',
                'cover_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Astrophysics for People in a Hurry',
                'author' => 'Neil deGrasse Tyson',
                'publisher' => 'W. W. Norton & Company',
                'publication_year' => 2019,
                'isbn' => '978-039-360-939-4',
                'category_id' => $allCategories['sains-matematika']->id,
                'pages' => 224,
                'shelf_location' => 'RAK-S04',
                'stock_total' => 5,
                'stock_available' => 5,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Penjelasan padat, cerdas, dan menyenangkan mengenai lubang hitam, materi gelap, energi gelap, dan hukum fisika yang mengatur jagat raya.',
                'cover_image' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=600&q=80',
            ],

            // Sejarah & Budaya
            [
                'title' => 'Nusantara: Sejarah Indonesia',
                'author' => 'Bernard H. M. Vlekke',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2020,
                'isbn' => '978-602-424-697-6',
                'category_id' => $allCategories['sejarah-budaya']->id,
                'pages' => 544,
                'shelf_location' => 'RAK-H03',
                'stock_total' => 4,
                'stock_available' => 2,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Arsip kronik historiografi terlengkap tentang pembentukan peradaban kepulauan Nusantara dari masa kerajaan Hindu-Buddha, era kesultanan Islam, hingga kolonialisme.',
                'cover_image' => 'https://images.unsplash.com/photo-1533158307587-828f0a76ef46?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Madilog: Materialisme, Dialektika & Logika',
                'author' => 'Tan Malaka',
                'publisher' => 'Narasi',
                'publication_year' => 2021,
                'isbn' => '978-979-168-387-6',
                'category_id' => $allCategories['sejarah-budaya']->id,
                'pages' => 568,
                'shelf_location' => 'RAK-H04',
                'stock_total' => 5,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Karya pemikiran filosofis salah satu founding fathers Indonesia untuk membebaskan akal budi rakyat dari belenggu mistisisme menuju cara berpikir ilmiah dan rasional.',
                'cover_image' => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=600&q=80',
            ],

            // Kesehatan & Gaya Hidup
            [
                'title' => 'Why We Sleep: Unlocking the Power of Sleep',
                'author' => 'Matthew Walker, PhD',
                'publisher' => 'Scribner',
                'publication_year' => 2020,
                'isbn' => '978-150-114-432-5',
                'category_id' => $allCategories['kesehatan-gaya-hidup']->id,
                'pages' => 368,
                'shelf_location' => 'RAK-K01',
                'stock_total' => 6,
                'stock_available' => 5,
                'has_ebook' => true,
                'is_featured' => true,
                'description' => 'Penemuan sains revolusioner tentang pentingnya tidur berkualitas bagi daya ingat, kekebalan tubuh, metabolisme, dan pencegahan penyakit kronis.',
                'cover_image' => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'title' => 'Sehat Tanpa Obat: Panduan Imunitas Holistik',
                'author' => 'dr. Zaidul Akbar',
                'publisher' => 'Syaamil Books',
                'publication_year' => 2022,
                'isbn' => '978-602-550-112-9',
                'category_id' => $allCategories['kesehatan-gaya-hidup']->id,
                'pages' => 280,
                'shelf_location' => 'RAK-K02',
                'stock_total' => 7,
                'stock_available' => 6,
                'has_ebook' => false,
                'is_featured' => false,
                'description' => 'Prinsip pola makan alami menggunakan rempah-rempah Nusantara, intermittent fasting, dan detoksifikasi alami tubuh untuk meningkatkan stamina.',
                'cover_image' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=600&q=80',
            ],

            // Seni & Arsitektur
            [
                'title' => 'Don\'t Make Me Think, Revisited',
                'author' => 'Steve Krug',
                'publisher' => 'New Riders',
                'publication_year' => 2020,
                'isbn' => '978-032-196-551-6',
                'category_id' => $allCategories['seni-desain-arsitektur']->id,
                'pages' => 216,
                'shelf_location' => 'RAK-A01',
                'stock_total' => 5,
                'stock_available' => 4,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Buku legendaris desain antarmuka dan usabilitas web/aplikasi. Prinsip intuitif bagaimana pengguna berinteraksi dengan produk digital tanpa kebingungan.',
                'cover_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=600&q=80',
            ],

            // Pendidikan
            [
                'title' => 'Menjadi Guru Inspiratif dan Berdaya',
                'author' => 'Munif Chatib',
                'publisher' => 'Kaifa',
                'publication_year' => 2019,
                'isbn' => '978-602-787-091-8',
                'category_id' => $allCategories['pendidikan-pembelajaran']->id,
                'pages' => 264,
                'shelf_location' => 'RAK-E01',
                'stock_total' => 4,
                'stock_available' => 3,
                'has_ebook' => true,
                'is_featured' => false,
                'description' => 'Metodologi Multiple Intelligences di ruang kelas untuk menemukan keunikan setiap anak didik dan menciptakan suasana belajar yang menyenangkan.',
                'cover_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $createdBooks = [];
        foreach ($newBooks as $bookInfo) {
            $slug = Str::slug($bookInfo['title']);
            $createdBooks[] = Book::updateOrCreate(
                ['isbn' => $bookInfo['isbn']],
                array_merge($bookInfo, [
                    'slug' => $slug,
                    'language' => 'Indonesia',
                ])
            );
        }

        // Ambil semua buku di database
        $allBooks = Book::all();

        // 4. Seeding Transaksi Sirkulasi Tambahan (Berbagai status: borrowed, overdue, approved, returned, pending)
        $statuses = [
            ['status' => 'borrowed', 'days_ago' => 4, 'due_in' => 3, 'fine' => 0, 'paid' => false],
            ['status' => 'borrowed', 'days_ago' => 2, 'due_in' => 5, 'fine' => 0, 'paid' => false],
            ['status' => 'overdue', 'days_ago' => 15, 'due_in' => -8, 'fine' => 8000, 'paid' => false],
            ['status' => 'overdue', 'days_ago' => 11, 'due_in' => -4, 'fine' => 4000, 'paid' => true, 'ref' => 'PAY-QRIS-20261021-881'],
            ['status' => 'approved', 'days_ago' => 1, 'due_in' => 7, 'fine' => 0, 'paid' => false],
            ['status' => 'pending', 'days_ago' => 0, 'due_in' => 7, 'fine' => 0, 'paid' => false],
            ['status' => 'pending', 'days_ago' => 1, 'due_in' => 7, 'fine' => 0, 'paid' => false],
            ['status' => 'returned', 'days_ago' => 25, 'due_in' => -18, 'fine' => 0, 'paid' => true],
            ['status' => 'returned', 'days_ago' => 18, 'due_in' => -11, 'fine' => 2000, 'paid' => true, 'ref' => 'PAY-TUNAI-20261014-119'],
            ['status' => 'returned', 'days_ago' => 30, 'due_in' => -23, 'fine' => 0, 'paid' => true],
        ];

        foreach ($statuses as $idx => $st) {
            $member = $allMembers[$idx % $allMembers->count()];
            $book = $allBooks[$idx % $allBooks->count()];
            $loanDate = Carbon::now()->subDays($st['days_ago']);
            $dueDate = $loanDate->copy()->addDays(7);
            $returnDate = $st['status'] === 'returned' ? $dueDate->copy()->addDays(rand(0, 2)) : null;

            $codeNum = str_pad((string) (100 + $idx), 4, '0', STR_PAD_LEFT);
            $loanCode = "PINJAM-202610-{$codeNum}";

            $loan = Loan::updateOrCreate(
                ['loan_code' => $loanCode],
                [
                    'user_id' => $member->id,
                    'book_id' => $book->id,
                    'loan_date' => $loanDate,
                    'due_date' => $dueDate,
                    'return_date' => $returnDate,
                    'status' => $st['status'],
                    'fine_amount' => $st['fine'],
                    'fine_paid' => $st['paid'],
                    'payment_method' => isset($st['ref']) ? (str_contains($st['ref'], 'QRIS') ? 'qris_simulasi' : 'tunai') : null,
                    'payment_reference' => $st['ref'] ?? null,
                    'fine_paid_at' => $st['paid'] && $st['fine'] > 0 ? Carbon::now()->subDays(1) : null,
                    'officer_id' => $staffUser ? $staffUser->id : null,
                    'notes' => "Sirkulasi sistem otomatis untuk data simulasi.",
                ]
            );

            // Jika statusnya overdue atau approved, kirimkan riwayat notifikasi ke tabel
            if ($st['status'] === 'overdue') {
                CirculationNotification::firstOrCreate([
                    'loan_id' => $loan->id,
                    'user_id' => $member->id,
                    'channel' => 'whatsapp',
                    'type' => 'overdue_alert',
                ], [
                    'recipient' => $member->phone ?? '081234567890',
                    'message' => "Pengingat Aksaraya: Peminjaman buku '{$book->title}' telah melewati batas tempo. Mohon segera mengembalikan.",
                    'status' => 'sent',
                ]);
            }
        }

        // 5. Tambahan Ulasan Buku (Reviews) Realistis
        $reviewsContent = [
            ['rating' => 5, 'comment' => 'Penjelasannya sangat mendalam dan membuka wawasan baru tentang topik ini. Sangat layak dibaca berulang kali.'],
            ['rating' => 5, 'comment' => 'Salah satu buku terbaik di perpustakaan Aksaraya! Gaya penulisannya sangat mengalir dan informatif.'],
            ['rating' => 4, 'comment' => 'Bagus sekali untuk pemula maupun praktisi. Contoh studi kasus yang diberikan sangat relevan.'],
            ['rating' => 5, 'comment' => 'Buku ini mengubah cara saya berpikir dan bekerja sehari-hari. Recommended!'],
            ['rating' => 4, 'comment' => 'Kualitas cetakan fisik dan terjemahan sangat rapi. Terima kasih perpustakaan Aksaraya sudah menyediakan.'],
        ];

        foreach ($allBooks as $idx => $b) {
            $reviewer = $allMembers[$idx % $allMembers->count()];
            $sampleRev = $reviewsContent[$idx % count($reviewsContent)];

            Review::updateOrCreate(
                ['user_id' => $reviewer->id, 'book_id' => $b->id],
                [
                    'rating' => $sampleRev['rating'],
                    'comment' => $sampleRev['comment'],
                ]
            );
        }

        // 6. Seeding Rak Buku Pribadi (User Shelves)
        foreach ($allMembers as $m) {
            // Berikan 2 buku want_to_read, 1 currently_reading, 1 already_read
            $randomBooks = $allBooks->random(min(4, $allBooks->count()));
            
            if (isset($randomBooks[0])) {
                UserShelf::updateOrCreate(
                    ['user_id' => $m->id, 'book_id' => $randomBooks[0]->id, 'shelf_type' => 'want_to_read'],
                    ['notes' => 'Rekomendasi dari teman untuk dibaca bulan depan.']
                );
            }
            if (isset($randomBooks[1])) {
                UserShelf::updateOrCreate(
                    ['user_id' => $m->id, 'book_id' => $randomBooks[1]->id, 'shelf_type' => 'currently_reading'],
                    ['notes' => 'Sedang dibaca di waktu santai sore hari.']
                );
            }
            if (isset($randomBooks[2])) {
                UserShelf::updateOrCreate(
                    ['user_id' => $m->id, 'book_id' => $randomBooks[2]->id, 'shelf_type' => 'already_read'],
                    ['notes' => 'Sudah selesai dibaca, catat poin-poin penting.']
                );
            }

            // 7. Seeding Reading Challenge & Reading History
            ReadingChallenge::updateOrCreate(
                ['user_id' => $m->id, 'year' => 2026],
                ['target_books' => rand(10, 25)]
            );

            // History membaca e-book
            if (isset($randomBooks[3])) {
                ReadingHistory::updateOrCreate(
                    ['user_id' => $m->id, 'book_id' => $randomBooks[3]->id],
                    [
                        'last_page' => rand(15, 80),
                        'total_pages' => 240,
                        'is_completed' => rand(0, 1) === 1,
                        'last_read_at' => Carbon::now()->subDays(rand(1, 10)),
                    ]
                );
            }
        }
    }
}
