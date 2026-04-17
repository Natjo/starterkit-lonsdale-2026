<?php
$reverse = !empty($args["is_reverse"]) ? " reverse" : "";
$title_col = !empty($args["is_title_col"]) ? true : false;
?>

<section <?= options("strate strate-text_image" . $reverse . ($title_col ? " title_col" : ""), $args) ?>>

    <header class="header">
        <?= component::title($args, 2, "title-2") ?>
    </header>

    <?= component::picture($args); ?>

    <div class="strate-content">
        <?= component::title($args, 2, "title-2 title-col") ?>

        <?= component::text($args, "rte") ?>

        <?= component::btn($args, "cta btn-1") ?>
    </div>

</section>