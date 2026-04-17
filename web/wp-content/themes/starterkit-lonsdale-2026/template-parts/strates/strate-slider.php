<section <?= options("strate strate-slider", $args) ?> data-module="strates/strate-slider" data-context="@visible true">
    <?= component::title($args, 1, "") ?>

    <?= component::slider($args["items"], "card-news", null, true, true); ?>
</section>