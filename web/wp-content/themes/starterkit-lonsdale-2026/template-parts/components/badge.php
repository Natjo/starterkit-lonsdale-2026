<?php
$name = !empty($args["name"]) ? trim((string) $args["name"]) : "";
if ($name === "") return;
$classes = !empty($args["classes"]) ? " " . (string) $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? (string) $args["attributes"] : "";
?>

<div class="badge<?= esc_attr($classes) ?>" <?= $attributes ?>>
    <?= esc_html($name) ?>
</div>