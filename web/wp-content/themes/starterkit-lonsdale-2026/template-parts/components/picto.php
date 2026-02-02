<?php
$size = !empty($args["size"]) ? " " . $args["size"] : '';
$animate = !empty($args["animate"]) ? true : false;
?>

<div class="picto<?= $size ?><?= $animate ? " animate" : "" ?>">
    <?= component::image($args['name']); ?>
    
    <?php if ($animate) : ?>
        <svg class="waves" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="var(--waves)">
            <circle class="circle-1" cx="150" cy="150" r="48" stroke-width="2.5" />

            <circle class="circle-2" cx="150" cy="150" r="100" stroke-width="2" />

            <circle class="circle-3" cx="150" cy="150" r="149" />
        </svg>
    <?php endif ?>
</div>