<?php

namespace Evolutio\PostTypes;

use WP_Post;

defined('ABSPATH') || exit();


abstract class MemberPostUtil
{
	const MEMBER_STATUSES = [
		'associate' => 'Associé',
		'collaborator' => 'Collaborateur',
	];

	/** @return  FieldData[] */
	private static function getMetaFields(): array
	{
		return [
			new FieldData("status", FieldType::STATUS, "Statut", "", 0),
			new FieldData("phone", FieldType::PHONE, "Téléphone", "06 00 00 00 00", 0),
			new FieldData("email", FieldType::EMAIL, "Email", "adresse@evolutio-avocats.com", 50),
			new FieldData("linkedin", FieldType::TEXT, "Identifiant LinkedIn", "elodie-durant-1234abcd", 30),
			new FieldData("description", FieldType::TEXTAREA, "Description générale (2 phrases)", "Élodie Durant est avocate après une première carrière en tant que conseillère juridique dans le secteur bancaire et financier. Toujours à l’écoute, elle accompagne ses clients avec rigueur et pragmatisme afin de répondre efficacement à leurs besoins.", 500),
			new FieldData("experience", FieldType::TEXT, "Expérience (1 phrase)", "12 ans d’expérience en entreprise, puis avocate depuis 2016.", 200),
			new FieldData("diplomas", FieldType::TEXTAREA, "Diplômes (1 par ligne)", "- Master 2 en Droit des Affaires – Université de Lyon
- Diplôme de l’Institut de Droit Comparé", 500),
			new FieldData("expertise", FieldType::TEXTAREA, "Domaines d'expertise (1 par ligne)", "- Droit des contrats et droit commercial
- Droit des sociétés et accompagnement des entrepreneurs
- Droit de la consommation
- Droit patrimonial et successions", 500)
		];
	}

	/**
	 * Set up and add the meta box.
	 */
	public static function RegisterPostType(): void
	{
		register_post_type('team_member', array(
			'labels' => array(
				'name' => 'Team Members',
				'singular_name' => 'Team Member',
				'add_new_item' => 'Add a Team Member',
				'edit_item' => 'Edit Team Member',
				'new_item' => 'New Team Member',
				'view_item' => 'View Team Member',
				'search_items' => 'Search Team Members',
				'not_found' => 'No team members found',
				'not_found_in_trash' => 'No team members found in trash',
				'featured_image' => 'Photo',
				'set_featured_image' => 'Set a photo',
				'remove_featured_image' => 'Remove the photo'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_rest' => true,
			'supports' => array('title', 'thumbnail'), // Title is the name, thumbnail for the photo
			'menu_position' => 5,
			'menu_icon' => 'dashicons-businessperson',
			'delete_with_user' => false,
		));
		add_action('add_meta_boxes', [self::class, 'AddMetaBox']);
		add_action('rest_api_init', [self::class, 'AddApiFields']);
		add_action('save_post', [self::class, 'Save']);
		add_action('admin_init', [self::class, '_RegisterCustomColumns']);
	}

	public static function _RegisterCustomColumns(): void
	{
		add_filter('manage_team_member_posts_columns', function ($columns) {
			$columns['status'] = __('Status', 'textdomain');
			return $columns;
		});

		add_filter('manage_edit-team_member_sortable_columns', function ($columns) {
			$columns['status'] = 'team_member_status';
			return $columns;
		});

		add_action('manage_team_member_posts_custom_column', function ($column, $post_id) {
			if ($column === 'status') {
				$status = get_post_meta($post_id, 'team_member_status', true);
				echo esc_html($status ?: __('Not set', 'textdomain'));
			}
		}, 10, 2);
	}

	/**
	 * Add the meta box to the post editor.
	 */
	public static function AddMetaBox(): void
	{
		add_meta_box(
			'team_member_details',
			'Team Member Details',
			[self::class, 'html'],
			'team_member'
		);
	}

	/**
	 * Add custom fields to the REST API.
	 */
	public static function AddApiFields(): void
	{
		foreach (self::getMetaFields() as $field) {
			register_rest_field(
				'team_member',
				$field->getName(),
				array(
					'get_callback' => function ($object) use ($field) {
						return get_post_meta($object['id'], 'team_member_' . $field->getName(), true);
					},
					'update_callback' => null,
					'schema' => array(
						'type' => 'string',
						'arg_options' => array(
							'sanitize_callback' => function ($value) {
								return sanitize_text_field($value);
							},
						),
					),
				)
			);
		}
		// Add featured image source to the REST API
		register_rest_field(
			'team_member',
			'profile_image_src',
			array(
				'get_callback' => function ($object) {
					$feat_img_array = wp_get_attachment_image_src(
						$object['featured_media'], // Image attachment ID
						'medium',  // Size.  Ex. "thumbnail", "medium", "large", "full", etc..
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
	 */
	public static function Save(int $post_id): void
	{
		// Security checks
		if (!isset($_POST['team_member_meta_nonce']) || !wp_verify_nonce($_POST['team_member_meta_nonce'], 'save_team_member_meta')) {
			return;
		}
		// Ensure the user has permission to edit
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		foreach (self::getMetaFields() as $field) {
			$key = 'team_member_' . $field->getName();
			if (isset($_POST[$key])) {
				if ($field->getType() === FieldType::TEXTAREA) {
					$sanitied_value = sanitize_textarea_field($_POST[$key]);
				} else {
					$sanitied_value = sanitize_text_field($_POST[$key]);
				}
				update_post_meta($post_id, $key, trim($sanitied_value));
			}
		}
	}

	/**
	 * Display the meta box HTML to the user.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function html(WP_Post $post): void
	{
		wp_nonce_field('save_team_member_meta', 'team_member_meta_nonce');

		foreach (self::getMetaFields() as $field) {
			$esc_key = 'team_member_' . esc_attr($field->getName());
			$value = get_post_meta($post->ID, $esc_key, true);
			$placeholder = esc_attr($field->getPlaceholder());
			$max_size = $field->getMaxSize();
			$esc_value = esc_attr($value);
			?>
			<p>
			<label for="<?php echo $esc_key; ?>">
				<?php echo esc_html($field->getLabel()); ?>
			</label>
			<br>

			<?php
			switch ($field->getType()) {
				case FieldType::STATUS:
					?>
					<select id="<?php echo $esc_key; ?>" name="<?php echo $esc_key; ?>">
						<?php foreach (self::MEMBER_STATUSES as $status => $satus_label) { ?>
							<option value="<?php echo $status; ?>" <?php selected($value, $status); ?>>
								<?php echo $satus_label; ?>
							</option>
						<?php } ?>
					</select>
					<?php
					break;
				case FieldType::TEXTAREA:
					?>
					<textarea id="<?php echo $esc_key; ?>" name="<?php echo $esc_key; ?>" placeholder="<?php echo $placeholder ?>" rows="5" cols="60"
										maxlength="<?php echo $max_size; ?>" <?php echo $field->isRequired() ? "required" : ""; ?>><?php echo esc_textarea($value); ?></textarea>
					<?php
					break;
				case FieldType::PHONE:
					?>
					<input type="tel" id="<?php echo $esc_key; ?>" name="<?php echo $esc_key; ?>" value="<?php echo $esc_value; ?>" placeholder="<?php echo $placeholder; ?>" size="30"
								 maxlength="20" <?php echo $field->isRequired() ? "required" : ""; ?> />
					<?php
					break;
				case FieldType::EMAIL:
					?>
					<input type="email" id="<?php echo $esc_key; ?>" name="<?php echo $esc_key; ?>" value="<?php echo $esc_value; ?>" placeholder="<?php echo $placeholder; ?>" size="60"
								 maxlength="<?php echo $max_size ?>" <?php echo $field->isRequired() ? "required" : ""; ?> />
					<?php
					break;
				default:
					?>
					<input type="text" id="<?php echo $esc_key; ?>" name="<?php echo $esc_key; ?>" value="<?php echo $esc_value; ?>" placeholder="<?php echo $placeholder; ?>" size="60"
								 maxlength="<?php echo $max_size ?>" <?php echo $field->isRequired() ? "required" : ""; ?> />
				<?php
			}
			echo '</p>';
		}
	}
}
