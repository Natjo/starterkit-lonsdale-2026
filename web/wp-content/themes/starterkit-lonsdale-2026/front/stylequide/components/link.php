<?php
$link = isset($link) && is_array($link) ? $link : [
    "title" => "Lorem ipsum",
    "url" => "/",
    "target" => "",
];
$icons_list = isset($icons_list) && is_array($icons_list) ? $icons_list : [];
?>

<sg-part type="component" tag="html,css" label="Link" name="link">
    <code class="sg-code-inline" data-syntax="php">
    link(
        $link,
        $classes = null,
        $icon = null,
        $attributes = null
    )
    </code>
    
    <div class="sg-components-builder">
        <table class="sg-table">
            <thead>
                <tr>
                    <th>$link</th>
                    <th>Description</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>args</strong></td>
                    <td>
                        <p>Tableau associatif avec le titre ou<br> le titre en string</p>
                    </td>
                    <td class="sg-table-value">
                        <input
                            type="hidden"
                            data-param="link"
                            value="$link"
                            data-link-json='<?= esc_attr(json_encode([
                                "title" => "Lorem ipsum",
                                "url" => "/",
                                "target" => "",
                            ])) ?>'>
                        <sg-snippet no-copy>
                            $link = [
                                "title" => "Lorem ipsum",
                                "url" => "/",
                                "target" => "",
                            ];
                        </sg-snippet>
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
                    <td>
                        <?php if (function_exists('getIcons')) getIcons($icons_list); ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>attributes</strong></td>
                    <td>
                        <p>Attributs optionnels</p>
                    </td>
                    <td>
                        <input type="text" data-param="attributes" placeholder='aria-hidden="true"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result code="component:link($link)"></sg-builder-result>
    </div>
</sg-part>