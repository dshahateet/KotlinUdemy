<?php
/** @var string $pageTitle @var string $pageDesc @var string $activeNav */
$pageTitle = $pageTitle ?? setting('meta_title', site_name());
$pageDesc  = $pageDesc ?? setting('meta_description');
$activeNav = $activeNav ?? '';
$fullTitle = $pageTitle === site_name() ? $pageTitle : $pageTitle . ' | ' . site_name();
$canonPath = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$canonQs   = $_GET;
unset($canonQs['lang']);
$canonical = SITE_URL . $canonPath . ($canonQs ? '?' . http_build_query($canonQs) : '');
$sep       = $canonQs ? '&' : '?';
$phone     = setting('phone');
$wa        = whatsapp_href();
?><!doctype html>
<html lang="<?= e(lang()) ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($pageDesc) ?>">
<link rel="canonical" href="<?= e($canonical . $sep . 'lang=' . lang()) ?>">
<link rel="alternate" hreflang="en" href="<?= e($canonical . $sep . 'lang=en') ?>">
<link rel="alternate" hreflang="ar" href="<?= e($canonical . $sep . 'lang=ar') ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($pageDesc) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:site_name" content="<?= e(site_name()) ?>">
<meta property="og:locale" content="<?= is_rtl() ? 'ar_AE' : 'en_US' ?>">
<link rel="icon" href="<?= e(asset('img/favicon.svg')) ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
<a class="skip-link" href="#main"><?= e(t('skip')) ?></a>

<div class="topbar">
  <div class="container topbar-inner">
    <div class="topbar-contacts">
      <a href="mailto:<?= e(INFO_EMAIL) ?>"><?= icon('mail') ?><span><?= e(INFO_EMAIL) ?></span></a>
      <?php if ($phone): ?>
        <a href="<?= e(phone_href($phone)) ?>" dir="ltr"><?= icon('phone') ?><span><?= e($phone) ?></span></a>
      <?php endif; ?>
    </div>
    <div class="topbar-actions">
      <?php if (setting('linkedin')): ?><a href="<?= e(setting('linkedin')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin') ?></a><?php endif; ?>
      <?php if (setting('facebook')): ?><a href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a><?php endif; ?>
      <a class="lang-switch" href="<?= e(lang_switch_url(is_rtl() ? 'en' : 'ar')) ?>" hreflang="<?= is_rtl() ? 'en' : 'ar' ?>"><?= icon('globe') ?><span><?= e(t('other_lang')) ?></span></a>
    </div>
  </div>
</div>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a class="logo" href="<?= e(url('')) ?>" aria-label="<?= e(site_name()) ?>">
      <img src="<?= e(asset('img/logo.svg')) ?>" alt="" width="44" height="44">
      <span class="logo-text"><strong><?= e(site_name()) ?></strong><small><?= e(setting('logo_tagline')) ?></small></span>
    </a>

    <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav" aria-label="<?= e(t('menu')) ?>">
      <?= icon('menu', 'icon icon-open') ?><?= icon('close', 'icon icon-close') ?>
    </button>

    <nav class="main-nav" id="mainNav" aria-label="Main">
      <ul>
        <li><a href="<?= e(url('')) ?>" class="<?= $activeNav === 'home' ? 'active' : '' ?>"><?= e(t('nav_home')) ?></a></li>
        <li class="has-sub">
          <a href="<?= e(url('services.php')) ?>" class="<?= $activeNav === 'services' ? 'active' : '' ?>"><?= e(t('nav_services')) ?><?= icon('chevron', 'icon caret') ?></a>
          <button class="sub-toggle" aria-label="<?= e(t('nav_services')) ?>"><?= icon('chevron') ?></button>
          <ul class="sub-menu">
            <?php foreach (active_services() as $s): ?>
              <li><a href="<?= e(service_url($s)) ?>"><?= icon($s['icon']) ?><?= e(f($s, 'title')) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li><a href="<?= e(url('about.php')) ?>" class="<?= $activeNav === 'about' ? 'active' : '' ?>"><?= e(t('nav_about')) ?></a></li>
        <li class="has-sub">
          <a href="<?= e(url('blog.php')) ?>" class="<?= $activeNav === 'media' ? 'active' : '' ?>"><?= e(t('nav_media')) ?><?= icon('chevron', 'icon caret') ?></a>
          <button class="sub-toggle" aria-label="<?= e(t('nav_media')) ?>"><?= icon('chevron') ?></button>
          <ul class="sub-menu">
            <li><a href="<?= e(url('blog.php')) ?>"><?= icon('report') ?><?= e(t('nav_blog')) ?></a></li>
            <li><a href="<?= e(url('case-studies.php')) ?>"><?= icon('layers') ?><?= e(t('nav_case_studies')) ?></a></li>
          </ul>
        </li>
        <li><a href="<?= e(url('contact.php')) ?>" class="<?= $activeNav === 'contact' ? 'active' : '' ?>"><?= e(t('nav_contact')) ?></a></li>
      </ul>
      <a class="btn btn-primary nav-cta" href="<?= e(url('contact.php')) ?>"><?= e(t('free_consultation')) ?></a>
    </nav>
  </div>
</header>

<main id="main">
