<?php
$navigation  = !empty($args["navigation"]) ? true : false;
$pagination  = !empty($args["pagination"]) ? true : false;
$classes     = !empty($args["classes"]) ? " " . $args["classes"] : "";
$label       = !empty($args["label"]) ? $args["label"] : __("Carrousel", "starterkit");
$slider_id   = "slider-" . wp_unique_id();
$status_id   = $slider_id . "-status";
?>

<div class="slider<?= $classes ?>" role="region" aria-label="<?= esc_attr($label) ?>">

    <?php if ($navigation) : ?>
        <div class="slider-navigation" aria-hidden="true">
            <button class="slider-btn prev" tabindex="-1">
                <?= component::icon("prev", 8, 12.57) ?>
            </button>
            <button class="slider-btn next" tabindex="-1">
                <?= component::icon("next", 8, 12.57) ?>
            </button>
        </div>
    <?php endif ?>

    <div class="slider-wrapper">
        <ul class="slider-content" role="list" aria-label="<?= esc_attr($label) ?>" datad-lenis-prevent-wheel>
            <?php foreach ($args["items"] as $item) : ?>
                <li class="item">
                    <?= component::card($args["card"], $item) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <?php if ($pagination) : ?>
        <nav class="slider-pagination" aria-label="<?= esc_attr__("Navigation du carrousel", "starterkit") ?>" data-slider-pagination></nav>
    <?php endif ?>

    <div id="<?= esc_attr($status_id) ?>" class="sr-only" aria-live="polite" aria-atomic="true" data-slider-status></div>
</div>