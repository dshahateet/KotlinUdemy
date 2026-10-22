<?php
require __DIR__ . '/includes/bootstrap.php';

$postType  = 'blog';
$heroTitle = t('nav_blog');
$heroText  = setting('blog_intro');

require ROOT . '/includes/posts-list.php';
