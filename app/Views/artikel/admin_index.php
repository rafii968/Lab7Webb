<?= $this->include('template/header'); ?>

<h2>Unit Pengelola Artikel</h2>
<div style="margin-bottom: 20px;">
    <a href="<?= base_url('/admin/artikel/add'); ?>" style="background-color: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">+ Tambah Artikel</a>
</div>

<table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
    <thead>
        <tr style="background-color: #f2f2f2; text-align: left;">
            <th style="border: 1px solid #ddd; padding: 12px;">ID</th>
            <th style="border: 1px solid #ddd; padding: 12px;">Judul</th>
            <th style="border: 1px solid #ddd; padding: 12px;">Status</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: center;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if($artikel): foreach($artikel as $row): ?>
        <tr>
            <td style="border: 1px solid #ddd; padding: 12px;"><?= $row['id']; ?></td>
            <td style="border: 1px solid #ddd; padding: 12px;">
                <strong><?= $row['judul']; ?></strong>
            </td>
            <td style="border: 1px solid #ddd; padding: 12px;">
                <span style="background: #e9ecef; padding: 2px 8px; border-radius: 10px; font-size: 0.8em;">
                    <?= $row['status'] == 1 ? 'Aktif' : 'Draft'; ?>
                </span>
            </td>
            <td style="border: 1px solid #ddd; padding: 12px; text-align: center;">
                <a href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>" style="color: #28a745; text-decoration: none; font-weight: bold;">Ubah</a> | 
                <a onclick="return confirm('Yakin menghapus data?');" href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>" style="color: #dc3545; text-decoration: none; font-weight: bold;">Hapus</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="4" style="border: 1px solid #ddd; padding: 12px; text-align: center;">Belum ada data artikel.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->include('template/footer'); ?>