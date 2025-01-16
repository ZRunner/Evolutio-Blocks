<?php

namespace Evolutio\Parts;

defined('ABSPATH') || exit();

class HeaderPatternUtil
{

    public static function RegisterPattern()
    {
        register_block_pattern(
            'evolutio/header',
            array(
                'title'       => "Evolutio Header",
                'description' => "A custom header block pattern for Evolutio.",
                'content'     => self::GetPatternContent(),
                'categories'  => array('header'),
                'postTypes'   => array('wp_block'),
                'keywords'    => array('header', 'evolutio'),
            )
        );
    }


    public static function GetPatternContent()
    {
        ob_start();
?>
        <!-- wp:evolutio/appbar {
            "links":[
                {"label":"Accueil","url":"/"},
                {"label":"Philosophie et expertise","url":"/"},
                {"label":"Nos services","url":"/services"},
                {"label":"Notre équipe","url":"/page-d-exemple"},
                {"label":"Le blog","url":"/"}],
            "websiteName":"Evolutio",
            "contactUrl":"/contactez-nous"
        } /-->
<?php
        return ob_get_clean();
    }
}
