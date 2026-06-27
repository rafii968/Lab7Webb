# Lab7Webb
MUHAMAD SAEFUL RAFII 312410374
# Laporan Praktikum: Sistem Pengelolaan Artikel (Modul 1-14)
##  Alur Pengerjaan & Penjelasan Halaman

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
<img width="959" height="474" alt="image" src="https://github.com/user-attachments/assets/a2d453dd-63d2-4ef6-98ee-10d309259522" />


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
<img width="359" height="428" alt="image" src="https://github.com/user-attachments/assets/cea6c573-160a-4f0b-a0c6-942c322b7bca" />

###  8. Upload File Gambar (Modul 7)

Pada modul ini saya menambahkan fitur upload gambar pada form tambah artikel. Gambar yang diupload akan disimpan di folder public/gambar/ dan nama filenya disimpan ke database.
<img width="959" height="441" alt="image" src="https://github.com/user-attachments/assets/610c13ab-25c5-4b0f-a2e9-5d8252fce8f0" />

Langkah 1 — Buat folder penyimpanan gambar
<img width="959" height="441" alt="Screenshot 2026-06-24 121255" src="https://github.com/user-attachments/assets/0eb83211-a2de-4864-b64a-efac94e4b9e4" />

Buat folder baru di dalam public/:
ci4/public/gambar/

### 9. Halaman Data Artikel AJAX (Modul 8)
[cite_start]Halaman ini menerapkan teknologi Asynchronous JavaScript and XML untuk manajemen data artikel yang lebih dinamis tanpa proses muat ulang halaman[cite: 12, 13].
* [cite_start]**Tampilan Tabel AJAX:** Menyajikan daftar artikel yang ditarik secara real-time dari server dalam format JSON melalui manipulasi data baris tabel menggunakan jQuery[cite: 24, 26, 44].
<img width="959" height="470" alt="Screenshot 2026-06-24 143404" src="https://github.com/user-attachments/assets/41da83c2-501a-4c96-a09b-0dec0f0375c3" />

* [cite_start]**Fungsi Hapus & Tambah Pop-up:** Menghapus baris artikel langsung dengan method DELETE setelah mendapatkan konfirmasi pengguna, serta menyediakan modal form interaktif untuk entri data baru secara instan tanpa interupsi reload[cite: 145, 146, 151].

### 10. Halaman Manajemen Admin - Pencarian & Pagination AJAX (Modul 9)
[cite_start]Halaman pusat kendali admin yang telah dioptimalkan performanya menggunakan modifikasi asinkron untuk penyaringan data skala besar[cite: 177, 181].
* [cite_start]**Fitur Pencarian & Filter Kategori:** Menyediakan kolom input judul dan drop-down kategori yang secara otomatis mengirim parameter pencarian dan memperbarui isi tabel secara instan[cite: 247, 347, 348].
<img width="959" height="436" alt="Screenshot 2026-06-24 145716" src="https://github.com/user-attachments/assets/b2e6f040-f7d6-4dfb-854a-afc02750be50" />

* [cite_start]**Navigasi Halaman (Pagination):** Memisahkan kumpulan data artikel ke dalam beberapa segmen halaman menggunakan template pagination yang tautannya dikontrol menggunakan skrip jQuery agar berpindah halaman tanpa kedipan browser[cite: 234, 326, 327].
<img width="959" height="379" alt="Screenshot 2026-06-24 145736" src="https://github.com/user-attachments/assets/f5b8c6db-c92d-4f42-a54b-a3f28d842819" />

### 11. Pengujian RESTful API Artikel (Modul 10)
Halaman ini berfokus pada arsitektur backend sebagai REST Server yang menyediakan endpoint data berbasis JSON untuk integrasi antar-aplikasi tanpa interface HTML.
* **Fitur Endpoint GET (Fetch Data):** Menampilkan seluruh data artikel maupun spesifik berdasarkan ID dalam format standar array JSON melalui pengujian HTTP Request.
<img width="959" height="475" alt="image" src="https://github.com/user-attachments/assets/3f026b9e-04b7-4986-a58d-5cb936994a1f" />

* **Fitur Endpoint POST & DELETE:** Memproses penambahan data artikel baru ke database via request body, serta menghapus data berdasarkan parameter URL ID secara asinkron menggunakan client agent.
<img width="375" height="212" alt="image" src="https://github.com/user-attachments/assets/bf5668e2-85a7-4e1d-9244-0440451083a9" />
<img width="959" height="209" alt="image" src="https://github.com/user-attachments/assets/96d84dab-4ab0-4c36-8781-95eb62bcbb8a" />
