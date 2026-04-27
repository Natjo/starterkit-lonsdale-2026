<?php

require_once __DIR__ . '/../part.php';
$icons_manifest = function_exists('sg_get_icons_manifest') ? sg_get_icons_manifest() : ['list' => [], 'notice' => ''];
$icons_list = $icons_manifest['list'] ?? [];
$icons_refresh_notice = $icons_manifest['notice'] ?? '';

?>

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" />
<link rel="stylesheet" href="<?= THEME_URL ?>front/stylequide/styles.css" />

<header class="sg-header">
    <h1>Styleguide</h1>
</header>