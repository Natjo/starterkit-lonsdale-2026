<?php
$list = ["email", "copy", "facebook", "x", "whatsapp"];
$list_json = wp_json_encode($list);
$list_php = "['" . implode("', '", array_map('esc_attr', $list)) . "']";
?>

<sg-part type="component" tag="css,html,js" label="Shares" name="shares">
    <code class="sg-code-inline" data-syntax="php">
        share($list, $classes = null)
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
                    <td><strong>$list</strong></td>
                    <td>Liste des boutons à afficher (email/copy/facebook/x/whatsapp)</td>
                    <td class="sg-table-value">
                        <input type="hidden" data-param="list" value="<?= esc_attr($list_php) ?>">
                        <input type="hidden" data-param="list_json" value="<?= esc_attr($list_json) ?>">

                        <div class="sg-checkbox-list " data-sg-array="list">
                            <?php
                            $share_options = [
                                "email" => "E-mail",
                                "copy" => "Copier le lien",
                                "facebook" => "Facebook",
                                "x" => "X (Twitter)",
                                "whatsapp" => "Whatsapp"
                            ];
                            foreach ($share_options as $val => $label): ?>
                                <label style="margin-right:12px; display:inline-flex; align-items:center;">
                                    <input
                                        type="checkbox"
                                        value="<?= esc_attr($val) ?>"
                                        <?= in_array($val, $list) ? 'checked' : '' ?>
                                    >
                                    <span style="margin-left:6px;"><?= esc_html($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                   
                    </td>
                </tr>
                <tr>
                    <td>classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ajouter class'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:shares($list)"
            data-sg-params="list,classes"></sg-builder-result>
    </div>
</sg-part>