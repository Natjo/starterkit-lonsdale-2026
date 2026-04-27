<?php
/*
Template Name: Front styleguide
*/
?>

<?php include __DIR__ . '/stylequide/common/header.php'; ?>

<?php
    // cards
    $card_news = [
        "title"  => "Lorem ipsum dolor sit amet",
        "images" => ["desktop" => 460]
    ];

    $link = [
        "title" => "Lorem ipsum",
        "url" => "/",
        "target" => "",
    ];
?>

<main id="main" role="main" class="styleguide">

    <!-- Styles -->
    <sg-section label="1. Styles" slug="styles" open>

        <?php sg_style('typography'); ?>

        <?php sg_style('rte'); ?>

        <?php sg_style('colors'); ?>

        <?php sg_style('backgrounds'); ?>

        <?php sg_style('layout'); ?>

    </sg-section>

    <!-- Components -->
    <sg-section label="2. Components" slug="components">

        <?php sg_component('title'); ?>

        <?php sg_component('tag'); ?>

        <?php sg_component('badge'); ?>

        <?php sg_component('link'); ?>

        <?php sg_component('btn'); ?>

        <?php sg_component('icon'); ?>

        <?php sg_component('picto'); ?>

        <?php sg_component('accordion'); ?>

        <?php sg_component('picture'); ?>

        <?php sg_component('list'); ?>

        <?php sg_component('slider'); ?>

    </sg-section>

    <!-- Cards -->
    <sg-section label="3. Cards" slug="cards">

        <?php sg_card('news'); ?>

    </sg-section>

    <!-- Heros -->
    <sg-section label="4. Heros" slug="heros">

        <?php sg_hero('homepage'); ?>

        <?php sg_hero('page'); ?>

    </sg-section>

    <!-- Strates -->
    <sg-section label="5. Strates" slug="strates">

        <?php sg_strate('text'); ?>

        <?php sg_strate('slider'); ?>

    </sg-section>

</main>

<script src="<?= THEME_URL ?>front/stylequide/core.js?v=<?= filemtime(__DIR__ . '/stylequide/core.js') ?>"></script>

<?php get_tpl();
