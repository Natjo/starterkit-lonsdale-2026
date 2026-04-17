<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="icon" type="image/png" href="<?= THEME_ASSETS ?>favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?= THEME_ASSETS ?>favicon/favicon.svg" />
    <link rel="shortcut icon" href="<?= THEME_ASSETS ?>favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?= THEME_ASSETS ?>favicon/apple-touch-icon.png" />
    <link rel="manifest" href="<?= THEME_ASSETS ?>favicon/site.webmanifest" />

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <meta name='HandheldFriendly' content='true' />
    <meta name='format-detection' content='telephone=no' />
    <meta name="msapplication-tap-highlight" content="no">

    <link rel="preload" href="<?= THEME_ASSETS ?>fonts/BouyguesRead-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= THEME_ASSETS ?>fonts/BouyguesRead-Bold.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= THEME_ASSETS ?>fonts/bouygues-speak.woff2" as="font" type="font/woff2" crossorigin>

    <?php wp_head(); ?>

    <?php styles(); ?>

    <?php if (is_page_template('front/page-styleguide.php')) : ?>
        <link rel="stylesheet" href="<?= THEME_ASSETS ?>styles/styleguide.css">
    <?php endif ?>

    <?php appjs(); ?>
</head>

<body <?php body_class(); ?>>
    
<?php 
/**
 * Do not remove
 * need to get styles from module/strates/components
 */
ob_start(); 
?>
