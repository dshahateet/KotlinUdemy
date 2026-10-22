<?php
require __DIR__ . '/includes/bootstrap.php';

$slug = (string)($_GET['slug'] ?? '');
$post = q('SELECT * FROM posts WHERE slug = ? AND is_active = 1', [$slug])->fetch();
if (!$post) {
    not_found();
}

$isCase    = $post['type'] === 'case_study';
$listUrl   = url($isCase ? 'case-studies.php' : 'blog.php');
$listName  = t($isCase ? 'nav_case_studies' : 'nav_blog');
$activeNav = 'media';
$pageTitle = f($post, 'title');
$pageDesc  = f($post, 'excerpt');

require ROOT . '/partials/header.php';
?>
<section class="page-hero">
  <div class="container narrow">
    <a class="back-link" href="<?= e($listUrl) ?>"><?= icon('arrow', 'icon flip-ltr') ?><?= e(t('back_to')) ?> <?= e($listName) ?></a>
    <h1><?= e(f($post, 'title')) ?></h1>
    <p><time datetime="<?= e($post['published_at']) ?>"><?= e(t('published_on')) ?> <?= e(format_date($post['published_at'])) ?></time></p>
  </div>
</section>

<article class="section">
  <div class="container narrow">
    <?php if ($post['image']): ?><img class="post-cover" src="<?= e(upload_url($post['image'])) ?>" alt=""><?php endif; ?>
    <div class="prose"><?= f($post, 'body') /* trusted HTML from admin */ ?></div>
  </div>
</article>
<?php
require ROOT . '/partials/contact-form.php';
require ROOT . '/partials/footer.php';
