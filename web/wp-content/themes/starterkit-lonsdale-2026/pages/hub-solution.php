<?php
/*
Template Name: Hub Solution
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

    <main>
        <?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

        <?= get_template_part('template-parts/heros/hero', "page", get_field('hub-solution_hero')); ?>

        <?= get_template_part('template-parts/strates-dispatch'); ?>
    </main>

<?php
get_footer();