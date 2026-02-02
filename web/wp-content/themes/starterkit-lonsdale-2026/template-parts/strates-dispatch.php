<?php

$strates = get_field('strates');
if (!empty($strates)) {
    foreach ($strates as $strate) {
        strate($strate['acf_fc_layout'], $strate);
    }
}
