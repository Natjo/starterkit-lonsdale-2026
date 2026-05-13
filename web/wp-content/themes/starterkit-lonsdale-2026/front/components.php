<?php

class component
{
    public static function title($args, $hx = 2, $classes = null, $attributes = null)
    {
        if (is_string($args)) {
            $args = ["title" => $args];
        }

        if (empty($args["title"])) return;

        get_template_part('template-parts/components/title', '', [
            "hx"      => $hx,
            "title"   => $args["title"],
            "center"  => (!empty($args["center"]) || !empty($args["title_center"])) ? true : false,
            "classes" => $classes,
            "attributes" => $attributes,
        ]);
    }

    public static function text($args, $classes = [])
    {

        if (!empty($args["legend"])) {
            $text = $args["legend"];
        }
        if (!empty($args["text"])) {
            $text = $args["text"];
        }
        if (!empty($args["intro"])) {
            $text = $args["intro"];
        }
        if (empty($text)) return;

        get_template_part('template-parts/components/text', '', [
            "text" => $text,
            "classes" => $classes,
        ]);
    }

    public static function picture($args, $sizes = "full", $classes = "", $lazy = true, $placeholder = false, $breakpoint = 768)
    {
        // $sizes accepte :
        // - une string  → desktop seulement (mobile retombe sur "full")
        // - un tableau  → [desktop, mobile] (index 0 = desktop, index 1 = mobile)
        if (is_array($sizes)) {
            $desktopSize = isset($sizes[0]) && $sizes[0] !== "" ? (string) $sizes[0] : "full";
            $mobileSize = isset($sizes[1]) && $sizes[1] !== "" ? (string) $sizes[1] : "full";
        } else {
            $desktopSize = is_string($sizes) && $sizes !== "" ? $sizes : "full";
            $mobileSize = "full";
        }

        get_template_part('template-parts/components/picture', '', [
            "images" => is_array($args) ? ($args["images"] ?? $args) : $args,
            "classes" => $classes,
            "lazy" => $lazy,
            "breakpoint" => $breakpoint,
            "placeholder" => $placeholder,
            "desktop_size" => $desktopSize,
            "mobile_size" => $mobileSize,
        ]);
    }

    public static function image($image, $size = "full", $classes = "", $lazy = true)
    {
        if (empty($image)) return;

        get_template_part('template-parts/components/image', '',   [
            "image" => $image,
            "size" => $size,
            "classes" => $classes,
            "lazy" => $lazy,
        ]);
    }

    public static function link($link, $classes = null, $icon = null, $attributes = null)
    {
        if (empty($link["title"])) return;

        get_template_part('template-parts/components/link', '', [
            "link" => $link,
            "classes" => $classes,
            "icon" => $icon,
            "attributes" => $attributes
        ]);
    }

    public static function btn($args, $classes = null, $icon = [], $attributes = null)
    {

        if (empty($args)) return;

        $isLink = is_array($args);

        if ($isLink  && empty($args["link"])) return;

        if ($isLink && empty($args["link"]["title"])) return;

        get_template_part('template-parts/components/btn', '', [
            "name" =>  $isLink ? "" : $args,
            "link" =>   $isLink ? $args["link"] : "",
            "classes" => $classes,
            "icon" => $icon,
            "attributes" => $attributes
        ]);
    }

    public static function header($args, $classes = null)
    {

        if (empty($args["title"]) && empty($args["text"])  && empty($args["link"])) return;

        get_template_part('template-parts/components/header', '', [
            "title" => !empty($args["title"]) ? $args["title"] : null,
            "text" => !empty($args["text"]) ? $args["text"] : null,
            "link" => !empty($args["link"]) ? $args["link"] : null,
            "center" => (!empty($args["center"]) || !empty($args["title_center"])) ? true : false,
            "classes" => $classes
        ]);
    }

    public static function icon($name, $width, $height, $classes = null)
    {
        if (empty($name)) return;

        get_template_part('template-parts/components/icon', '', [
            "name" => $name,
            "width" =>  $width,
            "height" =>  $height,
            "url" =>  THEME_ASSETS,
            "classes" => $classes
        ]);
    }

    public static function picto($name, $type = "", $size = "", $classes = null, $attributes = null)
    {
        if (empty($name)) return;

        get_template_part('template-parts/components/picto', '', [
            "name" => $name,
            "type" => $type,
            "size" => $size,
            "classes" => $classes,
            "attributes" => $attributes,
        ]);
    }

    public static function tag($args, $type = "info", $classes = null, $attributes = null)
    {
        get_template_part('template-parts/components/tag', '', [
            "args" => $args,
            "type" => $type,
            "classes" => $classes,
            "attributes" => $attributes,
        ]);
    }

    public static function autocomplete($items, $label, $classes = null, $attributes = null)
    {
        if (empty($items) || !is_array($items)) return;
        addStyle("autocomplete", "components");
        get_template_part('template-parts/components/autocomplete', '', [
            "items" => $items,
            "label" => $label,
        ]);
    }


    /**
     * @param array<int, array{name:string,value?:string,selected?:bool,disabled?:bool}> $args
     */
    public static function select($args, $label, $multi = false, $classes = null, $attributes = null)
    {
        if (empty($args) || !is_array($args)) return;

        addStyle("select", "components");
        get_template_part('template-parts/components/select', '', [
            "args" => $args,
            "label" => $label,
            "multi" => $multi,
            "classes" => $classes,
            "attributes" => $attributes,
        ]);
    }

    public static function badge($name, $classes = null, $attributes = null)
    {
        if (empty($name)) return;
        $args = [
            "name" => $name,
            "classes" => $classes,
            "attributes" => $attributes,
        ];
        get_template_part('template-parts/components/badge', '', $args);
    }

    /**
     * @param array<int, mixed> $trigger [0] => "btn"|"link", [1] => label (null = défaut i18n), [2] => classes CSS du déclencheur
     */
    public static function dialog($content, $trigger = ["btn", null, null], $classes = null, $attributes = null)
    {
        if (!is_array($trigger)) {
            $trigger = ["btn", null, null];
        }
        $t = array_values($trigger);
        $t = array_pad($t, 3, null);
        $kind = isset($t[0]) ? strtolower(trim((string) $t[0])) : "btn";
        $kind = $kind === "link" ? "link" : "btn";
        $trigger = [
            $kind,
            ($t[1] !== null && trim((string) $t[1]) !== "") ? trim((string) $t[1]) : null,
            ($t[2] !== null && trim((string) $t[2]) !== "") ? trim((string) $t[2]) : null,
        ];

        addStyle("dialog", "components");
        get_template_part('template-parts/components/dialog', '', [
            "content" => $content,
            "trigger" => $trigger,
            "classes" => $classes,
            "attributes" => $attributes,
        ]);
    }



    public static function shares($list, $classes = null)
    {
        if (empty($list)) return;

        $args = [
            "list" => $list,
            "classes" => $classes
        ];

        get_template_part('template-parts/components/shares', '', $args);
    }

    public static function video($url, $title = "", $poster = null, $autoplay = false, $loop = false, $classes = null, $attributes = null)
    {
        if (empty($url)) return;
        addStyle("video", "components");
        get_template_part('template-parts/components/video', '', [
            "url" => $url,
            "title" => $title,
            "poster" => $poster,
            "autoplay" => $autoplay,
            "loop" => $loop,
            "classes" => $classes,
            "attributes" => $attributes,
        ]);
    }

    public static function card($name, $args)
    {
        global $components;

        addStyle($name, "cards");
        $slug = trim((string) $name);
        if ($slug === '') return;

        // Backward compatibility: allow calling `card("news")` when the template
        // is named `card-news.php`.
        $base = get_template_directory() . '/template-parts/cards/';
        $direct = $base . $slug . '.php';
        $prefixed = $base . 'card-' . $slug . '.php';
        if (!file_exists($direct) && file_exists($prefixed)) {
            $slug = 'card-' . $slug;
        }

        get_template_part('template-parts/cards/' . $slug, null, $args);
    }

    public static function slider($items, $card = "news", $classes = null, $navigation = true, $pagination = false)
    {
        if (empty($items)) return;

        addStyle("slider", "components");

        $args = [
            "items" => $items,
            "navigation" => $navigation,
            "pagination" => $pagination,
            "card" => $card,
            "classes" => $classes,
        ];
        get_template_part('template-parts/components/slider', '', $args);
    }

    public static function list($items, $card = "news", $classes = null)
    {
        if (empty($items)) return;

        //addStyle("list", "components");

        $args = [
            "items" => $items,
            "card" => $card,
            "classes" => $classes,
        ];
        get_template_part('template-parts/components/list', '', $args);
    }

    public static function accordion($items, $classes = null, $attributes = null)
    {
        if (empty($items)) return;
        addStyle("accordion", "components");
        $args = [
            "items" => $items,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/accordion', '', $args);
    }

    public static function navanchor($items, $classes = null, $attributes = null)
    {
        if (empty($items)) return;
        $args = [
            "items" => $items,
            "classes" => $classes,
            "attributes" => $attributes
        ];
        get_template_part('template-parts/components/navanchor', '', $args);
    }
}
