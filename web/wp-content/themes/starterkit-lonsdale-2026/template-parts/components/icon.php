<?php

$width = $args["width"];
$height = $args["height"];
$name =  $args["name"];
$url = $args["url"];
$classes = $args["classes"];

?>
<svg class="icon<?= !empty($classes) ? " " . $classes : "" ?>" width="<?= $width ?>" height="<?= $height ?>" aria-hidden="true" viewBox="0 0 <?= $width ?> <?= $height ?>">
    <use xlink:href="<?= $url ?>img/icons.svg#<?= $name ?>"></use>
</svg>