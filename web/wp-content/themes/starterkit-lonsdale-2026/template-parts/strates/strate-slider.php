<section  <?= options("strate strate-slider", $args["options"]) ?> data-module="strates/strate-slider" data-context="@visible true">
  <div class="grid">

    <?= component::title($args, 1, "") ?>

    <?= component::slider($args["items"], "card-news"); ?>

  </div>
</section>