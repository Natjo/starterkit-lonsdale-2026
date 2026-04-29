<?php
$card_news = isset($card_news) && is_array($card_news) ? $card_news : [
    "title" => "Lorem ipsum dolor sit amet",
    "images" => ["desktop" => 460],
];
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
                    <td><strong>items</strong></td>
                    <td>
                        <p>Tableau de cards</p>
                    </td>
                    <td>
                        <input type="hidden" data-param="items" placeholder='$items'>
                        <sg-snippet no-copy>
                            $items = 
                            [
                                [
                                    "title" => "Lorem ipsum dolor sit amet",
                                    "images" => ["desktop" => 460]
                                ],
                                [
                                    "title" => "Lorem ipsum dolor sit amet",
                                    "images" => ["desktop" => 460]
                                ],
                            ]
                        </sg-snippet>
                    </td>
                </tr>
                <tr>
                    <td><strong>card</strong></td>
                    <td>
                        <p>Type de carte</p>
                    </td>
                    <td>
                        <select data-param="card">
                            <option value="news" selected>news</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>classes</strong></td>
                    <td>
                        <p>Classes optionnelles</p>
                    </td>
                    <td>
                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result code="component:list([$card_news, $card_news], 'news', 'sg-list')"></sg-builder-result>
    </div>
</sg-part>