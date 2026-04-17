<?php

$nonce = wp_create_nonce('sm_nonce');

?>

<link rel='stylesheet' id='wp-block-library-css' href="<?= wp_guess_url() ?>/wp-content/plugins/smart-compress/styles.css" media='all' />
<div class="wrap" id="sc-main" data-nonce="<?= $nonce ?>" data-ajaxurl="<?= AJAX_URL ?>">
    <?php if ($hasimagick) : ?>
        <div class="sc-content">
            <header>
                <h1>Smart Compress</h1>
            </header>

            <section>
                <h2>Qualité des images <button class="sc-btn-tips" value="quality">?</button></h2>

                <div class="sc-choices">
                    <input id="sc-quality-1" type="radio" name="sc-quality" value="1" <?= $sc_quality == 1 ? "checked" : "" ?>><label for="sc-quality-1"><span>Best</span></label>
                    <input id="sc-quality-0" type="radio" name="sc-quality" value="0" <?= $sc_quality == 0 ? "checked" : "" ?>><label for="sc-quality-0"><span>Optimal</span></label>
                    <input id="sc-quality-2" type="radio" name="sc-quality" value="2" <?= $sc_quality == 2 ? "checked" : "" ?>><label for="sc-quality-2"><span>Green</span></label>
                </div>
            </section>

            <section>
                <h2>Generate Uploads <button class="sc-btn-tips" value="uploads">?</button></h2>

                <form action="test" class="sc-generate">
                    <ul class="sc-choices ">

                        <?php foreach ($sc_last_generate as $year => $item) : ?>
                            <?php
                            $last =  "";
                            if (!empty($item['date'])) {
                                $date =  new DateTimeImmutable($item['date']);
                                $last = $date->format('d M Y à H:i');
                            }
                            ?>
                            <li>
                                <input id="sc-year-<?= $year ?>" <?= $item['active'] == 1 ?  ' checked="true"' : '' ?> type="checkbox" name="sc-year[]" value="<?= $year ?>"><label for="sc-year-<?= $year ?>"><span><?= $year ?></span></label><small>- <?= $last ?></small>
                            </li>
                        <?php endforeach ?>
                    </ul>

                    <button type="button" class="sc-btn btn-generate">Generate</button>
                </form>
            </section>

            <section>
                <h2>Developper <button class="sc-btn-tips" value="developper">?</button></h2>
            </section>
        </div>

        <div class="sc-tips">
            <div class="sc-tips-panel" data-page="quality">
                <h2>Qualité de l'image</h2>
                <p class="sc-text quality-0">Un rendu un peu dégradé pour un maximum de performance, en <b>X2</b> le resultat est <b>quasi identique</b> et <b>2X</b> plus léger </p>
                <p class="sc-text quality-1">Un rendu optimal - site corporate</p>
                <p class="sc-text quality-2">Un rendu des images de qualité et une compression imperceptible</p>
                <p class="sc-text quality-3">Un rendu fin et detaillé si les images contiennent beaucoup de texte</p>
                <p>
                    Les images compressées on différents paramètres:<br>
                    - <b>Compression</b>, taux de compression allant de 0 à 100%.<br>
                    - <b>Blur</b> détermine si le rendu doit être plus net ou plus flou au resize.<br>
                    - <b>Radius</b> et <b>Sigma</b> qui ajoute via l'effet <b>sharpenImage</b> la possibilité de rendre l'image plus net<br>
                </p>
                <br>

                <h3>best</h3>
                <p>
                    compression = 80%<br>
                    blur = 0.96<br>
                    radius = 1<br>
                    sigma = .8
                </p>

                <h3>Optimal</h3>
                <p>
                    compression = 70% <br>
                    blur = 1<br>
                    radius = .8<br>
                    sigma = .6
                </p>

                <h3>Green</h3>
                <p>
                    compression = 50% <br>
                    blur = 1<br>
                    radius = 0<br>
                    sigma = 0
                </p>
            </div>

            <div class="sc-tips-panel" data-page="type">
                <h2>Type de génération d'image</h2>

                <h3>On upload</h3>
                <p>Génère les webps à l'upload ou via un plugin de regénération comme <b>Regenerate Thumbnails</b></p>

                <h3>On the fly</h3>
                <p>Génère les webp à la volée. Les images ne seront pas générées à l'upload</p>
            </div>


            <div class="sc-tips-panel" data-page="uploads">
                <h2>Génération des webps dans le dosssier uploads</h2>
                <p>
                    Création des images du dossier uploads au format webp en fonction des années.
                </p>

            </div>



            <div class="sc-tips-panel" data-page="developper">
                <h2>Developper</h2>



                <p>
                    Le <b>plugins</b> va créer une image au format webp aux mêmes tailles définies avec <code>add_image_size</code>.<br>
                </p>

                <p>
                    On récupère les vraies tailles définies par Wordpress lors de la génération via <code>wp_get_attachment_metadata</code>, qui peuvent être différentes de celles définient dans <code>add_image_size</code> en fonction de la taille de l'image uploadée.
                </p>

                <p>
                    L'image généré sera dans le même dossier et aura le même nom:<br>

                    2024/09/myimage-300x200<b>.jpg</b><br>
                    2024/09/myimage-300x200<b>.webp</b>
                </p>

                <p>
                    On générera les images webp via le hook <code>wp_generate_attachment_metadata</code>, à chaque upload dans médias ou dans les fields ...<br>
                    Et avec le hook <code>wp_delete_file</code> appelé à chaque effacement, on effacera les webp correspondant

                </p>


                </p>
                <br>

                <hr>
                <div>
                    <h3>Purger les images webp du dossier upload</h3>
                    <button class="sc-btn btn-purge">Purge</button>
                </div><br>
                <hr>
                <div>
                    <h3>Génération des webps dans les assets</h3>

                    <p>
                        Génére les images des assets au format webp dans le dossier <b>/assets/img/webp/</b>
                    </p>

                    <button class="sc-btn btn-assets">Generate</button>
                </div>
            </div>
        </div>

        <div class="sc-dialog">
            <div class="sc-dialog-box">
                <h3 class="title">Purging</h3>

                <progress id="sc-progress" value="0" max="100"></progress>
                <small id="sc-progress-number">0/0</small>

                <div class="sc-action">
                    <button class="sc-btn pause">Pause</button>
                    <button class="sc-btn cancel">Cancel</button>
                    <button class="sc-btn close">Close</button>
                </div>
            </div>

        </div>

        <div class="sc-image">
            <h1>Upload and convert image</h1>

            <div class="sc-image-image">
                <img src="<?= wp_guess_url() ?>/wp-content/plugins/smart-compress/img/landscape-webp-600x592.webp" alt="">
            </div>

            <div class="sc-image-control">
                <div class="sc-image-action">
                    <button class="sc-image-upload">upload</button>
                    <button class="sc-image-download">download</button>
                </div>

                <div class="sc-image-quality">
                    <label>Qualité</label>
                    <input type="range">

                    <label>Sharpe</label>
                    <input type="range">
                    <input type="range">
                </div>

            </div>
        </div>

    <?php else : ?>
        <div class="sc-content">
            <header>
                <h1>Smart Compress</h1>
                <div class="sc-alert">
                    <img src="<?= wp_guess_url() ?>/wp-content/plugins/smart-compress/img/alert.svg" alt="">
                    <p>Le module php Imagick n'est pas installé sur ce serveur</p>
                </div>
            </header>
        </div>
    <?php endif ?>

</div>

<script src="<?= wp_guess_url() ?>/wp-content/plugins/smart-compress/app.js"></script>