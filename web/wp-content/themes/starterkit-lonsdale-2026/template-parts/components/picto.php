<?php

?>

<?php
$size = isset($args['size']) ? trim((string) $args['size']) : '';
$animate = !empty($args['animate']);
$size_class = $size !== '' ? ' ' . esc_attr($size) : '';
?>

<div class="picto<?= $size_class ?><?= $animate ? " animate" : "" ?>">

    <?php component::icon($args['name'], 24, 24) ?>
  
</div>