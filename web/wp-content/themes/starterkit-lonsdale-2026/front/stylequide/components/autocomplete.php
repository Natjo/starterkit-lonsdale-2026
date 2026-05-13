<?php
$items = [
    ["name" => "France", "value" => "fr"],
    ["name" => "Allemagne", "value" => "de"],
    ["name" => "Espagne", "value" => "es"],
];
?>

<sg-part type="component" tag="html,css,js" label="Auto complete" name="autocomplete">
    <code class="sg-code-inline" data-syntax="php">
        autocomplete($items, $label, $classes = null, $attributes = null)
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
                    <td>Tableau  avec le nom et la valeur</td>
                    <td class="sg-table-value">
                      <span class="sg-args-var" data-param="items" data-args-type="var"
                            data-args-value="$items"
                            data-args-json='<?= esc_attr(wp_json_encode($items)) ?>'>
                            <sg-snippet no-copy></sg-snippet>
                        </span>

                    </td>
                </tr>
                <tr>
                    <td>$label</td>
                    <td></td>
                    <td class="sg-table-value">
                        <input type="text" data-param="label" placeholder='mon label'>
                    </td>
                </tr>
                <tr>
                    <td>$classes</td>
                    <td></td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
                <tr>
                    <td>$attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='aria-hidden="true"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:autocomplete($items, 'Label)"
            data-sg-params="items,label,classes,attributes"
            data-sg-module="<?= esc_url(THEME_URL . 'front/stylequide/components/autocomplete-styleguide.js') ?>"></sg-builder-result>
    </div>
</sg-part>
