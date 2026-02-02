<?php
$args["images"]["desktop_size"] = "764_620";
?>

<header class="hero hero-page">
    <div class="hero-content grid">
        <?= component::title($args, 1, "title-1"); ?>

        <?= component::text($args, "intro"); ?>
    </div>

    <?= component::picture($args); ?>
</header>