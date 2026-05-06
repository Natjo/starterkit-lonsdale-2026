<?php
$content = isset($args["content"]) ? (string) $args["content"] : "";
$trigger_cfg = isset($args["trigger"]) && is_array($args["trigger"]) ? array_values($args["trigger"]) : ["btn", null, null];
$trigger_cfg = array_pad($trigger_cfg, 3, null);
$type = isset($trigger_cfg[0]) && strtolower((string) $trigger_cfg[0]) === "link" ? "link" : "btn";
$trigger_label = ($trigger_cfg[1] !== null && trim((string) $trigger_cfg[1]) !== "")
    ? trim((string) $trigger_cfg[1])
    : __("Open dialog", "starterkit");
$trigger_classes = ($trigger_cfg[2] !== null && trim((string) $trigger_cfg[2]) !== "")
    ? trim((string) $trigger_cfg[2])
    : "";
$classes = !empty($args["classes"]) ? " " . (string) $args["classes"] : "";
$attributes = !empty($args["attributes"]) ? (string) $args["attributes"] : "";

$close_label = __("Close", "starterkit");
$id = "";

if ($content === "") return;
if ($id === "") $id = "dialog-" . wp_unique_id();
$content_id = $id . "-content";
$aria_label = $trigger_label !== "" ? $trigger_label : __("Dialog", "starterkit");

$trigger_extra = $trigger_classes !== "" ? " " . $trigger_classes : "";
?>

<?php if ($type === "link") : ?>
    <?php
    $dialog_link = [
        "title" => $trigger_label,
        "url" => "#",
        "target" => "",
    ];
    $link_classes = trim("dialog-trigger" . $trigger_extra);
    $link_attributes = sprintf(
        ' role="button" data-dialog-id="%s" aria-haspopup="dialog" aria-controls="%s" aria-expanded="false"',
        esc_attr($id),
        esc_attr($id)
    );
    component::link($dialog_link, $link_classes ?: null, null, $link_attributes);
    ?>
<?php else : ?>
    <?php
    $btn_classes = trim("dialog-trigger" . $trigger_extra);
    component::btn(
        esc_html($trigger_label),
        $btn_classes,
        "",
        'type="button"
        data-dialog-id="' . esc_attr($id) . '"
        aria-haspopup="dialog"
        aria-controls="' . esc_attr($id) . '"
        aria-expanded="false"'
    );
    ?>
<?php endif; ?>

<dialog
    id="<?= esc_attr($id) ?>"
    class="dialog<?= esc_attr($classes) ?>"
    data-dialog
    aria-label="<?= esc_attr($aria_label) ?>"
    aria-describedby="<?= esc_attr($content_id) ?>"
    <?= $attributes ?>>
    <div class="dialog-inner">
        <div id="<?= esc_attr($content_id) ?>" class="dialog-content">
            <?= wp_kses_post($content) ?>
        </div>
        <form method="dialog" class="dialog-actions">
            <button type="submit" class="dialog-close" value="close" data-dialog-close>
                <?= esc_html($close_label) ?>
            </button>
        </form>
    </div>
</dialog>
