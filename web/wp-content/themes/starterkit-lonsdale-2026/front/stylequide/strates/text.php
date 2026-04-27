<?php
$classes = [
    [
        "title" => "Reverse",
        "value" => "reverse",
    ],
];
?>

<sg-part label="Text" name="strate-text" type="strate"
    classes='<?= esc_attr(wp_json_encode($classes)) ?>' full>
    <?php
    strate("strate-text", [
        "title" => "Lorem ipsum dolor sit amet",
        "text" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. At, laudantium? Perferendis error laudantium sunt natus architecto illum debitis? Quia, praesentium.",
    ])
    ?>
</sg-part>