<?php
$name = !empty($args["name"]) ? $args["name"] : "";
$link = !empty($args["link"]) ? $args["link"] : null;
$target = !empty($link["target"]) && $link["target"] != "" ? ' target="_blank"' : '';
$classes = !empty($args["classes"]) ? " " . $args["classes"] : "";
$attributes = !empty($args["attributes"]) ?  $args["attributes"] : "";
$icon =  !empty($args["icon"]) ? $args["icon"] : false;
?>

<?php if ($link) : ?>
    <a href="<?= $link["url"] ?>" class="btn <?= $classes ?>" <?= $attributes . $target ?>><?= $icon ? component::icon(...$icon) : "" ?><?=  $link["title"] ?></a>
<?php else : ?>
    <button class="btn <?= $classes ?>" <?= $attributes ?>><?= $icon ? component::icon(...$icon) : "" ?><span><?= $name ?></span></button>
<?php endif ?>