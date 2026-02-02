<?php
// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
// Add a new top level menu link to the ACP
add_action('admin_menu', function () {
    add_menu_page(
        'Smart Compress', // Title of the page
        'Smart Compress', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'smart-compress/includes/sc-index.php', // The 'slug' - file to display when clicking the link
        '',
        'dashicons-performance',
    );
});
