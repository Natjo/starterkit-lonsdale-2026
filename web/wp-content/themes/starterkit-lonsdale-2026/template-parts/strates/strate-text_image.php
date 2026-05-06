<?php
$reverse = !empty($args["is_reverse"]) ? " reverse" : "";
$title_col = !empty($args["is_title_col"]) ? true : false;
?>

<section <?= options("strate strate-text_image" . $reverse . ($title_col ? " title_col" : ""), $args) ?>>

    <header class="header">
        <?php component::title($args, 2, "title-2") ?>
    </header>

    <?php component::picture($args); ?>

    <div class="strate-content">
        <?php component::title($args, 2, "title-2 title-col") ?>

        <?php component::text($args, "rte") ?>

        <?php component::btn($args, "cta btn-1") ?>
    </div>

</section>