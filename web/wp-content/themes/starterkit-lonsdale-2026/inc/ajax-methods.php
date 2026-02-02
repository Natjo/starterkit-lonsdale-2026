<?php

// autocomplete header 
add_action('wp_ajax_search_autocomplete', 'search_autocomplete');
add_action('wp_ajax_nopriv_search_autocomplete', 'search_autocomplete');

function search_autocomplete()
{

    checkNonce('search_autocomplete_nonce');


    $html = "";
    $inc = 0;
    $resultsEs = elasticsearch_search_articles($_POST['s'], null, 1, 6);
    if (!empty($resultsEs['elements'])) {
        foreach ($resultsEs['elements'] as $post) {
            if (!empty($post['post_id'])){
                $rowId = $post['post_id'];
                $html .= '<li role="option"><a href="' . get_the_permalink($rowId) . '">' . get_the_title($rowId) . '</a></li>';
                $inc++;
            }
        }
    }

    $response['html'] =  $html;
    $response['nb'] = $inc;

    wp_send_json($response);
}


// hub articles
add_action('wp_ajax_articles', 'articles_callback');
add_action('wp_ajax_nopriv_articles', 'articles_callback');
function articles_callback()
{
    checkNonce('articles_nonce');

    $page = $_POST["page"];
    $category = $_POST["category"];
    $itemsPerPage = $_POST["itemsPerPage"];

    $items = getCategoryHubArticlesItems($category, $page, $itemsPerPage);
    $pagination = getCategoryHubArticlesPager($category, $page, $itemsPerPage);

    ob_start();
    bouygues_custom_breadcrumb($category);
    $breadcrumb = ob_get_clean();

    $response = [
        "breadcrumb" => $breadcrumb,
        "html" => grid($items, "article", "articles", "line"),
        "pagination" => $pagination
    ];

    wp_send_json($response);
}

// search
add_action('wp_ajax_search', 'search_callback');
add_action('wp_ajax_nopriv_search', 'search_callback');
function search_callback()
{
    checkNonce('search_nonce');

    $page = $_POST["page"];
    $itemsPerPage = $_POST["itemsPerPage"];
    $query = $_POST["s"];
    
    $resultsEs = elasticsearch_search_articles($query, null, $page, $itemsPerPage);
    $items = getSearchArticlesItemsFromEs($resultsEs);
    $pagination = getSearchArticlesItemsPagerFromES($resultsEs);

    $response = [
        "html" => grid($items, "article", "articles", "line"),
        "pagination" => $pagination
    ];

    wp_send_json($response);
}


add_action('wp_ajax_like_post', 'like_post');
add_action('wp_ajax_nopriv_like_post', 'like_post');

function like_post()
{
    checkNonce('like_nonce');

    $postID = $_POST['postID'];
    $nbLikes = get_field('nb_like', $postID);
    $ipClient = get_ip_address();

    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}likes WHERE ip_adress = '$ipClient' AND post_id = '$postID' ");

    $isLike = is_null($row);

    if ($isLike) :
        $wpdb->insert("{$wpdb->prefix}likes", array('ip_adress' => $ipClient, 'post_id' => $postID));
        update_field('nb_like', intval($nbLikes) + 1, $postID);
    else :
        $wpdb->delete("{$wpdb->prefix}likes", array('ip_adress' => $ipClient, 'post_id' => $postID));
        update_field('nb_like', intval($nbLikes) - 1, $postID);
    endif;

    $response['Nblikes'] = get_field('nb_like', $postID);
    $response['likeStatus'] = $isLike;

    wp_send_json($response);
}


add_action('wp_ajax_vote_callback', 'vote_callback');
add_action('wp_ajax_nopriv_vote_callback', 'vote_callback');
function vote_callback()
{
    checkNonce('vote_nonce');

    $response = [];

    if (!empty($_POST['questionId']) && !empty($_POST['reponse'])) {
        global $wpdb;

        $poll_table_name = $wpdb->prefix . 'sondages';

        /*
        // Création de la table si elle n'existe pas
        $charset_collate = $wpdb->get_charset_collate();
        $create_table_poll_sql = "CREATE TABLE $poll_table_name (
          poll_id bigint(20) DEFAULT NULL,
          ip varchar(45) DEFAULT NULL,
          response int(11) DEFAULT NULL,
          time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
          PRIMARY KEY  (poll_id, ip)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($create_table_poll_sql);
        */

        $idPoll = $_POST['questionId'];
        $idResponse = $_POST['reponse'];

        // On récupère l'adresse IP
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // On vérfie si l'IP a déjà répondu a ce sondage
        $sqlSelect = $wpdb->prepare("SELECT * FROM $poll_table_name WHERE ip = '$ip' AND poll_id = %d;", $idPoll);
        $row = $wpdb->get_row($sqlSelect);
        $isNotExist = is_null($row);
        if ($isNotExist) :
            $wpdb->insert($poll_table_name, array('ip' => $ip, 'poll_id' => $idPoll, 'response' => $idResponse));
        else :
            $wpdb->update($poll_table_name, array('response' => $idResponse), array('ip' => $ip, 'poll_id' => $idPoll));
        endif;

        // On récupère le résultat du sondage
        $sql = "SELECT response, COUNT(*) nb_reponses, ROUND(COUNT(*) * 100 / count_total) as pct_reponse
        FROM $poll_table_name t1 
        INNER JOIN (SELECT COUNT(*) AS count_total FROM $poll_table_name WHERE poll_id = %d) t2
        WHERE poll_id = %d 
        GROUP BY response;";
        $sqlSelectResultForPoll = $wpdb->prepare($sql, [$idPoll, $idPoll]);
        $rowSelectResultForPoll = $wpdb->get_results($sqlSelectResultForPoll);

        $response['status'] = 200;
        $response['result'] = [];

        foreach ($rowSelectResultForPoll as $row) {
            $response['result']["response_" . $row->response] = $row->pct_reponse;
        }
    } else {
        $response['status'] = 500;
    }

    wp_send_json($response);
}
