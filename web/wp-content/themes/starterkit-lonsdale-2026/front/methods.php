<?php


function hasWebp($image)
{
    $ext = pathinfo($image[0])['extension'];
    $src = null;
    if ($ext != "svg") {
        $webp = str_replace("." . $ext, ".webp", $image[0]);
        if (file_exists(str_replace("https://" . $_SERVER['HTTP_HOST'] . "/", ABSPATH, $webp))) {
            $src = $webp;
        }
    }

    return $src ? $src : null;
}


/* options des strates */
function options($classes, $args)
{
    $container = "";
    if (!empty($args["container"])) {
        $container = !empty($args["container"]) ? " ctr-" . $args["container"] : "";
    }

    $marginBottom = " mb-0";
    if (!empty($args["margin"]["bottom"])) {
        if ($args["margin"]["bottom"] == "md") {
            $marginBottom = "";
        } else {
            $marginBottom = !empty($args["margin"]["bottom"]) ? " mb-" . $args["margin"]["bottom"] : "";
        }
    }

    $marginTop = "";
    if (!empty($args["margin"]["top"])) {
        $marginTop = !empty($args["margin"]["top"]) ? " mt-" . $args["margin"]["top"] : "";
    }

    $background = "";
    $paddingTop = "";
    $paddingBottom = "";
    if (!empty($args["background"]["hasbackground"])) {
            $background = " bg-" . $args["background"]["color"];
            $padding = $args["background"]["padding"];

            if ($padding["top"] == "md") {
                $paddingTop = "";
            } else {
                $paddingTop =   " pt-" . $padding["top"];
            }
            
            if ($padding["bottom"] == "md") {
                $paddingBottom = "";
            } else {
                $paddingBottom =   " pb-" . $padding["bottom"];
            }
       
    }

    $id = "";
    if (!empty($args["id"])) {
        $id = ' id="' . $args["id"] . '"';
    }

    return 'class="' . $classes . $marginBottom . $marginTop . $background . $paddingTop . $paddingBottom . $container . '"' . $id;
}


$css_files = [];

function addStyle($name, $folder)
{
    global $css_files;

    $value = "$folder/$name/$name.css";
    if (file_exists(THEME_DIR . "assets/$value")) {
        if (!array_key_exists($folder, $css_files)) {
            $css_files[$folder] = [];
        }
        if (!in_array($value, $css_files[$folder])) array_push($css_files[$folder], $value);
    }
}

function strate($name, $args)
{
    addStyle($name, "strates");
    get_template_part("template-parts/strates/$name", null, $args);
}

function hero($name, $args)
{
    addStyle($name, "heros");
    get_template_part("template-parts/heros/$name", null, $args);
}

function styles()
{
    global $css_files;

    $v = filemtime(get_template_directory() . '/assets/styles.css');
    $version = ENV_PROD ? "?v=" . $v : "";

    echo '<link rel="preload" href="' . THEME_ASSETS . "styles.css" . $version . '" as="style" type="text/css">' . "\r\n";
    echo '<link rel="stylesheet" href="' . THEME_ASSETS . "styles.css" . $version . '"/>' . "\r\n";

    sort($css_files);

    foreach ($css_files as $type) {
        foreach ($type as $file) {
            echo '<link rel="stylesheet" href="' . THEME_ASSETS . $file .  $version . '"/>' . "\r";
        }
    }
}


function appjs()
{
    $v = filemtime(get_template_directory() . '/assets/app.js');
    $version = ENV_PROD ? "?v=" . $v : "";

    echo '
    <link rel="modulepreload" href="' . THEME_ASSETS . "app.js" . $version . '">
 
    <script defer id="appjs"  type="module" src="' . THEME_ASSETS . "app.js" . $version . '"
    data-ajax_url="' . AJAX_URL . '"
    data-version="' . (ENV_PROD ? $v : '') . '"></script>';

    /*     
    <link rel="modulepreload" href="<?= THEME_ASSETS ?>strates/strate-slider/strate-slider.js">
    <link rel="modulepreload" href="<?= THEME_ASSETS ?>components/slider/slider.js">  */
}
