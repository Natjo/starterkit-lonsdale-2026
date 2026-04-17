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
function options($classes, $args = [])
{
    if (empty($args["options"])) {
        return 'class="' . $classes . '"';
    } else {
        $options = $args["options"];

        $container = "";
        if (!empty($options["container"])) {
            $container = !empty($options["container"]) ? " ctr-" . $options["container"] : "";
        }

        $marginBottom = " mb-0";
        if (!empty($options["margin"]["bottom"])) {
            if ($options["margin"]["bottom"] == "md") {
                $marginBottom = "";
            } else {
                $marginBottom = !empty($options["margin"]["bottom"]) ? " mb-" . $options["margin"]["bottom"] : "";
            }
        }

        $marginTop = "";
        if (!empty($options["margin"]["top"])) {
            $marginTop = !empty($options["margin"]["top"]) ? " mt-" . $options["margin"]["top"] : "";
        }

        $background = "";
        $paddingTop = "";
        $paddingBottom = "";
        if (!empty($options["background"]["hasbackground"])) {
            $background = " bg-" . $options["background"]["color"];
            $padding = $options["background"]["padding"];

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
        if (!empty($options["id"])) {
            $id = ' id="' . $options["id"] . '"';
        }

        return 'class="' . $classes . $marginBottom . $marginTop . $background . $paddingTop . $paddingBottom . $container . '"' . $id;
    }
}

$css_files = [];
$css_bundles = [];
$css_bundles_manifest = null;

function addStyle($name, $folder)
{
    global $css_files, $css_bundles, $css_bundles_manifest;

    $value = "$folder/$name/$name.css";
    if (file_exists(THEME_DIR . "assets/$value")) {
        // Decide bundle vs on-demand based on builder manifest
        if ($css_bundles_manifest === null) {
            $manifest_path = THEME_DIR . "assets/bundles/css-bundles.json";
            $css_bundles_manifest = file_exists($manifest_path) ? json_decode(@file_get_contents($manifest_path), true) : [];
        }

        $isBundled =
            !empty($css_bundles_manifest[$folder]) &&
            is_array($css_bundles_manifest[$folder]) &&
            in_array($value, $css_bundles_manifest[$folder], true);

        if ($isBundled) {
            $css_bundles[$folder] = true;
            return;
        }

        // On-demand: keep the previous behavior (assets/<folder>/<name>/<name>.css)
        if (!array_key_exists($folder, $css_files)) $css_files[$folder] = [];
        if (!in_array($value, $css_files[$folder], true)) $css_files[$folder][] = $value;
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
    global $css_files, $css_bundles;

    $v = filemtime(get_template_directory() . '/assets/styles.css');
    $version = ENV_PROD ? "?v=" . $v : "";

    $styles_path = get_template_directory() . '/assets/styles.css';
    /* styles.css 
    echo '<link rel="preload" href="' . THEME_ASSETS . "styles.css" . $version . '" as="style" type="text/css">' . "\r\n";
    echo '<link rel="stylesheet" href="' . THEME_ASSETS . "styles.css" . $version . '"/>' . "\r\n";
    */

    $inline_css = "";

    // Inline critical CSS (styles.css is small in this starterkit)
    $critical = @file_get_contents($styles_path);
    if (!empty($critical)) {
        // Drop sourcemap comment to avoid useless lookups
        $critical = preg_replace('/\\/\\*#\\s*sourceMappingURL=.*?\\*\\//', '', $critical);
        $inline_css .= trim($critical) . "\n";
    } else {
        $styles_href = THEME_ASSETS . "styles.css" . $version;
        echo '<link rel="preload" href="' . $styles_href . '" as="style" type="text/css">' . "\r\n";
        echo '<link rel="stylesheet" href="' . $styles_href . '"/>' . "\r\n";
    }

    // ── Inline hero CSS (avoid an extra request for the page hero) ───────────
    if (!empty($css_files["heros"])) {
        $files = $css_files["heros"];
        sort($files);
        foreach ($files as $file) {
            $path = THEME_DIR . "assets/" . $file;
            $css = @file_get_contents($path);
            if (!empty($css)) {
                $css = preg_replace('/\\/\\*#\\s*sourceMappingURL=.*?\\*\\//', '', $css);
                $inline_css .= "\n" . trim($css) . "\n";
            }
        }
    }

    // Emit one single inline style tag (critical + hero)
    if (!empty(trim($inline_css))) {
        // When CSS is inlined, relative url(...) resolves against the page URL (not /assets/styles.css).
        // Fix font URLs so they always point to theme assets.
        $inline_css = preg_replace(
            '/url\\(\\s*([\'"]?)(?:\\.\\/)?fonts\\//i',
            'url($1' . THEME_ASSETS . 'fonts/',
            $inline_css
        );
        echo '<style id="critical-css">' . $inline_css . '</style>' . "\r\n";
    }

    // ── Bundles order: components → cards → strates ──────────────────────────
    foreach (["components", "cards", "strates"] as $group) {
        if (empty($css_bundles[$group])) continue;

        $bundle_path = get_template_directory() . "/assets/bundles/{$group}.css";
        $bundle_v = ENV_PROD && file_exists($bundle_path) ? "?v=" . filemtime($bundle_path) : "";
        $bundle_href = THEME_ASSETS . "bundles/{$group}.css" . $bundle_v;

        echo '<link rel="preload" href="' . $bundle_href . '" as="style" type="text/css">' . "\r\n";
        echo '<link rel="stylesheet" href="' . $bundle_href . '"/>' . "\r\n";
    }

    // ── Default: keep per-file CSS for other folders ─────────────────────────
    ksort($css_files);
    foreach ($css_files as $folder => $files) {
        if (in_array($folder, ["heros"], true)) continue;
        sort($files);
        foreach ($files as $file) {
            echo '<link rel="stylesheet" href="' . THEME_ASSETS . $file . $version . '"/>' . "\r\n";
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

function sg_parse_literal_string($value)
{
    $value = trim((string) $value);
    if ($value === '') return '';
    if (
        (str_starts_with($value, "'") && str_ends_with($value, "'")) ||
        (str_starts_with($value, '"') && str_ends_with($value, '"'))
    ) {
        return substr($value, 1, -1);
    }
    return $value;
}

function sg_parse_icon_literal($value)
{
    $value = trim((string) $value);
    if ($value === '') return [];
    if (!preg_match('/^\[\s*[\'"]([^\'"]+)[\'"]\s*,\s*([0-9.]+)\s*,\s*([0-9.]+)\s*\]$/', $value, $m)) {
        return [];
    }
    return [$m[1], (float) $m[2], (float) $m[3]];
}

function sg_ajax_preview_btn()
{
    $args_raw = isset($_POST['args']) ? wp_unslash($_POST['args']) : '';
    $classes_raw = isset($_POST['classes']) ? wp_unslash($_POST['classes']) : '';
    $icon_raw = isset($_POST['icon']) ? wp_unslash($_POST['icon']) : '';
    $attributes_raw = isset($_POST['attributes']) ? wp_unslash($_POST['attributes']) : '';

    $args_value = trim((string) $args_raw);
    if ($args_value === '' || $args_value === '$args') {
        $args = 'Je suis un bouton';
    } else {
        $args = sg_parse_literal_string($args_value);
    }

    $classes = sg_parse_literal_string($classes_raw);
    $icon = sg_parse_icon_literal($icon_raw);
    $attributes = trim((string) $attributes_raw);

    ob_start();
    component::btn($args, $classes, $icon, $attributes);
    $html = ob_get_clean();

    wp_send_json_success([
        'html' => $html,
    ]);
}

add_action('wp_ajax_sg_preview_btn', 'sg_ajax_preview_btn');
add_action('wp_ajax_nopriv_sg_preview_btn', 'sg_ajax_preview_btn');

function sg_ajax_preview_title()
{
    $args_raw = isset($_POST['args']) ? wp_unslash($_POST['args']) : '';
    $hx_raw = isset($_POST['hx']) ? wp_unslash($_POST['hx']) : '';
    $classes_raw = isset($_POST['classes']) ? wp_unslash($_POST['classes']) : '';

    $args_value = trim((string) $args_raw);
    if ($args_value === '' || $args_value === '$args') {
        $args = 'Lorem ipsum dolor sit amet';
    } else {
        $args = sg_parse_literal_string($args_value);
    }

    $hx = absint($hx_raw);
    if ($hx < 1 || $hx > 6) {
        $hx = 1;
    }

    $classes = sg_parse_literal_string($classes_raw);

    ob_start();
    component::title($args, $hx, $classes);
    $html = ob_get_clean();

    wp_send_json_success([
        'html' => $html,
    ]);
}

add_action('wp_ajax_sg_preview_title', 'sg_ajax_preview_title');
add_action('wp_ajax_nopriv_sg_preview_title', 'sg_ajax_preview_title');

function sg_build_demo_list_items($count)
{
    $count = max(1, min(12, absint($count)));
    $items = [];
    for ($i = 1; $i <= $count; $i++) {
        $items[] = [
            "title" => "Lorem ipsum dolor sit amet #" . $i,
            "images" => ["desktop" => 460],
        ];
    }
    return $items;
}

function sg_ajax_preview_list()
{
    $items_raw = isset($_POST['items']) ? wp_unslash($_POST['items']) : '';
    $card_raw = isset($_POST['card']) ? wp_unslash($_POST['card']) : '';
    $classes_raw = isset($_POST['classes']) ? wp_unslash($_POST['classes']) : '';

    $items_value = trim((string) $items_raw);
    $count = 3;
    if (preg_match('/^\d+$/', $items_value)) {
        $count = (int) $items_value;
    }

    $items = sg_build_demo_list_items($count);
    $card = trim(sg_parse_literal_string($card_raw));
    if ($card === '' || $card === '$card') {
        $card = 'news';
    }
    $classes = sg_parse_literal_string($classes_raw);

    ob_start();
    component::list($items, $card, $classes);
    $html = ob_get_clean();

    wp_send_json_success([
        'html' => $html,
    ]);
}

add_action('wp_ajax_sg_preview_list', 'sg_ajax_preview_list');
add_action('wp_ajax_nopriv_sg_preview_list', 'sg_ajax_preview_list');
