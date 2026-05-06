<?php
$items = (isset($items) && is_array($items)) ? $items : [];
?>

<sg-part type="component" tag="html,css,js" label="Accordion" name="accordion">
    <code class="sg-code-inline" data-syntax="php">
        accordion($items, $classes = null, $attributes = null)
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
                    <td>$items</td>
                    <td>Tableau associatif avec les items</td>
                    <td class="sg-table-value">
                        <span class="sg-args-var" data-param="items" data-args-type="var"
                            data-args-value="$items"
                            data-args-json='<?= esc_attr(wp_json_encode($items)) ?>'>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>classes</td>
                    <td>>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ajouter class'>
                    </td>
                </tr>
                <tr>
                    <td>attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='ajouter attributs'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:accordion($items)"
            data-sg-params="items,classes,attributes"
            data-sg-module="<?= esc_url(THEME_ASSETS . 'components/accordion/accordion.js') ?>"></sg-builder-result>
    </div>
</sg-part>