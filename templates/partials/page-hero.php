<?php /** @var string $title @var string $subtitle @var ?string $image @var string $accent */ ?>
<section class="page-hero accent-<?= e($accent ?? 'orange') ?>"<?php if (!empty($image)): ?> style="--hero-image: url('<?= e(asset('img/' . $image)) ?>')"<?php endif; ?>>
    <div class="container">
        <h1 class="page-hero-title"><?= e($title) ?></h1>
        <?php if (!empty($subtitle)): ?><p class="page-hero-subtitle"><?= e($subtitle) ?></p><?php endif; ?>
    </div>
</section>
