<?= $this->include('template/header'); ?>

<h2><?= $title; ?></h2>

<?php if($artikel): ?>
    <?php foreach($artikel as $row): ?>
        <article class="entry">
            <h3>
                <a href="<?= base_url('/artikel/' . $row['slug']); ?>">
                    <?= $row['judul']; ?>
                </a>
            </h3>
            <p><?= substr($row['isi'], 0, 200); ?></p>
            <hr>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <p>Belum ada data.</p>
<?php endif; ?>

<?= $this->include('template/footer'); ?>