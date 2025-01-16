<?php

namespace Evolutio;

/**
 * Plugin Name:       Evolutio Blocks
 * Description:       A cool blocks collection for the Evolutio Avocats lawfirm website.
 * Requires at least: 6.7
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Arthur Blaise
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       evolutio
 *
 * @package EvolutioBlocks
 */

require_once __DIR__ . '/vendor/autoload.php';

use Evolutio\Templates\HomePageTemplateUtil;
use Evolutio\Parts\HeaderPatternUtil;

// Exit if accessed directly
defined('ABSPATH') || exit();

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function evolutio_register_blocks()
{
	$plugin_dir = __DIR__;
	$build_dir = $plugin_dir . '/build/blocks';
	$plugin_url = plugin_dir_url(__FILE__);

	// Automatically register all blocks in the 'build' directory
	foreach (glob($build_dir . '/*', GLOB_ONLYDIR) as $block_dir) {
		$block_name = basename($block_dir);
		$style_path = $plugin_dir . "/src/blocks/{$block_name}/style.css";

		register_block_type($block_dir);

		// Conditionally enqueue the stylesheet if it exists
		if (file_exists($style_path)) {
			wp_enqueue_style(
				"evolutio-{$block_name}-style",
				$plugin_url . "src/blocks/{$block_name}/style.css",
				[],
				filemtime($style_path) // Cache-busting based on file modification time
			);
		}
	}
}
add_action('init', __NAMESPACE__ . '\\evolutio_register_blocks');

function evolutio_register_templates()
{
	HomePageTemplateUtil::RegisterTemplate();
}
add_action('init', __NAMESPACE__ . '\\evolutio_register_templates');

function evolutio_register_patterns()
{
	HeaderPatternUtil::RegisterPattern();
}
add_action('init', __NAMESPACE__ . '\\evolutio_register_patterns');
