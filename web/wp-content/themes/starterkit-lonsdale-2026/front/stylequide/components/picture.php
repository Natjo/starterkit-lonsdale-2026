<sg-part type="component" tag="html" label="Picture" name="picture">
    <code class="sg-code-inline" data-syntax="php">
        picture($args, $classes = "", $lazy = true, $placeholder = false, $breakpoint = 768)
    </code>

    <div>Créer un element picture avec un id de l'image, un chemin de l'image ou un tableau d'arguments. </div>

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
                    <td>$args</td>
                    <td>
                        <ul>
                            <li>- id de l'image</li>
                            <li>- chemin de l'image dans assets</li>
                            <li>- <b>$args</b> de la strate</li>
                        </ul>
                    </td>
                    <td class="sg-table-value">
                        <select data-param="args-type">
                            <option value="id" selected>id</option>
                            <option value="src">url</option>
                            <option value="var">$args</option>
                        </select>

                        <input type="number" data-param="args" data-args-type="id" value="460">
                        <input type="text" data-param="args" data-args-type="src" value="img/test.jpg">
                        <span class="sg-args-var" data-param="args" data-args-type="var"
                            data-args-value="$args"
                            data-args-json='<?= esc_attr(wp_json_encode([
                                                "images" => [
                                                    "desktop" => 417,
                                                    "mobile"  => 456,
                                                ],
                                            ])) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>$classes</td>
                    <td></td>
                    <td>
                        <input type="text" data-param="classes" placeholder='ma class'>
                    </td>
                </tr>
                <tr>
                    <td>$lazy</td>
                    <td>Lazy load</td>
                    <td>
                        <input type="checkbox" data-param="lazy" checked>
                    </td>
                </tr>
                <tr>
                    <td>placeholder</td>
                    <td>Afficher un placeholder si pas d'images desktop ou mobile</td>
                    <td>
                        <input type="checkbox" data-param="placeholder">
                    </td>
                </tr>
                <tr>
                    <td>$breakpoint</td>
                    <td>mobile / desktop</td>
                    <td>
                        <input type="number" data-param="breakpoint" value="768">
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:picture(460)"
            data-sg-params="args,classes,lazy,placeholder,breakpoint"></sg-builder-result>
    </div>

    <div class="sg-implementation">
        <h4 class="sg-h4">Sizes</h4>


        <sg-snippet no-copy class="implementation">
  
$args["images"]["desktop_size"] = "413_224";

$args["images"]["mobile_size"] = "200_100";

component::picture($args);
        </sg-snippet>
    </div>

</sg-part>