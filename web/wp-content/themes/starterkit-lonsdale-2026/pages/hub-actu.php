<?php
/*
Template Name: Hub Actu
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

<main>
    <?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?php
    $hero = get_field('hub-news_hero');

    // Récupère la dernière actualité mise en avant (is_highlight)
    $newsHighlight = null;
    $highlight_posts = get_posts([
        'post_type' => 'news',
        'post_status' => 'publish',
        'numberposts' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_key' => 'is_highlight',
        'meta_value' => 1,
    ]);

    if (empty($highlight_posts)) {
        $highlight_posts = get_posts([
            'post_type' => 'news',
            'post_status' => 'publish',
            'numberposts' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
    }

    if (!empty($highlight_posts)) {
        $highlight_post = $highlight_posts[0];
        $fields = get_field('field-news', $highlight_post->ID);

        $images = !empty($fields['images']) ? $fields['images'] : [];
        $readtime = !empty($fields['readtime']) ? $fields['readtime'] : '';

        $terms = get_the_terms($highlight_post->ID, 'category_news');
        $categories = [];
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $categories[] = '#' . esc_html($term->name);
            }
        }

        $hero['news'] = [
            'category' => implode(' ', $categories),
            'title' => get_the_title($highlight_post->ID),
            'url' => get_permalink($highlight_post->ID),
            'date' => get_the_date('d/m/Y', $highlight_post->ID),
            'readtime' => $readtime,
            'images' => $images,
        ];
        $newsHighlight = $highlight_post->ID;
    } else {
        $hero['news'] = [];
    }
    ?>
    <?= get_template_part('template-parts/heros/hero', 'highlight', $hero); ?>

    <?php
    // Page courante (supporte paged et page)
    $paged = get_query_var('paged') ? (int) get_query_var('paged') : (get_query_var('page') ? (int) get_query_var('page') : 1);
    $posts_per_page = 12;

    $query_args = [
        'post_type' => 'news',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
    ];
    if (!empty($newsHighlight)) {
        $query_args['post__not_in'] = [$newsHighlight];
    }
    $news_query = new WP_Query($query_args);
    ?>
    
    <section class="strate strate-news">
        <?php if ($news_query->have_posts()) : ?>

            <ul class="list">
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                    <li class="item">
                        <?= get_template_part('template-parts/cards/card', 'news', get_the_ID()); ?>
                    </li>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </ul>

            <?php
            $paginationArgs = [
                'total' => $news_query->max_num_pages,
                'current' => $paged,
                'highlight_id' => $newsHighlight,
                'permalink_structure' => get_option('permalink_structure'),
            ];
            ?>
            <?= get_template_part('template-parts/blocks/block', 'pagination', $paginationArgs); ?>
        <?php else : ?>
            <p><?= __('Aucune actualité disponible pour le moment.', 'lsd_lang') ?></p>
        <?php endif; ?>
    </section>
</main>

<?php
get_footer();
