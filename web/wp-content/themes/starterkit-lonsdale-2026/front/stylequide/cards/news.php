<?php
$classes = [
    [
        "title" => "Horizontal",
        "value" => "horizontal"
    ],
    [
        "title" => "Reverse",
        "value" => "reverse"
    ]
];

$selects = [
    [
        "title" => "Theme",
        "name" => "theme",
        "choices" => [
            "aucun" => "",
            "dark" => "theme-dark",
            "pink" => "theme-pink",
        ],
    ],
];
?>


<sg-part label="News" name="card-news" cols="3"
    classes='<?= esc_attr(wp_json_encode($classes)) ?>'
    selects='<?= esc_attr(wp_json_encode($selects)) ?>'>

    <?php component::card('card-news', $card_news) ?>
    <?php component::card('card-news', $card_news) ?>
    <?php component::card('card-news', $card_news) ?>
</sg-part>