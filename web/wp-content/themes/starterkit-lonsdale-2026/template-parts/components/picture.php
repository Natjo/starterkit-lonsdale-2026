<?php
$desktop = $args["desktop"] ?? [];
$mobile  = $args["mobile"]  ?? [];

$breakpoint = $args["breakpoint"];
$lazy = !empty($args["lazy"]) ? ' loading="lazy"' : "";
$priority = $lazy ? ' fetchpriority="low"' : ' fetchpriority="high"';
$alt = !empty($desktop["alt"]) ? ' alt="' . esc_attr($desktop["alt"]) . '"' : 'alt=""';
$classes = !empty($args["classes"]) ? ' class="' . esc_attr($args["classes"]) . '"' : "";
$media = $mobile ? ' media="(min-width:' . $breakpoint . 'px)"' : "";
$media_mobile = $mobile ? ' media="(max-width:' . ($breakpoint - 1) . 'px)"' : "";

$size_attr = function ($img) {
    $w = (int) ($img["width"] ?? 0);
    $h = (int) ($img["height"] ?? 0);
    if ($w <= 0 || $h <= 0) return '';
    return ' width="' . $w . '" height="' . $h . '"';
};
?>
<picture<?= $classes ?>>
    <?php if ($mobile) : ?>
        <?php if (!empty($mobile["webp"])) : ?>
            <source<?= $size_attr($mobile) ?> srcset="<?= $mobile["webp"] ?>" <?= $media_mobile ?> type="image/webp">
        <?php endif ?>
        <source<?= $size_attr($mobile) ?> srcset="<?= $mobile["src"] ?>" <?= $media_mobile ?> type="image/jpg">
    <?php endif ?>

    <?php if ($desktop) : ?>
        <?php if (!empty($desktop["webp"])) : ?>
            <source<?= $size_attr($desktop) ?> srcset="<?= $desktop["webp"] ?>" <?= $media ?> type="image/webp">
        <?php endif ?>
        <source<?= $size_attr($desktop) ?> srcset="<?= $desktop["src"] ?>" <?= $media ?> type="image/jpg">
    <?php endif ?>

    <img src="<?= $desktop["src"] ?>" <?= $alt ?><?= $size_attr($desktop) ?> <?= $lazy . $priority ?>>
</picture>