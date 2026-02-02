<?php
$name = $args["name"];
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
?>

<div class="tag<?= $classes ?>">
    <?= $name ?>
</div>