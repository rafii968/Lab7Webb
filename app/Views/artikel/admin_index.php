<?= $this->include('template/header'); ?>

<div class="container" style="margin: 20px;">
    <h2>Daftar Artikel (Admin - AJAX)</h2>
    <hr>

    <div class="row mb-3" style="margin-bottom: 15px;">
        <div class="col-md-6">
            <form id="search-form" class="form-inline" style="display: flex; gap: 10px;">
                <input type="text" name="q" id="search-box" value="<?= $q; ?>" placeholder="Cari judul artikel..." class="form-control" style="padding: 6px; width: 250px;">
                
                <select name="kategori_id" id="category-filter" class="form-control" style="padding: 6px;">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori']; ?>" <?= ($kategori_id == $k['id_kategori']) ? 'selected' : ''; ?>>
                            <?= $k['nama_kategori']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <input type="submit" value="Cari" class="btn btn-primary" style="padding: 6px 15px; cursor: pointer;">
            </form>
        </div>
    </div>

    <div id="article-container">
        </div>

    <div id="pagination-container" style="margin-top: 15px; display: flex; justify-content: center;">
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    const articleContainer = $('#article-container');
    const paginationContainer = $('#pagination-container');
    const searchForm = $('#search-form');
    const searchBox = $('#search-box');
    const categoryFilter = $('#category-filter');

    // Fungsi Utama untuk mengambil data via AJAX (Pencarian & Pagination)
    const fetchData = (url) => {
        // Tampilkan indikator loading saat data sedang diambil
        articleContainer.html('<p style="text-align:center;">Loading data artikel...</p>');
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Penting: Agar isAJAX() di CI4 mendeteksi ini request AJAX
            },
            success: function(data) {
                renderArticles(data.artikel);
                renderPagination(data.pagination_links);
            },
            error: function() {
                articleContainer.html('<p style="text-align:center; color:red;">Gagal mengambil data dari server.</p>');
            }
        });
    };

    // Fungsi untuk merender list artikel ke dalam bentuk tabel HTML
    const renderArticles = (articles) => {
        let html = '<table class="table-data" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead><tbody>';

        if (articles.length > 0) {
            articles.forEach(article => {
                let statusText = (article.status == 1) ? 'Aktif' : 'Non-Aktif';
                let isiPotong = article.isi ? article.isi.substring(0, 70) + '...' : '';

                html += '<tr>';
                html += '<td>' + article.id + '</td>';
                html += '<td><b>' + article.judul + '</b><br><small style="color:#666;">' + isiPotong + '</small></td>';
                html += '<td>' + (article.nama_kategori ? article.nama_kategori : '-') + '</td>';
                html += '<td>' + statusText + '</td>';
                html += '<td>';
                html += '<a class="btn btn-primary" style="margin-right:5px; padding: 4px 10px;" href="<?= base_url('admin/artikel/edit/') ?>' + article.id + '">Ubah</a>';
                html += '<a class="btn btn-danger btn-delete-admin" style="padding: 4px 10px;" href="<?= base_url('admin/artikel/delete/') ?>' + article.id + '">Hapus</a>';
                html += '</td>';
                html += '</tr>';
            });
        } else {
            html += '<tr><td colspan="5" style="text-align:center;">Tidak ada data artikel ditemukan.</td></tr>';
        }

        html += '</tbody></table>';
        articleContainer.html(html);
    };

    // Fungsi untuk merender Navigasi Halaman (Pagination Links)
    const renderPagination = (paginationLinks) => {
        if (paginationLinks) {
            paginationContainer.html(paginationLinks);
        } else {
            paginationContainer.html('');
        }
    };

    // Pemicu aksi saat Form Pencarian di-Submit
    searchForm.on('submit', function(e) {
        e.preventDefault();
        const q = searchBox.val();
        const kategori_id = categoryFilter.val();
        
        // Buat URL dengan query string search & kategori
        let targetUrl = "<?= base_url('admin/artikel') ?>?q=" + encodeURIComponent(q) + "&kategori_id=" + kategori_id;
        fetchData(targetUrl);
    });

    // Pemicu otomatis saat Pilihan Kategori diubah tanpa perlu klik tombol Cari
    categoryFilter.on('change', function() {
        searchForm.trigger('submit');
    });

    // Menangani klik link Pagination agar berpindah halaman via AJAX (tidak reload halaman)
    $(document).on('click', '#pagination-container a', function(e) {
        e.preventDefault();
        let targetUrl = $(this).attr('href');
        if (targetUrl && targetUrl !== '#') {
            fetchData(targetUrl);
        }
    });

    // Ambil data pertama kali saat halaman dibuka
    fetchData('<?= base_url('admin/artikel') ?>');
});
</script>

<?= $this->include('template/footer'); ?>