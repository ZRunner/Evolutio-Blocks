<?php

/**
 * Plugin Name:       Evolutio Block
 * Description:       A cool blocks collection for the Evolutio Avocats lawfirm website.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Arthur Blaise
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       evolutio
 *
 * @package CreateBlock
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function evolutio_blocks_init()
{
	$plugin_dir = __DIR__;
	$build_dir = $plugin_dir . '/build/blocks';

	// Automatically register all blocks in the 'build' directory
	foreach (glob($build_dir . '/*', GLOB_ONLYDIR) as $block_dir) {
		register_block_type($block_dir);
	}

	// Enqueue global styles
	$plugin_url = plugin_dir_url(__FILE__);
	wp_enqueue_style(
		'evolutio-appbar-style',
		$plugin_url . 'src/blocks/appbar/style.css',
		[],
		filemtime($plugin_dir . '/src/blocks/appbar/style.css') // Cache-busting based on file modification time
	);
}
add_action('init', 'evolutio_blocks_init');
