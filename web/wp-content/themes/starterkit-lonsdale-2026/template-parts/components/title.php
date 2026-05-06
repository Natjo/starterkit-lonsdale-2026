<?php
$args = isset($args) && is_array($args) ? $args : [];
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$classes .= (!empty($args["center"]) || !empty($args["title_center"])) ? " is_title_center" : '';
$hx = "h" . $args["hx"];
$attributes = !empty($args["attributes"]) ? (string) $args["attributes"] : "";
?>

<<?= $hx; ?> class="title<?= $classes; ?>" <?= $attributes ?>><?= $args['title']; ?></<?= $hx; ?>>