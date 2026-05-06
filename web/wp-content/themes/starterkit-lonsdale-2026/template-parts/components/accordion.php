<?php
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? $args["attributes"] : "";
?>

<div class="accordion<?= $classes ?>" data-module="components/accordion">
    <?php foreach ($args["items"] as $item) : ?>
        <?php $uniqid = uniqid(); ?>
        <div class="details">
            <h2 id="summary-<?= $uniqid ?>">
                <button class="summary" aria-expanded="false" aria-controls="panel-<?= $uniqid ?>"><?= $item["title"] ?><?= component::icon("caret",12,7) ?></button>
            </h2>

            <div id="panel-<?= $uniqid ?>" class="details-content" role="region" aria-labelledby="summary-<?= $uniqid ?>" aria-hidden="true">
                <div class="text rte">
                    <?= $item["text"] ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>