<?php

define('THEME_DIR', get_template_directory() . '/');
define('THEME_ASSETS', get_template_directory_uri() . '/assets/'); //
define('THEME_URL', get_template_directory_uri() . '/');
define('HOME_URL', get_home_url());
define('AJAX_URL', admin_url('admin-ajax.php'));


if (!ENV_LOCAL) {
    define('ACF_LITE', true);
}


require_once(__DIR__ . '/inc/datatypes.php');
require_once(__DIR__ . '/inc/configuration.php');
require_once(__DIR__ . '/inc/configuration_security.php');
require_once(__DIR__ . '/inc/methods.php');
require_once(__DIR__ . '/inc/ajax-methods.php');
require_once(__DIR__ . '/inc/strates_helper.php');
require_once(__DIR__ . '/inc/breadcrumb_methods.php');

require_once(__DIR__ . '/front/methods.php');
require_once(__DIR__ . '/front/components.php');


//template
function get_tpl()
{
    include("inc/tpl.php");
}

