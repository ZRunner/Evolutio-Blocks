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

	// Automatically register all blocks in the 'build' directory
	foreach (glob($build_dir . '/*', GLOB_ONLYDIR) as $block_dir) {
		register_block_type($block_dir);
	}

	// Enqueue block styles
	$plugin_url = plugin_dir_url(__FILE__);
	wp_enqueue_style(
		'evolutio-appbar-style',
		$plugin_url . 'src/blocks/appbar/style.css',
		[],
		filemtime($plugin_dir . '/src/blocks/appbar/style.css') // Cache-busting based on file modification time
	);
	wp_enqueue_style(
		'evolutio-header-image-style',
		$plugin_url . 'src/blocks/header-image/style.css',
		[],
		filemtime($plugin_dir . '/src/blocks/header-image/style.css') // Cache-busting based on file modification time
	);
	wp_enqueue_style(
		'evolutio-intro-quote-style',
		$plugin_url . 'src/blocks/intro-quote/style.css',
		[],
		filemtime($plugin_dir . '/src/blocks/intro-quote/style.css') // Cache-busting based on file modification time
	);
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
