<?php
get_header();
get_template_part('template-parts/general/block', 'header_nav');

$fields = get_fields();

// Hero
$hero = [];
$hero['title'] = get_the_title();
$hero['text'] = !empty($fields['field-solutions']['intro']) ? $fields['field-solutions']['intro'] : '';
$hero['images'] = !empty($fields['field-solutions']['images']) ? $fields['field-solutions']['images'] : '';

// Récupération des termes category_news de la solution courante
$latestNews = [];
$solution_terms = get_the_terms(get_the_ID(), 'category_news');
$term_ids = [];
if (!empty($solution_terms) && !is_wp_error($solution_terms)) {
    foreach ($solution_terms as $term) {
        $term_ids[] = (int) $term->term_id;
    }
}

// 1. Essaye de récupérer jusqu'à 3 news partageant au moins un des termes
if (!empty($term_ids)) {
    $latestNews = get_posts([
        'post_type'      => 'news',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'tax_query'      => [
            [
                'taxonomy' => 'category_news',
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ]
        ],
    ]);
}

// 2. Si moins de 3 résultats, complète avec les dernières news globales (sans doublons)
if (count($latestNews) < 3) {
    $needed = 3 - count($latestNews);
    $additional = get_posts([
        'post_type'      => 'news',
        'posts_per_page' => $needed,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'post__not_in'   => $latestNews,
    ]);
    if (!empty($additional)) {
        $latestNews = array_unique(array_merge($latestNews, $additional));
    }
}

// 3. Sécurise le format attendu (array d'IDs, max 3)
$latestNews = array_slice(array_map('intval', $latestNews), 0, 3);

$latestSolutions = get_posts([
    'post_type'      => 'solutions',
    'posts_per_page' => 5,
    'exclude'        => [get_the_ID()],
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'fields'         => 'ids', // retourne uniquement les IDs
    'no_found_rows'  => true,   // optimisation: pas de comptage total
]);
?>

<main>
    <?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

    <?= get_template_part('template-parts/heros/hero', "flexible", $hero); ?>

    <?= get_template_part('template-parts/strates-dispatch'); ?>

    <?= get_template_part('template-parts/strates/strate', 'highlight', [
        "options" => [
            "container" => "md",
            "margin" => [
                "bottom" => "md",
                "top" => ""
            ],
            "background" => [
                "hasBackground" => false,
                "color" => "color-1",
                "padding" => "md"
            ],
            "id" => ""
        ],
        "title" => !empty($fields['solution_highlight']['title']) ? $fields['solution_highlight']['title'] : '',
        "text" => !empty($fields['solution_highlight']['text']) ? $fields['solution_highlight']['text'] : '',
        "items" => $latestNews,
        "link" => !empty($fields['solution_highlight']['link']) ? $fields['solution_highlight']['link'] : []
    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'cross_navigation', [
         "options" => [
            "container" => "md",
            "margin" => [
                "bottom" => "md",
                "top" => ""
            ],
            "background" => [
                "hasBackground" => false,
                "color" => "color-1",
                "padding" => "md"
            ],
            "id" => ""
        ],
        "title" => !empty($fields['solution_highlight_solutions']['title']) ? $fields['solution_highlight_solutions']['title'] : '',
        "items" => $latestSolutions
    ]); ?>
</main>

<?php
get_footer();
