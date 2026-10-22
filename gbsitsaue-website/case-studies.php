<?php
require __DIR__ . '/includes/bootstrap.php';

$postType  = 'case_study';
$heroTitle = t('nav_case_studies');
$heroText  = setting('case_studies_intro');

require ROOT . '/includes/posts-list.php';
