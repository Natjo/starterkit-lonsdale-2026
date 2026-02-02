<nav id="breadcrumb" aria-label="Fil D’Ariane">
    <div <?= !empty($args["container"]) ? ' class="container"' : ""  ?>>
        <ol>
            <?php breadcrumb_generate_all_li(); ?>
        </ol>
    </div>
</nav>