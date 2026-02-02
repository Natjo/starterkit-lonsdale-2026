<?php

// Retirer le menu "Articles" pour les non-administrateurs
function bvf_remove_posts_menu() {
    remove_menu_page( 'edit.php' ); // "Articles" (Posts)
}
add_action( 'admin_menu', 'bvf_remove_posts_menu', 999 );

function create_cpt()
{
    $labels = array(
        'name'                => __( 'Actualités', 'lsd_lang'),
        'singular_name'       => __( 'Actualité', 'lsd_lang'),
    );

    $args = array(
        'label'               => __( 'Types d’actualité', 'lsd_lang'),
        'description'         => __( 'Toutes les actualités', 'lsd_lang'),
        'labels'              => $labels,
        'supports'            => array( 'title', 'excerpt', 'author', 'revisions', 'custom-fields'),
        'show_in_rest'        => false,
        'menu_icon'           => 'dashicons-admin-post',
        'hierarchical'        => true,
        'public'              => true,
        'has_archive'         => false,
        'rewrite' => array(
            'slug' => 'blog',
            'with_front' => true
        )
    );

    register_post_type( 'news', $args );

    $labels = array(
        'name'                => __( 'Solutions', 'lsd_lang'),
        'singular_name'       => __( 'Solution', 'lsd_lang'),
    );

    $args = array(
        'label'               => __( 'Types de solution', 'lsd_lang'),
        'description'         => __( 'Toutes les solutions', 'lsd_lang'),
        'labels'              => $labels,
        'supports'            => array( 'title', 'excerpt', 'author', 'revisions', 'custom-fields'),
        'show_in_rest'        => false,
        'menu_icon'           => 'dashicons-lightbulb',
        'hierarchical'        => true,
        'public'              => true,
        'has_archive'         => false,
        'rewrite' => array(
            'slug' => 'solutions',
            'with_front' => true
        )
    );

    register_post_type( 'solutions', $args );
}
add_action( 'init', 'create_cpt', 0 );

function create_taxonomy() {
    register_taxonomy(
        'category_news',
        ['news', 'solutions'],
        array(
            'hierarchical' => false,
            'show_admin_column' => true,
            'label' => __( 'Catégories', 'lsd_lang'),
            'query_var' => true
        )
    );
}
add_action( 'init', 'create_taxonomy');

// --- Rewrite rules pagination Hub Actu ---
function bv_register_hub_actu_pagination_rewrite() {
    $pages = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'pages/hub-actu.php',
        'post_type' => 'page',
        'post_status' => 'publish'
    ]);
    if (!empty($pages)) {
        foreach ($pages as $page) {
            $slug = $page->post_name;
            add_rewrite_rule("^{$slug}/page/([0-9]+)/?$", 'index.php?pagename=' . $slug . '&paged=$matches[1]', 'top');
        }
    }
}
add_action('init', 'bv_register_hub_actu_pagination_rewrite');