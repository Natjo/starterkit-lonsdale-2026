<?php

class component
{
    public static function title($args, $hx = 2, $classes = null)
    {
        if (is_string($args)) {
            $args = ["title" => $args];
        }

        if (empty($args["title"])) return;

        get_template_part('template-parts/components/title', '', [
            "hx"      => $hx,
            "title"   => $args["title"],
            "center"  => (!empty($args["center"]) || !empty($args["title_center"])) ? true : false,
            "classes" => $classes
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

    public static function picture($args, $classes = "", $lazy = true, $placeholder = false, $breakpoint = 768)
    {
        // Accepts: array $args with "images" key, a direct image ID (int), or a path (string).
        // "images" can be ["desktop" => id, "mobile" => id], a scalar, or an indexed array.
        $raw = is_array($args) ? ($args["images"] ?? null) : $args;

        if (!is_array($raw) || !array_key_exists("desktop", $raw) && !array_key_exists("mobile", $raw)) {
            $raw = ["desktop" => is_array($raw) ? reset($raw) : $raw];
        }

        if (empty($raw["desktop"]) && empty($raw["mobile"])) {
            if ($placeholder) {
                echo '<picture class="placeholder' . ($classes ? " $classes" : "") . '"></picture>';
            }
            return;
        }

        $resolve = function ($id, $size) {
            if (is_string($id) && !is_numeric($id)) {
                $width = 0; $height = 0;
                if (preg_match('/(\d+)x(\d+)/', basename($id), $m)) {
                    [$width, $height] = [(int)$m[1], (int)$m[2]];
                }
                return ["src" => $id, "width" => $width, "height" => $height, "alt" => "", "webp" => ""];
            }
            $img = lsd_get_thumb((int)$id, $size);
            if (empty($img[0])) return [];
            return ["src" => $img[0], "width" => $img[1], "height" => $img[2], "alt" => $img[3], "webp" => hasWebp($img)];
        };

        get_template_part('template-parts/components/picture', '', [
            "desktop" => !empty($raw["desktop"]) ? $resolve($raw["desktop"], $args["desktop_size"] ?? "full") : [],
            "mobile" => !empty($raw["mobile"])  ? $resolve($raw["mobile"],  $args["mobile_size"]  ?? "full") : [],
            "classes" => $classes,
            "lazy" => $lazy,
            "breakpoint" => $breakpoint,
            "placeholder" => $placeholder,
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

    public static function picto($name, $size = "", $animate = false)
    {
        if (empty($name)) return;

        get_template_part('template-parts/components/picto', '', [
            "name" => $name,
            "size" => $size,
            "animate" => $animate
        ]);
    }

    public static function tag($args, $classes = null)
    {
        if (empty($args["tag"])) return;

        get_template_part('template-parts/components/tag', '', [
            "name" => $args["tag"],
            "classes" => $classes,
        ]);
    }

    public static function badge($name, $size = "md")
    {
        if (empty($name)) return;
        $args = [
            "name" => $name,
            "size" => $size,

        ];
        get_template_part('template-parts/components/badge', '', $args);
    }

    public static function list($items, $card = "card-news", $classes = null)
    {
        if (empty($items)) return;
        $cardName = str_starts_with((string) $card, "card-") ? $card : "card-" . $card;

        $args = [
            "items" => $items,
            "card" => $cardName,
            "classes" => $classes
        ];

        get_template_part('template-parts/components/list', '', $args);
    }



    public static function card($name, $args)
    {
        global $components;

        addStyle($name, "cards");

        get_template_part('template-parts/cards/' . $name, null, $args);
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

    public static function accordion($items, $classes = null, $attributes = null)
    {
        if (empty($items)) return;
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
