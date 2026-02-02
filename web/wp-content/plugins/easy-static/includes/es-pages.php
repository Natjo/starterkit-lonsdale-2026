<section id="pages" class="tab-content">
    <header>
        <h2>Générer les pages du sites</h2>
    </header>

    <section>
        <button class="es-btn plug-static-btn-generate"><span>Generate</span></button>

        <?php if (!empty($last_generate)) : ?>
            <span class="es-last_generated"> Last generated : <?= date("j F Y h:i:s", strtotime($last_generate)) ?></span>
        <?php endif; ?>
    </section>

    <?php if (ENV_PREPROD_LONSDALE) : ?>
        <section>
            <header>
                <h2>Htaccess preprod</h2>
            </header>

            <div class="es-auth">
                <div>
                    <label for="">User</label>
                    <input type="text" id="es-auth-user" value="<?= $authentification["user"] ?>">
                </div>

                <div>
                    <label for="">Password</label>
                    <input type="password" id="es-auth-password" value="<?= $authentification["password"] ?>">
                </div>
            </div>
        </section>

        <hr>
    <?php endif; ?>

    <section>
        <header>
            <h2>Options</h2>
        </header>

        <ul>
            <li><input id="es-option-minify" type="checkbox" <?= $isminify === true ? "checked" : "" ?>><label>Compresser les pages générées</label></li>
        </ul>
        <ul>
            <li><input id="es-option-auto" type="checkbox" <?= $isauto === true ? "checked" : "" ?>><label>Génération automatique</label></li>
        </ul>

   
    </section>
    <!--     <br>

    <section>
        <ul>
            <li>- Les <b>posts/page</b> sont regenerés automatiquement à la publication</li>
            <li>- La modification d'un <b>menu</b> regénère toutes les pages</li>
            <li>- La modification d'un <b>paramètre</b> regénère toutes les pages</li>
            <li>- La modification d'un <b>réglage</b> regénère toutes les pages</li>
        </ul>
        <br><br>
        <div style="background: rgba(255, 208, 0, 0.12);;border: 1px solid rgba(255, 208, 0, 1); padding: 5px 20px;">
            <p style="font-weight: 800;margin-bottom:0;">
                Pour toutes modfications autres, il faudra re générer toute les pages :
            </p>

            <ul>
                <li>- Regenerer les miniatures</li>
                <li>- Mise à jour Worpress</li>
                <li>- Changement des fichiers php</li>
            </ul>
        </div>

    </section> -->
</section>