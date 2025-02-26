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

use Evolutio\Parts\FooterPatternUtil;
use Evolutio\Parts\HeaderPatternUtil;
use Evolutio\PostTypes\CustomerReviewPostUtil;
use Evolutio\PostTypes\ServicesPostUtil;

// Exit if accessed directly
defined('ABSPATH') || exit();

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function evolutio_register_blocks(): void
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

function evolutio_register_patterns(): void
{
	HeaderPatternUtil::RegisterPattern();
	FooterPatternUtil::RegisterPattern();
	CustomerReviewPostUtil::RegisterPostType();
}

add_action('init', __NAMESPACE__ . '\\evolutio_register_patterns');

function evolutio_register_post_types(): void
{
	CustomerReviewPostUtil::RegisterPostType();
	ServicesPostUtil::RegisterPostType();
}

add_action('init', __NAMESPACE__ . '\\evolutio_register_post_types');

function evolutio_redirect_blog_url()
{
	if (is_feed() || !isset($_SERVER['REQUEST_URI'])) {
		return; // Skip redirection for feeds
	}

	$path = untrailingslashit(wp_parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

	if ($path === '/blog-it' || is_category('it')) {
		wp_redirect(home_url('/blog'), 301);
		exit();
	}
}
add_action('template_redirect', __NAMESPACE__ . '\\evolutio_redirect_blog_url');
