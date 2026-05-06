<section <?= options("strate strate-slider", $args) ?> data-module="strates/strate-slider" data-context="@visible true">
    <?php component::title($args, 1, "") ?>

    <?php component::slider($args["items"], "card-news", null, true, true); ?>
</section>