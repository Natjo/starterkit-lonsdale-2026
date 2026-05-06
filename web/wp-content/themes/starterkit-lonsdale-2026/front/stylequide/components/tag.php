<?php
$tag_link = [
    "title" => "Lorem ipsum",
    "url" => "/",
    "target" => "",
];
?>

<sg-part type="component" tag="html,css" label="Tag" name="tag">
    <code class="sg-code-inline" data-syntax="php">
        tag($args, $type = "info", $classes = null, $attributes = null)
    </code>

    <p>Le tag est un élément d’indication ou d’interaction (selon les contextes) permettant de catégoriser, classer, organiser les contenus d’un site à l’aide de mots clés. Il aide les usagers à rechercher et à trouver facilement une information. </p>
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
                    <td>Label ou $link </td>
                    <td class="sg-table-value">
                        <input type="text" data-param="args" data-args-type="label" value='Lorem ipsum dolor sit amet'>
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$link"
                            data-args-json='<?= esc_attr(wp_json_encode($tag_link)) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>type</td>
                    <td>info / btn / link</td>
                    <td class="sg-table-value">
                        <select data-param="type" data-default="info">
                            <option value="info" selected>info</option>
                            <option value="btn">btn</option>
                            <option value="link">link</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ajouter class'>
                    </td>
                </tr>
                <tr>
                    <td>attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='aria-label="Tag"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:tag('Lorem ipsum dolor sit amet')"
            data-sg-params="args,type,classes,attributes"></sg-builder-result>
    </div>
</sg-part>