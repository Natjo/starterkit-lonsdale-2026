<?php
$link = !empty($args["link"]) ? $args["link"] : [];
$target = !empty($link["target"]) && $link["target"] != "" ? ' target="_blank"' : '';
$classes = !empty($args["classes"]) ? $args["classes"] : "";
$icon = !empty($args["icon"]) ? '<span aria-hidden="true" class="material-icons">' . $args["icon"] . '</span>' : "";
$attributes = !empty($args["attributes"]) ?  $args["attributes"] : "";
?>

<a href="<?= $link["url"] ?>" class="nj-link <?= $classes ?>" <?= $attributes . $target ?>><?= $link["title"] ?><?= $icon ?></a>