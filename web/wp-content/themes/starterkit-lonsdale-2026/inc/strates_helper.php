<?php

class Strate_Helper
{
    public static function strate_fields_init_args($aStrate)
    {
        $args = [
            'options' => [],
            'header' => [],
            'fields' => [],
        ];
        $args = Strate_Helper::strate_fields_custom_options($args, $aStrate);
        $args = Strate_Helper::strate_fields_custom_header($args, $aStrate);
        return $args;
    }

    public static function strate_fields_custom_options($args, $aStrate)
    {
        $args = Strate_Helper::strate_fields_custom_margin($args, $aStrate);
        return $args;
    }

    public static function strate_fields_custom_margin($args, $aStrate)
    {
        if (!empty($aStrate['configuration']['strate_option_margin'])) {
            $args['options']['margin'] = !empty($aStrate['configuration']['strate_option_margin']) ? $aStrate['configuration']['strate_option_margin'] : '';
        }
        return $args;
    }

    public static function strate_fields_custom_header($args, $aStrate)
    {
        if (!empty($aStrate['header']['headline'])) {
            $args['header']['headline'] = !empty($aStrate['header']['headline']) ? $aStrate['header']['headline'] : '';
        }
        if (!empty($aStrate['header']['icon'])) {
            $args['header']['icon'] = !empty($aStrate['header']['icon']) ? $aStrate['header']['icon'] : '';
        }
        if (!empty($aStrate['header']['title'])) {
            $args['header']['title'] = !empty($aStrate['header']['title']) ? $aStrate['header']['title'] : '';
        }
        if (!empty($aStrate['header']['text'])) {
            $args['header']['intro'] = !empty($aStrate['header']['text']) ? $aStrate['header']['text'] : '';
        }
        if (!empty($aStrate['header']['id'])) {
            $args['header']['id'] = !empty($aStrate['header']['id']) ? $aStrate['header']['id'] : '';
        }
        return $args;
    }

    public static function strate_text($aStrate)
    {
        $args = Strate_Helper::strate_fields_init_args($aStrate);

        if (!empty($aStrate['text_content'])) {
            $args['fields']['text'] = $aStrate['text_content'];
        }

        return $args;
    }

    public static function strate_image($aStrate)
    {
        $args = Strate_Helper::strate_fields_init_args($aStrate);

        if (!empty($aStrate['image'])) {
            $args['fields']['images'] = [
                'desktop' => lsd_get_thumb($aStrate['image'], '822_412'),
                'mobile' => lsd_get_thumb($aStrate['image'], '768_400'),
                'width' => 822,
                'height' => 412,
                'alt' => get_post_meta($aStrate['image'], '_wp_attachment_image_alt', true)
            ];
        }

        return $args;
    }
}
