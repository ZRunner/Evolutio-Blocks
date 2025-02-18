<?php

namespace Evolutio\PostTypes;

defined('ABSPATH') || exit();

abstract class ServicesPostUtil
{
	const FIELDS = array('service_mobile_name', 'service_description', 'service_url');

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
				'not_found' => 'No service found',
				'not_found_in_trash' => 'No service found in trash'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_rest' => true,
			'supports' => array('title', 'thumbnail'), // Only allow title (reviewer's name)
			'menu_position' => 5,
			'menu_icon' => 'dashicons-admin-tools',
			'delete_with_user' => false,
		));
		add_action('add_meta_boxes', [self::class, 'AddMetaBox']);
		add_action('rest_api_init', [self::class, 'AddApiFields']);
		add_action('save_post', [self::class, 'Save']);
	}

	/**
	 * Add the meta box to the post editor.
	 */
	public static function AddMetaBox(): void
	{
		add_meta_box(
			'service_details',       // Unique ID
			'Service Details',       // Box title
			[self::class, 'html'],  // Content callback, must be of type callable
			'service',               // Post type
		);
	}

	/**
	 * Add custom fields to the REST API.
	 */
	public static function AddApiFields(): void
	{
		foreach (self::FIELDS as $field) {
			register_rest_field(
				'service',
				$field,
				array(
					'get_callback' => function ($object) use ($field) {
						// Get field as single value from post meta.
						return get_post_meta($object['id'], $field, true);
					},
					'update_callback' => null,
					'schema' => array(
						'type' => 'string',
						'arg_options' => array(
							'sanitize_callback' => function ($value) {
								// Make the value safe for storage.
								return sanitize_text_field($value);
							},
						),
					),
				)
			);
		}
	}

	/**
	 * Save the meta box selections.
	 *
	 * @param int $post_id The post ID.
	 */
	public static function Save(int $post_id): void
	{
		if (!isset($_POST)) {
			echo 'No data to save';
			return;
		}
		foreach (self::FIELDS as $field) {
			if (array_key_exists($field, $_POST)) {
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
