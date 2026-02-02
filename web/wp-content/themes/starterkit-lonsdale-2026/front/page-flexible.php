<?php
/*
Template Name: Front flexible
*/

get_header();
get_template_part('template-parts/general/block', 'header_nav');
?>

<?= get_template_part('template-parts/general/block', 'breadcrumb'); ?>

<?= get_template_part('template-parts/heros/hero', "flexible", [
    "title" => "Hero <b>flexible</b>",
    "text" => "25 millions de Français interagissent chaque jour avec Bouygues Telecom.",
    "images" => [
        "desktop" => 8
    ],
]); ?>

<main class="sg-main">

    <h1 class="sg-heading">Cards</h1>

    <?php
    $options = [
        "options" => [
            "container" => "xs",
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

    <!-- Cards -->
    <div class="sg-cards">

        <h1 class="sg-heading" class="sg-heading">Card picto <strong>card-picto</strong></h1>

        <div class="sg-card">
            <?php
            $card_picto = [
                "images" => [
                    "desktop" => 117
                ],
                "title" => "Industrialiser vos modèles de revenus",
                "text" => "<p>Pour passer à l’échelle en toute confiance</p>"
            ]
            ?>
            <?= get_template_part('template-parts/cards/card', 'picto', $card_picto) ?>

            <?php
            $card_picto["title"] =  "Générer de nouveaux revenus digitaux";
            // $card_picto["text"] =  "Sans complexité technique";
            ?>
            <?= get_template_part('template-parts/cards/card', 'picto', $card_picto) ?>
        </div>

        <hr>

        <h1 class="sg-heading" class="sg-heading">Card quote <strong>card-quote</strong></h1>

        <div class="sg-card-1">
            <?php
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
            <?= get_template_part('template-parts/cards/card', 'quote', $card_quote) ?>
            <?php
            $card_quote["title"] = "<strong>Citatio)))n</strong> avec image";
            ?>
            <?= get_template_part('template-parts/cards/card', 'quote', $card_quote_big) ?>
        </div>

        <hr>

        <h1 class="sg-heading" class="sg-heading">Card keynumber <strong>card-keynumber</strong></h1>

        <div class="sg-card">
            <?php
            $card_keynumber =  [
                "images" => [],
                "prefix" => "+",
                "number" => "48",
                "suffix" => "M",
                "text" => "Partenaires actifs"
            ];
            $card_keynumber_picto =  [
                "images" => [
                    "desktop" => 117
                ],
                "prefix" => "+",
                "number" => "48",
                "suffix" => "M",
                "text" => "Partenaires actifs"
            ];
            ?>
            <?= get_template_part('template-parts/cards/card', 'keynumber', $card_keynumber) ?>

            <?= get_template_part('template-parts/cards/card', 'keynumber', $card_keynumber_picto) ?>

        </div>

        <hr>

        <h1 class="sg-heading" class="sg-heading">Card timeline <strong>card-timeline</strong></h1>

        <div class="sg-card-2">
            <?php
            $card_timeline = [
                "date" => 2025,
                "title" => "Arcu morbi id lacus nunc justo ut <strong>urna et.</strong>",
                "text" => "<p>Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet. Amet minim mollit</p>",
                "images" => [
                    "desktop" => 7,
                    "mobile" => 8
                ],
            ]
            ?>
            <?= get_template_part('template-parts/cards/card', 'timeline', $card_timeline) ?>
        </div>

        <hr>

        <h1 class="sg-heading" class="sg-heading">Card page <strong>card-page</strong></h1>

        <div class="sg-card-2">
            <?php
            $card_page = [
                "title" => "Id sed auctor ac id ultricies",
                "images" => [
                    "desktop" => 7
                ],
                "url" => "/"
            ];
            ?>
            <?= get_template_part('template-parts/cards/card', 'page', $card_page) ?>
        </div>

        <hr>

        <h1 class="sg-heading" class="sg-heading">Card news <strong>card-news</strong></h1>

        <div class="sg-card">
            <?php
            $card_news = [
                "static" => true,
                "category" =>  "Categorie",
                "title" => "Amet pharetra urna gravida vitae convallis non et felis ultrices. Sed tincidunt in diam quisque sed diam molestie a. Turpis et.",
                "images" => [
                    "desktop" => 7,
                ],
                "date" => "12/08/2024 ",
                "readtime" =>  "5mn",
            ]
            ?>
            <?= get_template_part('template-parts/cards/card', 'news', $card_news) ?>
        </div>

        <hr>

        <h1 class="sg-heading" class="sg-heading">Card solution <strong>card-solution</strong></h1>

        <div class="sg-card">
            <?php
            $card_solution = [
                "picto" =>  121,
                "title" => "Monétisation & facturation",
                "text" => "Réduire la fraude et simplifier facilement l’onboarding client ",
                "images" => [
                    "desktop" => 7,
                ],
                "url" => "/"
            ]
            ?>
            <?= get_template_part('template-parts/cards/card', 'solution', $card_solution) ?>
        </div>
    </div>

    <hr>



    <h1 class="sg-heading">Strates</h1>

    <!-- strates -->
    <h1 class="sg-heading">10.2.1 Wysiwyg | 10.2.3 Wysiwyg 2 colonnes<strong>strate-text</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'text', [
        ...$options,
        "is_cols" => true,
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

   <?= get_template_part('template-parts/strates/strate', 'text', [
        ...$options,
        "is_cols" => false,
        "title" => "Bloc text <strong>d)))e texte</strong>",
        "text" => "
            <h2>Urna tristique eget tellus dui sed velit ultrices egestas id. Nibh dis dictum sed</h2>
            <p>Le paiement sur facture est une solution de micropaiement alternatif qui a été développée par les opérateurs télécoms membres de <a href=''>l’AF2M.</a></p>
            <p>Il permet aujourd’hui d’acheter de nombreux biens et services numériques comme :</p>
        
            <p>C’est ce service de <a href=''>paiement sur facture</a> qui permet également aux clients des opérateurs télécoms d’effectuer des dons à des associations caritatives de manière simple, rapide et en toute sécurité via SMS.
            Comment effectuer un don par SMS ? <a href=''>On vous explique ici.</a><br>
            <strong>A suspendisse eu auctor lacus. Venenatis mattis habitant metus vestibulum ut senectus ut at. Suscipit tellus faucibus massa viverra. </strong></p>",
        "link" => [
            "title" => "",
            "url" => "",
            "target" => ""
        ]
    ]); ?>
    <hr>

    <h1 class="sg-heading">10.2.2 Wysiwyg + picto (texte centré) <strong>strate-text_picto</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'text_picto', [
        ...$options,
        "images" => [
            "desktop" => 121
        ],
        "text" => "<p>Sagittis orci tempor id pellentesque iaculis sollicitudin morbi id. Mattis rutrum pulvinar odio amet volutpat sem augue. Felis placerat posuere feugiat quam nullam. Nulla sed tristique orci arcu sed pellentesque. Morbi tincidunt bibendum interdum integer sapien commodo vitae ut etiam.</p>",
    ]); ?>

    <hr>


    <h1 class="sg-heading">10.3 Texte animé <strong>strate-text_animate</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'text_animate', [
        ...$options,
        "tag" => "Notre conviction",
        "text" => "À la  Value Factory, nous ne promettons pas des idées. Nous coconstruisons des business au service de la valeur.",
    ]); ?>

    <hr>

    <h1 class="sg-heading">10.4 Texte + image + titre dans la colonne <strong>strate-text_image</strong></h1>

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

    <h1 class="sg-heading">10.4 Texte + image + reverse + titre dans la colonne <strong>strate-text_image</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'text_image', [
        ...$options,
        "is_reverse" => true,
        "is_title_col" => true,
        "title" => "L’opérateur qui pense au-delà de la <strong>co)))nnectivité</strong>",
        "text" => "
            <h3><span style='font-family: var(--font-2);'>Bouygues Telecom, au-delà des abonnements, des réseaux et des box.</span></h3>
            <p>C’est aussi une plateforme puissante, prête à accueillir de nouveaux business — et à leur donner corps. C’est là que la Value Factory entre en jeu.</p>
            ",
        "images" => [
            "desktop" => 8,
            "mobile" => 8
        ],
        "link" => []
    ]); ?>

    <h1 class="sg-heading">10.4 Texte + image <strong>strate-text_image</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'text_image', [
        ...$options,
        "is_reverse" => false,
        "title" => "Bloc Texte + Visuel <strong>reverse</strong>",
        "text" => "
            <p><strong>Ante egestas egestas eu vitae nibh sodales. Sit eget non est condimentum integer quis.</strong></p>
            <p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. Risus arcu scelerisque dui consequat rutrum. Velit cras tellus sit volutpat in. Etiam odio pellentesque sollicitudin in orci vel viverra condimentum. <a href=''>Ornare lorem commodo</a> amet ut interdum euismod augue natoque. Dui nec et nulla lorem at tempus ut. Diam pellentesque nisl egestas blandit ac. Duis elementum senectus maecenas tellus eros risus nisl ultricies. Hendrerit in elementum eget lorem. Etiam mattis dolor tempus sit tristique varius fermentum neque.</p>
            ",
        "images" => [
            "desktop" => 8,
            "mobile" => 8
        ],
        "link" => [
            "title" => "Comprendre notre vision",
            "url" => "/"
        ]
    ]); ?>
    <hr>

    <h1 class="sg-heading">10.5 Texte + cards <strong>strate-text_pictos</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'text_pictos', [
        ...$options,
        "is_cols" => false,
        "title" => "Notre modèle est fait pour celles et ceux qui veulent <strong>faire vite, bien, et autrement.</strong>",
        "text" => "<p>La Value Factory repousse les codes établis pour inventer des services utiles, performants et inattendus, capables de créer de la valeur dès aujourd’hui.</p>",
        "link" => [],
        "items_bg" => "",
        "items" => [$card_picto, $card_picto, $card_picto]
    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'text_pictos', [
        ...$options,
        "is_cols" => true,
        "title" => "Bloc <strong>texte + picto / 2 colonnes</strong>",
        "text" => "
        <p><strong>Générer de nouveaux revenus digitaux</strong></p>
        <p>Convallis laoreet vestibulum aliquet ultrices mattis vestibulum eros urna maecenas. In a pharetra sit pharetra. Dui purus vulputate justo pellentesque. Morbi vestibulum eu mi enim libero etiam leo. Enim suspendisse arcu a viverra massa. Lorem ultricies bibendum nibh tristique vel egestas quam lorem sed.</p>",
        "link" => [
            "title" => "Exemple de CTA",
            "url" => "/"
        ],
        "items_bg" => "pattern",
        "items" => [$card_picto, $card_picto, $card_picto]
    ]); ?>

    <hr>

    <h1 class="sg-heading">10.6 | 10.7 Etapes <strong>strate-steps</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'steps', [
        ...$options,
        "is_cols" => true,
        "is_reverse" => true,
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


    <h1 class="sg-heading">10.7 Timeline texte <strong>strate-timeline_text</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'steps', [
        ...$options,
        "title" => "Bloc <strong>Timeline + texte</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
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


    <h1 class="sg-heading">10.7 Timeline texte animation picto <strong>strate-timeline_text</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'steps', [
        ...$options,
        "title" => "",
        "text" => "",
        "picto_animated" => true,
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

    <h1 class="sg-heading">Preuves <strong>strate-evidences</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'evidences', [
        ...$options,

        "title" => "Bloc <strong>Preuves</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
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
    <hr>
    <div id="count"></div>
    <h1 class="sg-heading">10.8.1 | 10.8.2 | 10.8.3 | 10.8.4 Citations <strong>strate-quote</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'quotes', [
        ...$options,
        "is_slider" => true,
        "has_pattern" => true,
        "title" => "",
        "text" => "",
        "items" => [
            $card_quote,
            $card_quote_big,
            $card_quote_big
        ]

    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'quotes', [
        ...$options,

        "title" => "",
        "text" => "",
        "items" => [
            $card_quote
        ]

    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'quotes', [
        ...$options,

        "title" => "",
        "text" => "",
        "items" => [
            $card_quote_without
        ]

    ]); ?>


    <h1 class="sg-heading">10.8.3 Citation X2 <strong>strate-quote</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'quotes', [
        ...$options,

        "title" => "Blocs Citation X2",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "items" => [
            $card_quote,
            $card_quote_without
        ]

    ]); ?>

    <h1 class="sg-heading">10.8.4 Slider de citation <strong>strate-quote</strong></h1>
    <?= get_template_part('template-parts/strates/strate', 'quotes', [
        ...$options,

        "title" => "Slider Citation",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "items" => [
            $card_quote_without,
            $card_quote_without,
            $card_quote_without,
        ]

    ]); ?>
    <hr>



    <h1 class="sg-heading">10.8.5 Citations croisées <strong>strate-quotes_cross</strong></h1>

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

    <hr>

    <!--  <h1 class="sg-heading">10.9.1 Visuel 12 colonnes <strong>image</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'image', [
        ...$options,

        "title" => "Visuel 12 colonnes | Visuels X2",
        "text" => "<p><strong>image</strong></p>",

        "images" => [
            "desktop" => 7,
            "mobile" => 8
        ],
        "text" => "<p>Légende - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sit amet ligula vel sem fermentum interdum quis nec urna. Aliquam ac urna porttitor, dapibus ex eu, auctor magna.</p>"
    ]); ?> -->

    <hr>

    <h1 class="sg-heading">10.9.2 Visuel/Vidéo 8 colonne <strong>strate-media</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'media', [
        ...$options,
        "title" => "Visuel <strong>12 colonnes</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "items" => [
            [
                "is_video" => false,
                "url_video" => "",
                "images" => [
                    "desktop" => 123,
                    "mobile" => 8
                ],
                "legend" => "<p>Légende - Lorem ipsum dolor sit amet, <a href=''>consectetur adipiscing elit.</a> Fusce sit amet ligula vel sem fermentum interdum quis nec urna. Aliquam ac urna porttitor, dapibus ex eu, auctor magna.</p>"
            ]
        ]
    ]); ?>


    <?= get_template_part('template-parts/strates/strate', 'media', [
        ...$options,
        "title" => "<strong>Visuel/Vidéo</strong> 8 colonnes",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "items" => [
            [
                "is_video" => true,
                "url_video" => "https://www.youtube.com/watch?v=kzRPBzSy4Tk",
                //   "url_video" => "https://www.dailymotion.com/video/x9uznkg",

                "images" => [
                    "desktop" => 123,
                    "mobile" => 8
                ],
                "legend" => "<p>Légende - Lorem ipsum dolor sit amet, <a href=''>consectetur adipiscing elit.</a> Fusce sit amet ligula vel sem fermentum interdum quis nec urna. Aliquam ac urna porttitor, dapibus ex eu, auctor magna.</p>"
            ]
        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading">10.9.3 Visuels x 2 <strong>strate-media</strong></h1>


    <?= get_template_part('template-parts/strates/strate', 'media', [
        ...$options,
        "title" => "<strong>Visuels</strong> X2",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "items" => [
            [
                "is_video" => false,
                "url_video" => "",
                "images" => [
                    "desktop" => 531,
                    "mobile" => 8
                ],
                "legend" => "<p>Légende - Lorem ipsum dolor sit amet, <a href=''>consectetur adipiscing elit.</a> Fusce sit amet ligula vel sem fermentum interdum quis nec urna. Aliquam ac urna porttitor, dapibus ex eu, auctor magna.</p>"

            ],
            [
                "is_video" => false,
                "url_video" => "",
                "images" => [
                    "desktop" => 8,
                    "mobile" => 7
                ],
                "legend" => "<p>Légende - Lorem ipsum dolor sit amet, <a href=''>consectetur adipiscing elit.</a> Fusce sit amet ligula vel sem fermentum interdum quis nec urna. Aliquam ac urna porttitor, dapibus ex eu, auctor magna.</p>"

            ]
        ]
    ]); ?>

    <hr>
    <!-- 
    <h1 class="sg-heading">10.9.3 Visuels x 2<strong>image_x2</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'image_x2', [
        ...$options,
        "header" => [
            "title" => "Visuel 12 colonnes | Visuels X2",
            "text" => "<p><strong>image_x2</strong></p>"
        ],
        "items" => [
            [
                "images" => [
                    "desktop" => 7,
                    "mobile" => 8
                ],
                "text" => "<p>Légende - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sit amet ligula vel sem fermentum interdum quis nec urna. Aliquam ac urna porttitor, dapibus ex eu, auctor magna.</p>"
            ]
        ]
    ]); ?>

    <hr> -->

    <h1 class="sg-heading">10.10 Tableau 2 colonnes | Tableau 3 colonnes <strong>strate-cols_list</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'cols_list', [
        ...$options,
        "title" => "Tableau 2 colonnes | Tableau 3 colonnes",
        "text" => "<p><strong>table</strong></p>",
        "items" => [
            [
                "items_bg" => "uni",
                "bg_color" => "bg-color-3",
                "items" => [
                    [
                        "text" => "Amet mollis lorem proin erat morbi condimentum at.",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Adipiscing ut etiam diam integer amet",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Massa feugiat ipsum fermentum",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Rhoncus urna a purus arcu morbi",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Sit sem eu ac non pharetra at.",
                        "is_bullet" => false
                    ]

                ]
            ],
            [
                "items_bg" => "uni",
                "bg_color" => "bg-color-1",
                "items" => [
                    [
                        "text" => "Amet mollis lorem proin erat morbi condimentum at.",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Adipiscing ut etiam diam integer amet",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Massa feugiat ipsum fermentum",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Rhoncus urna a purus arcu morbi",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Sit sem eu ac non pharetra at.",
                        "is_bullet" => false
                    ]

                ]
            ]
        ]
    ]); ?>
    <?= get_template_part('template-parts/strates/strate', 'cols_list', [
        ...$options,
        "has_image" => true,
        "images" => [
            "desktop" => 8
        ],
        "title" => "Tableau image",
        "text" => "<p><strong>table</strong></p>",
        "items" => [
            [
                "items_bg" => "uni",
                "bg_color" => "bg-color-3",
                "items" => [
                    [
                        "text" => "Amet mollis lorem proin erat morbi condimentum at.",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Adipiscing ut etiam diam integer amet",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Massa feugiat ipsum fermentum",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Rhoncus urna a purus arcu morbi",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Sit sem eu ac non pharetra at.",
                        "is_bullet" => false
                    ]

                ]
            ],
            [
                "items_bg" => "uni",
                "bg_color" => "bg-color-1",
                "items" => [
                    [
                        "text" => "Amet mollis lorem proin erat morbi condimentum at.",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Adipiscing ut etiam diam integer amet",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Massa feugiat ipsum fermentum",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Rhoncus urna a purus arcu morbi",
                        "is_bullet" => false
                    ],
                    [
                        "text" => "Sit sem eu ac non pharetra at.",
                        "is_bullet" => false
                    ]

                ]
            ]
        ]
    ]); ?>
    <hr>

    <h1 class="sg-heading">10.11.1 | 10.11.2 Chiffres clés <strong>strate-keynumbers</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'keynumbers', [
        ...$options,
        "title" => "Chiffres clés <strong>x4</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "has_image" => false,
        "is_cols" => false,
        "is_reverse" => false,
        "images" => [
            "desktop" => 8
        ],
        "link" => [],
        "items" => [
            $card_keynumber_picto,
            $card_keynumber_picto,
            $card_keynumber,
            $card_keynumber,

        ]
    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'keynumbers', [
        ...$options,
        "title" => "Chiffres clés <strong>x4</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "has_image" => false,
        "is_cols" => false,
        "is_reverse" => false,
        "images" => [
            "desktop" => 8
        ],
        "link" => [],
        "items" => [
            $card_keynumber,
            $card_keynumber,
            $card_keynumber

        ]
    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'keynumbers', [
        ...$options,
        "title" => "Chiffres clés <strong>x4</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "has_image" => false,
        "is_cols" => true,
        "is_reverse" => false,
        "images" => [
            "desktop" => 8
        ],
        "link" => [
            "title" => "Exemple de CTA",
            "url" => "/"
        ],
        "items" => [
            $card_keynumber,
            $card_keynumber,
            $card_keynumber,
            $card_keynumber
        ]
    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'keynumbers', [
        ...$options,
        "title" => "Chiffres clés <strong>x4</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "has_image" => false,
        "is_cols" => true,
        "is_reverse" => true,
        "images" => [
            "desktop" => 8
        ],
        "link" => [
            "title" => "Exemple de CTA",
            "url" => "/"
        ],
        "items" => [
            $card_keynumber,
            $card_keynumber,
            $card_keynumber,
            $card_keynumber
        ]
    ]); ?>


    <?= get_template_part('template-parts/strates/strate', 'keynumbers', [
        ...$options,
        "title" => "Chiffres clés <strong>x4</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "has_image" => true,
        "is_cols" => false,
        "is_reverse" => false,
        "images" => [
            "desktop" => 8
        ],
        "link" => [],
        "items" => [
            $card_keynumber_picto,
            $card_keynumber_picto,
            $card_keynumber_picto,
            $card_keynumber_picto
        ]
    ]); ?>

    <?= get_template_part('template-parts/strates/strate', 'keynumbers', [
        ...$options,
        "title" => "Chiffres clés <strong>x4</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "has_image" => true,
        "is_cols" => true,
        "is_reverse" => true,
        "images" => [
            "desktop" => 8
        ],
        "link" => [],
        "items" => [
            $card_keynumber_picto,
            $card_keynumber_picto,
            $card_keynumber_picto,
            $card_keynumber_picto
        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading">10.12 Automatic scroll slider <strong>strate-slider_auto</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'slider_auto', [
        ...$options,
        "title" => "Automatic scroll <strong>Slider</strong>",
        "text" => "<p>Augue tellus in duis amet pharetra a lorem est ultrices. Porta facilisis nascetur ultrices mattis. Blandit pellentesque amet pellentesque et in accumsan malesuada leo. </p>",
        "items" => [
            [
                "images" => [
                    "desktop" => 123
                ],
                "title" => "Activation",
                "text" => "<p><strong>Activer aujourd’hui ce qui fera la différence demain</strong></p><p>Nous testons, ajustons et déployons vos projets dans des conditions réelles pour accélérer leur adoption. Grâce à la puissance de distribution de Bouygues Telecom, nous transformons une innovation en usage concret, visible et mesurable dès les premières étapes</p>",
                "quote" => [
                    "text" => "“La value Factory nous a permis de nous engager pour le développement du don par SMS, au profit des associations et fondations d’intérêt général”",
                    "name" => "name",
                    "function" => "function",
                    "logo" => 120
                ]
            ],
            [
                "images" => [
                    "desktop" => 7
                ],
                "title" => "Activation",
                "text" => "Activer aujourd’hui ce qui fera la différence demain<p>Nous testons, ajustons et déployons vos projets dans des conditions réelles pour accélérer leur adoption. Grâce à la puissance de distribution de Bouygues Telecom, nous transformons une innovation en usage concret, visible et mesurable dès les premières étapes</p>",
                "quote" => [
                    "text" => "“La value Factory nous a permis de nous engager pour le développement du don par SMS, au profit des associations et fondations d’intérêt général”",
                    "name" => "name",
                    "function" => "function",
                    "logo" => 120
                ]
            ],
            [
                "images" => [
                    "desktop" => 8
                ],
                "title" => "Activation",
                "text" => "Activer aujourd’hui ce qui fera la différence demain<p>Nous testons, ajustons et déployons vos projets dans des conditions réelles pour accélérer leur adoption. Grâce à la puissance de distribution de Bouygues Telecom, nous transformons une innovation en usage concret, visible et mesurable dès les premières étapes</p>",
                "quote" => [
                    "text" => "“La value Factory nous a permis de nous engager pour le développement du don par SMS, au profit des associations et fondations d’intérêt général”",
                    "name" => "name",
                    "function" => "function",
                    "logo" => 120
                ]
            ]

        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">10.13 Téléchargement <strong>strate-download</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'download', [
        ...$options,
        "title" => "Lien de téléchargement",
        "items" => [
            [
                "title" => "Est iaculis in semper ornare duis cras. Euismod nec sed tempus et.",
                "file" => "wesh.pdf",
                "images" => [
                    "desktop" => 7,
                ]
            ],
            [
                "title" => "Est iaculis in semper ornare duis cras. Euismod nec sed tempus et.",
                "file" => "wesh.pdf",
                "images" => []
            ]
        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">10.14 Liste <strong>strate-list</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'list', [
        ...$options,
        "title" => "<strong>Des solutio)))ns</strong> pensées pour vos enjeux.",
        "text" => "
       
        <h4>Nous n’empilons pas des offres. Nous activons des leviers.</h4>
        <p>Chaque solution de la Value Factory est née d’un besoin terrain identifié : mieux monétiser, mieux engager, mieux sécuriser. Et pensée pour créer une valeur tangible, ici et maintenant.</p>",
        "images" => [
            'desktop' => 1174
        ],
        "items" => [
            [
                "label" => "Convallis tristique mattis",
                "picto" => 124,
                "is_post" => false,
                "post_id" => 1,
                "images" => [
                    "desktop" => 7
                ],
                "text" => "Simplifier l’achat de services digitaux, améliorer le taux de conversion",
                "link" => [
                    "title" => "Découvrir la solution",
                    "url" => "/"
                ]
            ],
            [
                "label" => "Convallis tristique mattis",
                "picto" => 121,
                "is_post" => false,
                "post_id" => 1,
                "images" => [
                    "desktop" => 8
                ],
                "text" => "zone de text",
                "link" => [
                    "title" => "Découvrir la solution",
                    "url" => "/"
                ]
            ],
            [
                "label" => "Lorem ipsum dolores",
                "picto" => 121,
                "is_post" => false,
                "post_id" => 1,
                "images" => [
                    "desktop" => 1096
                ],
                "text" => "zone de text",
                "link" => [
                    "title" => "Découvrir la solution",
                    "url" => "/"
                ]
            ],
            [
                "label" => "Last item of the world",
                "picto" => 121,
                "is_post" => false,
                "post_id" => 1,
                "images" => [
                    "desktop" => 1096
                ],
                "text" => "zone de text",
                "link" => [
                    "title" => "Découvrir la solution",
                    "url" => "/"
                ]
            ],

        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">10.15 Slider bénéfices <strong>strate-slider_profit</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'slider_profit', [
        ...$options,

        "items" => [
            [
                "suptitle" => "Slider bénéfices",
                "picto" => 124,
                "title" => "Arcu id leo volutpat facilisis feugiat volutpat",
                "text" => "<p>Dolor pharetra massa interdum egestas id dolor lorem. Porttitor urna erat arcu sed. Vel sit viverra leo mauris elementum iaculis augue felis. Eget imperdiet sed viverra.</p>",
                "images" => [
                    "desktop" => 7,
                ],
                "link" => [
                    "title" => "Découvrir notre savoir-faire",
                    "url" => "/"
                ]
            ],
            [
                "suptitle" => "Notre méthos",
                "picto" => 124,
                "title" => "Hydratation",
                "text" => "<p>Dolor pharetra massa interdum egestas id dolor lorem. Porttitor urna erat arcu sed. Vel sit viverra leo mauris elementum iaculis augue felis.</p>",
                "images" => [
                    "desktop" => 8
                ],
                "link" => [
                    "title" => "Découvrir notre savoir-faire",
                    "url" => "/"
                ]
            ],
            [
                "suptitle" => "Lorem",
                "picto" => 124,
                "title" => "Lorem ipsum dolores",
                "text" => "<p>Dolor pharetra massa interdum egesta. Vel sit viverra leo mauris elementum iaculis augue felis.</p>",
                "images" => [
                    "desktop" => 58
                ],
                "link" => [
                    "title" => "Découvrir notre savoir",
                    "url" => "/"
                ]
            ]
        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">10.16 Infinite scroll clients <strong>strate-infinite_loop</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'infinite_loop', [
        ...$options,
        "title" => "Infinite scroll clients",
        "items" => [
            [
                "items" => [
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ]
                ]
            ],
            [
                "items" => [
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ],
                    [
                        "images" => [
                            "desktop" => 120,
                        ]
                    ]
                ]
            ]
        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">10.17 Timeline <strong>strate-timeline</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'timeline', [
        ...$options,
        "title" => "Timeline",
        "text" => "<p>Cras leo egestas lectus scelerisque quam mus malesuada tincidunt fames. Nulla proin eleifend adipiscing laoreet amet mauris imperdiet. Volutpat imperdiet sit auctor lacus elit a dolor diam in.</p>",
        "items" => [$card_timeline, $card_timeline, $card_timeline, $card_timeline, $card_timeline]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">Separator<strong>strate-separator</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'separator', [
        ...$options,
        "pattern" => "pattern-2",
    ]); ?>


    <hr>


    <h1 class="sg-heading" class="sg-heading">Toute nos Solutions <strong>strate-solutions</strong></h1>
    <?= get_template_part('template-parts/strates/strate', 'solutions', [
        ...$options,
        "title" => "Toutes nos <strong>solutions</strong>",
        "text" => "Consequat mauris vel erat nunc orci praesent dignissim rhoncus dui. Sit feugiat tempus et velit integer at nunc non odio. Turpis eget faucibus diam imperdiet mauris. Commodo.",
        "items" => [
            [
                "postId" => 469
            ],
            [
                "postId" => null,
                "title" => "Produits publicitaires et Data",
                "images" => [
                    "desktop" => 122
                ],
                "text" => "Touchez les bonnes audiences avec une data opérateur responsable.",
                "picto" => 124,
                "url" => "/"
            ],
            [
                "postId" => null,
                "title" => "Business messaging",
                "images" => [
                    "desktop" => 58
                ],
                "text" => "Rétablir un lien direct, utile et personnalisé avec vos clients",
                "picto" => 124,
                "url" => "/"
            ]

        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">Text colonnes <strong>strate-text_cols</strong></h1>
    <?= get_template_part('template-parts/strates/strate', 'text_cols', [
        ...$options,
        "items" => [
            [
                "title" => "Est enim sed proin a pellentesque",
                "text" => "Aliquam libero pellentesque tincidunt arcu sapien risus a. Quis duis nulla velit viverra pretium sapien. Feugiat purus."
            ],
            [
                "title" => "Est enim sed proin a pellentesque",
                "text" => "Aliquam libero pellentesque tincidunt arcu sapien risus a. Quis duis nulla velit viverra pretium sapien. Feugiat purus."
            ]
        ]
    ]); ?>

    <hr>

    <h1 class="sg-heading" class="sg-heading">10.18 Cross navigation <strong>strate-cross_navigation</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'cross_navigation', [
        ...$options,
        "title" => "Cross navigation",
        "items" => [
            635,
            637,
            [
                "title" => "Politique de confidentialité",
                "url" => "/",
                "images" => [
                    "desktop" => 8
                ]
            ],
            [
                "title" => "Publicité segmentée",
                "url" => "/",
                "images" => [
                    "desktop" => 7
                ]
            ],
            [
                "title" => "Confiance numérique et Cybersécurité",
                "url" => "/",
                "images" => [
                    "desktop" => 58
                ]
            ]
        ]
    ]); ?>



    <hr>
    <hr>

    <h1 class="sg-heading" class="sg-heading">À la une <strong>highlight</strong></h1>

    <?= get_template_part('template-parts/strates/strate', 'highlight', [
        ...$options,
        "title" => "À la une",
        "text" => "<p>Les derniers lancements, cas et prises de parole de la Value Factory.</p>",
        "items" => [1, 24],
        "link" => [
            "title" => "Voir toute l’actualité",
            "url" => "/"
        ]
    ]); ?>

</main>

<style>
    .sg-main {
        display: grid;
        grid-template-columns: [full] var(--ctr-offset) [fluid] 1fr [ctr] minmax(auto, calc(var(--ctr-width)/2)) [center] minmax(auto, calc(var(--ctr-width)/2)) [ctr-end] 1fr [fluid-end] var(--ctr-offset) [full-end];
    }

    .sg-heading {
        margin-bottom: 50px;
        padding-top: 30px;
        color: #000;
        grid-column: fluid;

        strong {
            font-weight: 500;
            font-size: 20px;
            padding-left: 10px;
            color: var(--color-2);

            &:before {
                content: "-";
                padding-right: 10px;
                color: #000;
            }
        }
    }

    .strate {
        grid-column: full;
    }

    .sg-cards {
        display: grid;
        grid-template-columns: subgrid;
        grid-column: full;
    }

    hr {
        grid-column: full;
        border: none;
        border-bottom-width: medium;
        border-bottom-style: none;
        border-bottom-color: currentcolor;
        width: 100%;
        border-bottom: 1px solid #000;
        margin-top: 40px;
    }

    .sg-card {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--gap);
        grid-column: ctr;
    }

    .sg-card-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--gap);
        grid-column: ctr;
    }

    .sg-card-1 {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: var(--gap);
        grid-column: ctr;
    }
</style>

<?php
get_footer();
