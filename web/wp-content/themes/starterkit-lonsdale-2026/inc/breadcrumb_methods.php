<?php
function find_hub_page_id($template_basename) {
    static $cache = [];

    // Déterminer la langue courante si possible
    $lang = '';
    if ( function_exists( 'pll_current_language' ) ) {
        $lang = pll_current_language() ?: '';
    } elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $lang = ICL_LANGUAGE_CODE;
    }

    $cache_key = $template_basename . '|' . $lang;
    if ( array_key_exists( $cache_key, $cache ) ) {
        return $cache[ $cache_key ];
    }

    $query_args = [
        'post_type'      => 'page',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => $template_basename,
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'suppress_filters' => false, // allow language plugins to filter
    ];

    if ( $lang ) {
        $query_args['lang'] = $lang;
    }

    $found = get_posts( $query_args );
    $result = ( ! empty( $found ) && is_array( $found ) ) ? $found[0] : false;

    $cache[ $cache_key ] = $result;
    return $result;
}

function breadcrumb_li( $label, $url = null, $is_current = false ) {
    if ( $url && ! $is_current ) {
        echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
    } else {
        echo '<li>' . esc_html( $label ) . '</li>';
    }
}

function breadcrumb_print_page_ancestors( $post_id ) {
    $parents = get_post_ancestors( $post_id );
    if ( ! empty( $parents ) ) {
        $parents = array_reverse( $parents );
        foreach ( $parents as $parent_id ) {
            $title = get_the_title( $parent_id );
            $link  = get_permalink( $parent_id );
            breadcrumb_li( $title, $link );
        }
    }
}

function breadcrumb_print_category_ancestors( $cat_id ) {
    $anc = get_ancestors( $cat_id, 'category' );
    if ( ! empty( $anc ) ) {
        $anc = array_reverse( $anc );
        foreach ( $anc as $ancestor_id ) {
            $term = get_category( $ancestor_id );
            if ( $term && ! is_wp_error( $term ) ) {
                breadcrumb_li( $term->name, get_category_link( $term->term_id ) );
            }
        }
    }
}

function breadcrumb_generate_all_li() {
    if ( ! function_exists( 'is_front_page' ) ) {
        // Si on n'est pas dans WordPress (sécurité), afficher le fallback statique
        echo '<li>Homepage</li>';
    } else {
        global $post;

        $home_label = __('Accueil', 'lsd_lang');
        $home_url   = esc_url( home_url( '/' ) );

        // Home / Accueil
        breadcrumb_li( $home_label, $home_url, ( is_front_page() || is_home() ) );

        // Cas : article unique
        if ( is_single() ) {
            $post_type = get_post_type( $post );

            // Spécifique : pour les post types 'solutions' et 'news' on veut un lien vers la première page utilisant le template hub correspondant
            if ( $post_type === 'solutions' ) {
                $hub_page_id = find_hub_page_id( 'pages/hub-solution.php' );
                if ( $hub_page_id ) {
                    breadcrumb_li( get_the_title( $hub_page_id ), get_permalink( $hub_page_id ) );
                }
            } elseif ( $post_type === 'news' ) {
                $hub_page_id = find_hub_page_id( 'pages/hub-actu.php' );
                if ( $hub_page_id ) {
                    breadcrumb_li( get_the_title( $hub_page_id ), get_permalink( $hub_page_id ) );
                }
            }

            // CPT génériques : lien vers l'archive du post type si disponible (cas fallback)
            if ( $post_type && $post_type !== 'post' ) {
                $archive_link = get_post_type_archive_link( $post_type );
                if ( $archive_link ) {
                    $pt_obj = get_post_type_object( $post_type );
                    $label  = $pt_obj ? $pt_obj->labels->singular_name : ucfirst( $post_type );
                    breadcrumb_li( $label, $archive_link );
                }
            } else {
                // Posts: afficher la catégorie principale (si existe) et ses parents
                $categories = get_the_category( $post->ID );
                if ( ! empty( $categories ) ) {
                    // On prend la première catégorie par défaut
                    $category = $categories[0];
                    breadcrumb_print_category_ancestors( $category->term_id );
                    breadcrumb_li( $category->name, get_category_link( $category->term_id ) );
                }
            }

            // Titre de l'article (élément courant sans lien)
            breadcrumb_li( get_the_title( $post->ID ), null, true );

            // Cas : page
        } elseif ( is_page() ) {
            if ( $post ) {
                breadcrumb_print_page_ancestors( $post->ID );
                breadcrumb_li( get_the_title( $post->ID ), null, true );
            }

            // Cas : catégorie archive
        } elseif ( is_category() ) {
            $cat = get_queried_object();
            if ( $cat && ! is_wp_error( $cat ) ) {
                breadcrumb_print_category_ancestors( $cat->term_id );
                breadcrumb_li( $cat->name, null, true );
            }

            // Cas : tag archive
        } elseif ( is_tag() ) {
            breadcrumb_li( single_tag_title( '', false ), null, true );

            // Cas : author archive
        } elseif ( is_author() ) {
            $author = get_queried_object();
            if ( $author ) {
                breadcrumb_li( $author->display_name, null, true );
            }

            // Cas : recherche
        } elseif ( is_search() ) {
            breadcrumb_li( sprintf( __( 'Search results for: %s', 'lsd_lang' ), get_search_query() ), null, true );

            // Cas : date archives
        } elseif ( is_day() ) {
            breadcrumb_li( get_the_date(), null, true );
        } elseif ( is_month() ) {
            breadcrumb_li( get_the_date( 'F Y' ), null, true );
        } elseif ( is_year() ) {
            breadcrumb_li( get_the_date( 'Y' ), null, true );

            // Cas : post type archive
        } elseif ( is_post_type_archive() ) {
            $obj = get_queried_object();
            if ( $obj && ! empty( $obj->labels->name ) ) {
                breadcrumb_li( $obj->labels->name, null, true );
            }

            // Cas : 404
        } elseif ( is_404() ) {
            breadcrumb_li( '404', null, true );
        }
    }
}