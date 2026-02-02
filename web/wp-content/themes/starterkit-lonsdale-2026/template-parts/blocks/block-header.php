<?php
$isH1 = !empty($args["isH1"]) ? true : false;
?>

<?php if (!empty($args["title"])) : ?>
    <header class="block-header">

        <?php if (!empty($args["icon"]) && $args["icon"] != "aucun") : ?>
            <!-- <?= icon($args["icon"], 64, 64) ?> -->
        <?php endif ?>

        <?php if (!empty($args["title"])) : ?>
            <?php if (!empty($args["headline"])) : ?>

                <?php if ($isH1) : ?>
                    <h1 class="headline-2" <?= !empty($args["id"]) ? ' id="' . $args["id"] . '"' : '' ?>>
                        <span class="variant"><?= variantFontLetter($args["title"]) ?></span>

                        <!-- <span class="variant" aria-hidden="true"><?= variantFontLetter($args["title"]) ?></span>
                        <span class=" sr-only"><?= cleanTitle($args["title"]) ?></span> -->
                    </h1>
                <?php else : ?>
                    <h2 class="headline-2" <?= !empty($args["id"]) ? ' id="' . $args["id"] . '"' : '' ?>>
                        <span class="variant"><?= variantFontLetter($args["title"]) ?></span>

                        <!--  <span class="variant" aria-hidden="true"><?= variantFontLetter($args["title"]) ?></span>
                        <span class=" sr-only"><?= cleanTitle($args["title"]) ?></span> -->
                    </h2>
                    
                    <!-- 
                    <h2 class="headline-2 sr-only" <?= !empty($args["id"]) ? ' id="' . $args["id"] . '"' : '' ?>>
                        <?= cleanTitle($args["title"]) ?>
                    </h2>
                    <div class="headline-2">
                        <span class="variant"><?= variantFontLetter($args["title"]) ?></span>
                    </div> -->

                <?php endif ?>
            <?php else : ?>
                <h2 class="title-2" <?= !empty($args["id"]) ? ' id="' . $args["id"] . '"' : '' ?>><?= $args["title"] ?></h2>
            <?php endif ?>
        <?php endif ?>

        <?php if (!empty($args["intro"])) : ?>
            <?php if (!empty($args["headline"])) : ?>
                <div class="intro"><?= $args["intro"] ?></div>
            <?php else : ?>
                <div class="intro sm"><?= $args["intro"] ?></div>
            <?php endif ?>
        <?php endif ?>

    </header>
<?php endif ?>