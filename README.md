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
