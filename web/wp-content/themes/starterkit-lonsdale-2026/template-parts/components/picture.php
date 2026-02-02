<?php

$desktop = [];
if (!empty($args["desktop"])) {
    $size = !empty($args["desktop_size"]) ? $args["desktop_size"] : "full";
    $image = lsd_get_thumb($args["desktop"],  $size);
    $desktop["src"] = $image[0];
    $desktop["width"] = $image[1];
    $desktop["height"] = $image[2];
    $desktop["alt"] = $image[3];
    $desktop["webp"] = hasWebp($image);
}

$mobile = [];
if (!empty($args["mobile"])) {
    $size = !empty($args["mobile_size"]) ? $args["mobile_size"] : "full";
    $image = lsd_get_thumb($args["mobile"], $size);
    $mobile["src"] = $image[0];
    $mobile["width"] = $image[1];
    $mobile["height"] = $image[2];
    $mobile["alt"] = $image[3];
    $mobile["webp"] = hasWebp($image);
}

$breakpoint = $args['breakpoint'];
$lazy = !empty($args["lazy"]) ? ' loading="lazy"' : "";
$alt = !empty($desktop["alt"]) ? ' alt="' . $desktop["alt"] . '"' : 'alt=""';
$classes = !empty($args["classes"]) ? ' class="' . $args["classes"] . '"' : "";

$media = "";
if (!empty($mobile)) {
    $media = ' media="(min-width:' . $breakpoint . 'px)"';
    $media_mobile = ' media="(max-width:' . ($breakpoint - 1) . 'px)"';
}

$placeholder = !empty($args["placeholder"]) ? true : false;

$priority = $lazy ? ' fetchpriority="low"' : ' fetchpriority="high"';


?>

<picture <?= $classes ?>>
    <?php if ($mobile) : ?>
        <?php if (!empty($mobile["webp"])) : ?>
            <source width="<?= $mobile["width"] ?>" height="<?= $mobile["height"] ?>" srcset="<?= $mobile["webp"] ?>" <?= $media_mobile ?> type="image/webp">
        <?php endif ?>

        <source width="<?= $mobile["width"] ?>" height="<?= $mobile["height"] ?>" srcset="<?= $mobile["src"] ?>" <?= $media_mobile ?> type="image/jpg">
    <?php endif ?>

    <?php if ($desktop) : ?>
        <?php if ($desktop["webp"]) : ?>
            <source width="<?= $desktop["width"] ?>" height="<?= $desktop["height"] ?>" srcset="<?= $desktop["webp"] ?>" <?= $media ?> type="image/webp">
        <?php endif ?>

        <source width="<?= $desktop["width"] ?>" height="<?= $desktop["height"] ?>" srcset="<?= $desktop["src"] ?>" <?= $media ?> type="image/jpg">
    <?php endif ?>

    <img src="<?= $desktop["src"] ?>" <?= $alt ?> width="<?= $desktop["width"] ?>" height="<?= $desktop["height"] ?>" <?= $lazy .$priority?>>
</picture>