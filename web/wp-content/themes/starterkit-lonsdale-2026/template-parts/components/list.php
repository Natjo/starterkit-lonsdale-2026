<?php
$args = (isset($args) && is_array($args)) ? $args : [];
$items = !empty($args["items"]) && is_array($args["items"]) ? $args["items"] : [];
$card = !empty($args["card"]) ? (string) $args["card"] : "news";
$classes = !empty($args["classes"]) ? " " . (string) $args["classes"] : "";

if (empty($items)) return;
?>

<ul class="list<?= esc_attr($classes) ?>" role="list">
    <?php foreach ($items as $item) : ?>
        <li class="item">
            <?php component::card($card, $item) ?>
        </li>
    <?php endforeach; ?>
</ul>
