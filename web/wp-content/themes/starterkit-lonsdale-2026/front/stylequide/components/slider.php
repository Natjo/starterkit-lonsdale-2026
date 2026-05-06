<?php
$items = (isset($items) && is_array($items)) ? $items : [];
?>

<sg-part type="component" tag="html,css,js" label="Slider" name="slider">
    <code class="sg-code-inline" data-syntax="php">
        slider($items, $card = "card-news", $classes = null, $navigation = true, $pagination = false)
    </code>

    <div class="sg-components-builder" full>
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
                    <td>$items</td>
                    <td></td>
                    <td class="sg-table-value">
                        <span class="sg-args-var" data-param="items" data-args-type="var"
                            data-args-value="$items"
                            data-args-json='<?= esc_attr(wp_json_encode($items)) ?>' hidden>
                            <sg-snippet no-copy></sg-snippet>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>card</td>
                    <td>Type de card</td>
                    <td class="sg-table-value">
                        <select data-param="card">
                            <option value="card-news" selected>card-news</option>
                        </select>
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
                    <td>navigation</td>
                    <td>Afficher les boutons de navigation</td>
                    <td class="sg-table-value">
                        <label>
                            <input type="checkbox" data-param="navigation" checked>
                            Activer
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>pagination</td>
                    <td>Afficher la pagination</td>
                    <td class="sg-table-value">
                        <label>
                            <input type="checkbox" data-param="pagination" checked>
                            Activer
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:slider($items, 'card-news', null, true, true)"
            data-sg-params="items,card,classes,navigation,pagination"
            data-sg-module="<?= esc_url(THEME_URL . 'front/stylequide/components/slider-styleguide.js') ?>"></sg-builder-result>
    </div>


    <div class="sg-implementation">
        <h4 class="sg-h4">Implementation</h4>


        <sg-snippet no-copy class="implementation">
            import Slider from "../../components/slider/slider.js";


            export default (el) => {
                const slider = el.querySelector(".slider");
                const myslider = new Slider(slider);
                myslider.add();
            };
        </sg-snippet>
    </div>
</sg-part>