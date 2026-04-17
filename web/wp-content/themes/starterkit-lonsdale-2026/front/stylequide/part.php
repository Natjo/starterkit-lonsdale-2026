<?php

/**
 * Styleguide — Generic demo block
 *
 * @param string $args['type']               "card" | "hero" | "component"
 * @param string $args['label']              Titre affiché dans le <summary>
 * @param string $args['name']               Slug (ex: "card-news", "hero-homepage", "slider")
 * @param array  $args['items']              Tableau d'args — un rendu par item (prioritaire sur args/count)
 * @param array  $args['args']               Args passés au rendu (si pas de items)
 * @param int    $args['count']              Répétitions avec les mêmes args (défaut: 1)
 * @param string $args['cols']               Nombre de colonnes ex: "3" → "cols-3". Vide ou absent = pas de grille.
 * @param array  $args['options']['classes'] Checkboxes CSS [ ["title" => "...", "value" => "..."] ]
 */

$type       = $args['type']               ?? 'card';
$label      = $args['label']              ?? $args['name'] ?? '';
$name       = $args['name']               ?? '';
$items      = $args['items']              ?? null;
$pArgs      = $args['args']               ?? [];
$count      = $args['count']              ?? 1;
$colsRaw    = $args['cols']               ?? '';
$cols       = $colsRaw !== '' ? 'sg-grid cols-' . esc_attr($colsRaw) : '';
$full       = $args['full']               ?? false;
$optClasses = $args['options']['classes'] ?? [];
$optSelect  = $args['options']['select']  ?? [];

if (!$name) return;

$render = function (array $itemArgs) use ($type, $name) {
    switch ($type) {
        case 'hero':
            hero($name, $itemArgs);
            break;

        case 'card':
            component::card($name, $itemArgs);
            break;

        case 'component':
            get_template_part("template-parts/components/$name", null, $itemArgs);
            break;
    }
};
?>

<details class="sg-details sg-strate-header" id="sg-<?= esc_attr($name) ?>">
    <summary>
        <h3 class="sg-h3"><?= esc_html($label) ?> <small><?= esc_html($name) ?></small></h3>
        <?= component::icon("caret", 20, 20) ?>
    </summary>
    <?php if ($optClasses || $optSelect) : ?>
    <div class="sg-options">
        <?php if ($optClasses) : ?>
            <div class="sg-classes">
                <?php foreach ($optClasses as $opt) : ?>
                    <div>
                        <label>
                            <input type="checkbox" class="sg-checkbox" id="sg-checkbox-<?= $name ?>-<?= esc_attr($opt['value']) ?>" value="<?= esc_attr($opt['value']) ?>">
                            <?= esc_html($opt['title']) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <?php if ($optSelect) : ?>
            <div class="sg-select">
                <?php foreach ($optSelect as $item) : ?>
                    <div>
                        <label><?= esc_html($item['title']) ?>: </label>
                        <select class="sg-select" id="sg-select-<?= $name ?>-<?= $item['name'] ?>">
                            <?php $inc = 0; ?>
                            <?php foreach ($item["choices"] as $key => $choice) : ?>
                                <option value="<?= $choice ?>" <?= $inc === 0 ? "selected" : "" ?>><?= $key ?></option>
                                <?php $inc++; ?>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
    <?php endif ?>

    <?php if (!$full) : ?><div class="sg-part <?= $cols ?>"><?php endif ?>
        <?php if ($items !== null) : ?>
            <?php foreach ($items as $item) : ?>
                <?php $render($item) ?>
            <?php endforeach ?>
        <?php else : ?>
            <?php for ($i = 0; $i < $count; $i++) : ?>
                <?php $render($pArgs) ?>
            <?php endfor ?>
        <?php endif ?>
    <?php if (!$full) : ?></div><?php endif ?>
</details>