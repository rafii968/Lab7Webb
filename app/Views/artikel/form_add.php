<?= $this->include('template/header'); ?>

<h2>Tambah Artikel Baru</h2>
<div style="background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
    <form action="" method="post">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Judul Artikel</label>
            <input type="text" name="judul" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Isi Artikel</label>
            <textarea name="isi" rows="10" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
        </div>
        <div>
            <input type="submit" value="Simpan Artikel" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
            <a href="<?= base_url('/admin/artikel'); ?>" style="margin-left: 10px; color: #666;">Batal</a>
        </div>
    </form>
</div>

<?= $this->include('template/footer'); ?>