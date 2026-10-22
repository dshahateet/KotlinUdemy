<?php /** @var string $heroTitle @var string $heroText */ ?>
<section class="page-hero">
  <div class="container">
    <?php if (!empty($heroKicker)): ?><span class="kicker kicker-light"><?= e($heroKicker) ?></span><?php endif; ?>
    <h1><?= e($heroTitle) ?></h1>
    <?php if (!empty($heroText)): ?><p><?= e($heroText) ?></p><?php endif; ?>
  </div>
</section>
