<?php
$url = isset($args["url"]) ? $args["url"] : "";
$classes = isset($args["classes"]) ? $args["classes"] : "";
$attributes = isset($args["attributes"]) ? $args["attributes"] : "";
?>

<sg-part type="component" tag="html,css,js" label="Video" name="video">

    <code class="sg-code-inline" data-syntax="php">
        video($url, $title = "", $poster = null, $autoplay = false, $loop = false, $classes = null, $attributes = null)
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
                    <td>$url</td>
                    <td>Url ou id de la video</td>
                    <td>
                        <input type="text" data-param="url" placeholder="url ou id" value="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                    </td>
                </tr>
                <tr>
                    <td>$title</td>
                    <td>Titre accessible</td>
                    <td>
                        <input type="text" data-param="title" placeholder="Titre de la vidéo" value="Never Gonna Give You Up">
                    </td>
                </tr>
                <tr>
                    <td>$poster</td>
                    <td>Url ou id de l'image de couverture</td>
                    <td>
                        <input type="text" data-param="poster" placeholder="url ou id" value="460">
                    </td>
                </tr>
                <tr>
                    <td>$autoplay</td>
                    <td></td>
                    <td>
                        <input type="checkbox" data-param="autoplay">
                    </td>
                </tr>

                <tr>
                    <td>$loop</td>
                    <td></td>
                    <td>
                        <input type="checkbox" data-param="loop">
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
                    <td>$attributes</td>
                    <td></td>
                    <td>
                        <input type="text" data-param="attributes" placeholder='data-foo="bar"'>
                    </td>
                </tr>
            </tbody>
        </table>

        <sg-builder-result
            code="component:video($url, $title, $poster, $autoplay, $loop, $classes, $attributes)"
            data-sg-params="url,title,poster,autoplay,loop,classes,attributes"
            data-sg-module="<?= esc_url(THEME_URL . 'front/stylequide/components/video-styleguide.js') ?>"></sg-builder-result>

    </div>
</sg-part>