<sg-part type="component" tag="html,css" label="Picto" name="picto">
    <code class="sg-code-inline" data-syntax="php">picto($name, $size = "", $animate = false)</code>
    
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
                    <td><strong>name</strong></td>
                    <td>
                        <p>Nom de l'icône</p>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="name">
                            <?php foreach ($icons_list as $icon) : ?>
                                <option value="<?= esc_attr($icon['id']) ?>"><?= esc_html($icon['id']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>classes</strong></td>
                    <td>
                        <p>Classes optionnelles</p>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="size">
                            <option value="sm">sm</option>
                            <option value="" selected>md</option>
                            <option value="lg">lg</option>
                        </select>
                        <input type="text" data-param="size" placeholder=''>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="sg-components-builder-result">
            <sg-code data-btn-builder>
                component:picto("youtube")
            </sg-code>

            <div class="sg-render" data-ajax-url="<?= esc_url(admin_url('admin-ajax.php')) ?>">
                <?php component::picto("youtube", "") ?>
            </div>
        </div>
    </div>

</sg-part>