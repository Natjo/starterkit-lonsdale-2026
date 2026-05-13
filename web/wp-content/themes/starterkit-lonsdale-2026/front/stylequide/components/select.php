<?php
$select_options = [
    ["name" => "Option 1", "value" => "option-1"],
    ["name" => "Option 2", "value" => "option-2", "selected" => true],
    ["name" => "Option 3", "value" => "option-3"],
];
?>

<sg-part type="component" tag="html,css,js" label="Select" name="select">
    <code class="sg-code-inline" data-syntax="php">
        select($args, $label, $multi = false, $classes = null, $attributes = null)
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
                    <td>$args</td>
                    <td>Liste d'options :<br> name, value, selected</td>
                    <td class="sg-table-value">
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$args"
                            data-args-json='<?= esc_attr(wp_json_encode($select_options)) ?>'>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>$label</td>
                    <td>Libellé affiché au-dessus</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="label" value="Mon label">
                    </td>
                </tr>
                <tr>
                    <td>$multi</td>
                    <td>Sélection multiple</td>
                    <td class="sg-table-value">
                        <label>
                            <input type="checkbox" data-param="multi">
                            Activer
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>$classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder="ma-class">
                    </td>
                </tr>
                <tr>
                    <td>$attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='data-foo="bar"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:select($args, 'Mon label')"
            data-sg-params="args,label,multi,classes,attributes"
            data-sg-module="<?= esc_url(THEME_URL . 'front/stylequide/components/select-styleguide.js') ?>"></sg-builder-result>
    </div>
</sg-part>