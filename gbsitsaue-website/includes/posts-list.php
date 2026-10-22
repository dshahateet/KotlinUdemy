<?php
/** Shared listing for blog.php and case-studies.php. Expects $postType, $heroTitle, $heroText. */
$perPage = 9;
$page    = max(1, (int)($_GET['page'] ?? 1));
$total   = (int)q('SELECT COUNT(*) FROM posts WHERE type = ? AND is_active = 1', [$postType])->fetchColumn();
$pages   = max(1, (int)ceil($total / $perPage));
$page    = min($page, $pages);
$offset  = ($page - 1) * $perPage;
$posts   = q("SELECT * FROM posts WHERE type = ? AND is_active = 1 ORDER BY published_at DESC, id DESC LIMIT $perPage OFFSET $offset", [$postType])->fetchAll();

$activeNav = 'media';
$pageTitle = $heroTitle;

require ROOT . '/partials/header.php';
require ROOT . '/partials/page-hero.php';
?>
<section class="section">
  <div class="container">
    <?php if (!$posts): ?>
      <p class="center muted"><?= e(t('no_posts')) ?></p>
    <?php else: ?>
      <div class="grid posts-grid">
        <?php foreach ($posts as $p): ?>
          <article class="card post-card">
            <a class="post-thumb" href="<?= e(post_url($p)) ?>">
              <?php if ($p['image']): ?><img src="<?= e(upload_url($p['image'])) ?>" alt="" loading="lazy">
              <?php else: ?><span class="thumb-placeholder"><?= icon($postType === 'case_study' ? 'layers' : 'report') ?></span><?php endif; ?>
            </a>
            <div class="post-body">
              <time datetime="<?= e($p['published_at']) ?>"><?= icon('calendar') ?><?= e(format_date($p['published_at'])) ?></time>
              <h2><a href="<?= e(post_url($p)) ?>"><?= e(f($p, 'title')) ?></a></h2>
              <p><?= e(f($p, 'excerpt')) ?></p>
              <a class="link-more" href="<?= e(post_url($p)) ?>"><?= e(t('read_more')) ?><?= icon('arrow', 'icon flip-rtl') ?></a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Pagination">
          <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php
require ROOT . '/partials/footer.php';
