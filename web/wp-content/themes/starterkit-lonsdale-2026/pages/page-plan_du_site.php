<?php

/**
 * - Template Name: Plan du site
 */

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>


<main class="sg-main">

    <?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?= get_template_part('template-parts/heros/hero', "page", ['title' => __('Plan du site', 'lsd_lang')]); ?>

    <section class="strate strate-sitemap">

        <div class="part">
            <h2 class="title title-3"><?= __('Pages', 'lsd_lang') ?></h2>
            <ul>
                <?php
                // Gestion du cas où la fonction n'existe pas
                $exclude_ids = function_exists('get_excluded_id_for_sitemap') ? get_excluded_id_for_sitemap() : '';
                wp_list_pages(array(
                    'exclude' => $exclude_ids,
                    'title_li' => '',
                )); ?>
            </ul>
        </div>

        <div class="part">
            <h2 class="title title-3"><?= __('Solutions', 'lsd_lang') ?></h2>
            <ul>
                <?php
                $solution_posts = get_posts(array(
                        'post_type'      => 'solutions',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                ));

                if (!empty($solution_posts)) {
                    foreach ($solution_posts as $post) {
                        setup_postdata($post);
                        echo '<li><a href="' . esc_url(get_permalink($post)) . '">' . esc_html(get_the_title($post)) . '</a></li>';
                    }
                    wp_reset_postdata();
                } else {
                    echo '<li>' . __('Aucune solution publiée pour le moment.', 'lsd_lang') . '</li>';
                }
                ?>
            </ul>
        </div>

        <div class="part">
            <h2 class="title title-3"><?= __('Actualités', 'lsd_lang') ?></h2>
            <ul>
                <?php
                $news_posts = get_posts(array(
                    'post_type'      => 'news',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));

                if (!empty($news_posts)) {
                    foreach ($news_posts as $post) {
                        setup_postdata($post);
                        echo '<li><a href="' . esc_url(get_permalink($post)) . '">' . esc_html(get_the_title($post)) . '</a></li>';
                    }
                    wp_reset_postdata();
                } else {
                    echo '<li>' . __('Aucune actualité pour le moment.', 'lsd_lang') . '</li>';
                }
                ?>
            </ul>
        </div>

    </section>
</main>

<?php
get_footer();
