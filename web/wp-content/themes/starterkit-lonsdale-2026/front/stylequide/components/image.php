<?php
$image_sizes = function_exists('get_intermediate_image_sizes') ? get_intermediate_image_sizes() : [];
$image_sizes = array_values(array_unique(array_merge(['full'], (array) $image_sizes)));
?>
<sg-part type="component" tag="html" label="Image" name="image">
    <code class="sg-code-inline" data-syntax="php">
        image($image, $size = "full", $classes = "", $lazy = true)
    </code>

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
                    <td>$image</td>
                    <td>ID média WordPress ou URL/chemin direct</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="image" value="460">
                    </td>
                </tr>
                <tr>
                    <td>$size</td>
                    <td>Crop wp (taille enregistrée)</td>
                    <td>
                        <select data-param="size">
                            <?php foreach ($image_sizes as $s) : ?>
                                <option value="<?= esc_attr($s) ?>"<?= $s === 'full' ? ' selected' : '' ?>><?= esc_html($s) ?></option>
                            <?php endforeach ?>
                        </select>
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
                        <input type="checkbox" data-param="lazy" checked>
                    </td>
                </tr>


            </tbody>
        </table>

        <sg-builder-result
            code="component:image($image, $size, $classes, $lazy)"
            data-sg-params="image,size,classes,lazy"></sg-builder-result>
    </div>


</sg-part>