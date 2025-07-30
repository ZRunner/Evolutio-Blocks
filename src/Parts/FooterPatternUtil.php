<?php

namespace Evolutio\Parts;

defined('ABSPATH') || exit();

class FooterPatternUtil
{

    public static function RegisterPattern()
    {
        register_block_pattern(
            'evolutio/footer',
            array(
                'title'       => "Evolutio Footer",
                'description' => "A custom footer block pattern for Evolutio.",
                'content'     => self::GetPatternContent(),
                'categories'  => array('footer'),
                'postTypes'   => array('wp_block'),
                'keywords'    => array('footer', 'evolutio'),
            )
        );
    }


    public static function GetPatternContent()
    {
        ob_start();
?>
        <!-- wp:evolutio/footer {
            "links": [
                {"label":"Paris", "icon":"hammer", "url":"http://www.avocatparis.org/annuaire"},
								{"label":"Lyon", "icon":"hammer", "url":"http://www.barreaulyon.com/Recherche-avocat"},
								{"label":"Dijon", "icon":"hammer", "url":"https://www.barreau-dijon.avocat.fr/trouver-un-avocat"},
								{"label":"LinkedIn", "icon":"linkedin", "url":"https://www.linkedin.com/company/evolutio-avocats"}
            ],
            "brandImage": {
                "id":306,
                "url":"/wp-content/uploads/2025/01/Evolutio-1.png"
            },
            "align":"full"
        } /-->
<?php
        return ob_get_clean();
    }
}
