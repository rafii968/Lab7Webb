 ## muhamamad saeful rafii (312410374)
 # Lab7Web - Backend Management Artikel & RESTful API

Repositori ini berisi proyek pengembangan **Backend & REST Server** menggunakan **Framework CodeIgniter 4**. Proyek ini mencakup seluruh rangkaian praktikum dari pembuatan modul antarmuka web standar (MVC), proteksi keamanan login admin, pengoptimalan performa halaman menggunakan AJAX JQuery, hingga penyediaan layanan data berbasis arsitektur RESTful API.

---

## 1. Arsitektur & Struktur Direktori Utama
Proyek ini dibangun menggunakan pola desain **Model-View-Controller (MVC)** bawaan CodeIgniter 4 dengan struktur inti sebagai berikut:
```text
lab7_php_ci/
├── app/
│   ├── Config/          # Konfigurasi sistem (Routes.php, Pager.php, Filters.php)
│   ├── Controllers/     # Logika pengontrol (Artikel.php, AjaxController.php, Post.php)
│   ├── Filters/         # Filter keamanan (Auth.php)
│   ├── Models/          # Manipulasi data database (ArtikelModel.php, KategoriModel.php)
│   └── Views/           # Template antarmuka HTML (artikel/, ajax/, template/)
└── public/              # Endpoint akses publik browser
## 2. Implementasi Per Modul Praktikum
## 1. Halaman User / Front-End (Modul 3)
Halaman utama website yang dirancang untuk diakses oleh pengunjung umum secara publik.
Tampilan Berita: Menampilkan daftar artikel arsip sejarah Indonesia (era VOC hingga kemerdekaan) yang diambil langsung dari database.
Fungsi: Sebagai media informasi publik di mana user umum hanya diberikan hak akses membaca (read-only) tanpa bisa memanipulasi data konten.
<img width="959" height="474" alt="Screenshot 2026-06-24 113421" src="https://github.com/user-attachments/assets/fd078bf7-1600-4580-a261-18020aeb6fab" />


## 2. Autentikasi Keamanan & Dashboard Admin (Modul 4)
Pemberian batasan hak akses halaman manajemen untuk mengamankan data sensitif aplikasi.
Fitur Login Admin: Menyediakan form validasi akun admin yang telah terintegrasi dengan komponen UI Bootstrap 5.
Dashboard Area: Membuka area kendali internal (CRUD data artikel) setelah lolos verifikasi sistem. Halaman ini diproteksi ketat menggunakan Auth Filter; pengguna ilegal yang belum login otomatis ditendang keluar jika mencoba menembak URL admin secara langsung.

<img width="959" height="436" alt="Screenshot 2026-06-24 145716" src="https://github.com/user-attachments/assets/5230d23a-f2f0-41df-afee-df18ce3de77e" />

## 3. Modul Data Artikel Berbasis AJAX (Modul 8)
Penerapan manipulasi data asinkronus menggunakan jQuery AJAX untuk memproses data tanpa perlu refresh halaman.
Fitur Tampil & Hapus Instan: Seluruh data ditampung dalam format JSON via request internal. Tombol hapus memicu HTTP DELETE asinkron yang langsung menghilangkan baris tabel seketika setelah disetujui pengguna.
<img width="959" height="470" alt="Screenshot 2026-06-24 143404" src="https://github.com/user-attachments/assets/40662597-5651-43eb-90a1-fc96bde62334" />


## 4. Pencarian & Pagination Otomatis AJAX (Modul 9)
Optimasi performa halaman manajemen admin dalam menangani data records berskala besar.
Fitur Search & Filter: Menghubungkan kolom input pencarian judul dan drop-down kategori dengan skrip penangkap data AJAX. Komponen tabel akan otomatis ber-render ulang menyaring artikel yang sesuai secara instan begitu kolom diisi atau kategori dipilih.
Pagination Terintegrasi: Pembagian segmentasi halaman dikelola dinamis melalui pendaftaran template bootstrap_full pada konfigurasi engine $templates (app/Config/Pager.php) agar perpindahan link halaman berjalan mulus tanpa kedipan browser.
<img width="959" height="436" alt="Screenshot 2026-06-24 145716" src="https://github.com/user-attachments/assets/14ce7263-1ce0-4cd2-99ee-016ec49a7945" />
<img width="959" height="379" alt="Screenshot 2026-06-24 145736" src="https://github.com/user-attachments/assets/edd1bf3a-22c4-4fc7-86cc-8cd89de85575" />

## 5. Penyediaan Layanan RESTful API Server (Modul 10)
Transformasi sistem backend murni menjadi penyedia data mentah (Web Service) guna mendukung arsitektur aplikasi modern terpisah.
Fungsi Endpoint Resource: Memanfaatkan kelas bawaan ResourceController pada file app/Controllers/Post.php dan pemetaan otomatis $routes->resource('post') untuk membuka endpoint API standar global (GET, POST, PUT, DELETE).
<img width="959" height="209" alt="Screenshot 2026-06-24 202340" src="https://github.com/user-attachments/assets/ad965609-5b73-41fb-abbc-66c8faf8b6ce" />
<img width="375" height="212" alt="Screenshot 2026-06-24 202319" src="https://github.com/user-attachments/assets/d9dada54-a47f-461d-9298-d36e77882aae" />
<img width="959" height="475" alt="Screenshot 2026-06-24 202256" src="https://github.com/user-attachments/assets/4bf0e575-68eb-4d6a-b472-487232fd1e1c" />

Pengujian HTTP Client Agent: Seluruh data dikembalikan dalam wujud respons kode status standar protokol HTTP (seperti 200 OK atau 201 Created) berformat data JSON yang diuji menggunakan Postman untuk memastikan kesiapan integrasi dengan aplikasi frontend eksternal (VueJS).
