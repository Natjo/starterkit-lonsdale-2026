<?php
function is_login_page()
{
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

function checkNonce($nonceContext)
{

    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : 0;
    if (!wp_verify_nonce($nonce, $nonceContext)) {
        exit(__('not authorized', 'domain'));
    }
}

function dateMonthInFr($date)
{
    $english_months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
    $french_months = array('Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc');
    return str_replace($english_months, $french_months,  $date);
}

// Array of taxonomies terms in post 
function lsd_get_the_terms_name($ID, $taxonomy)
{
    $arr = array();
    $terms = get_the_terms($ID, $taxonomy);
    if ($terms) {
        foreach ($terms as $term) {
            array_push($arr, $term->name);
        }
    }
    return $arr;
}

// Image url fonction id
function lsd_get_thumb($id, $size = 'full')
{
    if ($id) {
        $img = wp_get_attachment_image_src($id, $size);
        $alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
        if ($img) {
   
            $extension = substr($img[0], strrpos($img[0], '.') + 1);

            if ($extension == 'gif' || $extension == 'GIF' || $extension == 'svg') :
                $img = wp_get_attachment_image_src($id, 'full');
            endif;

            $imgUrl = is_array($img) ? reset($img) : "";

            if ("full" == $size) {
                $imgUrl = wp_get_original_image_url($id);
            }

            $src = $imgUrl;
            $upload_dir = wp_upload_dir();
            $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $src);

            if ($extension == 'svg') {
                preg_match("#viewbox=[\"']\d* \d* ([0-9.]*) ([0-9.]*)#i", file_get_contents($image_path), $d);
                $getimagesize = [$d[1], $d[2]];
            } else {
                $getimagesize = wp_getimagesize($image_path);
            }
              
            return array($imgUrl, $getimagesize[0], $getimagesize[1], $alt);
        }
    }
}

// Image url function de mise en avant des articles
function lsd_get_featured($id, $size = 'medium')
{
    if ($id) {

        $img_id = get_post_thumbnail_id($id);

        if ("full" == $size) {
            $imgUrl = wp_get_original_image_url($img_id);
        } else {
            $img = wp_get_attachment_image_src($img_id, $size);
            $extension = substr($img[0], strrpos($img[0], '.') + 1);

            if ($extension == 'gif' || $extension == 'GIF') :
                $img = wp_get_attachment_image_src($img_id, 'full');
            endif;

            $imgUrl = is_array($img) ? reset($img) : "";
        }
        return $imgUrl;
    }
}

function get_image_thumb_alt($postID)
{
    if (!empty(get_field('thumb_article', $postID))) {
        $thumb = get_field('thumb_article', $postID);
    } else {
        $terms = get_the_terms($postID, 'category');
        if (isset($terms[0])) {
            $thumb = get_field('cat_image', $terms[0]->taxonomy . '_' . $terms[0]->term_id);
        }
    }

    $imageAlt = "";
    if (isset($thumb) && $thumb) {
        $imageAlt = get_post_meta($thumb, '_wp_attachment_image_alt', true);
    }

    return $imageAlt;
}

function get_image_alt($postID)
{
    $thumb = get_field('thumb_article', $postID);

    $imageAlt = get_post_meta($thumb, '_wp_attachment_image_alt', true);

    if ($imageAlt == '') {
        $imageAlt = get_the_title($postID);
    }

    return $imageAlt;
}

function getYoutubeIdFromUrl($url)
{
    $parts = parse_url($url);

    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        } else if (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }

    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }

    return "";
}

function getDailymotionIdFromUrl($url) {
    // Exemples d'URLs Dailymotion:
    // https://www.dailymotion.com/video/x7tgczb
    // https://dai.ly/x7tgczb
    // https://www.dailymotion.com/embed/video/x7tgczb
    // On capture le segment après "/video/" ou le code court après le domaine dai.ly
    $id = null;
    // Cas domaine dailymotion.com
    if (preg_match('#dailymotion\.com/(?:embed/)?video/([a-zA-Z0-9]+)#', $url, $m)) {
        $id = $m[1];
        // Cas domaine dai.ly
    } elseif (preg_match('#dai\.ly/([a-zA-Z0-9]+)#', $url, $m)) {
        $id = $m[1];
    }
    return $id;
}

function youtube_id_from_url($url)
{
    $parts = parse_url($url);

    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        } else if (isset($qs['vi'])) {
            return $qs['vi'];
        }
    }

    if (isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path) - 1];
    }

    return "";
}

function console($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function classes($values)
{
    return !empty($values["classes"]) ? " " . $values["classes"] : "";
}

