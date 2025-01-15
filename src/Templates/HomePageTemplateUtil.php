<?php

namespace Evolutio\Templates;

defined('ABSPATH') || exit();

class HomePageTemplateUtil
{
    const PLUGIN_SLUG = 'evolutio';
    const TEMPLATE_ID = 'evolutio-homepage-template';

    public static function RegisterTemplate()
    {
        register_block_template(
            self::PLUGIN_SLUG . "//" . self::TEMPLATE_ID,
            array(
                'title' => "Homepage Template",
                'description' => "A test template for the homepage :)",
                'content' => self::GetTemplateContent(),
            )
        );
    }

    public static function GetTemplateContent()
    {
        ob_start();
?>
        <!-- wp:template-part {"slug":"header","lock":{"move":true,"remove":true}} /-->
        <span>Hello Z world!</span>
        <!-- wp:group {"tagName":"main","lock":{"move":true,"remove":true},"style":{"spacing":{"margin":{"top":"0rem"},"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"blockGap":"var:preset|spacing|superbspacing-medium"}},"layout":{"type":"default"}} -->
        <main class="wp-block-group" style="margin-top:0rem;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
            <!-- wp:post-content {"align":"full","layout":{"type":"default"},"lock":{"move":true,"remove":true}} /-->
        </main>
        <!-- /wp:group -->

        <!-- wp:template-part {"slug":"footer","lock":{"move":true,"remove":true}} /-->
<?php
        return ob_get_clean();
    }
}
