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
		self::_RegisterParentPostType();
		self::_RegisterChildPostType();
	}

	public static function _RegisterParentPostType(): void
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
		add_action('add_meta_boxes', [self::class, '_AddParentMetaBox']);
		add_action('rest_api_init', [self::class, '_AddParentApiFields']);
		add_action('save_post', [self::class, '_SavePost'], 10, 2);
	}

	public static function _RegisterChildPostType(): void
	{
		register_post_type('sub_service', array(
			'labels' => array(
				'name' => 'Sub-Services',
				'singular_name' => 'Sub-Service',
				'add_new_item' => 'Add a Sub-Service',
				'edit_item' => 'Edit Sub-Service',
				'new_item' => 'New Sub-Service',
				'view_item' => 'View Sub-Service',
				'search_items' => 'Search Sub-Services',
				'not_found' => 'No Sub-Services found',
				'not_found_in_trash' => 'No Sub-Services found in trash'
			),
			'public'       => true,
			'show_in_rest' => true,
			'supports'     => array('title'), // Rich text description
			'menu_position' => 7,
			'menu_icon'     => 'dashicons-list-view',
		));

		add_action('add_meta_boxes', [self::class, '_AddChildMetaBox']);
		add_action('rest_api_init', [self::class, '_AddChildApiFields']);
		add_action('admin_init', [self::class, '_RegisterChildCustomColumns']);
	}

	public static function _RegisterChildCustomColumns()
	{
		add_filter('manage_sub_service_posts_columns', function ($columns) {
			$columns['parent_service'] = __('Parent Service', 'textdomain');
			return $columns;
		});

		add_filter('manage_edit-sub_service_sortable_columns', function ($columns) {
			$columns['parent_service'] = 'parent_service_id';
			return $columns;
		});

		add_action('manage_sub_service_posts_custom_column', function ($column, $post_id) {
			if ($column === 'parent_service') {
				$parent_id = get_post_meta($post_id, 'parent_service_id', true);
				if ($parent_id) {
					$parent_title = get_the_title($parent_id);
					echo esc_html($parent_title);
				} else {
					echo '<em>' . __('None', 'textdomain') . '</em>';
				}
			}
		}, 10, 2);
	}

	/**
	 * Add the meta box to the post editor.
	 */
	public static function _AddParentMetaBox(): void
	{
		add_meta_box(
			'service_details',       // Unique ID
			'Service Details',       // Box title
			[self::class, '_ParentHtml'],   // Content callback, must be of type callable
			'service',               // Post type
			'normal'			     // Context
		);
	}

	public static function _AddChildMetaBox(): void
	{
		add_meta_box(
			'sub_service_parent',
			'Parent Service',
			[self::class, '_ChildHtml'],
			'sub_service',
			'normal'
		);
	}

	/**
	 * Add custom fields to the REST API.
	 */
	public static function _AddParentApiFields(): void
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

	public static function _AddChildApiFields(): void
	{
		register_rest_field(
			'sub_service',
			'sub_service_description',
			array(
				'get_callback' => function ($object) {
					return get_post_meta($object['id'], 'sub_service_description', true);
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

	/**
	 * Save the meta box selections.
	 *
	 * @param int $post_id The post ID.
	 * @param WP_Post $post The post object.
	 */
	public static function _SavePost(int $post_id, $post): void
	{
		// Security checks
		if (!isset($_POST['service_meta_nonce']) || !wp_verify_nonce($_POST['service_meta_nonce'], 'save_service_meta')) {
			return;
		}
		// Ensure the user has permission to edit
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		// Check if a parent service is selected
		if (get_post_type($post_id) === 'sub_service') {
			if (empty($_POST['parent_service_id'])) {
				wp_die(__('A parent service is required for sub-services.', 'textdomain'));
			}
		}

		if (get_post_type($post_id) === 'service') {
			foreach (self::META_FIELDS as $field) {
				if (isset($_POST[$field])) {
					update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
				}
			}
		} else if (get_post_type($post_id) === 'sub_service') {
			if (isset($_POST['parent_service_id'])) {
				update_post_meta($post_id, 'parent_service_id', intval($_POST['parent_service_id']));
			}
			if (isset($_POST['sub_service_description'])) {
				$allowed_tags = array(
					'p' => array(),
					'span' => array(),
					'br' => array(),
					'strong' => array(),
					'em' => array(),
					'ul' => array(),
					'ol' => array(),
					'li' => array(),
					'blockquote' => array(),
					'a' => array('href' => array(), 'title' => array()),
				);
				update_post_meta($post_id, 'sub_service_description', wp_kses($_POST['sub_service_description'], $allowed_tags));
			}
		}
	}

	/**
	 * Display the meta box HTML to the user.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function _ParentHtml($post): void
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

	public static function _ChildHtml($post): void
	{
		$parent_service_id = get_post_meta($post->ID, 'parent_service_id', true);
		$services = get_posts(array('post_type' => 'service', 'numberposts' => -1));

		wp_nonce_field('save_service_meta', 'service_meta_nonce');
	?>

		<p>
			<label for="parent_service_id">Select Parent Service:</label><br>
			<select id="parent_service_id" name="parent_service_id" required>
				<option value="" disabled <?php echo empty($parent_service_id) ? 'selected' : ''; ?>>Select a Parent</option>
				<?php
				foreach ($services as $service) {
					$selected = ($service->ID == $parent_service_id) ? 'selected' : '';
					echo '<option value="' . $service->ID . '" ' . $selected . '>' . esc_html($service->post_title) . '</option>';
				}
				?>
			</select>
		</p>

		<label for="sub_service_description">Sub-Service description</label><br>
<?php

		$content = get_post_meta($post->ID, 'sub_service_description', true);
		$editor_settings = array(
			'textarea_name' => 'sub_service_description',
			'media_buttons' => false, // Disable "Add Media" button
			'teeny' => true,          // Use a minimal toolbar
			'quicktags' => true,     // Disable HTML view
			'tinymce' => array(
				'toolbar1' => 'bold,italic,underline,bullist,numlist,blockquote,link,unlink,undo,redo,removeformat',
			),
		);

		wp_editor($content, 'sub_service_description', $editor_settings);
	}
}
