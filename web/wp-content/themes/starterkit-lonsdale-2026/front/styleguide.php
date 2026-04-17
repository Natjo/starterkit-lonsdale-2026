<?php
/*
Template Name: Front styleguide
*/
function part(array $args)
{
    get_template_part('front/stylequide/part', null, $args);
}

$icons_manifest_file = __DIR__ . '/stylequide/icons-manifest.php';
require_once $icons_manifest_file;
$icons_manifest = function_exists('sg_get_icons_manifest') ? sg_get_icons_manifest() : ['list' => [], 'notice' => ''];
$icons_list = $icons_manifest['list'] ?? [];
$icons_refresh_notice = $icons_manifest['notice'] ?? '';


// cards
$card_news = [
    "title"  => "Lorem ipsum dolor sit amet",
    "images" => ["desktop" => 460]
]

?>

<link rel="stylesheet" href="<?= THEME_URL ?>front/stylequide/styles.css" />

<header class="sg-header">
    <h1>Styleguide</h1>
    <form method="post" class="sg-refresh-icons-form">
        <button type="submit" name="refresh_icons_manifest" value="1" class="sg-refresh-icons-btn">Refresh icons</button>
        <?php if ($icons_refresh_notice !== '') : ?>
            <small class="sg-refresh-icons-notice"><?= esc_html($icons_refresh_notice) ?></small>
        <?php endif; ?>
    </form>
</header>

<main id="main" role="main" class="styleguide">

    <!-- Styles -->
    <sg-section label="1. Styles" slug="styles" open>
        <sg-part type="style" label="Typography" name="typography">
            <h3 class="sg-h4">Title</h3>

            <table class="sg-table" data-btn-builder>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Result</th>
                        <th>Size</th>
                        <th>line-height</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>title-1</strong></td>
                        <td>
                            <p class="title-1"> Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        </td>
                        <td><small>20px</small></td>
                        <td><small>28px</small></td>
                    </tr>
                    <tr>
                        <td><strong>title-2</strong></td>
                        <td>
                            <p class="title-2">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        </td>
                        <td><small>24px</small></td>
                        <td><small>32px</small></td>
                    </tr>
                    <tr>
                        <td>title-3</td>
                        <td>
                            <p class="title-3">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        </td>
                        <td><small>28px</small></td>
                        <td><small>32px</small></td>
                    </tr>
                    <tr>
                        <td><strong>title-4</strong></td>
                        <td>
                            <p class="title-4">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        </td>
                        <td><small>32px</small></td>
                        <td><small>32px</small></td>
                    </tr>


                </tbody>
            </table>

            <h3 class="sg-h4">Text</h3>

            <table class="sg-table">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Result</th>
                        <th>Size</th>
                        <th>line-height</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong></strong></td>
                        <td>
                            <p class="">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        </td>
                        <td><small>24px</small></td>
                        <td><small>32px</small></td>
                    </tr>
                    <tr>
                        <td><strong>intro</strong></td>
                        <td>
                            <p class="intro"> Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                        </td>
                        <td><small>20px</small></td>
                        <td><small>28px</small></td>
                    </tr>





                </tbody>
            </table>
        </sg-part>

        <sg-part type="style" label="Rte" name="rte">
            <div class="rte">
                <h2>Title 2</h2>
                <h3>Title 3</h3>
                <h4>Title 4</h4>
                <p class="intro">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Accusamus magnam ipsum inventore delectus iusto culpa quam aliquam explicabo illo voluptates, maiores impedit repellendus possimus omnis consectetur corporis reprehenderit! Natus reprehenderit eos nobis corrupti tempore, harum ad atque obcaecati voluptatibus et quo ratione, id aliquam in. Asperiores quasi veniam corrupti perspiciatis reprehenderit. Pariatur, minima. Omnis dolore consequatur culpa quod doloremque cupiditate delectus! Est voluptates saepe aspernatur earum, porro harum laboriosam accusamus doloremque. Provident natus, magnam voluptatibus libero recusandae reprehenderit ad. Dolorem ab deleniti dignissimos ipsum at natus ratione et esse fuga quod. Necessitatibus, ipsam aliquam soluta numquam cupiditate natus officiis sint.</p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iusto voluptatem nostrum nihil, modi repellendus accusantium ducimus ipsam optio totam nobis velit similique eaque fugiat consectetur sit eum? Aut, quibusdam nulla iusto ab dolore facilis voluptatibus autem omnis distinctio quod quasi repellat dignissimos nostrum eaque alias, provident enim nisi doloremque deserunt sequi dolorum est, rerum iste aperiam. Eligendi blanditiis sapiente ipsa quasi accusamus sed suscipit quaerat rerum alias sequi sint ipsum, incidunt unde amet? Eos saepe consequuntur, magnam sunt recusandae repellat nisi dolorum quae repudiandae, minus, culpa eum corrupti dolorem impedit harum libero quod ex ipsa voluptate. Autem blanditiis doloremque et eos beatae totam odio omnis, pariatur nobis ad fuga? Facere nobis vero eaque dolore, ratione expedita accusamus tempore neque molestiae, magnam culpa officia consectetur cumque, ea quisquam ipsum. Tenetur unde quos reiciendis assumenda, harum ad inventore alias illum deleniti iure ex minus, ut voluptas accusamus non maxime natus maiores ducimus eligendi sed modi. Laudantium, fugiat laborum blanditiis, culpa, dolorem velit at commodi eos voluptatum quas consequuntur! Deserunt maiores voluptates, modi ex quis quo ratione nostrum qui doloremque voluptatum. Dolore eos nam eius et, labore architecto est necessitatibus totam quasi, iusto pariatur? Esse dolores at eius maiores natus voluptatibus repudiandae et.</p>

                <p><strong>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iusto voluptatem nostrum nihil</strong></p>

                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iusto voluptatem nostrum nihil, modi repellendus accusantium ducimus ipsam optio totam nobis velit similique eaque fugiat consectetur sit eum? Aut, quibusdam nulla iusto ab dolore facilis voluptatibus autem omnis distinctio quod quasi repellat dignissimos nostrum eaque alias, provident enim nisi doloremque deserunt sequi dolorum est, rerum iste aperiam. </p>

                <p>
                    <a href="">Lorem ipsum</a>
                </p>

                <ul>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore est voluptatem dolores aliquid molestiae vel corporis sint, nam fuga similique!Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore est voluptatem dolores aliquid molestiae vel corporis sint, nam fuga similique!</li>
                </ul>

                <ol>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore est voluptatem dolores aliquid molestiae vel corporis sint, nam fuga similique!Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore est voluptatem dolores aliquid molestiae vel corporis sint, nam fuga similique!</li>
                </ol>
            </div>
        </sg-part>

        <sg-part type="style" label="Colors" name="colors">
            <h3 class="sg-h4">Brand</h3>

            <div class="sg-grid cols-3">
                <sg-color name="Color 1" variable="--color-1"></sg-color>
                <sg-color name="Color 2" variable="--color-2"></sg-color>
                <sg-color name="Color 3" variable="--color-3"></sg-color>
            </div>
            <h3 class="sg-h4">Gris</h3>
            <div class="sg-grid cols-3">
                <sg-color name="Color gray light" variable="--color-gray-light"></sg-color>
                <sg-color name="Color gray" variable="--color-gray"></sg-color>
                <sg-color name="Color gray dark" variable="--color-gray-dark"></sg-color>
            </div>

        </sg-part>

        <sg-part type="style" label="Backgrounds" name="backgrounds">
            <div class="sg-grid cols-3">
                <sg-bg-color name="Background color 1" class=".bg-color-1"></sg-bg-color>
                <sg-bg-color name="Background color 2" class=".bg-color-2"></sg-bg-color>
                <sg-bg-color name="Background color 3" class=".bg-color-3"></sg-bg-color>
            </div>
        </sg-part>

        <sg-part type="style" label="Layout" name="layout" full>
            <div class="sg-part">
                <p>Les strates ont un layout avec des zones full, fluid, ctr, ctr-sm, center, ctr-sm-end, ctr-end, fluid-end, full-end.</p>

            </div>

            <div class="sg-layout strate">
                <div class="sg-line" data-line="full"></div>
                <div class="sg-line" data-line="fluid"></div>
                <div class="sg-line" data-line="ctr"></div>
                <div class="sg-line" data-line="ctr-sm"></div>
                <div class="sg-line center" data-line="center"></div>
                <div class="sg-line right" data-line="ctr-sm-end"></div>
                <div class="sg-line right" data-line="ctr-end"></div>
                <div class="sg-line right" data-line="fluid-end"></div>
                <div class="sg-line right" data-line="full-end"></div>

                <div class="sg-ctr" style="grid-column: full;grid-row: 1;">full</div>
                <div class="sg-ctr" style="grid-column: fluid;grid-row: 2;">fluid</div>
                <div class="sg-ctr ctr" style="grid-column: ctr;grid-row: 3;">ctr</div>
                <div class="sg-ctr" style="grid-column: ctr-sm;grid-row: 4;">ctr-sm</div>
                <div class="sg-ctr" style="grid-column: full/center;grid-row: 5;">full/center</div>
                <div class="sg-ctr" style="grid-column: center/full;grid-row: 6;">full/center-end</div>
                <div class="sg-ctr" style="grid-column: fluid/center;grid-row: 7;">fluid/center</div>
                <div class="sg-ctr" style="grid-column: center/fluid;grid-row: 8;">center/fluid</div>
                <div class="sg-ctr" style="grid-column: ctr/center;grid-row: 9;">ctr/center</div>
                <div class="sg-ctr" style="grid-column: center/ctr;grid-row: 10;">center/ctr</div>
            </div>


        </sg-part>

    </sg-section>

    <!-- Components -->
    <sg-section label="2. Components" slug="components">

        <!-- Title -->
        <sg-part type="component" tag="css,html,js" label="Title" name="title">

            <code class="sg-code-inline" data-syntax="php">title($args, $hx = 2, $classes = null)</code>
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
                            <td><strong>$args</strong></td>
                            <td>
                                <p>Tableau associatif avec le titre ou<br> le titre en string</p>
                            </td>
                            <td>
                                <input type="text" data-param="args" placeholder='$args'>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>$hx</strong></td>
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
                            <td><strong>$classes</strong></td>
                            <td>
                                <p>Classes optionnelles</p>
                            </td>
                            <td>
                                <input type="text" data-param="classes" placeholder='ma class'>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <sg-code data-btn-builder>
                    component:title($args)
                </sg-code>

                <div class="sg-render" data-ajax-url="<?= esc_url(admin_url('admin-ajax.php')) ?>">
                    <?php component::title('Lorem ipsum dolor sit amet', 1) ?>
                </div>
            </div>
        </sg-part>

        <!-- Tag -->
        <sg-part type="component" tag="html,css" label="Tag" name="tag">

        </sg-part>

        <!-- Badge -->
        <sg-part type="component" tag="html,css" label="Badge" name="badge">

        </sg-part>

        <!-- Btn -->
        <sg-part type="component" tag="html,css" label="Btn" name="btn">
            <code class="sg-code-inline" data-syntax="php">btn($args, $classes = null, $icon = [], $attributes = null)</code>

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
                            <td>
                                <input type="text" data-param="args" placeholder='$args'>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>classes</strong></td>
                            <td>
                                <p>Classes optionnelles</p>
                            </td>
                            <td>
                                <select data-param="classes">
                                    <option value="btn-1" selected>btn-1</option>
                                    <option value="btn-2">btn-2</option>
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
                                <select data-param="icon">
                                    <option value="">Aucune</option>
                                    <?php foreach ($icons_list as $icon) :
                                        $icon_id = isset($icon['id']) ? (string) $icon['id'] : '';
                                        $icon_w = isset($icon['width']) ? (string) $icon['width'] : '20';
                                        $icon_h = isset($icon['height']) ? (string) $icon['height'] : '20';
                                        if ($icon_id === '') continue;
                                        $icon_value = sprintf("['%s', %s, %s]", $icon_id, $icon_w, $icon_h);
                                    ?>
                                        <option value="<?= esc_attr($icon_value) ?>"><?= esc_html($icon_id) ?></option>
                                    <?php endforeach; ?>
                                </select>
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

                <sg-code data-btn-builder>
                    component:btn($args)
                </sg-code>

                <div class="sg-render" data-ajax-url="<?= esc_url(admin_url('admin-ajax.php')) ?>">
                    <?php component::btn("Je suis un bouton", "btn-primary") ?>
                </div>
            </div>

        </sg-part>

        <!-- Icon -->
        <sg-part type="component" tag="html,css" label="Icon" name="icon">
            <code class="sg-code-inline" data-syntax="php">icon($name, $width = 24, $height = 24, $classes = null)</code>
            <?php sgIcons($icons_list); ?>

        </sg-part>

        <!-- Picto -->
        <sg-part type="component" tag="html,css" label="Picto" name="picto">
            <code class="sg-code-inline" data-syntax="php">picto($name, $size = "", $animate = false)</code>

            <?php component::picto('youtube', "lg") ?>
        </sg-part>

        <!-- Accordion -->
        <sg-part type="component" tag="html,css,js" label="Accordion" name="accordion">

        </sg-part>

        <!-- Picture -->
        <sg-part type="component" tag="html,css" label="Picture" name="picture">
            <code class="sg-code-inline" data-syntax="php">picture($args, $classes = null)</code>

            <?php component::picture(["desktop" => 102], "full") ?>
        </sg-part>

        <!-- List -->
        <sg-part type="component" tag="html" label="List" name="list">
            <code class="sg-code-inline" data-syntax="php">list($items, $card = "news", $classes = null)</code>

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
                            <td><strong>items</strong></td>
                            <td>
                                <p>Tableau d'items</p>
                            </td>
                            <td>
                                <input type="text" data-param="items" placeholder='$items'>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>card</strong></td>
                            <td>
                                <p>Nom de la carte</p>
                            </td>
                            <td>
                                <select data-param="card">
                                    <option value="news" selected>news</option>
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

                <sg-code data-btn-builder>
                    component:list($items)
                </sg-code>

                <div class="sg-render" data-ajax-url="<?= esc_url(admin_url('admin-ajax.php')) ?>">
                    <?php component::list([$card_news, $card_news, $card_news], "news", "sg-list") ?>
                </div>
            </div>
        </sg-part>

        <!-- Slider -->
        <sg-part type="component" tag="html,css,js" label="Slider" name="slider">
            <code class="sg-code-inline" data-syntax="php">slider($items, $card = "offer", $classes = null)</code>

            <?php component::slider([
                "title" => "Lorem ipsum dolor sit amet",
                "images" => ["desktop" => 102]
            ]) ?>
        </sg-part>


    </sg-section>


    <!-- Cards -->
    <sg-section label="3. Cards" slug="cards">

        <!-- News -->

        <sg-part label="News" name="card-news" cols="3"
            classes='[{"title":"Horizontal","value":"horizontal"},{"title":"Reverse","value":"reverse"}]'
            selects='[{"title":"Theme","name":"theme","choices":{"aucun":"","dark":"theme-dark","pink":"theme-pink"}}]'>

            <?php component::card('card-news', $card_news) ?>
            <?php component::card('card-news', $card_news) ?>
            <?php component::card('card-news', $card_news) ?>
        </sg-part>
    </sg-section>


    <!-- Heros -->
    <sg-section label="4. Heros" slug="heros">

        <!-- Homepage -->
        <sg-part label="Homepage" name="hero-homepage" full>
            <?php
            hero("hero-homepage", [
                "title"  => "Lorem ipsum dolor sit amet",
                "images" => ["desktop" => 460],
            ]);
            ?>
        </sg-part>

        <!-- Page -->
        <sg-part label="Page" name="hero-page" full>
            <?php
            hero("hero-page", [
                "title" => "Lorem ipsum dolor sit amet"
            ]);
            ?>
        </sg-part>
    </sg-section>


    <!-- Strates -->
    <sg-section label="5. Strates" slug="strates">

        <!-- Text -->
        <sg-part label="Text" name="strate-text" type="strate"
            classes='[{"title":"Reverse","value":"reverse"}]' full>
            <?php
            strate("strate-text", [
                "title" => "Lorem ipsum dolor sit amet",
                "text" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. At, laudantium? Perferendis error laudantium sunt natus architecto illum debitis? Quia, praesentium.",
            ])
            ?>
        </sg-part>

        <!-- Slider -->
        <sg-part label="Slider" name="strate-slider" type="strate" full>
            <?php
            strate('strate-slider', [
                "title" => "Lorem ipsum dolor sit amet",
                "text" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. At, laudantium? Perferendis error laudantium sunt natus architecto illum debitis? Quia, praesentium.",
                "items" => [
                    $card_news,
                    $card_news,
                    $card_news,
                ]
            ]) ?>
        </sg-part>
    </sg-section>

</main>

<script src="<?= THEME_URL ?>front/stylequide/core.js?v=<?= filemtime(__DIR__ . '/stylequide/core.js') ?>"></script>

<?php get_tpl();
