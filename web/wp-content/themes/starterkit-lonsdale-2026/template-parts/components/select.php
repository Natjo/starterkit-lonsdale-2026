<?php
/** @var array $args */
$options = isset($args['args']) && is_array($args['args']) ? $args['args'] : [];
$label = isset($args['label']) ? trim((string) $args['label']) : '';
$multi = !empty($args['multi']);
$classes = isset($args['classes']) ? trim((string) $args['classes']) : '';
$attributes = isset($args['attributes']) ? trim((string) $args['attributes']) : '';

if (empty($options)) return;

$uid = uniqid();
$btn_id = 'select-' . $uid;
$listbox_id = 'listbox-' . $uid;

$active_descendant_id = '';
$selected_names = [];
foreach ($options as $i => $opt) {
    if (empty($opt['selected'])) continue;
    if ($active_descendant_id === '') $active_descendant_id = $uid . '-' . $i;
    if (!empty($opt['name'])) $selected_names[] = (string) $opt['name'];
    if (!$multi) break;
}
$initial_label = !empty($selected_names) ? implode(', ', $selected_names) : $label;

$root_class = 'select' . ($classes !== '' ? ' ' . esc_attr($classes) : '');
?>

<div class="<?= $root_class ?>" data-placeholder="<?= esc_attr($label) ?>"<?= $attributes !== '' ? ' ' . $attributes : '' ?>>
    <button
        role="combobox"
        id="<?= esc_attr($btn_id) ?>"
        value="<?= esc_attr($initial_label) ?>"
        aria-controls="<?= esc_attr($listbox_id) ?>"
        aria-haspopup="listbox"
        tabindex="0"
        <?= $active_descendant_id !== '' ? 'aria-activedescendant="' . esc_attr($active_descendant_id) . '"' : '' ?>
        aria-expanded="false">
        <?= esc_html($initial_label) ?>
    </button>
    <div aria-live="assertive" role="alert" class="sr-only" data-select-announce></div>
    <ul role="listbox" id="<?= esc_attr($listbox_id) ?>"<?= $multi ? ' aria-multiselectable="true"' : '' ?>>
        <?php foreach ($options as $i => $opt) :
            $name = isset($opt['name']) ? (string) $opt['name'] : '';
            $value = isset($opt['value']) ? (string) $opt['value'] : $name;
            $selected = !empty($opt['selected']);
            if ($name === '' && $value === '') continue;
        ?>
            <li
                role="option"
                id="<?= esc_attr($uid . '-' . $i) ?>"
                data-value="<?= esc_attr($value) ?>"
                <?= $selected ? 'aria-selected="true"' : '' ?>><?= esc_html($name) ?></li>
        <?php endforeach ?>
    </ul>
</div>
