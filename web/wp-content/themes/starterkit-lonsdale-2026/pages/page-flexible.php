<?php

$fields = get_fields();

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

<?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

<?= get_template_part('template-parts/heros/hero', "page", get_field('flexible_hero')); ?>

<main class="sg-main">
    <?= get_template_part('template-parts/strates-dispatch'); ?>
</main>

<?php
get_footer();
