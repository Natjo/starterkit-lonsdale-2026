<?php

if (!function_exists('sg_build_icons_manifest')) {
    function sg_build_icons_manifest(string $svg_path, string $json_path): array
    {
        if (!file_exists($svg_path)) {
            return ['ok' => false, 'message' => 'icons.svg introuvable.'];
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $loaded = $dom->load($svg_path);
        libxml_clear_errors();
        if (!$loaded) {
            return ['ok' => false, 'message' => 'Impossible de parser icons.svg.'];
        }

        $symbols = $dom->getElementsByTagName('symbol');
        $items = [];
        foreach ($symbols as $symbol) {
            $id = trim((string) $symbol->getAttribute('id'));
            if ($id === '') {
                continue;
            }

            $width = trim((string) $symbol->getAttribute('width'));
            $height = trim((string) $symbol->getAttribute('height'));

            if ($width === '' || $height === '') {
                $viewbox = trim((string) $symbol->getAttribute('viewBox'));
                $parts = preg_split('/\s+/', $viewbox);
                if (is_array($parts) && count($parts) === 4) {
                    if ($width === '') {
                        $width = $parts[2];
                    }
                    if ($height === '') {
                        $height = $parts[3];
                    }
                }
            }

            $items[] = [
                'id' => $id,
                'width' => $width !== '' ? $width : '24',
                'height' => $height !== '' ? $height : '24',
            ];
        }

        if (!is_dir(dirname($json_path))) {
            wp_mkdir_p(dirname($json_path));
        }

        $written = file_put_contents(
            $json_path,
            json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        if ($written === false) {
            return ['ok' => false, 'message' => 'Impossible d\'ecrire icons.json.'];
        }

        return ['ok' => true, 'message' => count($items) . ' icones synchronisees.'];
    }
}

if (!function_exists('sg_get_icons_manifest')) {
    function sg_get_icons_manifest(): array
    {
        $icons_json_path = __DIR__ . '/components/icons.json';
        $icons_svg_path = rtrim(THEME_DIR, '/\\') . '/assets/img/icons.svg';
        $icons_refresh_notice = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refresh_icons_manifest'])) {
            $result = sg_build_icons_manifest($icons_svg_path, $icons_json_path);
            $icons_refresh_notice = $result['message'];
        }

        $icons_list = [];
        if (file_exists($icons_json_path)) {
            $decoded = json_decode((string) file_get_contents($icons_json_path), true);
            if (is_array($decoded)) {
                $icons_list = $decoded;
            }
        }

        return [
            'list' => $icons_list,
            'notice' => $icons_refresh_notice,
        ];
    }
}

if (!function_exists('sgIcons')) {
    function sgIcons(array $icons_list): void
    {
        if (empty($icons_list)) {
            echo '<p>Aucune donnée. Clique sur "Refresh icons".</p>';
            return;
        }

        echo '<ul class="sg-icons-list">';
        foreach ($icons_list as $icon) {
            $icon_id = isset($icon['id']) ? (string) $icon['id'] : '';
            $icon_w = isset($icon['width']) ? (string) $icon['width'] : '24';
            $icon_h = isset($icon['height']) ? (string) $icon['height'] : '24';
            if ($icon_id === '') continue;
            ?>
            <li class="sg-icons-item">
                <button
                    type="button"
                    class="sg-icon-pick"
                    data-icon-pick
                    data-icon-id="<?= esc_attr($icon_id) ?>"
                    data-icon-w="<?= esc_attr($icon_w) ?>"
                    data-icon-h="<?= esc_attr($icon_h) ?>"
                    aria-label="Sélectionner l'icône <?= esc_attr($icon_id) ?>"
                >
                    <div class="st-icon"><?php component::icon($icon_id, $icon_w, $icon_h); ?></div>
                    <div class="st-infos">
                        <b><?= esc_html($icon_id) ?></b>
                    </div>
                </button>
            </li>
            <?php
        }
        echo '</ul>';
    }
}

if (!function_exists('getIcons')) {
    /**
     * Render an icons <select> with the styleguide literal format:
     * value="['id', w, h]"
     */
    function getIcons(array $icons_list, string $data_param = 'icon', string $empty_label = 'Aucune'): void
    {
        ?>
        <select data-param="<?= esc_attr($data_param) ?>">
            <option value=""><?= esc_html($empty_label) ?></option>
            <?php foreach ($icons_list as $icon) :
                $icon_id = isset($icon['id']) ? (string) $icon['id'] : '';
                $icon_w = isset($icon['width']) ? (string) $icon['width'] : '20';
                $icon_h = isset($icon['height']) ? (string) $icon['height'] : '20';
                if ($icon_id === '') continue;
                $icon_value = sprintf("['%s', %s, %s]", $icon_id, $icon_w, $icon_h);
            ?>
                <option value="<?= esc_attr($icon_value) ?>"><?= esc_html($icon_id) ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
}

if (!function_exists('sg_component')) {
    /**
     * Include a styleguide component partial from `front/stylequide/components/`.
     * Usage: sg_component('btn')
     */
    function sg_component(string $name): void
    {
        $name = trim($name);
        if ($name === '' || !preg_match('/^[a-z0-9_-]+$/i', $name)) return;

        // Make common styleguide vars available to the included partial.
        // (Included files inherit the local function scope.)
        global $icons_list;

        $path = __DIR__ . '/components/' . $name . '.php';
        if (!file_exists($path)) return;
        include $path;
    }
}

if (!function_exists('sg_card')) {
    /**
     * Include a styleguide card partial from `front/stylequide/cards/`.
     * Usage: sg_card('news')
     */
    function sg_card(string $name): void
    {
        $name = trim($name);
        if ($name === '' || !preg_match('/^[a-z0-9_-]+$/i', $name)) return;

        // Make common styleguide vars available to the included partial.
        global $card_news, $icons_list;

        $path = __DIR__ . '/cards/' . $name . '.php';
        if (!file_exists($path)) return;
        include $path;
    }
}

if (!function_exists('sg_hero')) {
    /**
     * Include a styleguide hero partial from `front/stylequide/heros/`.
     * Usage: sg_hero('homepage')
     */
    function sg_hero(string $name): void
    {
        $name = trim($name);
        if ($name === '' || !preg_match('/^[a-z0-9_-]+$/i', $name)) return;

        // Make common styleguide vars available to the included partial.
        global $card_news, $icons_list;

        $path = __DIR__ . '/heros/' . $name . '.php';
        if (!file_exists($path)) return;
        include $path;
    }
}

if (!function_exists('sg_strate')) {
    /**
     * Include a styleguide strate partial from `front/stylequide/strates/`.
     * Usage: sg_strate('text')
     */
    function sg_strate(string $name): void
    {
        $name = trim($name);
        if ($name === '' || !preg_match('/^[a-z0-9_-]+$/i', $name)) return;

        // Make common styleguide vars available to the included partial.
        global $card_news, $icons_list;

        $path = __DIR__ . '/strates/' . $name . '.php';
        if (!file_exists($path)) return;
        include $path;
    }
}

if (!function_exists('sg_style')) {
    /**
     * Include a styleguide style partial from `front/stylequide/styles/`.
     * Usage: sg_style('typography')
     */
    function sg_style(string $name): void
    {
        $name = trim($name);
        if ($name === '' || !preg_match('/^[a-z0-9_-]+$/i', $name)) return;

        // Simple aliases (file names in FR vs calls in EN).
        $aliases = [
            'typography' => 'typographie',
        ];
        $resolved = $aliases[strtolower($name)] ?? $name;

        // Make common styleguide vars available to the included partial.
        global $icons_list;

        $path = __DIR__ . '/styles/' . $resolved . '.php';
        if (!file_exists($path)) return;
        include $path;
    }
}

// Backward-compatible alias (older calls).
if (!function_exists('sg_btn')) {
    function sg_btn(string $name = 'btn'): void
    {
        sg_component($name);
    }
}

// Allow requiring this file as a helper library (define functions only).
if (!isset($args) || !is_array($args)) return;

/**
 * Styleguide — Generic demo block
 *
 * @param string $args['type']               "card" | "hero" | "component"
 * @param string $args['label']              Titre affiché dans le <summary>
 * @param string $args['name']               Slug (ex: "card-news", "hero-homepage", "slider")
 * @param array  $args['items']              Tableau d'args — un rendu par item (prioritaire sur args/count)
 * @param array  $args['args']               Args passés au rendu (si pas de items)
 * @param int    $args['count']              Répétitions avec les mêmes args (défaut: 1)
 * @param string $args['cols']               Nombre de colonnes ex: "3" → "cols-3". Vide ou absent = pas de grille.
 * @param array  $args['options']['classes'] Checkboxes CSS [ ["title" => "...", "value" => "..."] ]
 */

$type       = $args['type']               ?? 'card';
$label      = $args['label']              ?? $args['name'] ?? '';
$name       = $args['name']               ?? '';
$items      = $args['items']              ?? null;
$pArgs      = $args['args']               ?? [];
$count      = $args['count']              ?? 1;
$colsRaw    = $args['cols']               ?? '';
$cols       = $colsRaw !== '' ? 'sg-grid cols-' . esc_attr($colsRaw) : '';
$full       = $args['full']               ?? false;
$optClasses = $args['options']['classes'] ?? [];
$optSelect  = $args['options']['select']  ?? [];

if (!$name) return;

$render = function (array $itemArgs) use ($type, $name) {
    switch ($type) {
        case 'hero':
            hero($name, $itemArgs);
            break;

        case 'card':
            component::card($name, $itemArgs);
            break;

        case 'component':
            get_template_part("template-parts/components/$name", null, $itemArgs);
            break;
    }
};
?>
