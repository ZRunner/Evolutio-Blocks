<?php

namespace Evolutio\PostTypes;

defined('ABSPATH') || exit();

abstract class CustomerReviewPostUtil
{
	/**
	 * Set up and add the meta box.
	 */
	public static function RegisterPostType(): void
	{
		register_post_type('review', array(
			'labels' => array(
				'name' => 'Reviews',
				'singular_name' => 'Review',
				'add_new_item' => 'Add a review',
				'edit_item' => 'Edit review',
				'new_item' => 'New review',
				'view_item' => 'View review',
				'search_items' => 'Search reviews',
				'not_found' => 'No review found',
				'not_found_in_trash' => 'No review found in trash'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_rest' => true,
			'supports' => array('title'), // Only allow title (reviewer's name)
			'menu_position' => 5,
			'menu_icon' => 'dashicons-star-half',
			'delete_with_user' => false,
		));
		add_action('add_meta_boxes', [self::class, 'AddMetaBox']);
		add_action('rest_api_init', [self::class, 'AddApiFields']);
		add_action('save_post', [self::class, 'Save']);
		add_action('admin_init', [self::class, '_RegisterCustomColumns']);
	}

	public static function _RegisterCustomColumns(): void
	{
		add_filter('manage_review_posts_columns', function ($columns) {
			$columns['job_title'] = __('Job title', 'textdomain');
			return $columns;
		});

		add_filter('manage_edit-review_sortable_columns', function ($columns) {
			$columns['job_title'] = 'review_job_title';
			return $columns;
		});

		add_action('manage_review_posts_custom_column', function ($column, $post_id) {
			if ($column === 'job_title') {
				$job_title = get_post_meta($post_id, 'review_job_title', true);
				if ($job_title) {
					echo esc_html($job_title);
				} else {
					echo '<em>' . __('None', 'textdomain') . '</em>';
				}
			}
		}, 10, 2);
	}

	/**
	 * Add the meta box to the post editor.
	 */
	public static function AddMetaBox()
	{
		add_meta_box(
			'review_details',       // Unique ID
			'Review Details',       // Box title
			[self::class, 'html'],  // Content callback, must be of type callable
			'review',               // Post type
		);
	}

	/**
	 * Add custom fields to the REST API.
	 */
	public static function AddApiFields()
	{
		$fields = array('review_job_title', 'review_text');
		foreach ($fields as $field) {
			register_rest_field(
				'review',
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
	public static function Save(int $post_id)
	{
		// Security checks
		if (!isset($_POST['review_meta_nonce']) || !wp_verify_nonce($_POST['review_meta_nonce'], 'save_review_meta')) {
			return;
		}
		// Ensure the user has permission to edit
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		if (!isset($_POST)) {
			echo 'No data to save';
			return;
		}
		if (array_key_exists('review_job_title', $_POST)) {
			update_post_meta($post_id, 'review_job_title', sanitize_text_field($_POST['review_job_title']));
		}
		if (array_key_exists('review_text', $_POST)) {
			update_post_meta($post_id, 'review_text', sanitize_textarea_field($_POST['review_text']));
		}
	}

	/**
	 * Display the meta box HTML to the user.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function html($post)
	{
		$job_title = get_post_meta($post->ID, 'review_job_title', true);
		$review_text = get_post_meta($post->ID, 'review_text', true);

		wp_nonce_field('save_review_meta', 'review_meta_nonce');
?>
		<p>
			<label for="review_job_title">Job Title</label><br>
			<input type="text" id="review_job_title" name="review_job_title" value="<?php echo esc_attr($job_title); ?>" size="30" />
		</p>
		<p>
			<label for="review_text">Review Text</label><br>
			<textarea id="review_text" name="review_text" rows="4" cols="50"><?php echo esc_textarea($review_text); ?></textarea>
		</p>
<?php
	}
}
