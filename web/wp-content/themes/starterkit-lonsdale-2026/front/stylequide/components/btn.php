<?php
$icons_list = isset($icons_list) && is_array($icons_list) ? $icons_list : [];
?>

<sg-part type="component" tag="html,css" label="Btn" name="btn">
    <code class="sg-code-inline" data-syntax="php">
        btn($args, $classes = null, $icon = [], $attributes = null)
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
                    <td>args</td>
                    <td>
                        <p>Tableau associatif avec le titre ou<br> le titre en string</p>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="args-type">
                            <option value="src">Nom</option>
                            <option value="var">$args</option>
                        </select>

                        <input type="text" data-param="args" data-args-type="src" value="Je suis un bouton">
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$args"
                            data-args-json='
                                    <?= esc_attr(json_encode([
                                        "link" => [
                                            "title" => "See more",
                                            "url"  => "",
                                            "target" => "",
                                        ],
                                    ])) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>

                    </td>
                </tr>

                <tr>
                    <td>classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <select data-param="classes">
                            <option value="btn-1" selected>btn-1</option>
                            <option value="btn-2">btn-2</option>
                        </select>

                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
                <tr>
                    <td>icon</td>
                    <td>Icône optionnelle</td>
                    <td>
                        <?php if (function_exists('getIcons')) getIcons($icons_list); ?>
                    </td>
                </tr>
                <tr>
                    <td>attributes</td>
                    <td>Attributs optionnels</td>
                    <td>
                        <input type="text" data-param="attributes" placeholder='aria-hidden="true"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result code="component:btn('Se more', 'btn-primary')"></sg-builder-result>
    </div>
</sg-part>