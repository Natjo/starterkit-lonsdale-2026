<?php
$icons_refresh_notice = isset($icons_refresh_notice) ? (string) $icons_refresh_notice : '';
$icons_list = isset($icons_list) && is_array($icons_list) ? $icons_list : [];
?>

<sg-part type="component" tag="html,css" label="Icons" name="icons" action="refresh">

    <form data-sg-action="refresh" method="post" class="sg-action sg-refresh-icons-form">
        <button type="submit" name="refresh_icons_manifest" value="1" class="sg-refresh-icons-btn">Refresh icons</button>
        <?php if ($icons_refresh_notice !== '') : ?>
            <small class="sg-refresh-icons-notice"><?= esc_html($icons_refresh_notice) ?></small>
        <?php endif; ?>
    </form>

    <code class="sg-code-inline" data-syntax="php">
        icon($name, $width = 24, $height = 24, $classes = null)
    </code>

    <div class="sg-components-builder">
        <?php sgIcons($icons_list); ?>

        <sg-builder-result
            code="component:icon('youtube', 24, 24)"
            data-sg-params="name,width,height,classes">
            
            <div class="sg-components-builder-adjust">

                <input type="hidden" data-param="name" value="youtube">
                <div>
                    <label>Width</label>
                    <input type="number" data-param="width" placeholder="24" min="1" step="1" inputmode="numeric">
                </div>
                <div>
                    <label>Height</label>
                    <input type="number" data-param="height" placeholder="24" min="1" step="1" inputmode="numeric">
                </div>
                <div>
                    <label>Classes</label>
                    <input type="text" data-param="classes" placeholder="classes">
                </div>
            </div>

        </sg-builder-result>
    </div>
</sg-part>
