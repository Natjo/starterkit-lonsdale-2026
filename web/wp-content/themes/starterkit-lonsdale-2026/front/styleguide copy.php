<?php
/*
Template Name: Frontaaaa styleguide
*/

/**
 * Parse a CSS file and extract all classes matching a given prefix.
 * Returns an array of [ 'class' => 'title-1', 'props' => [...], 'responsive' => [...] ]
 * Supports one level of nested @media blocks (PostCSS nesting syntax).
 */
function sg_parse_css(string $file, string $prefix): array
{
    if (!file_exists($file)) return [];
    $css = file_get_contents($file);
    $css = preg_replace('/\/\*.*?\*\//s', '', $css); // strip comments

    $results = [];
    // Match .prefix-xxx { ... } blocks with optional nested @media { }
    $pattern = '/\.' . preg_quote($prefix, '/') . '(-\w+)\s*\{((?:[^{}]|\{[^{}]*\})*)\}/s';
    preg_match_all($pattern, $css, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $class = $prefix . $match[1];
        $block = $match[2];
        $entry = ['class' => $class, 'props' => [], 'responsive' => []];

        // Direct properties (strip nested @media first)
        $direct = preg_replace('/@media[^{]*\{[^{}]*\}/s', '', $block);
        preg_match_all('/\b([\w-]+)\s*:\s*([^;{]+);/', $direct, $props, PREG_SET_ORDER);
        foreach ($props as $p) {
            $entry['props'][trim($p[1])] = trim($p[2]);
        }

        // Responsive overrides from @media blocks
        preg_match_all('/@media\s*\(([^)]+)\)\s*\{([^{}]*)\}/s', $block, $media, PREG_SET_ORDER);
        foreach ($media as $m) {
            $key = trim($m[1]);
            preg_match_all('/\b([\w-]+)\s*:\s*([^;{]+);/', $m[2], $mProps, PREG_SET_ORDER);
            foreach ($mProps as $p) {
                $entry['responsive'][$key][trim($p[1])] = trim($p[2]);
            }
        }

        $results[] = $entry;
    }

    return $results;
}

/**
 * Resolve CSS variable font-family names to human-readable labels.
 */
function sg_font_label(string $value): string
{
    return match (trim($value)) {
        'var(--font-1)' => 'BouyguesRead',
        'var(--font-2)' => 'BouyguesSpeak',
        default         => $value,
    };
}

/**
 * Resolve breakpoint custom media query keys to readable labels.
 */
function sg_breakpoint_label(string $key): string
{
    if (str_contains($key, 'lg-up'))   return 'lg+';
    if (str_contains($key, 'md-up'))   return 'md+';
    if (str_contains($key, 'xl-up'))   return 'xl+';
    if (str_contains($key, 'md-down')) return 'md−';
    return $key;
}

/**
 * Parse CSS custom properties matching a given prefix from the :root block.
 * Returns [ '--prefix-name' => 'value', ... ]
 */
function sg_parse_root_vars(string $file, string $prefix): array
{
    if (!file_exists($file)) return [];
    $css = file_get_contents($file);
    $css = preg_replace('/\/\*.*?\*\//s', '', $css);
    $css = preg_replace('/^\s*\/\/.*$/m', '', $css);

    preg_match('/:root\s*\{([^}]+)\}/s', $css, $root);
    $block = $root[1] ?? '';

    $vars = [];
    preg_match_all('/(' . preg_quote($prefix, '/') . '[\w-]+)\s*:\s*([^;]+);/', $block, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        $vars[trim($m[1])] = trim($m[2]);
    }
    return $vars;
}

function sg_parse_colors(string $file): array
{
    return sg_parse_root_vars($file, '--color-');
}


?>

<header class="sg-header">
    <h1>Styleguide</h1>

    <nav>
        <ul class="level0">
            <li>
                <a href="#sg-styles">Styles</a>
                <div class="level1">
                    <ul>
                        <li>
                            <a href="#sg-style-titles">Titles</a>
                        </li>
                        <li>
                            <a href="#sg-style-rte">Rte</a>
                        </li>
                        <li>
                            <a href="#sg-style-colors">Colors</a>
                        </li>
                        <li>
                            <a href="#sg-style-fonts">Fonts</a>
                        </li>
                    </ul>
                </div>

            </li>
            <li>
                <a href="#sg-components">Components</a>
                <div class="level1">
                    <ul>
                        <li>
                            <a href="#sg-components-btns">Btns</a>
                        </li>
                        <li>
                            <a href="#sg-components-links">Liens</a>
                        </li>
                        <li>
                            <a href="#sg-components-icons">Icons</a>
                        </li>
                        <li>
                            <a href="#sg-components-badges">Badges</a>
                        </li>
                        <li>
                            <a href="#sg-components-tags">Tags</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li>
                <a href="#sg-cards">Cards</a>
                <div class="level1">
                    <ul>
                        <li>
                            <a href="#sg-card-market">Market</a>
                        </li>
                        <li>
                            <a href="#sg-card-offer">Offer</a>
                        </li>
                        <li>
                            <a href="#sg-card-case">Case</a>
                        </li>
                        <li>
                            <a href="#sg-card-highlight">Highlight</a>
                        </li>
                        <li>
                            <a href="#sg-card-about">About</a>
                        </li>
                        <li>
                            <a href="#sg-card-key">Key</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#sg-heros">Heros</a>
                <div class="level1">
                    <ul>
                        <li>
                            <a href="#sg-hero-homepage">Homepage</a>
                        </li>
                        <li>
                            <a href="#sg-hero-page">Page</a>
                        </li>
                        <li>
                            <a href="#sg-hero-flexible">Flexible</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#sg-strates">Strates</a>
                <div class="level1">
                    <ul>
                        <li>
                            <a href="#sg-strate-markets">Markets</a>
                        </li>
                        <li>
                            <a href="#sg-strate-offers">Offers</a>
                        </li>
                        <li>
                            <a href="#sg-strate-image">Image</a>
                        </li>
                        <li>
                            <a href="#sg-strate-about">About</a>
                        </li>
                        <li>
                            <a href="#sg-strate-features">Features</a>
                        </li>
                        <li>
                            <a href="#sg-strate-text">Text</a>
                        </li>
                        <li>
                            <a href="#sg-strate-text_image">Text image</a>
                        </li>

                        <li>
                            <a href="#sg-strate-accordion">Acordéon</a>
                        </li>
                        <li>
                            <a href="#sg-strate-intro">Intro</a>
                        </li>
                        <li>
                            <a href="#sg-strate-keynumbers">Keynumbers</a>
                        </li>
                        <li>
                            <a href="#sg-strate-highlight">Highlight</a>
                        </li>
                        <li>
                            <a href="#sg-strate-cases">Cases</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</header>

<main id="main" role="main" class="styleguide">

    <!-- Styles -->
    <section class="sg-section" id="sg-styles">
        <details class="sg-details sg-stratestrate-header" id="sg-section-styles" open>
            <summary>
                <h2 class="sg-h2">1. Styles</h2>
                <?= component::icon("caret", 20, 20) ?>
            </summary>


            <!-- Titles -->
            <details class="sg-details sg-strate-header" id="sg-style-titles">
                <summary>
                    <h3 class="sg-h3">Titles</h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-part">
                    <p class="sg-note"><code>.title</code> — classe de base à combiner avec un modificateur de taille.</p>
                    <?php
                    $titleClasses  = sg_parse_css(THEME_DIR . 'assets/components/title/title.css', 'title');
                    $titleClasses  = array_values(array_filter($titleClasses, fn($t) => $t['class'] !== 'title'));
                    $allBreakpoints = [];
                    foreach ($titleClasses as $t) {
                        foreach (array_keys($t['responsive']) as $bp) {
                            $allBreakpoints[$bp] = sg_breakpoint_label($bp);
                        }
                    }
                    ?>
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Font</th>
                                <th>Default</th>
                                <?php foreach ($allBreakpoints as $label) : ?>
                                    <th><?= esc_html($label) ?></th>
                                <?php endforeach ?>
                                <th>Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($titleClasses as $t) :
                                $weight = $t['props']['font-weight'] ?? '';
                            ?>
                                <tr>
                                    <td><code><?= esc_html($t['class']) ?></code></td>
                                    <td><small><?= esc_html(sg_font_label($t['props']['font-family'] ?? '')) ?></small></td>
                                    <td><small>
                                            <?= esc_html($t['props']['font-size'] ?? '—') ?>
                                            <?= $weight ? ' / ' . esc_html($weight) : '' ?>
                                        </small></td>
                                    <?php foreach ($allBreakpoints as $bp => $label) :
                                        $rSize   = $t['responsive'][$bp]['font-size']   ?? '—';
                                        $rWeight = $t['responsive'][$bp]['font-weight'] ?? $weight;
                                    ?>
                                        <td><small><?= esc_html($rSize) ?><?= $rWeight ? ' / ' . esc_html($rWeight) : '' ?></small></td>
                                    <?php endforeach ?>
                                    <td>
                                        <p class="title <?= esc_attr($t['class']) ?>">Lorem ipsum dolor sit amet</p>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                    <h3 class="sg-h4" style="margin-top:3rem">Modificateurs</h3>
                    <p class="sg-note"><code>.title</code> — <code>&lt;strong&gt;</code> colore en --color-2, <code>&lt;span&gt;</code> active font-feature "ss01".</p>
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th>Balise</th>
                                <th>Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>&lt;strong&gt;</code></td>
                                <td>
                                    <p class="title title-2">Lorem <strong>ipsum</strong> dolor</p>
                                </td>
                            </tr>
                            <tr>
                                <td><code>&lt;span&gt;</code></td>
                                <td>
                                    <p class="title title-2">Lorem <span>ipsum</span> dolor</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </details>

            <!-- Rte -->
            <details class="sg-details sg-strate-header" id="sg-style-rte">
                <summary>
                    <h3 class="sg-h3">Rte</h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-part">
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
                </div>
            </details>

            <!-- Colors -->
            <details class="sg-details sg-strate-header" id="sg-style-colors">
                <summary>
                    <h3 class="sg-h3">Colors</h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-part">
                    <?php
                    $allColors  = sg_parse_colors(THEME_DIR . 'assets/styles/variables.css');
                    $palette    = array_filter($allColors, fn($v, $k) => preg_match('/^--color-\d+$/', $k), ARRAY_FILTER_USE_BOTH);
                    $colorText  = array_filter($allColors, fn($v, $k) => str_starts_with($k, '--color-text'), ARRAY_FILTER_USE_BOTH);
                    $colorGray  = array_filter($allColors, fn($v, $k) => str_starts_with($k, '--color-gray'), ARRAY_FILTER_USE_BOTH);
                    ?>

                    <?php if ($palette) : ?>
                        <h3 class="sg-h4">Palette</h3>
                        <div class="sg-grid cols-4">
                            <?php foreach ($palette as $name => $value) : ?>
                                <div class="sg-color" style="--color: <?= esc_attr($value) ?>">
                                    <div>
                                        <div class="sg-color-name"><?= esc_html(str_replace('--color-', '', $name)) ?></div>
                                        <small><?= esc_html($name) ?></small>
                                        <small><?= esc_html($value) ?></small>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <?php if ($colorText) : ?>
                        <h3 class="sg-h4">Text</h3>
                        <div class="sg-grid cols-4">
                            <?php foreach ($colorText as $name => $value) : ?>
                                <div class="sg-color" style="--color: <?= esc_attr($value) ?>">
                                    <div>
                                        <div class="sg-color-name"><?= esc_html(str_replace('--color-', '', $name)) ?></div>
                                        <small><?= esc_html($name) ?></small>
                                        <small><?= esc_html($value) ?></small>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <?php if ($colorGray) : ?>
                        <h3 class="sg-h4">Grays</h3>
                        <div class="sg-grid cols-4">
                            <?php foreach ($colorGray as $name => $value) : ?>
                                <div class="sg-color" style="--color: <?= esc_attr($value) ?>">
                                    <div>
                                        <div class="sg-color-name"><?= esc_html(str_replace('--color-', '', $name)) ?></div>
                                        <small><?= esc_html($name) ?></small>
                                        <small><?= esc_html($value) ?></small>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                </div>
            </details>

            <!-- Fonts -->
            <details class="sg-details sg-strate-header" id="sg-style-fonts">
                <summary>
                    <h3 class="sg-h3">Fonts</h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-part">
                    <?php
                    $fonts = sg_parse_root_vars(THEME_DIR . 'assets/styles/variables.css', '--font-');
                    ?>
                    <div class="sg-grid cols-2">
                        <?php foreach ($fonts as $name => $value) :
                            $label = sg_font_label($name);
                        ?>
                            <div class="sg-font-card">
                                <p style="font-family: <?= esc_attr($value) ?>; font-size: 3.2rem; line-height: 1.2; margin-bottom: 1rem">
                                    Aa Bb Cc Dd Ee<br>
                                    0 1 2 3 4 5 6 7 8 9
                                </p>
                                <strong><?= esc_html($label) ?></strong>
                                <small style="display:block; opacity:.6"><?= esc_html($name) ?></small>
                                <small style="display:block; opacity:.4"><?= esc_html($value) ?></small>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </details>


        </details>
    </section>

    <!-- Cards -->
    <section class="sg-section" id="sg-cards">
        <details class="sg-details sg-stratestrate-header" id="sg-section-cards" open>
            <summary>
                <h2 class="sg-h2">3. Cards</h2>
                <?= component::icon("caret", 20, 20) ?>
            </summary>

            <!-- Card News -->
            <?php part([
                "type"    => "card",
                "cols"    => "3",
                "label"   => "News",
                "name"    => "card-news",
                "options" => [
                    "classes" => [
                        ["title" => "Horizontal", "value" => "horizontal"],
                    ],
                ],
                "args" => [
                    "title"  => "Lorem ipsum dolor sit amet",
                    "images" => ["desktop" => 460],
                ],
            ]); ?>
        </details>
    </section>


    <!-- Heros -->
    <section class="sg-section" id="sg-heros">
        <details class="sg-details sg-stratestrate-header" id="sg-section-heros" open>
            <summary>
                <h2 class="sg-h2">4. Heros</h2>
                <?= component::icon("caret", 20, 20) ?>
            </summary>

            <!-- Hero Homepage -->
            <?php part([
                "type"  => "hero",
                "label" => "Homepage",
                "name"  => "hero-homepage",
                "args"  => [
                    "title"  => "Lorem ipsum dolor sit amet",
                    "images" => ["desktop" => 460],
                ],
            ]); ?>

            <!-- Hero Page -->
            <?php part([
                "type"  => "hero",
                "label" => "Page",
                "name"  => "hero-page",
                "args"  => ["title" => "Lorem ipsum dolor sit amet"],
            ]); ?>
        </details>
    </section>


</main>



<script>
    document.querySelector("title").innerText = "Styleguide";

    // Persist <details> open state across reloads
    const SG_KEY = "sg-open";
    localStorage.removeItem("sg-details");
    const sgState = JSON.parse(localStorage.getItem(SG_KEY) || "{}");

    document.querySelectorAll("details.sg-strate-header[id]").forEach(el => {
        if (el.id in sgState) el.open = sgState[el.id];
        el.addEventListener("toggle", () => {
            sgState[el.id] = el.open;
            localStorage.setItem(SG_KEY, JSON.stringify(sgState));
        });
    });
    const test = document.querySelectorAll("[class*=card-]");

    // selects
    const selects = document.querySelectorAll(".sg-select");
    selects.forEach(select => {
        const nextElement = select.closest(".sg-options").nextElementSibling;
        let els = [];
        els.push(nextElement);
        if (nextElement.classList.contains("sg-part")) {
            els = nextElement.querySelectorAll("[class*=card-],[class*=nj-btn],[class*=nj-icon-material],[class*=nj-tag]");
        }

        const options = select.querySelectorAll("option");
        const classes = [];
        options.forEach(option => option.value && classes.push(option.value))
        const value = select.options[select.selectedIndex].value;
        els.forEach(el => value && el.classList.add(value))

        select.onchange = () => {
            classes.forEach(classe => classe && els.forEach(el => el.classList.remove(classe)))
            els.forEach(el => select.value && el.classList.add(select.value))
        }
    })

    // checkbox
    const checkboxs = document.querySelectorAll(".sg-checkbox");
    checkboxs.forEach(checkbox => {
        const nextElement = checkbox.closest(".sg-options").nextElementSibling;
        let el = nextElement;
        if (nextElement.classList.contains("sg-part")) {
            el = nextElement.querySelector("[class*=card-]");
        }
        el.classList[checkbox.checked ? "add" : "remove"](checkbox.value);
        checkbox.onchange = () => {
            el.classList[checkbox.checked ? "add" : "remove"](checkbox.value);
        }
    })

</script>


<style>
    html {
        scroll-behavior: smooth;
    }

    .sg-header {
        background-color: aliceblue;
        padding: 0px var(--ctr-offset);
        display: flex;
        align-items: center;
        gap: 40px;
        position: sticky;
        top: 0;
        z-index: 100;
        height: 80px;
        box-sizing: border-box;
        background-color: #319fff;
        color: #fff;

        nav {
            display: flex;
            display: flex;
            align-items: baseline;
            height: 100%;

        }

        .level0 {
            height: 100%;
            display: flex;
            background-color: #319fff;

            >li {
                /*position: relative;*/
                height: 100%;
                transition: background-color .2s ease;

                &:hover {
                    /* background-color: #2a90ea;*/

                    >a {
                        text-decoration: underline;
                        text-decoration-thickness: 1px;
                        text-underline-offset: 3px;
                    }

                    .level1 {
                        pointer-events: initial;
                        clip-path: polygon(100% 0, 100% 100%, 0 100%, 0 0);
                        transition: clip-path .3s ease 0.1s;

                        ul {
                            translate: 0 0px;
                            opacity: 1;
                        }
                    }

                    &:before {
                        display: block;
                    }
                }
            }

        }

        .level1 {
            height: 100%;
            columns: 3;
            background-color: #2a90ea;
            top: 100%;
            left: 0;
            width: 100%;
            height: auto;
            position: absolute;
            z-index: 2;
            display: block;
            gap: 0;
            transition: clip-path .3s ease 0s;
            pointer-events: none;
            padding: 60px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            clip-path: polygon(100% 0, 100% 0%, 0 0%, 0 0);

            ul {
                display: inline-flex;
                flex-wrap: wrap;
                max-width: 1024px;
                translate: 0 -20px;
                opacity: 0;
                gap: 40px;
                transition: translate .3s ease, opacity .3s ease;
            }

            li {
                width: auto;
            }

            a {
                border: 1px dotted rgba(255, 255, 255, .5);
                min-width: 160px;
            }
        }

        a {
            display: flex;
            align-items: center;
            font-size: 18px;
            height: 100%;
            box-sizing: border-box;
            padding: 20px;
            justify-content: center;


            &:hover {
                text-decoration: underline;
                text-decoration-thickness: 1px;
                text-underline-offset: 3px;
            }
        }
    }

    .sg-section {
        scroll-margin-top: 80px;
    }

    .sg-h2 {
        font-size: 32px;
        color: #000;
        padding-top: 15px;
        padding-bottom: 15px;
        box-sizing: border-box;
        height: 69px;
        font-weight: 500;

        small {
            display: inline-block;
            font-size: 24px;
            opacity: .5;
            margin-left: 10px;
            font-weight: 300;
        }
    }

    .sg-h3 {
        font-size: 24px;
        padding-top: 18px;
        padding-bottom: 18px;
        color: #000;
        font-weight: 500;

        small {
            display: inline-block;
            font-size: 18px;
            opacity: .5;
            margin-left: 10px;
            font-weight: 400;
        }

        b {
            font-weight: 700
        }
    }

    .sg-h4 {
        margin-bottom: 20px;
        font-size: 24px;

        &:not(:first-of-type) {
            margin-top: 20px;
        }
    }

    .sg-details {
        scroll-margin-top: 150px;
        background-color: #fff;

        summary {
            padding-left: var(--ctr-offset);
            padding-right: var(--ctr-offset);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #cccccc9a;
            cursor: pointer;
            background-color: #fff;
            border-left: 0px solid #ffb300;
         

            h2,
            h3 {
                transition: background-color .25s ease, translate .25s ease;
            }

            .icon {
                transition: rotate .2s ease;
            }
        }

        &.sg-stratestrate-header {
            --sg-bg: #c8ddf263;
            --sg-bg: #ecf3fb;

            >summary {
                position: sticky;
                top: 80px;
                z-index: 5
            }
        }

        &.sg-strate-header {
            --sg-bg: #e9e9da;
            /*>summary {
                position: sticky;
                top: 150px;
                z-index: 4
            }*/
        }

        &[open],
        &:hover {
            >summary {
                background-color: var(--sg-bg);
                /*  border-left-width: 6px;*/
            }
        }

        &[open] {
            >summary {
                .icon {
                    rotate: 180deg
                }
            }
        }
    }

    .sg-options {
        padding: 20px 40px;
        background: rgba(0, 0, 0, .05);
        display: flex;
        gap: 30px;
        align-items: center;
        border-bottom: 1px solid #ddd;

        label {
            font-weight: 500;
            margin-right: 6px;
        }
    }

    .sg-grid {
        display: grid;
        grid-template-columns: repeat(var(--cols), 1fr);
        gap: 20px;
        align-items: start;
        width: 100%;


        &.cols-3 {
            --cols: 3;
        }

        &.cols-4 {
            --cols: 4;
        }

        &.cols-5 {
            --cols: 5;
        }

        &.cols-6 {
            --cols: 6;
        }

        &.cols-7 {
            --cols: 7;
        }

        &.cols-8 {
            --cols: 8;
        }
    }

    .sg-part {
        display: grid;
        justify-content: start;
        justify-items: start;
        padding: 30px var(--ctr-offset) 40px;
        gap: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, .15);
        box-sizing: border-box;
    }

    .sg-color {
        display: grid;
        grid-template-columns: min-content 1fr;
        gap: 20px;
        margin-bottom: 20px;

        small {
            opacity: .6;
        }

        &:before {
            content: "";
            width: 120px;
            height: 60px;
            background: var(--color);
            margin-bottom: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, .15);
            border: 1px #e3e2e2 solid;
        }
    }

    .sg-color-name {
        font-weight: 600;
    }

    .sg-table {
        width: 100%;
        border: 1px solid rgba(0, 0, 0, .15);

        thead {
            margin-bottom: 10px;
            background-color: rgba(0, 0, 0, .03);
        }

        th {
            text-align: left;
        }


        td,
        th {
            padding: 14px 12px;
            border-right: 1px dotted rgba(0, 0, 0, .2);

            &:first-of-type {
                width: fit-content;
                white-space: nowrap;
                width: 100px;
            }


        }

        .center {
            text-align: center;
            width: 80px !important;
            ;
        }

        &::has(thead) {

            td,
            th {
                &:first-of-type {
                    width: fit-content;
                    white-space: nowrap;
                    width: 100px;
                }

                &:last-of-type {
                    width: 80px;
                    text-align: center;
                    border-right: none;
                }

                &:nth-last-child(2) {
                    width: 80px;
                    text-align: center;
                }
            }
        }

        &:not(:has(thead)) {
            tr {
                border-bottom: 1px solid rgba(0, 0, 0, .15);
                ;
            }
        }
    }

    .sg-code {
        font-size: 18px;

        strong {
            font-weight: 400;
            color: #52a65c;
            font-size: inherit;
        }

        b {
            font-weight: 400;
            opacity: .5;
        }

        small {
            font-weight: 400;
            font-size: inherit;
            color: #ff8f00
        }

        em {
            font-size: 14px;
            opacity: .8;
        }
    }

    .styleguide {
        pre {
            font-size: 14px;
            font-weight: 300;
            opacity: .8;
        }

    }
</style>
<?php get_tpl();
