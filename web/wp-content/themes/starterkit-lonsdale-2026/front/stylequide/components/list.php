<?php
$items = (isset($items) && is_array($items)) ? $items : [];

?>

<sg-part type="component" tag="html" label="List" name="list">
    <code class="sg-code-inline" data-syntax="php">
        list($items, $card = "news", $classes = null)
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
                    <td>items</td>
                    <td>Tableau de cards</td>
                    <td class="sg-table-value">
                        <span class="sg-args-var" data-param="items" data-args-type="var"
                            data-args-value="$items"
                            data-args-json='<?= esc_attr(wp_json_encode($items)) ?>'>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>card</td>
                    <td>Type de carte</td>
                    <td>
                        <select data-param="card">
                            <option value="news" selected>news</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>classe</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:list($items, 'news', 'sg-list')"
            data-sg-params="items,card,classes"
        ></sg-builder-result>
    </div>
</sg-part>
