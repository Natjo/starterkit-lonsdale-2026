<?php

/**
 * Do not remove
 * need to get styles from module/strates/components
 */

$content = ob_get_clean(); ?>

<?php get_header(); ?>
<?= $content; ?>
<?php get_footer(); ?>
