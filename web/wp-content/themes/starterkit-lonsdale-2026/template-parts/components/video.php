<?php

/** @var array $args */
$url = isset($args["url"]) ? trim((string) $args["url"]) : "";
$title = isset($args["title"]) ? trim((string) $args["title"]) : "";
$poster = isset($args["poster"]) ? trim((string) $args["poster"]) : "";
$autoplay = !empty($args["autoplay"]);
$loop = !empty($args["loop"]);
$classes = isset($args["classes"]) ? trim((string) $args["classes"]) : "";
$attributes = isset($args["attributes"]) ? trim((string) $args["attributes"]) : "";

if ($url === "") return;

// Numeric ID → résoudre via la médiathèque WordPress.
if (is_numeric($url)) {
    $resolved = wp_get_attachment_url((int) $url);
    if ($resolved) $url = $resolved;
}
if ($poster !== "" && is_numeric($poster)) {
    $resolved_poster = wp_get_attachment_url((int) $poster);
    $poster = $resolved_poster ? $resolved_poster : "";
}

$is_youtube = (bool) preg_match('#(?:youtube\.com|youtu\.be)#i', $url);
$is_vimeo = (bool) preg_match('#vimeo\.com#i', $url);
$type = $is_youtube ? "youtube" : ($is_vimeo ? "vimeo" : "video");

// URL d'embed (idle vs autoplay) pour YouTube / Vimeo.
$idle_src = "";
$autoplay_src = "";
if ($is_youtube) {
    $vid = youtube_id_from_url($url);
    if ($vid === "") return;
    $base = ['rel' => 0];
    if ($loop) { $base['loop'] = 1; $base['playlist'] = $vid; }
    $idle_src = 'https://www.youtube.com/embed/' . rawurlencode($vid) . '?' . http_build_query($base);
    $autoplay_src = 'https://www.youtube.com/embed/' . rawurlencode($vid) . '?' . http_build_query($base + ['autoplay' => 1, 'mute' => 1]);
} elseif ($is_vimeo) {
    $vid = "";
    if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) $vid = $m[1];
    if ($vid === "") return;
    $base = [];
    if ($loop) $base['loop'] = 1;
    $idle_src = 'https://player.vimeo.com/video/' . rawurlencode($vid) . ($base ? '?' . http_build_query($base) : '');
    $autoplay_src = 'https://player.vimeo.com/video/' . rawurlencode($vid) . '?' . http_build_query($base + ['autoplay' => 1, 'muted' => 1]);
}

$root_class = "video" . ($classes !== "" ? " " . esc_attr($classes) : "");
$has_facade = $poster !== "";
$iframe_title = $title !== "" ? $title : __("Lecteur vidéo", "starterkit");
$play_label = $title !== ""
    ? sprintf(__("Lire la vidéo : %s", "starterkit"), $title)
    : __("Lire la vidéo", "starterkit");
// Quand la facade est visible, l'iframe / <video> est retiré de l'ordre de tabulation
// (le bouton .poster prend le focus). Le JS retire `tabindex="-1"` au click.
$hide_tab = $has_facade ? ' tabindex="-1"' : '';
?>

<div class="<?= esc_attr($root_class) ?>" data-type="<?= esc_attr($type) ?>" data-autoplay="<?= $autoplay ? "true" : "false" ?>"<?= ($is_youtube || $is_vimeo) ? ' data-autoplay-src="' . esc_attr($autoplay_src) . '"' : '' ?><?= $attributes !== "" ? " " . $attributes : "" ?>>
    <?php if ($is_youtube || $is_vimeo) : ?>
        <iframe
            src="<?= esc_url($autoplay ? $autoplay_src : $idle_src) ?>"
            title="<?= esc_attr($iframe_title) ?>"
            loading="lazy"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen<?= $hide_tab ?>></iframe>

    <?php else :
        $flags = ['controls', 'preload="metadata"'];
        if ($autoplay) {
            $flags[] = 'autoplay';
            $flags[] = 'muted';
            $flags[] = 'playsinline';
        }
        if ($loop) $flags[] = 'loop';
    ?>
        <video
            <?= $poster !== "" ? 'poster="' . esc_url($poster) . '"' : "" ?>
            <?= $title !== "" ? 'title="' . esc_attr($title) . '"' : "" ?>
            <?= implode(' ', $flags) ?><?= $hide_tab ?>>
            <source src="<?= esc_url($url) ?>" type="video/mp4">
        </video>
    <?php endif ?>

    <?php if ($has_facade) : ?>
        <button type="button" class="poster" aria-label="<?= esc_attr($play_label) ?>">
            <img src="<?= esc_url($poster) ?>" alt="">
            <div class="play" aria-hidden="true">
                <?php component::icon("play", 60, 60) ?>
            </div>
        </button>
    <?php endif ?>
</div>
