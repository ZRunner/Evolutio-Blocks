<?php

namespace Evolutio\PostTypes;

defined('ABSPATH') || exit();

abstract class ServicesPostUtil
{
	const PARENT_META_FIELDS = ['service_mobile_name', 'service_description', 'service_url'];

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

	public static function _RegisterChildCustomColumns(): void
	{
		add_filter('manage_sub_service_posts_columns', function ($columns) {
			$columns['parent_services'] = __('Parent Services', 'textdomain');
			return $columns;
		});

		add_filter('manage_edit-sub_service_sortable_columns', function ($columns) {
			$columns['parent_services'] = 'parent_services';
			return $columns;
		});

		add_action('manage_sub_service_posts_custom_column', function ($column, $post_id) {
			if ($column === 'parent_services') {
				$parent_ids = get_post_meta($post_id, 'parent_service_ids', true);
				if (!empty($parent_ids) && is_array($parent_ids)) {
					$parent_titles = array_map('get_the_title', $parent_ids);
					echo esc_html(implode(', ', $parent_titles));
				} else {
					echo '<em>' . __('None', 'textdomain') . '</em>';
				}
			}
		}, 10, 2);

		// Add sorting
		add_action('pre_get_posts', function ($query) {
			if (!is_admin() || !$query->is_main_query()) {
				return;
			}

			if ($query->get('orderby') === 'parent_services') {
				$query->set('meta_key', 'parent_service_ids');
				$query->set('orderby', 'meta_value');
			}
		});
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
		foreach (self::PARENT_META_FIELDS as $field) {
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
			'parent_service_ids',
			array(
				'get_callback' => function ($object) {
					$parent_ids = get_post_meta($object['id'], 'parent_service_ids', true);
					return is_array($parent_ids) ? array_map('intval', $parent_ids) : [];
				},
				'update_callback' => null,
				'schema' => array(
					'type' => 'array',
					'items' => array(
						'type' => 'integer',
					),
					'arg_options' => array(
						'sanitize_callback' => 'wp_parse_id_list',
					),
				),
			)
		);
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
			if (empty($_POST['parent_service_ids'])) {
				wp_die(__('At least one parent service is required for sub-services.', 'textdomain'));
			}
		}

		if (get_post_type($post_id) === 'service') {
			foreach (self::PARENT_META_FIELDS as $field) {
				if (isset($_POST[$field])) {
					update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
				}
			}
		} else if (get_post_type($post_id) === 'sub_service') {
			if (isset($_POST['parent_service_ids'])) {
				$parent_service_ids = array_map('intval', $_POST['parent_service_ids']);
				update_post_meta($post_id, 'parent_service_ids', $parent_service_ids);
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
		$parent_service_ids = get_post_meta($post->ID, 'parent_service_ids', true) ?: [];
		$services = get_posts(array('post_type' => 'service', 'numberposts' => -1));

		wp_nonce_field('save_service_meta', 'service_meta_nonce');
		?>

		<p>
			<label for="parent_service_ids">Select Parent Services:</label><br>
		<div style="display: flex; flex-wrap: wrap; gap: 10px;">
			<?php foreach ($services as $service): ?>
				<label style="display: flex; align-items: center; gap: 5px; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; background: #f9f9f9;">
					<input
						type="checkbox"
						name="parent_service_ids[]"
						value="<?php echo $service->ID; ?>"
						<?php echo in_array($service->ID, (array)$parent_service_ids) ? 'checked' : ''; ?>
					/>
					<?php echo esc_html($service->post_title); ?>
				</label>
			<?php endforeach; ?>
		</div>
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
