<?php
get_header();

get_template_part('template-parts/general/block', 'header_nav');

$fields = get_field('option_404', 'option')
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.13.0/lottie.min.js" integrity="sha512-uOtp2vx2X/5+tLBEf5UoQyqwAkFZJBM5XwGa7BfXDnWR+wdpRvlSVzaIVcRe3tGNsStu6UMDCeXKEnr4IBT8gA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<main class="page-404">
    <div class="inner">


        <h1 class="title-1">Oups !</h1>

        <div class="text rte">
            <?= !empty($fields['text']) ? $fields['text'] : '' ?>
        </div>

        <div id="svgContainer"></div>

        <script>
            var svgContainer = document.getElementById('svgContainer');
            var animItem = bodymovin.loadAnimation({
                wrapper: svgContainer,
                animType: 'svg',
                loop: true,
                path: '<?= THEME_ASSETS ?>img/404.json'
            });
        </script>

        <?= !empty($fields['link']) ? component::btn($fields['link'], "cta btn-1") : '' ?>
    </div>
</main>

<?php
get_footer();
