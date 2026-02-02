<?php
/*
Plugin Name: Easy static
Description: Generate static site
Version: 1.6.0 
Author: Martin Jonathan
*/

global $wpdb;
global $table_prefix;
global $authentification;
global $table;
global $haschange;
global $isStatic;
global $isminify;
global $es_isauto;
global $is_es_active;
global $es_folder_static;
global $es_isauto;

$es_folder_static =  "easy-static";

// Create table easystatic if not exist
$charset_collate = $wpdb->get_charset_collate();
$table =  $table_prefix . "easystatic";
if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
    $sql = "CREATE TABLE $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        `es_option` varchar(20) NOT NULL,
        `sc_value` longtext NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

$is_es_active = is_plugin_active('easy-static/easy-static.php');

// Create options
$easy_static_active = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'active'");
if (empty($easy_static_active)) {
    $data = array('es_option' => "active", 'sc_value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
    $isStatic = false;
} else {
    $isStatic = $easy_static_active[0]->sc_value === "0" ? false : true;
}

$easy_static_user = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'user'");
if (empty($easy_static_user)) {
    $data = array('es_option' => "user", 'sc_value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_password = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'password'");
if (empty($easy_static_password)) {
    $data = array('es_option' => "password", 'sc_value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_slug = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'slug'");
if (empty($easy_static_slug)) {
    $data = array('es_option' => "slug", 'sc_value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
}

$easy_static_minify = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'minify'");
if (empty($easy_static_minify)) {
    $data = array('es_option' => "minify", 'sc_value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
}

$easy_static_generate = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'generate'");
if (empty($easy_static_generate)) {
    $data = array('es_option' => "generate", 'sc_value' => "");
    $format = array('%s', '%s');
    $wpdb->insert($table, $data, $format);
    $last_generate = "";
} else {
    $last_generate = $easy_static_generate[0]->sc_value;
}

$easy_static_haschange = $wpdb->get_results("SELECT * FROM " . $table . " WHERE es_option = 'haschange'");
if (empty($easy_static_haschange)) {
    $data = array('es_option' => "haschange", 'sc_value' => false);
    $format = array('%s', '%d');
    $wpdb->insert($table, $data, $format);
    $haschange = false;
} else {
    $haschange = $easy_static_haschange[0]->sc_value === "0" ? false : true;
}

$es_isauto = false;
 
$minify = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE es_option = 'minify'");
$isminify =  $minify[0]->sc_value === "true" ? true : false;

// authentification
$user = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE es_option = 'user'");
$password = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE es_option = 'password'");
$authentification["user"] =  $user[0]->sc_value;
$authentification["password"] = $password[0]->sc_value;

// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'includes/es-functions.php';

require_once plugin_dir_path(__FILE__) . 'includes/es-admin-ajax.php';


// set haschange to true if page/post is edited
add_action('save_post', 'wpdocs_notify_subscribers', 10, 3);
function wpdocs_notify_subscribers($post_id, $post, $update)
{
    global $is_es_active;
    global $es_isauto;

    if ($is_es_active) {
        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = ["post", "page"];

        foreach (get_post_types($args, 'names', 'and')  as $post_type) {
            array_push($post_types, $post_type);
        }
        if ($post->post_status == "publish") {
            // if ($post->static_active) {
            if (in_array($post->post_type, $post_types)) {
                if ($es_isauto) {
                    //  generate_post($post);
                } else {
                    hasChanged();
                }
            }
        }
        // }
    }
}

//réglages
add_action('check_admin_referer', 'check_nav_menu_updates', 11, 3);
function check_nav_menu_updates($action)
{
    global $is_es_active;
    global $es_isauto;

    $arr = ["general-options", "writing-options", "reading-options"]; //"update-permalink"

    if ($is_es_active) {
        if (in_array($action, $arr)) {
            if ($es_isauto) {
                // generate_all();
            } else {
                hasChanged();
            }
        }
    }
}

// menu
// wp_update_nav_menu() fired twice, so wee need to once
global $once;
$once = 0;
function wpdocs_update_menu_stuff_after_update($menu_id, $menu_data = array())
{
    global $once;
    global $es_isauto;
    $once++;

    global $is_es_active;
    if ($once == 2) {
        if ($is_es_active) {
            if ($es_isauto) {
                // generate_all();
            } else {
                hasChanged();
            }
        }
        $once = 0;
    }
}
add_action('wp_update_nav_menu', 'wpdocs_update_menu_stuff_after_update', 10, 1);

// Parameters
function clear_advert_main_transient($post_id)
{
    global $easy_static_active;
    global $es_isauto;
    $screen = get_current_screen();
    if ($easy_static_active[0]->sc_value) {
        if ($screen->base === "toplevel_page_acf-options-parametres") {
            if ($es_isauto) {
                // generate_all();
            } else {
                hasChanged();
            }
        }
    }
}
add_action('acf/save_post', 'clear_advert_main_transient', 20);

// css common admin
function commoncss()
{
    wp_register_style('mein-plugin', plugins_url('common.css', __FILE__));
    wp_enqueue_style('mein-plugin');
}
if (is_admin()) add_action('init', 'commoncss');
