<sg-part type="component" tag="html,css" label="Link" name="link">
    <code class="sg-code-inline" data-syntax="php">link($args)</code>
    
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
                    <td><strong>args</strong></td>
                    <td>
                        <p>Tableau associatif avec le titre ou<br> le titre en string</p>
                    </td>
                    <td class="sg-table-value">
                        <input
                            type="hidden"
                            data-param="link"
                            value="$link"
                            data-link-json='<?= esc_attr(json_encode($link)) ?>'>
                        <sg-snippet no-copy>
                            $link = [
                            "title" => "Lorem ipsum",
                            "url" => "/",
                            "target" => "",
                            ];
                        </sg-snippet>
                    </td>
                </tr>

                <tr>
                    <td><strong>classes</strong></td>
                    <td>
                        <p>Classes optionnelles</p>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="classes">
                            <option value="link-1" selected>link-1</option>
                            <option value="link-2">link-2</option>
                        </select>

                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
                <tr>
                    <td><strong>icon</strong></td>
                    <td>
                        <p>Icône optionnelle</p>
                    </td>
                    <td>
                        <?php getIcons($icons_list); ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>attributes</strong></td>
                    <td>
                        <p>Attributs optionnels</p>
                    </td>
                    <td>
                        <input type="text" data-param="attributes" placeholder='aria-hidden="true"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="sg-components-builder-result">
            <sg-code data-btn-builder>
                component:link($args)
            </sg-code>

            <div class="sg-render" data-ajax-url="<?= esc_url(admin_url('admin-ajax.php')) ?>">
                <?php component::link($link) ?>
            </div>
        </div>
    </div>
</sg-part>