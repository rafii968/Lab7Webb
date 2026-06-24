<?= $this->include('template/header'); ?>

<div class="container" style="margin: 20px;">
    <h1>Data Artikel (AJAX)</h1>
    <hr>
    
    <button id="btnTambah" class="btn btn-primary" style="margin-bottom: 15px; padding: 8px 15px; cursor: pointer;">+ Tambah Artikel</button>
    
    <table class="table-data" id="artikelTable" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>
</div>

<div id="formModal" style="display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fff; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 40%; border-radius: 5px;">
        <span id="closeModal" style="float: right; font-size: 24px; font-weight: bold; cursor: pointer;">&times;</span>
        <h3 id="modalTitle">Tambah Artikel Baru</h3>
        <hr>
        <form id="artikelForm">
            <div style="margin-bottom: 12px;">
                <label style="display: block; margin-bottom: 5px;">Judul Artikel:</label>
                <input type="text" id="judul" name="judul" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; margin-bottom: 5px;">Isi Artikel:</label>
                <textarea id="isi" name="isi" rows="5" required style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; cursor: pointer;">Simpan Artikel</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // Fungsi menampilkan loading
    function showLoadingMessage() {
        $('#artikelTable tbody').html('<tr><td colspan="4" style="text-align:center;">Loading data...</td></tr>');
    }

    // 1. FUNGSI LOAD DATA (GET)
    function loadData() {
        showLoadingMessage();
        $.ajax({
            url: "<?= base_url('ajax/getData') ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                var tableBody = "";
                if (data.length === 0) {
                    tableBody = '<tr><td colspan="4" style="text-align:center;">Tidak ada data artikel.</td></tr>';
                } else {
                    for (var i = 0; i < data.length; i++) {
                        var row = data[i];
                        var statusText = (row.status == 1) ? 'Aktif' : 'Non-Aktif';
                        
                        tableBody += '<tr>';
                        tableBody += '<td>' + row.id + '</td>';
                        tableBody += '<td>' + row.judul + '</td>';
                        tableBody += '<td><span class="status">' + statusText + '</span></td>';
                        tableBody += '<td>';
                        tableBody += '<a href="<?= base_url('admin/artikel/edit/') ?>' + row.id + '" class="btn btn-primary" style="margin-right: 5px;">Edit</a>';
                        tableBody += '<a href="#" class="btn btn-danger btn-delete" data-id="' + row.id + '">Delete</a>';
                        tableBody += '</td>';
                        tableBody += '</tr>';
                    }
                }
                $('#artikelTable tbody').html(tableBody);
            },
            error: function() {
                $('#artikelTable tbody').html('<tr><td colspan="4" style="text-align:center; color:red;">Gagal memuat data.</td></tr>');
            }
        });
    }

    loadData();

    // 2. LOGIKA MODAL POPUP
    $('#btnTambah').click(function() {
        $('#artikelForm')[0].reset(); // Reset isi form
        $('#formModal').fadeIn(); // Tampilkan modal
    });

    $('#closeModal').click(function() {
        $('#formModal').fadeOut(); // Sembunyikan modal
    });

    // 3. FUNGSI TAMBAH DATA (POST) VIA AJAX
    $('#artikelForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah reload halaman bawaan form submit

        $.ajax({
            url: "<?= base_url('ajax/add') ?>",
            method: "POST",
            data: $(this).serialize(), // Mengambil semua data inputan form
            dataType: "json",
            success: function(response) {
                if (response.status === 'OK') {
                    $('#formModal').fadeOut(); // Tutup modal popup
                    loadData(); // Memperbarui baris tabel secara real-time
                } else {
                    alert('Gagal menyimpan data.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan sistem saat menyimpan data.');
            }
        });
    });

    // 4. FUNGSI HAPUS DATA (DELETE) VIA AJAX
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
            $.ajax({
                url: "<?= base_url('ajax/delete/') ?>" + id,
                method: "DELETE",
                success: function(response) {
                    if (response.status === 'OK') {
                        loadData();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting article: ' + textStatus + ' - ' + errorThrown);
                }
            });
        }
    });
});
</script>

<?= $this->include('template/footer'); ?>