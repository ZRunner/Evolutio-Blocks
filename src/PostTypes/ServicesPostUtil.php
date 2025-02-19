<?php

namespace Evolutio\PostTypes;

defined('ABSPATH') || exit();

abstract class ServicesPostUtil
{
	const META_FIELDS = ['service_mobile_name', 'service_description', 'service_url'];

	/**
	 * Set up and add the meta box.
	 */
	public static function RegisterPostType(): void
	{
		register_post_type('service', array(
			'labels' => array(
				'name' => 'Services',
				'singular_name' => 'Service',
				'add_new_item' => 'Add a service',
				'edit_item' => 'Edit service',
				'new_item' => 'New service',
				'view_item' => 'View service',
				'search_items' => 'Search services',
				'not_found' => 'No service found',
				'not_found_in_trash' => 'No service found in trash'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_rest' => true,
			'supports' => array('title', 'thumbnail'), // Only allow title (reviewer's name)
			'menu_position' => 6,
			'menu_icon' => 'dashicons-admin-tools',
			'delete_with_user' => false,
		));
		add_action('add_meta_boxes', [self::class, 'AddMetaBox']);
		add_action('rest_api_init', [self::class, 'AddApiFields']);
		add_action('save_post', [self::class, 'Save'], 10, 2);
	}

	/**
	 * Add the meta box to the post editor.
	 */
	public static function AddMetaBox(): void
	{
		add_meta_box(
			'service_details',       // Unique ID
			'Service Details',       // Box title
			[self::class, 'html'],   // Content callback, must be of type callable
			'service',               // Post type
			'normal'			     // Context
		);
	}

	/**
	 * Add custom fields to the REST API.
	 */
	public static function AddApiFields(): void
	{
		foreach (self::META_FIELDS as $field) {
			register_rest_field(
				'service',
				$field,
				array(
					'get_callback' => function ($object) use ($field) {
						// Get field as single value from post meta
						return get_post_meta($object['id'], $field, true);
					},
					'update_callback' => null,
					'schema' => array(
						'type' => 'string',
						'arg_options' => array(
							'sanitize_callback' => 'sanitize_text_field'
						),
					),
				)
			);
		}
		// Add featured image source to the REST API
		register_rest_field(
			'service',
			'featured_image_src',
			array(
				'get_callback' => function ($object) {
					$feat_img_array = wp_get_attachment_image_src(
						$object['featured_media'], // Image attachment ID
						'large',  // Size.  Ex. "thumbnail", "medium", "large", "full", etc..
						true // Whether the image should be treated as an icon.
					);
					return $feat_img_array ? $feat_img_array[0] : '';
				},
				'update_callback' => null,
				'schema' => array('type' => 'string'),
			)
		);
	}

	/**
	 * Save the meta box selections.
	 *
	 * @param int $post_id The post ID.
	 * @param WP_Post $post The post object.
	 */
	public static function Save(int $post_id, $post): void
	{
		// Security checks
		if (!isset($_POST['service_meta_nonce']) || !wp_verify_nonce($_POST['service_meta_nonce'], 'save_service_meta')) {
			return;
		}
		// Ensure the user has permission to edit
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		foreach (self::META_FIELDS as $field) {
			if (isset($_POST[$field])) {
				update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
			}
		}
	}

	/**
	 * Display the meta box HTML to the user.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function html($post): void
	{
		$service_mobile_name = get_post_meta($post->ID, 'service_mobile_name', true);
		$service_url = get_post_meta($post->ID, 'service_url', true);
		$service_description = get_post_meta($post->ID, 'service_description', true);

		wp_nonce_field('save_service_meta', 'service_meta_nonce');
?>
		<p>
			<label for="service_mobile_name">Short name (on mobile screens)</label><br>
			<input type="text" id="service_mobile_name" name="service_mobile_name" value="<?php echo esc_attr($service_mobile_name); ?>" required size="30" />
		</p>
		<p>
			<label for="service_url">Service local URL</label><br>
			<input type="text" id="service_url" name="service_url" value="<?php echo esc_attr($service_url); ?>" required size="30" />
		</p>
		<p>
			<label for="service_description">Service description</label><br>
			<textarea id="service_description" name="service_description" rows="4" cols="50" required><?php echo esc_textarea($service_description); ?></textarea>
		</p>
<?php
	}
}
