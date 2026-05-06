<?php
$icons_list = isset($icons_list) && is_array($icons_list) ? $icons_list : [];
?>

<sg-part type="component" tag="html,css" label="Picto" name="picto">
    <code class="sg-code-inline" data-syntax="php">
        picto($name, $size = "", $animate = false)
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
                    <td>name</td>
                    <td>Nom de l'icône</td>
                    <td class="sg-table-value">
                        <select data-param="name">
                            <?php foreach ($icons_list as $icon) : ?>
                                <option value="<?= esc_attr($icon['id']) ?>"<?= ($icon['id'] ?? '') === 'youtube' ? ' selected' : '' ?>><?= esc_html($icon['id']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <select data-param="size">
                            <option value="sm">sm</option>
                            <option value="" selected>md</option>
                            <option value="lg">lg</option>
                        </select>
                        <input type="text" data-param="size" placeholder=''>
                    </td>
                </tr>
                <tr>
                    <td>animate</td>
                    <td>Animation</td>
                    <td class="sg-table-value">
                        <label>
                            <input type="checkbox" data-param="animate">
                            Activer
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:picto('youtube', '')"
            data-sg-params="name,size,animate"
        ></sg-builder-result>
    </div>
</sg-part>
