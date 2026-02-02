<?php

function theme_setup()
{
    load_theme_textdomain('theme', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ),
        'post-thumbnails'
    );

    register_nav_menus(array(
        'menu-header' => 'Menu Principal',
        'menu-footer' => 'Menu Footer',
    ));

    add_filter('show_admin_bar', '__return_false');

    add_image_size('436_284', 436, 284, array('center', 'center')); // card news
    add_image_size('60_60', 60, 60, array('center', 'center')); // card quote
    add_image_size('284_378', 284, 378, array('center', 'center')); // card quote
    add_image_size('320_430', 320, 430, array('center', 'center'));

    //add_image_size('698_424', 698, 424, array('center', 'center')); // card timeline
    add_image_size('413_224', 413, 224, array('center', 'center')); // card timeline

    add_image_size('666_356', 666, 356, array('center', 'center'));
    add_image_size('437_226', 437, 226, array('center', 'center')); // card solution
    add_image_size('130_87', 130, 87, array('center', 'center')); // download

    add_image_size('764_620', 764, 620, array('center', 'center')); // hero page
    add_image_size('1352_530', 1352, 530, array('center', 'center')); // hero news | hero highlight | hero flexible 

    add_image_size('198_318', 198, 318, array('center', 'center')); // sub nav
}

// Remove unused format
function disable_unused_format($sizes)
{
    unset($sizes['300x300']);
    unset($sizes['600x800']);
    /* unset($sizes['768x768']);*/
    unset($sizes['large']);
    unset($sizes['2048x2048']);
    unset($sizes['scaled']);
    unset($sizes['1536x1536']);
    unset($sizes['thumbnail_example']);
    unset($sizes['medium_large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_format');



/*
 * TINY MCE
 */

// Set formats
function wysiwyg_block_formats($args)
{
    $args['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
    return $args;
}
add_filter('tiny_mce_before_init', 'wysiwyg_block_formats');


// Custom Formats
add_filter('mce_buttons_2', 'juiz_mce_buttons_2');
if (!function_exists('juiz_mce_buttons_2')) {
    function juiz_mce_buttons_2($buttons)
    {
        array_unshift($buttons, 'styleselect');

        return $buttons;
    }
}
add_filter('tiny_mce_before_init', 'juiz_mce_before_init');
if (!function_exists('juiz_mce_before_init')) {
    function juiz_mce_before_init($styles)
    {
        $style_formats = array(
            array(
                'title' => 'Bullet point picto',
                'inline' => 'span',
                'classes' => 'type-picto'
            ),
        );
        $styles['style_formats'] = json_encode($style_formats);

        return $styles;
    }
}
add_filter('acf/fields/wysiwyg/toolbars', 'wysiwyg_type');
function wysiwyg_type($toolbars)
{
    $toolbars['Title'] = array();
    $toolbars['Title'][1] = array('bold');
    return $toolbars;
}

// Ajoute une classe wrapper sur les champs WYSIWYG ACF avec toolbar "Title"
add_filter('acf/prepare_field', function ($field) {
    if (isset($field['type']) && $field['type'] === 'wysiwyg') {
        $toolbar = isset($field['toolbar']) ? strtolower($field['toolbar']) : '';
        if ($toolbar === strtolower('Title')) {
            if (!isset($field['wrapper'])) {
                $field['wrapper'] = [];
            }
            if (!isset($field['wrapper']['class'])) {
                $field['wrapper']['class'] = '';
            }
            $field['wrapper']['class'] .= ' acf-wysiwyg-title-large';
            $field['wrapper']['class'] .= ' title title-2';
        }
    }
    return $field;
});

// Style admin: augmente la hauteur de TinyMCE pour les champs marqués
add_action('admin_head', function () {
    echo '<style>
    /* Cible uniquement les WYSIWYG avec la classe wrapper ajoutée */
    .acf-wysiwyg-title-large .acf-editor-wrap iframe {
        min-height: 100px !important;
        height: 100px !important;
    }
    /* Selon la version, TinyMCE utilise un conteneur .mce-edit-area */
    .acf-wysiwyg-title-large .mce-edit-area iframe {
        min-height: 100px !important;
        height: 100px !important;
    }
    /* Pour l’éditeur par défaut (textarea avant init), au besoin */
    .acf-wysiwyg-title-large textarea.wp-editor-area {
        min-height: 100px !important;
        height: 100px !important;
    }
    </style>';
});

function my_mce4_options($init)
{
    $custom_colours = '
        "25465F", "color-1",
        "00A0BD", "color-2",
        "EFF2F8", "color-3",
        "EB5B00", "color-4",
        "0053A5", "color-5",
    ';
    // Add custom colors to the color map
    $init['textcolor_map'] = '[' . $custom_colours . ']';
    // Enable the text color picker
    $init['textcolor_rows'] = 6; // expand color rows to show all custom colors
    return $init;
}
add_filter('tiny_mce_before_init', 'my_mce4_options');

// add fontselect
add_filter('mce_buttons_2', function ($buttons) {
    array_unshift($buttons, 'fontselect');
    return $buttons;
});
add_filter('tiny_mce_before_init', function ($initArray) {
    $initArray['font_formats'] = 'BouyguesRead=var(--font-1);BouyguesSpeak=var(--font-2)';
    return $initArray;
});


if (!function_exists('juiz_init_editor_styles')) {
    add_action('after_setup_theme', 'juiz_init_editor_styles');
    function juiz_init_editor_styles()
    {
        add_editor_style('assets/styles.css');
    }
}

// Tiny MCE, add class rte
add_filter('tiny_mce_before_init', 'wpse_editor_styles_class');
function wpse_editor_styles_class($settings)
{
    $settings['body_class'] = 'rte mce';

    return $settings;
}

// wysywig sup sub
function enable_more_buttons($buttons)
{
    $buttons[] = "superscript";
    $buttons[] = "subscript";

    return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons");

// tiny mce Formatage avec les <p>
//add_filter('tiny_mce_before_init', 'prevent_deleting_pTags');
function prevent_deleting_pTags($init)
{
    $init['wpautop'] = false;
    return $init;
}




//REMOVE FILE TYPE
add_filter('style_loader_tag', 'remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'remove_type_attr', 10, 2);

function remove_type_attr($tag, $handle)
{
    return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}

function remove_menus()
{
    //remove_menu_page('edit.php'); // remove articles from menu
    remove_menu_page('edit-comments.php'); //Comments
}
add_action('admin_menu', 'remove_menus');

function acf_add_main_options()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page('Paramètres');
    }
}

add_filter('wp_default_scripts', 'removeJqueryMigrate');
function removeJqueryMigrate(&$scripts)
{
    if (!is_admin()) {
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array('jquery-core'), '1.4.1');
    }
}

add_action('after_setup_theme', 'theme_setup');
// add_action( 'admin_bar_menu', 'remove_default_post_type_menu_bar', 999 );
// add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );
add_action('wp_loaded', 'acf_add_main_options');

//REMOVE : emoji 🗑
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// Remove wp-embed.min.js
function my_deregister_scripts()
{
    wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

// Remove default css
function smartwp_remove_wp_block_library_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS
}
add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100);
add_action('wp_enqueue_scripts', 'mywptheme_child_deregister_styles', 20);
function mywptheme_child_deregister_styles()
{
    wp_dequeue_style('classic-theme-styles');
}

// no image compression
add_filter('jpeg_quality', function ($arg) {
    return -1;
});
add_filter('wp_editor_set_quality', function ($arg) {
    return -1;
});

// empeche que l'image soit scaled si trop grande
add_filter('big_image_size_threshold', '__return_false');

/*  DISABLE GUTENBERG STYLE IN HEADER| WordPress 5.9 */
function wps_deregister_styles()
{
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'wps_deregister_styles', 100);


// --------------------------------------------------------------------------------------
// Désactivation de l'éditeur wordpress sur les page (pour n'avoir que les champs ACF)
// --------------------------------------------------------------------------------------
add_action('init', 'init_remove_support', 100);

function init_remove_support()
{
    remove_post_type_support("page", 'editor');
}

remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

// remove oembed
function disable_embeds_code_init()
{

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');

    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
    add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');

    // Remove all embeds rewrite rules.
    add_filter('rewrite_rules_array', 'disable_embeds_rewrites');

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
}
add_action('init', 'disable_embeds_code_init', 9999);

function disable_embeds_tiny_mce_plugin($plugins)
{
    return array_diff($plugins, array('wpembed'));
}

function disable_embeds_rewrites($rules)
{
    foreach ($rules as $rule => $rewrite) {
        if (false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}

/*
 * Menu and list reset
 */
// remove classes and ids of Walker_Nav_Menu
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);
function clear_nav_menu_item_id($id, $item, $args)
{
    return "";
}
add_filter('nav_menu_css_class', 'clear_nav_menu_item_class', 10, 3);
function clear_nav_menu_item_class($classes, $item, $args)
{
    return array();
}


// remove classes and ids of wp_list_pages()
add_filter('wp_list_pages', 'remove_page_class');
function remove_page_class($wp_list_pages)
{
    $pattern = '/\<li class="page_item[^>]*>/';
    $replace_with = '<li>';
    return preg_replace($pattern, $replace_with, $wp_list_pages);
}


// remove application/json
function remove_json_api()
{

    // Remove the REST API lines from the HTML Header
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');

    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Remove all embeds rewrite rules.
    add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
}
add_action('after_setup_theme', 'remove_json_api');

//Disable Speculative Loading
add_filter('wp_speculation_rules_configuration', '__return_null');

// Remove inline css
remove_action('wp_head', 'wp_print_auto_sizes_contain_css_fix', 1);

//



// Add a table of image sizes to the Settings > Media admin page
add_action('admin_init', function () {
    add_settings_section(
        'dummy_registered_image_sizes_info',
        esc_html__('Registered Image Sizes', 'text_domain'),
        function () {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>' . esc_html__('Name', 'text_domain') . '</th><th>' . esc_html__('Dimensions', 'text_domain') . '</th></tr></thead>';
            foreach ((array) wp_get_registered_image_subsizes() as $size => $dims) {
                if (! in_array($size, ['thumbnail', 'medium', 'large'], true)) {
                    $width = $dims['width'] ?? 0;
                    $height = $dims['height'] ?? 0;
                    echo "<tr><td><strong>{$size}</strong></td><td>{$width}x{$height}</td>";
                }
            }
            echo '</table>';
        },
        'media'
    );
}, PHP_INT_MAX);

// remove the_content in post
function remove_post_content_editor()
{
    remove_post_type_support('page', 'editor');
    remove_post_type_support('post', 'editor');
    remove_post_type_support('post', 'excerpt');
}
add_action('admin_init', 'remove_post_content_editor');



function allow_svg_uploads($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_uploads');
