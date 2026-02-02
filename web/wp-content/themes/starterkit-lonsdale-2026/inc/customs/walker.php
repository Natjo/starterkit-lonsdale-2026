<?php

class menu_header_Walker extends Walker_Nav_Menu
{

    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $hasChildren = $args->walker->has_children;
        $title = $item->title;
        $permalink = $item->url;
        $target = !empty($item->target) ? ' rel="noreferrer"  target="' . $item->target . '"' : '';
        $output .= '<li>';
        if (!empty($hasChildren))
            $output .= '<a class="link-' . $depth . ' desktop" href="' . $permalink . '" ' . $target . '>';
        else
            $output .= '<a class="link-' . $depth . '" href="' . $permalink . '" ' . $target . '>';
        if ($item->object == "destinations")  $output .= icon('pin', 20, 28);
        $output .= $title;
        $output .= '</a>';
        if (!empty($hasChildren)) $output .= '<button class="btn-0 mobile">' . $title . '</button>';
    }

    // @see Walker::start_lvl()
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Default class.
        $classes = array('nav-links level-' . ($depth + 1));

        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @since 4.8.0
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args    An object of `wp_nav_menu()` arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = implode(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }
}

class Lsd_Header_Walker extends Walker_Nav_Menu
{
    public $idElement = 0;
    public $idLvl = 0;
    public $depthLvl = 0;
    public $parentLink = "";
    public $parentName = "";
    public $bIsPushCard = false;

    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        if (0 !== $this->depthLvl) {
            $this->bIsPushCard = false;
        }

        $output .= "</li>{$n}";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $this->idElement += 1;

        $title = $item->title;
        $target = !empty($item->target) ? ' rel="noreferrer" target="' . $item->target . '"' : '';
        $permalink = $item->url;

        $menu_header_picto = get_field('menu_header_picto', $item->ID);
        $picto = !empty($menu_header_picto['icon']) ? $menu_header_picto['icon'] : '';

        $is_current_item = in_array('current-menu-item', $item->classes);
        $is_parent_of_current = in_array('current-menu-parent', $item->classes);

        $sClasseActive = $is_current_item ? ' active' : '';
        $is_parent_active = $is_parent_of_current ? ' active' : '';
        $classe = 'link' . $depth;
        $has_children = !empty($args->walker->has_children);

        // Attribut aria-current
        $ariaCurrent = $is_current_item ? 'page' : ($is_parent_of_current ? 'true' : '');
        $sAriaCurrent = !empty($ariaCurrent) ? ' aria-current="' . esc_attr($ariaCurrent) . '"' : '';

        $output .= '<li class="item' . $depth . ($has_children ? ' has-children' : '') . '">';


        if ($depth == 0) {
            //desktop
            $output .= '<a ' . data_trk("header", $title) . ($has_children ? ' aria-haspopup="true" aria-expanded="false"' : '') . ' href="' . $permalink . '" ' . $target . ' class="desktop ' . $classe . $sClasseActive . $is_parent_active . '"' . $sAriaCurrent . ' >';
            $output .=  $title;
            $output .= '</a>';

            //mobile
            $output .= '<div class="link0-wrapper mobile">';
            $output .= '<a ' . data_trk("header", $title) . ' href="' . $permalink . '" ' . $target . ' class="' . $classe . $sClasseActive . $is_parent_active . '"' . $sAriaCurrent . ' >';
            $output .= icon($picto, 36, 36) . $title;
            $output .= '</a>';
            if ($has_children) $output .= '<button class="btn-level1" aria-label="Afficher les Sous rubriques" aria-expanded="false" aria-haspopup="true">' . icon("chevron", 20, 12) . '</button>';
            $output .= '</div>';
        } else {
            $output .= '<a ' . data_trk("header", $title) . ' href="' . $permalink . '" ' . $target . ' class="' . $classe . $sClasseActive . $is_parent_active . '"' . $sAriaCurrent . ' >';
            $output .=  $title;
            $output .= '</a>';
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        $indent = str_repeat($t, $depth);
        $level = $depth + 1;
        $output .= "{$indent}<div class='level{$level}'><ul class='ul{$level}' tabindex='-1'>{$n}";

        $this->depthLvl += 1;
        $this->idLvl += 1;
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth == 1) $output .= "</div>\n";
        $output .= "</ul>\n";
        $this->depthLvl -= 1;
    }
}


class Lsd_Footer_Mag_Walker extends Walker_Nav_Menu
{

    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $title = $item->title;
        $permalink = $item->url;
        $target = !empty($item->target) ? ' rel="noreferrer"  target="' . $item->target . '"' : '';

        $output .= '<li>';

        $output .= '<a ' .  data_trk("footer", $title) . ' href="' . $permalink . '" ' . $target . '>';
        $output .= $title;
        $output .= '</a>';
    }

    // @see Walker::start_lvl()
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul>{$n}";
    }
}

class Lsd_Footer_Bouygues_Walker extends Walker_Nav_Menu
{
    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $title = $item->title;
        $permalink = $item->url;

        $target = !empty($item->target) ? ' aria-label="' . $title . ', s\'ouvre dans un nouvel onglet" rel="noreferrer"  target="' . $item->target . '"' : '';
        $output .= '<li>';
        $output .= '<a ' . data_trk("footer", $title) . ' href="' . $permalink . '" ' . $target . '>';
        $output .= $title;
        $output .= '</a>';
    }

    // @see Walker::start_lvl()
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul>{$n}";
    }
}

class Lsd_Footer_Social_Walker extends Walker_Nav_Menu
{

    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $title = $item->title;
        $permalink = $item->url;
        $target = !empty($item->target) ? ' rel="noreferrer"  target="' . $item->target . '"' : '';

        $output .= '<li>';

        $sPicto = get_field('icon', $item->ID);
        $output .= '<a ' . data_trk("share", $title) . ' href="' . $permalink . '" ' . $target . ' aria-label="' . $title . ', s\'ouvre dans un nouvel onglet">';
        $output .= icon($sPicto, 36, 36);
        $output .= '</a>';
    }

    // @see Walker::start_lvl()
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul>{$n}";
    }
}

class Lsd_Footer_Subfooter_Walker extends Walker_Nav_Menu
{
    // @see Walker::start_el()
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $title = $item->title;
        $isButton = $item->classes[0] === "button" ? 'role="button"' : "";
        $permalink = $item->url;
        $target = !empty($item->target) ? ' rel="noreferrer"  target="' . $item->target . '"' : '';
        $output .= '<li>';
        $output .= '<a ' . $isButton . ' href="' . $permalink . '" ' . $target . '>';
        $output .= $title;
        $output .= '</a>';
    }

    // @see Walker::start_lvl()
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "{$n}{$indent}<ul>{$n}";
    }
}
