<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$classes .= (!empty($args["center"]) || !empty($args["title_center"])) ? " is_title_center" : '';
$hx = "h" . $args["hx"];
?>

<<?= $hx; ?> class="title<?= $classes; ?>"><?= $args['title']; ?></<?= $hx; ?>>