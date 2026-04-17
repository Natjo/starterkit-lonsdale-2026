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
        $icons_json_path = __DIR__ . '/icons.json';
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
        ?>
        <table class="sg-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Preview</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>PHP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($icons_list as $icon) :
                    $icon_id = isset($icon['id']) ? (string) $icon['id'] : '';
                    $icon_w = isset($icon['width']) ? (string) $icon['width'] : '24';
                    $icon_h = isset($icon['height']) ? (string) $icon['height'] : '24';
                    $icon_php = sprintf('component::icon("%s", %s, %s);', $icon_id, $icon_w, $icon_h);
                    $icon_php_copy = sprintf('<?= %s ?>', $icon_php);
                    if ($icon_id === '') continue;
                ?>
                    <tr>
                        <td><strong><?= esc_html($icon_id) ?></strong></td>
                        <td class="center"><?php component::icon($icon_id, $icon_w, $icon_h); ?></td>
                        <td><small><?= esc_html($icon_w) ?></small></td>
                        <td><small><?= esc_html($icon_h) ?></small></td>
                        <td>
                            <code class="sg-code-inline" data-syntax="php"><?= esc_html($icon_php) ?></code>
                            <button
                                type="button"
                                class="sg-copy-btn"
                                data-copy="<?= esc_attr($icon_php_copy) ?>"
                                aria-label="Copier le code PHP de l'icône <?= esc_attr($icon_id) ?>"
                            >Copier</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
}
