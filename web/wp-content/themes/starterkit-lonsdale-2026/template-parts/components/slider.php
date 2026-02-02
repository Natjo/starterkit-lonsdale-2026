<?php
$navigation = !empty($args["navigation"]) ? true : false;
$pagination = !empty($args["pagination"]) ? true : false;
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";

?>

<div class="slider<?= $classes ?>">
    <?php if ($navigation) : ?>
        <div class="slider-navigation">
            <button class="slider-btn prev"><?= component::icon("prev", 8, 12.57) ?></button>

            <button class="slider-btn next"><?= component::icon("next", 8, 12.57) ?></button>
        </div>
    <?php endif ?>

    <div class="slider-wrapper">
        <ul class="slider-content" role="list">
            <?php foreach ($args["items"] as $item) : ?>
                <li class="item" role="listitem">
                    <?= component::card($args["card"], $item) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>