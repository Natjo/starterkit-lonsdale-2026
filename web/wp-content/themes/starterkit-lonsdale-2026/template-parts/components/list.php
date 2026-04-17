<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$card = !empty($args["card"]) ? $args["card"] : "card-news";
?>
<ul class="list<?= $classes ?>">
    <?php foreach ($args["items"] as $item) : ?>
        <li>
            <?php if (is_array($item)) : ?>
                <?= component::card($card, $item) ?>
            <?php else : ?>
                <?= esc_html((string) $item) ?>
            <?php endif ?>
        </li>
    <?php endforeach ?>
</ul>

