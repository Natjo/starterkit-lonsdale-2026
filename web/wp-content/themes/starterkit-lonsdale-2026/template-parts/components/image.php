<?php
$size = $args["size"];
$image = lsd_get_thumb($args["image"],  $size);

$src = $image[0];
$width = $image[1];
$height = $image[2];
$alt = $image[3];
$ext = pathinfo($src)['extension'];
$lazy = !empty($args["lazy"]) ? ' loading="lazy"' : "";
$alt = !empty($alt) ? ' alt="' . $alt . '"' : 'alt=""';
if ($ext == "svg") $alt = "";
$classes = !empty($args["classes"]) ? ' class="' . $args["classes"] . '"' : "";

$webp = hasWebp($image);
if ($webp) {
    $src = $webp;
}


?>

<img <?= $classes ?> src="<?= $src ?>" <?= $alt ?> width="<?= $width ?>" height="<?= $height ?>" <?= $lazy ?>>