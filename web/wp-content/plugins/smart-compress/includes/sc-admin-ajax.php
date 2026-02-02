<?php
// type
add_action('wp_ajax_sm_type', 'sm_type_callback');
add_action('wp_ajax_nopriv_sm_type', 'sm_type_callback');
function sm_type_callback()
{
    checkNonce('sm_nonce');

    global $wpdb;
    global $sc_table;

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "UPDATE " . $sc_table . " SET sc_value = '" . $_POST['value'] . "' WHERE sc_option = 'type'";
    mysqli_query($link, $sql);
    mysqli_close($link);

    $response = array();

    $response["msg"] = $_POST["value"];

    wp_send_json($response);
}

// quality
add_action('wp_ajax_sm_quality', 'sm_quality_callback');
add_action('wp_ajax_nopriv_sm_quality', 'sm_quality_callback');
function sm_quality_callback()
{
    checkNonce('sm_nonce');

    global $sc_table;
    global $sc_quality;
    $sc_quality = $_POST['value'];

    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "UPDATE " . $sc_table . " SET sc_value = '" . $_POST['value'] . "' WHERE sc_option = 'quality'";
    mysqli_query($link, $sql);
    mysqli_close($link);

    $files = ["portait", "landscape", "landscape1"];
    $sizes = [[600, 592], [300, 296], [150, 148], [75, 74]];
    foreach ($files as $file) {
        foreach ($sizes as $size) {
            $newWidth = $size[0];
            $newHeight = $size[1];
            $filename = $file . "-webp-" . $newWidth . "x" . $newHeight;
            $originalFilepath = WP_CONTENT_DIR . "/plugins/smart-compress/img/" . $file . ".jpg";
            $resizedFilepath = WP_CONTENT_DIR . "/plugins/smart-compress/img/" . $filename . ".webp";
            $crop = null;

            sm_generate($originalFilepath, $resizedFilepath, $newWidth, $newHeight, $crop);
        }
    }

    $response = array();

    $response["msg"] = "";

    wp_send_json($response);
}

// generate
add_action('wp_ajax_sm_generate', 'sm_generate_callback');
add_action('wp_ajax_nopriv_sm_generate', 'sm_generate_callback');
function sm_generate_callback()
{
    checkNonce('sm_nonce');

    global $sc_table;
    global $sc_last_generate;

    $ids = [];

    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => "inherit",
        'post_parent' => null, // any parent
    );

    $attachments = get_posts($args);

    $years = implode("|", $_POST["sc-year"]);

    if ($attachments) {
        foreach ($attachments as $post) {
            $mime = $post->post_mime_type;
            if ($mime == "image/jpeg"  || $mime == "image/png") {
                if (preg_match('/uploads\/(' . $years . ')\//',  $post->guid, $matches)) {
                    array_push($ids, $post->ID);
                }
            }
        }
    }
    foreach ($sc_last_generate as $year => $item) {
        $sc_last_generate[$year]["active"] = 0;
    }

    foreach ($_POST["sc-year"] as $year) {
        $sc_last_generate[$year]["active"] = 1;
        $sc_last_generate[$year]["date"] = date("Y-m-d h:i:s");
    }


    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "UPDATE " . $sc_table . " SET sc_value = '" . json_encode($sc_last_generate) . "' WHERE sc_option ='last_generate' ";
    mysqli_query($link, $sql);
    mysqli_close($link);

    $response = array();

    $response["ids"] = $ids;

    wp_send_json($response);
}

// add image
add_action('wp_ajax_sm_add', 'sm_add_callback');
add_action('wp_ajax_nopriv_sm_add', 'sm_add_callback');
function sm_add_callback()
{
    checkNonce('sm_nonce');

    $id = $_POST['id'];

    $metadata = wp_get_attachment_metadata($id);
    if (empty($metadata["sizes"])) return;

    $originalFilepath = WP_CONTENT_DIR . "/uploads/" . $metadata["file"];

    global $_wp_additional_image_sizes;
    $dirname = pathinfo($metadata['file'], PATHINFO_DIRNAME);

    $metadata["sizes"]['original'] = [
        "file" => $metadata["file"],
        "width" => $metadata["width"],
        "height" => $metadata["height"],
    ];

    foreach ($metadata["sizes"] as $key => $size) {
        $crop = $key == "original" ? [] : $_wp_additional_image_sizes[$key]["crop"];
        $newWidth = $size["width"];
        $newHeight = $size["height"];
        $filename = pathinfo($size["file"], PATHINFO_FILENAME);
        $resizedFilepath = WP_CONTENT_DIR . "/uploads/" . $dirname . "/" .  $filename . ".webp";

        sm_generate($originalFilepath, $resizedFilepath, $newWidth, $newHeight, $crop);
    }

    $response = array();

    $response["msg"] = "sm_add_callback";

    wp_send_json($response);
}

// purge
add_action('wp_ajax_sm_purge', 'sm_purge_callback');
add_action('wp_ajax_nopriv_sm_purge', 'sm_purge_callback');
function sm_purge_callback()
{
    checkNonce('sm_nonce');

    $ids = [];

    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
    );

    $attachments = get_posts($args);

    if ($attachments) {
        foreach ($attachments as $post) {
            array_push($ids, $post->ID);
        }
    }


    $response = array();

    $response["ids"] = $ids;

    wp_send_json($response);
}

// remove image
add_action('wp_ajax_sm_remove', 'sm_remove_callback');
add_action('wp_ajax_nopriv_sm_remove', 'sm_remove_callback');
function sm_remove_callback()
{
    checkNonce('sm_nonce');

    $id = $_POST['id'];
    $metadata = wp_get_attachment_metadata($id);
    $pathinfos =  pathinfo($metadata['file']);
    $dirname = $pathinfos['dirname'];
    $ext = $pathinfos['extension'];

    foreach ($metadata['sizes'] as $size) {
        $file = WP_CONTENT_DIR . "/uploads/" . $dirname . "/" .  $size['file'];
        $filecompressed = str_replace("." . $ext, ".webp", $file);
        wp_delete_file($filecompressed);
    }

    $response = array();

    $response["file"] = $filecompressed;

    wp_send_json($response);
}



// assets
add_action('wp_ajax_sm_assets', 'sm_assets_callback');
add_action('wp_ajax_nopriv_sm_assets', 'sm_assets_callback');
function sm_assets_callback()
{
    checkNonce('sm_nonce');

    $images = [];

    function getDirContents($dir, &$results = array())
    {

        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (is_dir($path) == false) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                getDirContents($path, $results);
                if (is_dir($path) == false) {
                    $results[] = $path;
                }
            }
        }
        return $results;
    }

    $folder =  get_template_directory() . '/assets/img/';

    rm_rf($folder."webp");
    mkdir($folder."webp", 0755, true);

    foreach (getDirContents($folder) as $image) {
        $ext =  pathinfo($image)['extension'];

        if ($ext == "png" || $ext == "jpg" || $ext == "gif") {
            array_push($images, $image);
        }
    }

    $response = array();
    $response["ids"] = $images;

    wp_send_json($response);
}

// add assets
add_action('wp_ajax_sm_add_assets', 'sm_add_assets_callback');
add_action('wp_ajax_nopriv_sm_add_assets', 'sm_add_assets_callback');
function sm_add_assets_callback()
{
    checkNonce('sm_nonce');

    $image = $_POST['id'];

    list($width, $height, $type,) = getimagesize($image);

    $originalFilepath = $image;
    $ext = pathinfo($image)['extension'];
    $resizedFilepath = str_replace("." . $ext, ".webp", $image);

    sm_generate($originalFilepath, str_replace("/img/", "/img/webp/", $resizedFilepath), $width, $height, null);

    $response = array();

    $response["msg"] = "sm_addasssets_callback";

    wp_send_json($response);
}
