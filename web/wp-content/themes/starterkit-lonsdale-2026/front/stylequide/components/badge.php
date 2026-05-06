<sg-part type="component" tag="html,css" label="Badge" name="badge">
    <code class="sg-code-inline" data-syntax="php">
        badge($name, $classes = null, $attributes = null)
    </code>
    <p>Le badge est un élément d’indication permettant de valoriser une information liée à un élément précis du site. </p>


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
                    <td></td>
                    <td class="sg-table-value">
                        <input type="text" data-param="name" data-args-type="label" value='Lorem ipsum dolor sit amet'>
                    </td>
                </tr>


                <tr>
                    <td>classes</td>
                    <td>Classes optionnelles</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="classes" placeholder='ajouter class'>
                    </td>
                </tr>
                <tr>
                    <td>attributes</td>
                    <td>Attributs optionnels</td>
                    <td class="sg-table-value">
                        <input type="text" data-param="attributes" placeholder='aria-label="Tag"'>
                    </td>
                </tr>
            </tbody>
        </table>


        <sg-builder-result
            code="component:badge('Lorem ipsum dolor sit amet')"
            data-sg-params="name,classes,attributes"></sg-builder-result>
    </div>
</sg-part>