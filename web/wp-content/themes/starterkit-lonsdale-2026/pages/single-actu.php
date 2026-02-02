<?php

get_header();
get_template_part('template-parts/general/block', 'header_nav');

$fields = get_fields();

// Hero
$hero = [];
$hero['title'] = get_the_title();
$hero['date'] = get_the_date('d/m/Y');
$hero['readtime'] = !empty($fields['field-news']['readtime']) ? $fields['field-news']['readtime'] : '';
$hero['images'] = !empty($fields['field-news']['images']) ? $fields['field-news']['images'] : '';
$hero['intro'] = !empty($fields['field-news']['intro']) ? $fields['field-news']['intro'] : '';
// Catégorie(s) depuis la taxonomie category_news
$terms = get_the_terms(get_the_ID(), 'category_news');
if (!empty($terms) && !is_wp_error($terms)) {
    // Concatène toutes les catégories avec un # devant chacune
    $categories = [];
    foreach ($terms as $term) {
        $categories[] = '#' . esc_html($term->name);
    }
    $hero['category'] = implode(' ', $categories);
} else {
    $hero['category'] = '';
}

// --- Récupération dynamique des 3 articles pour le bloc "Ces articles pourraient vous intéresser" ---
$highlight_items = [];
$current_id = get_the_ID();

// 1) Essayer de récupérer jusqu'à 3 posts "news" avec la même/les mêmes catégories (category_news)
$related_ids = [];
$tax_query = [];
if (!empty($terms) && !is_wp_error($terms)) {
    $tax_query = [
        [
            'taxonomy' => 'category_news',
            'field'    => 'term_id',
            'terms'    => wp_list_pluck($terms, 'term_id'),
            'operator' => 'IN',
        ],
    ];
}

if (!empty($tax_query)) {
    $related_query = new WP_Query([
        'post_type'           => 'news',
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post__not_in'        => [$current_id],
        'tax_query'           => $tax_query,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        'fields'              => 'ids',
    ]);

    if ($related_query->have_posts()) {
        $related_ids = $related_query->posts;
    }
    wp_reset_postdata();
}

// 2) Compléter si moins de 3 avec les derniers posts publiés (antéchronologique)
$need = 3 - count($related_ids);
if ($need > 0) {
    $exclude_ids = array_merge($related_ids, [$current_id]);
    $fallback_query = new WP_Query([
        'post_type'           => 'news',
        'posts_per_page'      => $need,
        'post_status'         => 'publish',
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post__not_in'        => $exclude_ids,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        'fields'              => 'ids',
    ]);

    if ($fallback_query->have_posts()) {
        $related_ids = array_merge($related_ids, $fallback_query->posts);
    }
    wp_reset_postdata();
}

$highlight_items = $related_ids; // tableau d'IDs (max 3)
?>

<main>
    <?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?= get_template_part('template-parts/heros/hero', 'news', $hero); ?>

    <?= get_template_part('template-parts/strates-dispatch'); ?>

    <footer class="article-footer">
        <div class="grid">
            <?= get_template_part('template-parts/blocks/block', "shares"); ?>
        </div>
    </footer>

    <?= get_template_part('template-parts/strates/strate', 'highlight', [
        "options" => [
            "container" => "md",
            "margin" => [
                "bottom" => "md",
                "top" => "md"
            ],
            "background" => [
                "hasBackground" => false,
            ]
        ],
        "title" => __('Ces articles pourraient <strong> vous intéresser</strong>', 'lsd_lang'),
        "text" => "",
        "items" => $highlight_items,
        "link" => []
    ]); ?>
</main>

<?php
get_footer();
