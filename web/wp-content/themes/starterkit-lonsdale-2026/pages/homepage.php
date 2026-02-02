<?php
/*
Template Name: Homepage
*/

?>

<?php get_template_part('template-parts/general/block', 'header_nav'); ?>

<main>
<?php $hero_homepage = get_field('hero-homepage') ?>
    <?=
    hero('hero-homepage', [
        "title" => $hero_homepage["title"],
        "images" => $hero_homepage["images"],
    ]);
    ?>

    <?= get_template_part('template-parts/strates-dispatch'); ?>
</main>

<?php get_tpl();
