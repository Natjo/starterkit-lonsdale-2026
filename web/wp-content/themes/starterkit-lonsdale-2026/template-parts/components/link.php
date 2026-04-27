<?php
$link = !empty($args["link"]) ? $args["link"] : [];
$target = !empty($link["target"]) && $link["target"] != "" ? ' target="_blank"' : '';
$classes = !empty($args["classes"]) ? $args["classes"] : "";
$icon_html = "";
if (!empty($args["icon"])) {
    $icon = $args["icon"];
    $name = (string) $icon[0];
    $width = isset($icon[1]) ? (float) $icon[1] : 20;
    $height = isset($icon[2]) ? (float) $icon[2] : 20;
    if ($width <= 0) $width = 20;
    if ($height <= 0) $height = 20;

    ob_start();
    component::icon($name, $width, $height);
    $icon_html = (string) ob_get_clean();
}
$attributes = !empty($args["attributes"]) ?  $args["attributes"] : "";

$title = !empty($icon_html) ? "<span>" . esc_html($link["title"]) . "</span>" : esc_html($link["title"]);
?>

<a href="<?= esc_url($link["url"] ?? "") ?>" class="link <?= esc_attr($classes) ?>" <?= $attributes . $target ?>><span><?= $title ?></span><?= $icon_html ?></a>