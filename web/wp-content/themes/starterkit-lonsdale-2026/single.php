<?php
$postype = get_post_type();

if ($postype == 'news') {
    get_template_part('pages/single-actu');
} elseif ($postype == 'solutions') {
    get_template_part('pages/single-solution');
} else {
    wp_redirect(home_url(), 301);
    exit;
}
?>