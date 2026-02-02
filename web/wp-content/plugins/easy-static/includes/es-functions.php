<?php
// Hook the 'admin_menu' action hook, run the function named 'mfp_Add_My_Admin_Link()'
// Add a new top level menu link to the ACP
add_action('admin_menu', 'mfp_Add_My_Admin_Link');
function mfp_Add_My_Admin_Link()
{
    global $haschange;
    global $isStatic;
    global $es_isauto;

    add_menu_page(
        'Easy static', // Title of the page
        ($haschange && $isStatic && !$es_isauto) ?  'Easy static <span class="awaiting-mod es-notification">⚠</span>' : 'Easy static', // Text to show on the menu link
        //'Easy static', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'easy-static/includes/es-index.php', // The 'slug' - file to display when clicking the link
        '',
        'dashicons-text-page',
    );
}
if ($isStatic) {
    add_action('admin_bar_menu', 'toolbar_link_to_mypage', 999);
}

function toolbar_link_to_mypage($wp_admin_bar)
{
    global $haschange;
    global $isStatic;
    global $es_isauto;

    $args = array(
        'id'    => 'easy_static',
        'title' => ($haschange && $isStatic && !$es_isauto) ? '<span class="error">Easy static</span>' : 'Easy static',
        'href'  => 'admin.php?page=easy-static%2Fincludes%2Fes-index.php',
        'meta'  => array('class' => 'es-btn_error')
    );
    $wp_admin_bar->add_node($args);
}


// helpers
class TinyHtmlMinifier
{
    private $options;
    private $output;
    private $build;
    private $skip;
    private $skipName;
    private $head;
    private $elements;

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->output = '';
        $this->build = [];
        $this->skip = 0;
        $this->skipName = '';
        $this->head = false;
        $this->elements = [
            'skip' => [
                'code',
                'pre',
                'script',
                'textarea',
            ],
            'inline' => [
                'a',
                'abbr',
                'acronym',
                'b',
                'bdo',
                'big',
                'br',
                'cite',
                'code',
                'dfn',
                'em',
                'i',
                'img',
                'kbd',
                'map',
                'object',
                'samp',
                'small',
                'span',
                'strong',
                'sub',
                'sup',
                'tt',
                'var',
                'q',
            ],
            'hard' => [
                '!doctype',
                'body',
                'html',
            ]
        ];
    }

    // Run minifier
    public function minify(string $html) : string
    {
        if (!isset($this->options['disable_comments']) ||
            !$this->options['disable_comments']) {
            $html = $this->removeComments($html);
        }

        $rest = $html;

        while (!empty($rest)) {
            $parts = explode('<', $rest, 2);
            $this->walk($parts[0]);
            $rest = (isset($parts[1])) ? $parts[1] : '';
        }

        return $this->output;
    }

    // Walk trough html
    private function walk(&$part)
    {
        $tag_parts = explode('>', $part);
        $tag_content = $tag_parts[0];

        if (!empty($tag_content)) {
            $name = $this->findName($tag_content);
            $element = $this->toElement($tag_content, $part, $name);
            $type = $this->toType($element);

            if ($name == 'head') {
                $this->head = $type === 'open';
            }

            $this->build[] = [
                'name' => $name,
                'content' => $element,
                'type' => $type
            ];

            $this->setSkip($name, $type);

            if (!empty($tag_content)) {
                $content = (isset($tag_parts[1])) ? $tag_parts[1] : '';
                if ($content !== '') {
                    $this->build[] = [
                        'content' => $this->compact($content, $name, $element),
                        'type' => 'content'
                    ];
                }
            }

            $this->buildHtml();
        }
    }

    // Remove comments
    private function removeComments($content = '')
    {
        return preg_replace('/(?=<!--)([\s\S]*?)-->/', '', $content);
    }

    // Check if string contains string
    private function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }

    // Return type of element
    private function toType($element)
    {
        return (substr($element, 1, 1) == '/') ? 'close' : 'open';
    }

    // Create element
    private function toElement($element, $noll, $name)
    {
        $element = $this->stripWhitespace($element);
        $element = $this->addChevrons($element, $noll);
        $element = $this->removeSelfSlash($element);
        $element = $this->removeMeta($element, $name);
        return $element;
    }

    // Remove unneeded element meta
    private function removeMeta($element, $name)
    {
        if ($name == 'style') {
            $element = str_replace(
                [
                    ' type="text/css"',
                    "' type='text/css'"
                ],
                ['', ''],
                $element
            );
        } elseif ($name == 'script') {
            $element = str_replace(
                [
                    ' type="text/javascript"',
                    " type='text/javascript'"
                ],
                ['', ''],
                $element
            );
        }
        return $element;
    }

    // Strip whitespace from element
    private function stripWhitespace($element)
    {
        if ($this->skip == 0) {
            $element = preg_replace('/\s+/', ' ', $element);
        }
        return trim($element);
    }

    // Add chevrons around element
    private function addChevrons($element, $noll)
    {
        if (empty($element)) {
            return $element;
        }
        $char = ($this->contains('>', $noll)) ? '>' : '';
        $element = '<' . $element . $char;
        return $element;
    }

    // Remove unneeded self slash
    private function removeSelfSlash($element)
    {
        if (substr($element, -3) == ' />') {
            $element = substr($element, 0, -3) . '>';
        }
        return $element;
    }

    // Compact content
    private function compact($content, $name, $element)
    {
        if ($this->skip != 0) {
            $name = $this->skipName;
        } else {
            $content = preg_replace('/\s+/', ' ', $content);
        }

        if (in_array($name, $this->elements['skip'])) {
            return $content;
        } elseif (in_array($name, $this->elements['hard']) ||
            $this->head) {
            return $this->minifyHard($content);
        } else {
            return $this->minifyKeepSpaces($content);
        }
    }

    // Build html
    private function buildHtml()
    {
        foreach ($this->build as $build) {

            if (!empty($this->options['collapse_whitespace'])) {

                if (strlen(trim($build['content'])) == 0)
                    continue;

                elseif ($build['type'] != 'content' && !in_array($build['name'], $this->elements['inline']))
                    trim($build['content']);

            }

            $this->output .= $build['content'];
        }

        $this->build = [];
    }

    // Find name by part
    private function findName($part)
    {
        $name_cut = explode(" ", $part, 2)[0];
        $name_cut = explode(">", $name_cut, 2)[0];
        $name_cut = explode("\n", $name_cut, 2)[0];
        $name_cut = preg_replace('/\s+/', '', $name_cut);
        $name_cut = strtolower(str_replace('/', '', $name_cut));
        return $name_cut;
    }

    // Set skip if elements are blocked from minification
    private function setSkip($name, $type)
    {
        foreach ($this->elements['skip'] as $element) {
            if ($element == $name && $this->skip == 0) {
                $this->skipName = $name;
            }
        }
        if (in_array($name, $this->elements['skip'])) {
            if ($type == 'open') {
                $this->skip++;
            }
            if ($type == 'close') {
                $this->skip--;
            }
        }
    }

    // Minify all, even spaces between elements
    private function minifyHard($element)
    {
        $element = preg_replace('!\s+!', ' ', $element);
        $element = trim($element);
        return trim($element);
    }

    // Strip but keep one space
    private function minifyKeepSpaces($element)
    {
        return preg_replace('!\s+!', ' ', $element);
    }
}


class TinyMinify
{
    public static function html(string $html, array $options = []): string
    {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }
}

function rm_rf($path)
{
    if (@is_dir($path) && is_writable($path)) {
        $dp = opendir($path);
        while ($ent = readdir($dp)) {
            if ($ent == '.' || $ent == '..') {
                continue;
            }
            $file = $path . DIRECTORY_SEPARATOR . $ent;
            if (@is_dir($file)) {
                rm_rf($file);
            } elseif (is_writable($file)) {
                unlink($file);
            } else {
                echo $file . "is not writable and cannot be removed. Please fix the permission or select a new path.\n";
            }
        }
        closedir($dp);
        return rmdir($path);
    } else {
        return @unlink($path);
    }
}

function copyfolder($from, $to)
{
    // (A1) SOURCE FOLDER CHECK
    if (!is_dir($from)) {
        exit("$from does not exist");
    }

    // (A2) CREATE DESTINATION FOLDER
    if (!is_dir($to)) {
        if (!mkdir($to)) {
            exit("Failed to create $to");
        };
        //echo "$to created\r\n";
    }

    // (A3) COPY FILES + RECURSIVE INTERNAL FOLDERS
    $dir = opendir($from);
    while (($ff = readdir($dir)) !== false) {
        if ($ff != "." && $ff != "..") {
            if (is_dir("$from$ff")) {
                copyfolder("$from$ff/", "$to$ff/");
            } else {
                if (!copy("$from$ff", "$to$ff")) {
                    exit("Error copying $from$ff to $to$ff");
                }
                // echo "$from$ff copied to $to$ff\r\n";
            }
        }
    }
    closedir($dir);
}

function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

class SitemapGenerator2
{
    private $config;
    private $scanned;
    private $isminify;
    private $site_url_base;
    private $authentification;

    // Constructor sets the given file for internal use
    public function __construct($isminify, $authentification)
    {
        $conf =  array(
            "SITE_URL" => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/',

            // Boolean for crawling external links.
            // <Example> *Domain = https://www.student-laptop.nl* , *Link = https://www.google.com* <When false google will not be crawled>
            "ALLOW_EXTERNAL_LINKS" => false,

            // Boolean for crawling element id links.
            // <Example> <a href="#section"></a> will not be crawled when this option is set to false
            "ALLOW_ELEMENT_LINKS" => false,

            // If set the crawler will only index the anchor tags with the given id.
            // If you wish to crawl all links set the value to ""
            // <Example> <a id="internal-link" href="/info"></a> When CRAWL_ANCHORS_WITH_ID is set to "internal-link" this link will be crawled
            // but <a id="external-link" href="https://www.google.com"></a> will not be crawled.
            "CRAWL_ANCHORS_WITH_ID" => "",

            // Array with absolute links or keywords for the pages to skip when crawling the given SITE_URL.
            // <Example> https://student-laptop.nl/info/laptops or you can just input student-laptop.nl/info/ and it will not crawl anything in that directory
            // Try to be as specific as you can so you dont skip 300 pages
            "KEYWORDS_TO_SKIP" => array('mailto', 'upload'),
        );
        // Setup class variables using the config
        $this->config = $conf;
        $this->scanned = [];
        $this->site_url_base = parse_url($this->config['SITE_URL'])['scheme'] . "://" . parse_url($this->config['SITE_URL'])['host'];
        $this->isminify = $isminify;
        $this->authentification = $authentification;
    }

    public function GenerateSitemap()
    {
        global $es_folder_static;
        rm_rf(WP_CONTENT_DIR . '/' . $es_folder_static . '/static/');
        mkdir(WP_CONTENT_DIR . '/' . $es_folder_static . '/static/', 0755, true);

        $this->crawlPage($this->site_url_base . "/");
    }

    private function getHtml($url, $page_url)
    {
        global $es_folder_static;
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        if (ENV_PREPROD_LONSDALE) {
            $user_pass =  $this->authentification["user"] . ':' .  $this->authentification["password"];
            $arrContextOptions['http'] =  array(
                'header' => array(
                    'Authorization: Basic ' . base64_encode($user_pass),
                )
            );
        }

        // folder
        $folder = str_replace($this->site_url_base . "/", "", $page_url);
        rm_rf(WP_CONTENT_DIR . '/' . $es_folder_static . '/static/' . $folder);
        mkdir(WP_CONTENT_DIR . '/' . $es_folder_static . '/static/' . $folder, 0755, true);

        if (ENV_LOCAL) {
            $docker_url = str_replace($this->site_url_base . "/", 'https://' . $_SERVER['SERVER_ADDR'] . '/', $url);
            $html = file_get_contents($docker_url . "?generate=true", false, stream_context_create($arrContextOptions));
            $html1 = str_replace('https://' . $_SERVER['SERVER_ADDR'],  "", $html);
            $html1 = str_replace($this->site_url_base,  "", $html1);
        } else {
            $html = file_get_contents($url . "?generate=true", false, stream_context_create($arrContextOptions));
            $html1 = str_replace($this->site_url_base, "", $html);
        }

        if ($this->isminify  === true) {
            file_put_contents(WP_CONTENT_DIR . "/'.$es_folder_static.'/static/" .  $folder  . 'index.html', TinyMinify::html($html1));
        } else {
            file_put_contents(WP_CONTENT_DIR . "/'.$es_folder_static.'/static/" .  $folder  . 'index.html', $html1);
        }

        //Load the html and store it into a DOM object
        if ($html) {
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            return $dom;
        }
    }

    // Recursive function that crawls a page's anchor tags and store them in the scanned array.
    private function crawlPage($page_url)
    {
        $page_url = rtrim($page_url, "/") . '/';

        if (ENV_LOCAL) {
            $page_url = str_replace('https://' . $_SERVER['SERVER_ADDR'], $this->site_url_base, $page_url);
        }

        $url = filter_var($page_url, FILTER_SANITIZE_URL);


        // Check if the url is invalid or if the page is already scanned;
        if (in_array($url, $this->scanned, FALSE) || !filter_var(str_replace("_", "", $page_url), FILTER_VALIDATE_URL)) {
            return;
        }

        // Add the page url to the scanned array
        array_push($this->scanned, $page_url);

        // Get the html content from the 
        $html = $this->getHtml($url, $page_url);

        if ($html) {
            $anchors = $html->getElementsByTagName('a');

            // Loop through all anchor tags on the page
            foreach ($anchors as $a) {

                $next_url = $a->getAttribute('href');

                if (ENV_LOCAL) {
                    $next_url = str_replace('https://' . $_SERVER['SERVER_ADDR'], $this->site_url_base, $next_url);
                }

                // Check if there is a anchor ID set in the config.
                if ($this->config['CRAWL_ANCHORS_WITH_ID'] != "") {
                    // Check if the id is set and matches the config setting, else it will move on to the next anchor
                    if ($a->getAttribute('id') != "" || $a->getAttribute('id') == $this->config['CRAWL_ANCHORS_WITH_ID']) {
                        continue;
                    }
                }

                // Split page url into base and extra parameters
                $base_page_url = explode("?", $page_url)[0];

                if (!$this->config['ALLOW_ELEMENT_LINKS']) {
                    // Skip the url if it starts with a # or is equal to root.
                    if (substr($next_url, 0, 1) == "#" || $next_url == "/") {
                        continue;
                    }
                }

                // Check if the given url is external, if yes it will skip the iteration
                // This code will only run if you set ALLOW_EXTERNAL_LINKS to false in the config.
                if (!$this->config['ALLOW_EXTERNAL_LINKS']) {
                    $parsed_url = parse_url($next_url);
                    if (isset($parsed_url['host'])) {
                        if ($parsed_url['host'] != parse_url($this->config['SITE_URL'])['host']) {
                            continue;
                        }
                    }
                }

                // Check if the link is absolute or relative.
                if (substr($next_url, 0, 7) != "http://" && substr($next_url, 0, 8) != "https://") {
                    $next_url = $this->convertRelativeToAbsolute($base_page_url, $next_url);
                }

                // Check if the next link contains any of the pages to skip. If true, the loop will move on to the next iteration.
                $found = false;
                foreach ($this->config['KEYWORDS_TO_SKIP'] as $skip) {
                    if (strpos($next_url, $skip) || $next_url === $skip) {
                        $found = true;
                    }
                }

                // Call the function again with the new URL
                if (!$found) {
                    $this->crawlPage($next_url);
                }
            }
        }
    }

    // Convert a relative link to a absolute link
    // Example: Relative /articles
    // Absolute https://student-laptop.nl/articles
    private function convertRelativeToAbsolute($page_base_url, $link)
    {
        $first_character = substr($link, 0, 1);
        if ($first_character == "?" || $first_character == "#") {
            return $page_base_url . $link;
        } else if ($first_character != "/") {
            return $this->site_url_base . "/" . $link;
        } else {
            return $this->site_url_base . $link;
        }
    }
}

function generate_all()
{
    global $isminify;
    global $authentification;
    global $table;


    $smg = new SitemapGenerator2($isminify, $authentification);
    $smg->GenerateSitemap();

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET sc_value = CURRENT_TIMESTAMP WHERE es_option ='generate' ";
    mysqli_query($link, $sql);

    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET sc_value = 0 WHERE es_option ='haschange' ";
    mysqli_query($link, $sql);

    mysqli_close($link);
}

function generate_post($post)
{
    global $isminify;
    global $authentification;
    global $table;
    global $es_folder_static;

    if ($post->post_type != "publish") return;

    $permalink = get_permalink($post->ID);

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    if (ENV_PREPROD_LONSDALE) {
        $user_pass = $authentification["user"] . ':' . $authentification["password"];
        $arrContextOptions['http'] =  array(
            'header' => array(
                'Authorization: Basic ' . base64_encode($user_pass),
            )
        );
    }

    $SITE_URL = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/';
    $site_url_base = parse_url($SITE_URL)['scheme'] . "://" . parse_url($SITE_URL)['host'];
    $folder = str_replace($site_url_base . "/", "", $permalink);

    if (ENV_LOCAL) {
        $docker_url = str_replace($site_url_base . "/", 'https://' . $_SERVER['SERVER_ADDR'] . '/', $permalink);
        $html = file_get_contents($docker_url . "?generate=true", false, stream_context_create($arrContextOptions));
        $html1 = str_replace('https://' . $_SERVER['SERVER_ADDR'],  "", $html);
        $html1 = str_replace($site_url_base,  "", $html1);
    } else {
        $html = file_get_contents($permalink . "?generate=true", false, stream_context_create($arrContextOptions));
        $html1 = str_replace($site_url_base, "", $html);
    }

    $markup = $isminify ?  TinyMinify::html($html1) : $html1;

    file_put_contents(WP_CONTENT_DIR . "/" . $es_folder_static . "/static/" .  $folder  . 'index.html', $markup);
}

function hasChanged()
{
    global $table;
    $link = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
    $sql = "UPDATE " . $table . " SET sc_value = true WHERE es_option ='haschange' ";
    mysqli_query($link, $sql);
    mysqli_close($link);
}
