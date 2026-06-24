# Lab7Webb
MUHAMAD SAEFUL RAFII 312410374
# Laporan Praktikum: Sistem Pengelolaan Artikel (Modul 1-4)
## 🛠️ Alur Pengerjaan & Penjelasan Halaman

### 1. Inisialisasi Project (Modul 1 & 2)
Pertama-tama, saya membuat struktur dasar **CodeIgniter 4**. Tahap ini adalah penyesuaian perintah praktek pertama untuk memastikan server lokal (Apache/MySQL) siap digunakan.
* **Konfigurasi Environment:** Mengatur file `.env` untuk koneksi database dan mengaktifkan mode *development* agar error terlihat jelas.
* **BaseController:** Menyiapkan library *Session* agar fitur login bisa berfungsi di seluruh halaman.

### disini saya menggunakan MySQL workbench 80
<img width="1891" height="966" alt="Screenshot 2026-04-03 084530" src="https://github.com/user-attachments/assets/582f24e4-fb60-488e-aacb-255a320a2a11" />
Lebih suka pake workbench

### 2. Halaman User / Front-End (Modul 3)
Halaman ini adalah wajah utama website yang nantinya akan dilihat oleh pengunjung umum.
* **Tampilan Berita:** Menampilkan daftar artikel yang diambil dari database.
<img width="1919" height="944" alt="Screenshot 2026-04-04 065646" src="https://github.com/user-attachments/assets/b6ed63ac-43cc-4116-93b4-1d45137666fd" />
* **Fungsi:** Sebagai media informasi publik di mana user hanya bisa membaca konten tanpa bisa mengubahnya.
<img width="1915" height="946" alt="Screenshot 2026-04-04 132454" src="https://github.com/user-attachments/assets/6c005a34-c672-448c-8e3b-2b50e51cce2c" />
<img width="1919" height="888" alt="Screenshot 2026-04-04 132541" src="https://github.com/user-attachments/assets/81f79d8e-8471-4b0c-9f63-509d506817de" />



### 3. Halaman Login Admin (Modul 4)
Halaman ini dibuat sebagai pintu masuk khusus untuk pengelola website (Admin).
* **Fitur Login:** Menggunakan form **Username** dan **Password** yang sudah rapi dengan Bootstrap 5.
* **Fungsi:** Memvalidasi kredensial pengguna. Jika data cocok, sistem akan memberikan akses ke area sensitif (Dashboard).
<img width="1919" height="945" alt="Screenshot 2026-04-04 132407" src="https://github.com/user-attachments/assets/649638b5-6487-4cbd-931e-d636f39e185e" />


### 4. Halaman Dashboard Admin (Modul 4)
Halaman ini adalah pusat kendali (Back-End) setelah admin berhasil masuk.
* **Manajemen Artikel:** Berisi tabel daftar artikel yang bisa dikelola (Tambah, Edit, Hapus).
* **Fungsi:** Membatasi akses publik. Halaman ini diproteksi oleh **Auth Filter** sehingga orang yang belum login akan otomatis ditendang keluar.
<img width="1919" height="952" alt="Screenshot 2026-04-04 132434" src="https://github.com/user-attachments/assets/a034d6c6-1cf0-469f-996a-3a508a4ce62f" />
<img width="1919" height="946" alt="Screenshot 2026-04-04 132554" src="https://github.com/user-attachments/assets/314a06a8-1a93-4162-91d9-c61139de160d" />


### 5. Sistem Keamanan (Auth & Session)
Terakhir, saya menerapkan sistem **Session**. Ini berfungsi untuk menjaga status login selama browser belum ditutup atau di-logout, sehingga user tidak perlu login berulang kali.

### 6. Pagination & Pencarian (Modul 5)
Setelah sistem CRUD dan login selesai, saya menambahkan fitur Pagination dan Pencarian pada halaman admin agar pengelolaan artikel lebih efisien ketika data sudah banyak.
Pagination: Data artikel dibatasi 10 record per halaman. Jika artikel sudah lebih dari 10, muncul tombol navigasi halaman secara otomatis.
Pencarian: Admin bisa mengetik kata kunci di form pencarian untuk memfilter artikel berdasarkan judul.
Perubahan utama ada di method admin_index() pada Artikel.php:
public function admin_index()
{
    $model = new ArtikelModel();
    $q     = $this->request->getVar('q') ?? '';

    $data = [
        'title'   => 'Daftar Artikel',
        'q'       => $q,
        'artikel' => $model->like('judul', $q)->paginate(10),
        'pager'   => $model->pager,
    ];

    return view('artikel/admin_index', $data);
}
Dan di view admin_index.php ditambahkan form pencarian dan link pagination:
<!-- Form Pencarian -->
<form method="get" class="form-search">
    <input type="text" name="q" value="<?= $q ?? ''; ?>" placeholder="Cari data">
    <input type="submit" value="Cari" class="btn btn-primary">
</form>

<!-- Link Pagination -->
<?= $pager->only(['q'])->links(); ?>

### 7. Relasi Tabel & Query Builder (Modul 6)
Pada modul ini saya memperdalam penggunaan Model, Relasi Tabel, dan Query Builder di CodeIgniter 4. Konsep utamanya adalah menghubungkan tabel artikel dengan tabel kategori menggunakan relasi One-to-Many.
Perubahan Database
Saya membuat tabel baru kategori dan menambahkan foreign key ke tabel artikel:

-- Tabel kategori
CREATE TABLE kategori (
    id_kategori INT(11) AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    slug_kategori VARCHAR(100),
    PRIMARY KEY (id_kategori)
);

-- Tambah kolom dan relasi ke tabel artikel
ALTER TABLE artikel
ADD COLUMN id_kategori INT(11),
ADD CONSTRAINT fk_kategori_artikel
FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori);

Model Kategori

Saya membuat model baru KategoriModel.php di folder app/Models/:
<?php
namespace App\Models;
use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori';
    protected $primaryKey    = 'id_kategori';
    protected $allowedFields = ['nama_kategori', 'slug_kategori'];
}
Query Builder dengan Join
Saya menambahkan method getArtikelDenganKategori() di ArtikelModel.php untuk mengambil data artikel beserta nama kategorinya sekaligus menggunakan JOIN:
public function getArtikelDenganKategori()
{
    return $this->db->table('artikel')
        ->select('artikel.*, kategori.nama_kategori')
        ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
        ->get()
        ->getResultArray();
}
Fungsi: Dengan query JOIN ini, kita tidak perlu melakukan 2 query terpisah. Cukup sekali query, data artikel dan nama kategorinya langsung tersedia bersamaan.
left join digunakan agar artikel yang belum punya kategori tetap muncul (tidak hilang dari daftar).

Struktur Direktori Akhir
ci4/
├── app/
│   ├── Cells/
│   │   └── ArtikelTerkini.php          ← View Cell sidebar (Modul 3)
│   ├── Config/
│   │   ├── Filters.php                 ← Registrasi filter auth & csrf
│   │   ├── Routes.php                  ← Semua routing aplikasi
│   │   └── Security.php               ← Konfigurasi CSRF
│   ├── Controllers/
│   │   ├── Artikel.php                 ← Controller utama CRUD + pagination
│   │   ├── Home.php
│   │   ├── Page.php
│   │   └── User.php                    ← Controller login & logout
│   ├── Filters/
│   │   └── Auth.php                    ← Filter proteksi halaman admin
│   ├── Models/
│   │   ├── ArtikelModel.php            ← Model artikel + query join kategori
│   │   ├── KategoriModel.php           ← Model kategori (Modul 6)
│   │   └── UserModel.php              ← Model user login
│   └── Views/
│       ├── artikel/
│       │   ├── admin_index.php         ← Tabel admin + search + pagination
│       │   ├── detail.php             ← Detail artikel
│       │   ├── form_add.php           ← Form tambah artikel + kategori
│       │   ├── form_edit.php          ← Form edit artikel + kategori
│       │   └── index.php              ← Daftar artikel publik
│       ├── components/
│       │   └── artikel_terkini.php    ← Komponen sidebar artikel terkini
│       ├── layout/
│       │   └── main.php              ← Layout utama (extend/section)
│       ├── template/
│       │   ├── header.php
│       │   └── footer.php
│       ├── user/
│       │   └── login.php             ← Halaman login admin
│       ├── about.php
│       ├── contact.php
│       └── home.php
└── public/
    └── style.css                      ← CSS keseluruhan tampilan

###  8. Upload File Gambar (Modul 7)

Pada modul ini saya menambahkan fitur upload gambar pada form tambah artikel. Gambar yang diupload akan disimpan di folder public/gambar/ dan nama filenya disimpan ke database.
Langkah 1 — Buat folder penyimpanan gambar

Buat folder baru di dalam public/:
ci4/public/gambar/


