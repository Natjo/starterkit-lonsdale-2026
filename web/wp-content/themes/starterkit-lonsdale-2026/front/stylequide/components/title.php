<sg-part type="component" tag="css,html" label="Title" name="title">
    <code class="sg-code-inline" data-syntax="php">
        title($args, $hx = 2, $classes = null, $attributes = null)
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
                    <td>$args / title</td>
                    <td>Tableau associatif avec le titre ou<br> le titre en string</td>
                    <td class="sg-table-value">
                        <select data-param="args-type">
                            <option value="title">Title</option>
                            <option value="var">$args</option>
                        </select>

                        <input type="text" data-param="args" data-args-type="title" value='Lorem ipsum dolor sit amet'>
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$args"
                            data-args-json='<?= esc_attr(wp_json_encode(["title" => "Lorem ipsum dolor"])) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>hx</td>
                    <td>Niveau de titre</td>
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
                    <td>classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <select data-param="classes">
                            <option value="title-1" selected>title-1</option>
                            <option value="title-2">title-2</option>
                        </select>
                        <input type="text" data-param="classes" placeholder='ajouter class'>
                    </td>
                </tr>
                <tr>
                    <td>attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='aria-label="Title"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:title('Lorem ipsum dolor sit amet', 2)"
            data-sg-params="args,hx,classes,attributes"></sg-builder-result>
    </div>
</sg-part>