<sg-part type="component" tag="css,html,js" label="Title" name="title">
    <code class="sg-code-inline" data-syntax="php">
        title($args, $hx = 2, $classes = null)
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
                    <td><strong>$args</strong>/<strong>title</strong></td>
                    <td>
                        <p>Tableau associatif avec le titre ou<br> le titre en string</p>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="args-type">
                            <option value="title">Title</option>
                            <option value="var">$args</option>
                        </select>

                        <input type="text" data-param="args" data-args-type="title" value='Lorem ipsum dolor sit amet'>
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$args"
                            data-args-json='<?= esc_attr(json_encode(["title" => "Lorem ipsum dolor"])) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>hx</strong></td>
                    <td>
                        <p>Niveau de titre</p>
                    </td>
                    <td>
                        <select data-param="hx">
                            <option value="1">1</option>
                            <option value="2" selected>2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>classes</strong></td>
                    <td>
                        <p>Classes optionnelles</p>
                    </td>
                    <td>
                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="sg-components-builder-result">
            <sg-code data-btn-builder>
                component:title($args)
            </sg-code>

            <div class="sg-render" data-ajax-url="<?= esc_url(admin_url('admin-ajax.php')) ?>">
                <?php component::title('Lorem ipsum dolor sit amet', 1) ?>
            </div>
        </div>
    </div>
</sg-part>