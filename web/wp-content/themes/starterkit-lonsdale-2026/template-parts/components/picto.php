<?php
$size = isset($args['size']) ? trim((string) $args['size']) : '';
$classes = isset($args['classes']) ? trim((string) $args['classes']) : '';
$attributes = isset($args['attributes']) ? trim((string) $args['attributes']) : '';
$size_class = $size !== '' ? ' ' . esc_attr($size) : '';
$extra_class = $classes !== '' ? ' ' . esc_attr($classes) : '';
?>

<div class="picto<?= $size_class . $extra_class ?>"<?= $attributes !== '' ? ' ' . $attributes : '' ?>>
    <?php component::icon($args['name'], 24, 24) ?>
</div>