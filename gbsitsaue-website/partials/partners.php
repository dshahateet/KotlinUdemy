<?php
$partners = q('SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
if (!$partners) {
    return;
}
?>
<section class="section section-light partners-section">
  <div class="container">
    <h2 class="section-title center"><?= e(t('our_partners')) ?></h2>
    <div class="partners">
      <?php foreach ($partners as $p): ?>
        <?php $inner = $p['logo'] ? '<img src="' . e(upload_url($p['logo'])) . '" alt="' . e($p['name']) . '" loading="lazy">' : '<span>' . e($p['name']) . '</span>'; ?>
        <?php if ($p['url']): ?>
          <a class="partner" href="<?= e($p['url']) ?>" target="_blank" rel="noopener"><?= $inner ?></a>
        <?php else: ?>
          <div class="partner"><?= $inner ?></div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
