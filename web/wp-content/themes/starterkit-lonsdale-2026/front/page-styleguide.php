<?php
/*
Template Name: Front styleguide
*/

get_header();
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

    <?php
    $card_picto = [
        "images" => [
            "desktop" => 117
        ],
        "title" => "Industrialiser vos modèles de revenus",
        "text" => "<p>Pour passer à l’échelle en toute confiance</p>"
    ];

    $card_quote_big = [
        "title" => "<strong>Citatio)))n</strong> avec image",
        "text" => "<p>La Value Factory est avant tout une aventure collective. Notre vision, c’est de co-construire avec nos partenaires des solutions utiles et efficaces, en mettant la puissance de la technologie au service de leurs ambitions.</p>",
        "name" => "Didier Delacourt",
        "function" => "Directeur nom de l’entreprise",
        "is_image_big" => true,
        "images" => [
            "desktop" => 743,
        ],
        "logo" => 120
    ];
    $card_quote = [
        "title" => "<strong>Citatio)))n</strong> sans image",
        "text" => "<p>La Value Factory est avant tout une aventure collective. Notre vision, c’est de co-construire avec nos partenaires des solutions utiles et efficaces, en mettant la puissance de la technologie au service de leurs ambitions.</p>",
        "name" => "Didier Delacourt",
        "function" => "Directeur nom de l’entreprise",
        "is_image_big" => false,
        "images" => [
            "desktop" => 743,
        ],
        "logo" => 120
    ];
    $card_quote_without = [
        "title" => "<strong>Citatio)))n</strong> sans image",
        "text" => "<p>La Value Factory est avant tout une aventure collective. Notre vision, c’est de co-construire avec nos partenaires des solutions utiles et efficaces, en mettant la puissance de la technologie au service de leurs ambitions.</p>",
        "name" => "Didier Delacourt",
        "function" => "Directeur nom de l’entreprise",
        "is_image_big" => false,
        "images" => [],
        "logo" => 120
    ]
    ?>

    <?php
    $options = [
        "options" => [
            "container" => "",
            "margin" => [
                "bottom" => "md",
                "top" => ""
            ],
            "background" => [
                "hasbackground" => false,
                "color" => "color-1",
                "padding" => [
                    "top" => "md",
                    "bottom" => "md"
                ]
            ],
            "id" => ""
        ],
    ]
    ?>

    <?php

    $option_cols = '
    <div>
        <label>2 colonnes: </label>
        <input type="checkbox" class="sg-checkbox" value="cols">
    </div>';

    $option_reverse = '
    <div>
        <label>Reverse: </label>
        <input type="checkbox" class="sg-checkbox" value="reverse">
    </div>';

    $option_has_pattern = '
    <div>
        <label>Pattern ? </label>
        <input type="checkbox" class="sg-checkbox" value="has_pattern">
    </div>';

    $option_is_number = '
    <div>
        <label>Is number ?: </label>
        <input type="checkbox" class="sg-checkbox" value="is_number">
    </div>';



    ?>

    <!-- Strates -->
    <section class="sg-section" id="sg-strates">
        <details class="sg-details sg-stratestrate-header" id="sg-section-strates">
            <summary>
                <h2 class="sg-h2">5. Strates</h2>
                <?= component::icon("caret", 20, 20) ?>
            </summary>

            <!-- Wysiwyg -->
            <details class="sg-details sg-strate-header" id="sg-strates-1">
                <summary>
                    <h3 class="sg-h3"><b>10.2.1 | 10.2.3</b> Wysiwyg <small>strate-text</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-options">
                    <?= $option_cols ?>
                </div>

                <!--      <div class="sg-options">
                    <div>
                        <label>Container: </label>
                        <select class="sg-select">
                            <option value="">Aucun</option>
                            <option value="ctr-md">Large</option>
                            <option value="ctr-sm">Moyen</option>
                            <option value="ctr-xs">Petit</option>
                        </select>
                    </div>

                    <div>
                        <label>Margin top: </label>
                        <select class="sg-select">
                            <option value="">Aucun</option>
                            <option value="mt-sm">Petit</option>
                            <option value="mt-md">Moyen</option>
                            <option value="mt-lg">Large</option>
                        </select>
                    </div>
                    <div>
                        <label>Margin bottom: </label>
                        <select class="sg-select">
                            <option value="">Aucun</option>
                            <option value="mb-sm">Petit</option>
                            <option value="mb-md">Moyen</option>
                            <option value="mb-lg">Grand</option>
                        </select>
                    </div>
                    <div>
                        <label>backgorund: </label>
                        <select class="sg-select">
                            <option value="">Aucun</option>
                            <option value="bg-color-1">Bleu foncé</option>
                            <option value="bg-color-3">Bleu clair</option>
                        </select>
                    </div>
                    <div>
                        <label>backgorund padding top: </label>
                        <select class="sg-select">
                            <option value="">Aucun</option>
                            <option value="pt-sm">Petit</option>
                            <option value="pt-md">Moyen</option>
                            <option value="pt-lg">Grand</option>
                        </select>
                    </div>

                    <div>
                        <label>backgorund padding bottom: </label>
                        <select class="sg-select">
                            <option value="">Aucun</option>
                            <option value="pt-sm">Petit</option>
                            <option value="pt-md">Moyen</option>
                            <option value="pt-lg">Grand</option>
                        </select>
                    </div>
                </div> -->

                <?= get_template_part('template-parts/strates/strate', 'text', [
                    ...$options,
                    "is_cols" => false,
                    "title" => "Ceci est un bloc SEO <strong>d)))e texte</strong>",
                    "text" => "
                    <h2>Urna tristique eget tellus dui sed velit ultrices egestas id. Nibh dis dictum sed</h2>
                    <p>Le paiement sur facture est une solution de micropaiement alternatif qui a été développée par les opérateurs télécoms membres de <a href=''>l’AF2M.</a></p>
                    <p>Il permet aujourd’hui d’acheter de nombreux biens et services numériques comme :</p>
                    <ul>
                        <li>les applications sur les stores,</li>
                        <li>les abonnements à des services de streaming vidéo, musical et des jeux,</li>
                        <li>les tickets de transport, de parking, de concert,</li>
                        <li>les votes et les jeux au sein de programmes TV ou radio,</li>
                        <li>Les achats sont ensuite directement reportés sur la facture du client.</li>
                    </ul>
                    <p>C’est ce service de <a href=''>paiement sur facture</a> qui permet également aux clients des opérateurs télécoms d’effectuer des dons à des associations caritatives de manière simple, rapide et en toute sécurité via SMS.
                    Comment effectuer un don par SMS ? <a href=''>On vous explique ici.</a><br>
                    <strong>A suspendisse eu auctor lacus. Venenatis mattis habitant metus vestibulum ut senectus ut at. Suscipit tellus faucibus massa viverra. </strong></p>",
                    "link" => [
                        "title" => "En savoir plus sur la Value Factory",
                        "url" => "/",
                        "target" => ""
                    ]
                ]); ?>
            </details>

            <!-- Wysiwyg -->
            <details class="sg-details sg-strate-header" id="sg-strates-2">
                <summary>
                    <h3 class="sg-h3"><b>10.2.2</b> Text + picto <small>strate-text_picto</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <?= get_template_part('template-parts/strates/strate', 'text_picto', [
                    ...$options,
                    "images" => [
                        "desktop" => 121
                    ],
                    "text" => "<p>Sagittis orci tempor id pellentesque iaculis sollicitudin morbi id. Mattis rutrum pulvinar odio amet volutpat sem augue. Felis placerat posuere feugiat quam nullam. Nulla sed tristique orci arcu sed pellentesque. Morbi tincidunt bibendum interdum integer sapien commodo vitae ut etiam.</p>",
                ]); ?>
            </details>

            <!-- Texte animé -->
            <details class="sg-details sg-strate-header" id="sg-strates-3">
                <summary>
                    <h3 class="sg-h3"><b>10.3</b> Texte animé <small>strate-text_animate</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <?= get_template_part('template-parts/strates/strate', 'text_animate', [
                    ...$options,
                    "tag" => "Notre conviction",
                    "text" => "À la  Value Factory, nous ne promettons pas des idées. Nous coconstruisons des business au service de la valeur.",
                ]); ?>
            </details>

            <!-- Texte + image -->
            <details class="sg-details sg-strate-header" id="sg-strates-4">
                <summary>
                    <h3 class="sg-h3"><b>10.4</b> Texte + image <small>strate-text_image</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-options">
                    <?= $option_reverse ?>
                </div>

                <?= get_template_part('template-parts/strates/strate', 'text_image', [
                    ...$options,
                    "is_reverse" => false,
                    "is_title_col" => true,
                    "title" => "Nous ne promettons pas des idées. Nous coconstruisons <strong>des business.</strong>",
                    "text" => "
                    <p>La Value Factory, c’est un moteur de création de nouveaux modèles économiques. Ni un lab, ni une entité Marketing, mais une entité hybride opérationnelle capable de passer :</p>
                        <ul>
                        <li><strong>De l'intuition à la vérification business</strong></li>
                        <li><strong>Du test unitaire à l'industrialisation</strong></li>
                        <li><strong>De la technologie au développement de valeur</strong></li>
                        </ul>
                        <p>Nous pensons en modèle de valeur avant de penser en fonctionnalité. Nous avançons en binôme : tech & business, main dans la main, du cadrage à la mise sur le marché.</p>
                        ",
                    "images" => [
                        "desktop" => 7,
                        "mobile" => 8
                    ],
                    "link" => [
                        "title" => "Comprendre notre vision",
                        "url" => "/"
                    ]
                ]); ?>
            </details>

            <!-- Texte + cards -->
            <details class="sg-details sg-strate-header" id="sg-strates-5">
                <summary>
                    <h3 class="sg-h3"><b>10.5</b> Texte + cards <small>strate-text_pictos</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-options">
                    <?= $option_cols ?>
                    <?= $option_has_pattern ?>
                </div>

                <?= get_template_part('template-parts/strates/strate', 'text_pictos', [
                    ...$options,
                    "is_cols" => false,
                    "title" => "Notre modèle est fait pour celles et ceux qui veulent <strong>faire vite, bien, et autrement.</strong>",
                    "text" => "<p>La Value Factory repousse les codes établis pour inventer des services utiles, performants et inattendus, capables de créer de la valeur dès aujourd’hui.</p>",
                    "link" => [],
                    "items_bg" => "",
                    "items" => [$card_picto, $card_picto, $card_picto]
                ]); ?>
            </details>

            <!-- Etapes -->
            <details class="sg-details sg-strate-header" id="sg-strates-6">
                <summary>
                    <h3 class="sg-h3"><b>10.6 | 10.7 </b> Etapes <small>strate-steps</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-options">
                    <?= $option_cols ?>
                    <?= $option_reverse ?>
                </div>

                <?= get_template_part('template-parts/strates/strate', 'steps', [
                    ...$options,
                    "is_cols" => false,
                    "is_reverse" => false,
                    "title" => "Bloc <strong>Timeline + image</strong>",
                    "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
                    "images" => [
                        "desktop" => 7,
                        "mobile" => 8
                    ],
                    "is_number" => true,
                    "items" => [
                        [
                            "title" => "Nom de l’étape",
                            "text" => "Varius adipiscing imperdiet commodo vestibulum feugiat id vitae velit dictum. Vitae ultricies dapibus id tristique morbi egestas eleifend eu est."
                        ],
                        [
                            "title" => "Lorem ipsum",
                            "text" => "Varius adipiscing imperdiet commodo vestibulum feugiat id vitae velit dictum. Vitae ultricies dapibus id tristique morbi egestas eleifend eu est."
                        ]
                    ]
                ]); ?>

            </details>

            <!-- Citations -->
            <details class="sg-details sg-strate-header" id="sg-strates-7">
                <summary>
                    <h3 class="sg-h3"><b>10.8.1 | 10.8.2 | 10.8.3 | 10.8.4 </b> Citations <small>strate-quotes</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <div class="sg-options">
                    <?= $option_has_pattern ?>
                    <div>
                        <label>Est un slider ? </label>
                        <input type="checkbox" class="sg-checkbox" value="variant">
                    </div>
                </div>

                <?= get_template_part('template-parts/strates/strate', 'quotes', [
                    ...$options,
                    "is_slider" => true,
                    "has_pattern" => true,
                    "title" => "POpsodpsoqd",
                    "text" => "",
                    "items" => [
                        $card_quote,
                        $card_quote_big,
                        $card_quote_big
                    ]

                ]); ?>

            </details>

            <!-- Citations croisées -->
            <details class="sg-details sg-strate-header" id="sg-strates-8">
                <summary>
                    <h3 class="sg-h3"><b>10.8.5</b> Citations croisées <small>strate-quotes_cross</small></h3>
                    <?= component::icon("caret", 20, 20) ?>
                </summary>

                <?= get_template_part('template-parts/strates/strate', 'quotes_cross', [
                    ...$options,
                    "title" => "Bloc <strong>Citation cro)))isée</strong>",
                    "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
                    "items" => [
                        [
                            "text" => "<p>La Value Factory est avant tout une aventure collective. Notre vision, c’est de co-construire avec nos partenaires des solutions utiles et efficaces, en mettant la puissance de la technologie au service de leurs ambitions.</p>",
                            "images_1" => [
                                "desktop" => 7,

                            ],
                            "name_1" => "Didier Delacourt",
                            "function_1" => "Profil Thecnique",
                            "images_2" => [
                                "desktop" => 8
                            ],
                            "name_2" => "Didier Delacourt",
                            "function_2" => "Profil Business",
                        ],
                        [
                            "text" => "<p>La Value Factory est avant tout une aventure collective. Notre vision, c’est de co-construire avec nos partenaires des solutions utiles et efficaces, en mettant la puissance de la technologie au service de leurs ambitions.</p>",
                            "images_1" => [
                                "desktop" => 531,
                            ],
                            "name_1" => "Didier Delacourt",
                            "function_1" => "Profil Thecnique",
                            "images_2" => [
                                "desktop" => 489,

                            ],
                            "name_2" => "Didier Delacourt",
                            "function_2" => "Profil Business",
                        ],
                    ],
                ]); ?>


            </details>
        </details>
    </section>

</main>




<script>
    document.querySelector("title").innerText = "Styleguide";
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

    // detail with local storage
    const details = document.querySelectorAll("details");
    let details_status = [];
    var cat = localStorage.getItem("sg-details");
    if (!cat) {
        localStorage.setItem("sg-details", JSON.stringify(details_status));
    } else {
        details_status = JSON.parse(cat);
    }

    details_status.forEach(status => {
        status && document.getElementById(status).setAttribute('open', true);
    })

    details.forEach(detail => {
        detail.addEventListener("toggle", (evt) => {
            if (detail.open) {
                !details_status.includes(detail.id) && details_status.push(detail.id);
            } else {
                details_status.splice(details_status.indexOf(detail.id), 1);
            }
            localStorage.setItem("sg-details", JSON.stringify(details_status));
        })
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
        display: grid;
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
            transition: border .3s ease;

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
<?php
get_footer();
