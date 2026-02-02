<?php
$text = $args["text"];
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
?>

<div class="text<?= $classes ?>">
    <?= $text ?>
</div>