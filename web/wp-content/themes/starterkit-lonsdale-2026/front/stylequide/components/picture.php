<?php
$image_sizes = function_exists('get_intermediate_image_sizes') ? get_intermediate_image_sizes() : [];
$image_sizes = array_values(array_unique(array_merge(['full'], (array) $image_sizes)));
?>
<sg-part type="component" tag="html" label="Picture" name="picture">
    <code class="sg-code-inline" data-syntax="php">
        picture($args, $sizes = "full", $classes = "", $lazy = true, $placeholder = false, $breakpoint = 768)
    </code>

    <div>Créer un element picture avec un id de l'image, un chemin de l'image ou un tableau d'arguments. </div>

    <div class="sg-components-builder">
        <table class="sg-table">
            <thead>
                <tr>
                    <th>Arg</th>
                    <th>Description</th>
                    <th>Valeur</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>$args</td>
                    <td>
                        <ul>
                            <li>id / url de l'image</li>
                            <li><b>$args</b> de la strate</li>
                        </ul>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="args-type">
                            <option value="id" selected>id</option>
                            <option value="src">url</option>
                            <option value="var">$args</option>
                        </select>

                        <input type="number" data-param="args" data-args-type="id" value="460">
                        <input type="text" data-param="args" data-args-type="src" value="img/test.jpg">
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$args"
                            data-args-json='<?= esc_attr(wp_json_encode([
                                                "images" => [
                                                    "desktop" => 417,
                                                    "mobile"  => 456,
                                                ],
                                            ])) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>$sizes</td>
                    <td>Crop wp (taille enregistrée). <br>
                    <td class="sg-table-value">
                        <label>
                            <span data-args-type-show="var">Desktop</span>
                            <select data-param="sizes_desktop" data-default="full">
                                <?php foreach ($image_sizes as $s) : ?>
                                    <option value="<?= esc_attr($s) ?>" <?= $s === 'full' ? ' selected' : '' ?>><?= esc_html($s) ?></option>
                                <?php endforeach ?>
                            </select>
                        </label>
                        <label data-args-type-show="var">
                            Mobile
                            <select data-param="sizes_mobile" data-default="full">
                                <?php foreach ($image_sizes as $s) : ?>
                                    <option value="<?= esc_attr($s) ?>" <?= $s === 'full' ? ' selected' : '' ?>><?= esc_html($s) ?></option>
                                <?php endforeach ?>
                            </select>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>$classes</td>
                    <td></td>
                    <td>
                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
                <tr>
                    <td>$lazy</td>
                    <td>Lazy load</td>
                    <td>
                        <input type="checkbox" data-param="lazy" checked data-default="true">
                    </td>
                </tr>
                <tr>
                    <td>placeholder</td>
                    <td>Afficher un placeholder si pas d'images</td>
                    <td>
                        <input type="checkbox" data-param="placeholder">
                    </td>
                </tr>
                <tr>
                    <td>$breakpoint</td>
                    <td>mobile / desktop</td>
                    <td>
                        <input type="number" data-param="breakpoint" value="768" data-default="768">
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:picture($args, $sizes, $classes, $lazy, $placeholder, $breakpoint)"
            data-sg-params="args,sizes,classes,lazy,placeholder,breakpoint"></sg-builder-result>
    </div>

</sg-part>