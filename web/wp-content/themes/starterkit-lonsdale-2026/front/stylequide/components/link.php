<?php
$link = isset($link) && is_array($link) ? $link : [
    "title" => "Lorem ipsum",
    "url" => "/",
    "target" => "",
];
$link_json = wp_json_encode($link);
$icons_list = isset($icons_list) && is_array($icons_list) ? $icons_list : [];
?>

<sg-part type="component" tag="html,css" label="Link" name="link">
    <code class="sg-code-inline" data-syntax="php">
    link($link, $classes = null, $icon = null, $attributes = null)
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
                    <td><strong>$link</strong></td>
                    <td>
                        <p>Tableau associatif </p>
                    </td>
                    <td class="sg-table-value">
                        <span class="sg-args-var" data-param="link" data-args-type="var"
                            data-args-value="$link"
                            data-args-json='<?= esc_attr($link_json) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td><strong>classes</strong></td>
                    <td>
                        <p>Classes optionnelles</p>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="classes">
                            <option value="link-1" selected>link-1</option>
                            <option value="link-2">link-2</option>
                        </select>

                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
                <tr>
                    <td><strong>icon</strong></td>
                    <td>
                        <p>Icône optionnelle</p>
                    </td>
                    <td class="sg-table-value">
                        <?php if (function_exists('getIcons')) getIcons($icons_list); ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>attributes</strong></td>
                    <td>
                        <p>Attributs optionnels</p>
                    </td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='aria-hidden="true"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:link($link)"
            data-sg-params="link,classes,icon,attributes"
        ></sg-builder-result>
    </div>
</sg-part>
