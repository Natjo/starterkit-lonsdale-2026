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

?>
<picture<?= $classes ?>>
    <?php if ($mobile) : ?>
        <?php if (!empty($mobile["webp"])) : ?>
            <source width="<?= $mobile["width"] ?>" height="<?= $mobile["height"] ?>" srcset="<?= $mobile["webp"] ?>" <?= $media_mobile ?> type="image/webp">
        <?php endif ?>
        <source width="<?= $mobile["width"] ?>" height="<?= $mobile["height"] ?>" srcset="<?= $mobile["src"] ?>" <?= $media_mobile ?> type="image/jpg">
    <?php endif ?>

    <?php if ($desktop) : ?>
        <?php if (!empty($desktop["webp"])) : ?>
            <source width="<?= $desktop["width"] ?>" height="<?= $desktop["height"] ?>" srcset="<?= $desktop["webp"] ?>" <?= $media ?> type="image/webp">
        <?php endif ?>
        <source width="<?= $desktop["width"] ?>" height="<?= $desktop["height"] ?>" srcset="<?= $desktop["src"] ?>" <?= $media ?> type="image/jpg">
    <?php endif ?>

    <img src="<?= $desktop["src"] ?>" <?= $alt ?> width="<?= $desktop["width"] ?>" height="<?= $desktop["height"] ?>" <?= $lazy . $priority ?>>
</picture>