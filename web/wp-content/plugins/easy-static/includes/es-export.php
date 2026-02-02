<?php


global $table;

$easy_static_slug = $wpdb->get_results("SELECT * FROM " . $table  . " WHERE es_option = 'slug'");
$nonce = wp_create_nonce('test_nonce');

?>

<section id="export" class="tab-content">

    <section>
        <header>
            <h2>Exporter le site statique</h2>
        </header>

        <div style="display: flex">
            <span style="">https://www.mywebsite.com/</span>
            <p class="fake-input" contenteditable="true" id="es-relative" translate="no"><?= $easy_static_slug[0]->sc_value ?></p><span style="opacity: .6;"><span class="es-notroot">/</span></span>
        </div>
        <br>
        <div>
            <button class="es-btn" id="es-download-pages"><span>Exporter</span></button>
            &nbsp; &nbsp;<a class="es-link-upload" id="es-download-uploads" href="" download>Download export</a>
        </div>

    </section>

</section>