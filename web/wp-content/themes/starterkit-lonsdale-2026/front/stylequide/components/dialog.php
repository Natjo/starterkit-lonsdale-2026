<?php
$content = '<p>Lorem ipsum dolor sit amet.</p>
<button>Bouton</button>';
?>


<sg-part type="component" tag="html,css,js" label="Dialog" name="dialog">
    <code class="sg-code-inline" data-syntax="php">
        dialog($content, $trigger = ["btn", null, null], $classes = null, $attributes = null)
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
                    <td><strong>content</strong></td>
                    <td>Variable <code>$content</code> (HTML).</td>
                    <td class="sg-table-value">
                        <span class="sg-args-var" data-param="content" data-args-type="var"
                            data-args-value="$content"
                            data-args-json="<?= esc_attr(wp_json_encode($content)) ?>">
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr class="sg-table-group">
                    <td>trigger</td>
                    <td>Type de déclencheur, libellé, classes</td>
                    <td class="sg-table-value">
                        <select data-param="type">
                            <option value="btn" selected>btn</option>
                            <option value="link">link</option>
                        </select>
                        <input type="text" data-param="trigger_name" value="Open dialog">
                        <input type="text" data-param="trigger_classes" placeholder="classes">
                    </td>
                </tr>
                <tr>
                    <td>classes</td>
                    <td>Classes sur le <code>&lt;dialog&gt;</code></td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ajouter class'>
                    </td>
                </tr>
                <tr>
                    <td>attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='aria-label="Dialog"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:dialog($content, ['btn', 'Open dialog', ''], null, null)"
            data-sg-params="content,type,trigger_name,trigger_classes,classes,attributes"
            data-sg-module="<?= esc_url(THEME_URL . 'front/stylequide/components/dialog-styleguide.js') ?>"></sg-builder-result>
    </div>


    <div class="sg-implementation">
        <h4 class="sg-h4">Implementation</h4>


        <sg-snippet no-copy class="implementation">
            import Dialog from "../../components/dialog/dialog";

            export default (el) => {
                const dialogs = el.querySelectorAll("dialog.dialog");

                dialogs.forEach((dialogEl) => {
                    const dialog = new Dialog(dialogEl);
                    dialog.onopen = () => {
                        alert("open");
                    };
                    dialog.onclose = () => {
                        alert("close");
                    };
                });
            };
        </sg-snippet>
    </div>
</sg-part>
