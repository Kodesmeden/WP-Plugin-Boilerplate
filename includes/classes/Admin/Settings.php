<?php

class BoilerplateSettings {
	
	public $option_prefix = BOILERPLATE_TEXT_DOMAIN . '_';
	public $settings = [];
	
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'pre_render_settings' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_init', [ $this, 'purge_option' ] );
		add_action( 'admin_menu', [ $this, 'add_settings_menus' ] );

		add_action( 'admin_notices', [ $this, 'show_updated_notice' ] );
		add_filter( 'pre_update_option', [ $this, 'skip_password_fields' ], 10, 3 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function pre_render_settings() {
		$this->settings = $this->get_settings();
	}
	
	public function enqueue_scripts() {
		$boilerplate_assets = plugin_dir_url( BOILERPLATE_FILE ) . 'assets';
		$cdnjs_assets = 'https://cdnjs.cloudflare.com/ajax/libs';
		
		wp_enqueue_media();
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'boilerplate', $boilerplate_assets . '/css/admin.min.css', [], BOILERPLATE_VERSION );
		wp_enqueue_style( 'select2', $cdnjs_assets . '/select2/4.0.13/css/select2.min.css', false, BOILERPLATE_VERSION );
		
		wp_register_script( 'boilerplate', $boilerplate_assets . '/js/admin.min.js', [ 'jquery' ], BOILERPLATE_VERSION, true );
		wp_localize_script( 'boilerplate', 'meta_image',
			[
				'title' => __( 'Choose or Upload Media', BOILERPLATE_TEXT_DOMAIN ),
				'button' => __( 'Use this image', BOILERPLATE_TEXT_DOMAIN ),
			]
		);
		
		wp_enqueue_script( 'select2', $cdnjs_assets . '/select2/4.0.13/js/select2.min.js', [ 'jquery' ], BOILERPLATE_VERSION, true );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'boilerplate' );
	}
	
	public function get_settings() {
		// Init array
		$settings = [];
		
		// Custom settings
		$settings['boilerplate-settings'] = [
			'full_title' => 'Custom Settings Title, on the settings page',
			'menu_title' => 'Custom Settings',
			'tab_title' => 'Settings',
			'groups' => [
				[
					'title' => __( 'Standard fields', BOILERPLATE_TEXT_DOMAIN ), // Leave title empty if you don't need it
					// 'description' => [ $this, 'general_description' ], // Custom callback
					'fields' => [
						[
							'id' => 'custom_text', // The $option_prefix will automatically be prepended to the id. So this option will become "boilerplate_custom_text" in the options table.
							'type' => 'text',
							'label' => __( 'Text Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => __( 'Description of your field here. All fields can use descriptions.', BOILERPLATE_TEXT_DOMAIN ),
							'default' => 'Pre-filled text. This is not saved until you save it',
						],
						[
							'id' => 'custom_number',
							'type' => 'number',
							'label' => __( 'Number Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'min' => '0',
							'max' => '',
							'step' => '1',
							'default' => '',
						],
						[
							'id' => 'custom_checkbox',
							'type' => 'checkbox',
							'label' => __( 'Checkbox Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => 'Description is the field label here.',
							'default' => '1', // If not empty, this is checked by default
						],
						[
							'id' => 'disabled_checkbox',
							'type' => 'checkbox',
							'label' => __( 'Disabled Checkbox Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => 'Description is the field label here.',
							'default' => '0', // If not empty, this is checked by default
							'disabled' => true,
						],
						[
							'id' => 'custom_radio',
							'type' => 'radio',
							'label' => __( 'Radio Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'options' => [
								'option1' => 'Option 1',
								'option2' => 'Option 2',
								'option3' => 'Option 3',
							],
							'default' => 'option2',
						],
						[
							'id' => 'custom_select',
							'type' => 'select',
							'label' => __( 'Select Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'options' => [
								'option1' => 'Option 1',
								'option2' => 'Option 2',
								'option3' => 'Option 3',
							],
							'default' => '',
						],
						[
							'id' => 'custom_textarea',
							'type' => 'textarea',
							'label' => __( 'Textarea Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'default' => '',
						],
						[
							'id' => 'custom_password',
							'type' => 'password',
							'label' => __( 'Password Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'default' => '',
							'purge_button' => true,
							'purge_button_text' => __( 'Remove Password', BOILERPLATE_TEXT_DOMAIN ),
							'confirm_dialog' => __( 'Are you sure you want to remove the password?', BOILERPLATE_TEXT_DOMAIN ),
						],
						[
							'id' => 'custom_message',
							'type' => 'message',
							'label' => __( 'Message Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => __( 'Label is optional...', BOILERPLATE_TEXT_DOMAIN ),
						],
					],
				],
			]
		];
		
		// Custom sub settings
		$settings['boilerplate-advanced-settings'] = [
			'full_title' => 'Advanced settings fields',
			'menu_title' => 'Advanced',
			'tab_title' => 'Advanced',
			'groups' => [
				[
					'title' => __( 'Advanced fields', BOILERPLATE_TEXT_DOMAIN ),
					'fields' => [
						[
							'id' => 'custom_image',
							'type' => 'image',
							'label' => __( 'Image Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => __( 'Image uploads through the media library.', BOILERPLATE_TEXT_DOMAIN ),
						],
						[
							'id' => 'custom_checkbox_group',
							'type' => 'checkbox_group',
							'label' => __( 'Multi Checkbox Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => __( 'Default value can be either a single option, or an array of options', BOILERPLATE_TEXT_DOMAIN ),
							'options' => [
								'option1' => 'Option 1',
								'option2' => 'Option 2',
								'option3' => 'Option 3',
								'option4' => 'Option 4',
							],
							'default' => 'option2',
						],
						[
							'id' => 'custom_multi_select',
							'type' => 'select_multi',
							'label' => __( 'Multi Select Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'options' => [
								'option1' => 'Option 1',
								'option2' => 'Option 2',
								'option3' => 'Option 3',
								'option4' => 'Option 4',
							],
							'default' => [ 'option2', 'option4' ],
						],
						[
							'id' => 'custom_color_picker',
							'type' => 'color',
							'label' => __( 'Color Picker Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'default' => '#303030',
						],
						[
							'id' => 'custom_text_group',
							'type' => 'text_group',
							'label' => __( 'Text Group', BOILERPLATE_TEXT_DOMAIN ),
							'description' => '',
							'options' => [
								'field_1' => 'Field 1',
								'field_2' => 'Field 2',
								'field_3' => 'Field 3',
							],
						],
						[
							'id' => 'custom_editor',
							'type' => 'editor',
							'label' => __( 'Editor Field', BOILERPLATE_TEXT_DOMAIN ),
							'description' => __( 'This is the full featured WordPress editor you know.', BOILERPLATE_TEXT_DOMAIN ),
							'default' => '',
						],
					],
				],
			]
		];
		
		return $settings;
	}
	
	public function register_settings() {
		$this->register_settings_fields( $this->settings );
	}
	
	public function add_settings_menus() {
		$counter = 1;
		$main_settings = '';
		
		foreach ( $this->settings as $key => $data ) {
			$full_title = $data['full_title'];
			$menu_title = $data['menu_title'];
			
			if ( $counter === 1 ) {
				$main_settings = $key;
				add_menu_page( $full_title, $menu_title, 'manage_options', $key, [ $this, 'render_boilerplate_settings_page' ], 'dashicons-admin-generic', 90 );
			} else {
				add_submenu_page( $main_settings, $full_title, $menu_title, 'manage_options', $key, [ $this, 'render_boilerplate_settings_page' ] );
			}
			
			++$counter;
		}
		
	}
	
	public function render_boilerplate_settings_page() {
		$current_page = htmlspecialchars( filter_input( INPUT_GET, 'page' ) ?: '' );
		
		if ( ! empty( $this->settings[ $current_page ] ) ) {
			$settings = $this->settings[ $current_page ];
			
			echo '<div class="wrap">
				<h1>' . $settings['full_title'] . '</h1>
				<form method="post" action="options.php" novalidate="novalidate">';

				$this->add_tabs();

				settings_fields( $current_page );
				do_settings_sections( $current_page );
				submit_button();

				echo '</form>
			</div>';
		}
	}
	
	public function add_tabs() {
		echo'<h2 class="nav-tab-wrapper">' . "\n";

		$current_page = htmlspecialchars( filter_input( INPUT_GET, 'page' ) ?: '' );
		foreach ( $this->settings as $key => $section ) {
			$url = admin_url( '/admin.php?page=' . $key );
			$title = $section['tab_title'];
			$active = $current_page == $key ? true : false;

			$class = 'nav-tab';
			if ( ! empty( $active ) ) {
				$class .= ' nav-tab-active';
			}

			echo '<a href="' . esc_attr( $url ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $title ) . '</a>' . "\n";
		}

		echo '</h2>' . "\n";
	}
	
	public function register_settings_fields( $settings ) {
		foreach ( $settings as $location => $data ) {
			foreach ( $data['groups'] as $key => $group ) {
				$group_title = $group['title'];
				$group_id = sanitize_title( $group_title );
				$description_callback = $group['description'] ?? '__return_empty_string';
				$fields = $group['fields'];
				
				add_settings_section( $group_id, $group_title, $description_callback, $location, [ 'before_section' => '<div class="postbox boilerplate"><div class="inside">', 'after_section' => '</div></div>' ] );
				
				foreach ( $fields as $field ) {
					if (! is_array($field)) {
						var_dump($field);
						die();
					}
					if ( empty( $field['id'] ) ) {
						$id = md5( ( ! empty( $field['description'] ) ? $field['description'] : $field['type'] ) . microtime( true ) );
						$field['id'] = $this->option_prefix . $id;
					} else {
						$field['id'] = $this->option_prefix . $field['id'];
					}
					
					register_setting( $location, $field['id'] );
					add_settings_field( $field['id'], ( $field['label'] ?? '' ), [ $this, 'render_settings_field' ], $location, $group_id, $field );
				}
			}
		}
	}
	
	public function render_settings_field( $field ) {
		switch ( $field['type'] ) {
			case 'checkbox':
				echo '<fieldset>';
				
				$default = get_option( $field['id'], $field['default'] );
				$disabled = (bool) ( $field['disabled'] ?? false );
				
				echo '<label for="' . esc_attr( $field['id'] ) . '" class="check-switch"><input name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" type="checkbox" value="1"' . ( ! empty( $default ) ? ' checked' : '' ) . ( ! empty( $disabled ) ? ' disabled' : '' ) . '><span class="slider"></span></label>' . $field['description'];
				
				echo '</fieldset>';
				
				break;
			case 'checkbox_group':
				echo '<fieldset>';
				
				if ( ! empty( $field['options'] ) ) {
					$default = get_option( $field['id'], $field['default'] );
					
					if ( ! is_array( $default ) ) {
						$default = [ $default ];
					}
					
					$i = 0;
					foreach ( $field['options'] as $key => $label ) {
						if ( ++$i > 1 ) {
							echo '<br>';
						}

						echo '<label for="' . esc_attr( $field['id'] ) . '-' . $i . '" class="check-switch"><input name="' . esc_attr( $field['id'] ) . '[' . $key . ']" id="' . esc_attr( $field['id'] ) . '-' . $i . '" type="checkbox" value="1"' . ( in_array( $key, $default ) || ! empty( $default[ $key ] ) ? ' checked' : '' ) . '><span class="slider"></span></label>' . $label;
					}
				}
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				echo '</fieldset>';
				
				break;
			case 'image':
				$image_id = get_option( $field['id'] );
				$image_object = '';
				
				if ( ! empty( $image_id ) ) {
					$image_object = wp_get_attachment_image( $image_id, 'medium' );
				}
				
				echo '<input name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" type="hidden" value="' . esc_attr( $image_id ) . '">
				<style>#' . esc_attr( $field['id'] ) . '-preview .image img{margin-bottom: 10px;}</style>
				<div id="' . esc_attr( $field['id'] ) . '-preview"><div class="image">' . $image_object . '</div></div>
				<input class="upload-image button" type="button" data-id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr__( 'Choose image', BOILERPLATE_TEXT_DOMAIN ) . '">
				<input class="remove-image button" type="button" data-id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr__( 'Remove image', BOILERPLATE_TEXT_DOMAIN ) . '"' . ( empty( $image_id ) ? ' style="display: none;"' : '' ) . '>';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'select':
				echo '<select name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" class="regular-text styled-select">';
				
				if ( ! empty( $field['options'] ) ) {
					$default = get_option( $field['id'], $field['default'] );
					
					foreach ( $field['options'] as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '"' . ( (string) $key === (string) $default ? ' selected' : '' ) . '>' . $value . '</option>';
					}
				}
				
				echo '</select>';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'select_multi':
				echo '<select name="' . esc_attr( $field['id'] ) . '[]" id="' . esc_attr( $field['id'] ) . '" class="regular-text styled-select" multiple>';
				
				if ( ! empty( $field['options'] ) ) {
					$default = get_option( $field['id'], $field['default'] );
					
					if ( ! is_array( $default ) ) {
						$default = [ (string) $default ];
					}
					
					foreach ( $field['options'] as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '"' . ( in_array( $key, $default ) ? ' selected' : '' ) . '>' . esc_html( $value ) . '</option>';
					}
				}
				
				echo '</select>';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'radio':
				echo '<fieldset>';
				
				if ( ! empty( $field['options'] ) ) {
					$default = get_option( $field['id'], $field['default'] );
					
					$i = 0;
					foreach ( $field['options'] as $key => $value ) {
						if ( ++$i > 1 ) {
							echo '<br>';
						}
						
						echo '<label for="' . esc_attr( $field['id'] ) . '-' . $i . '" class="check-switch"><input name="' . esc_attr( $field['id'] ) . '" id="' . $field['id'] . '-' . $i . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $key ) . '"' . ( $key === $default ? ' checked' : '' ) . '><span class="slider"></span></label>' . $value;
					}
				}
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				echo '</fieldset>';
				
				break;
			case 'color':
				$default = get_option( $field['id'], $field['default'] );
				
				echo '<input name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" type="text" value="' . esc_attr( $default ) . '" data-default-color="' . esc_attr( $default ) . '" class="regular-text color-picker">';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'textarea':
				$default = get_option( $field['id'], $field['default'] );
				
				echo '<textarea name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" rows="6" class="regular-text code">' . esc_html( $default ) . '</textarea>';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'number':
				$default = get_option( $field['id'], $field['default'] );
				
				$min = $field['min'] ?? '0';
				$max = $field['max'] ?? null;
				$step = $field['step'] ?? '1';
				
				echo '<input name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $default ) . '" min="' . esc_attr( $min ) . '"' . ( ! empty( $max ) ? ' max="' . esc_attr( $max ) . '"' : '' ) . ' step="' . esc_attr( $step ) . '" class="regular-text">';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'password':
				$default = esc_attr( get_option( $field['id'] ) );
				$placeholder = $field['placeholder'] ?? '';
				$purge_button = (bool) ( $field['purge_button'] ?? true );
				$purge_button_text = $field['purge_button_text'] ?? __( 'Purge', BOILERPLATE_TEXT_DOMAIN );
				$confirm_dialog = $field['confirm_dialog'] ?? __( 'Are you sure?', BOILERPLATE_TEXT_DOMAIN );
				
				$params = '';
				if ( ! empty( $default ) ) {
					$default = str_repeat( '*', mb_strlen( $default ) );
					$params = ' readonly="readonly" style="pointer-events: none"';
				}

				echo '<input name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . $default . '" placeholder="' . esc_attr( $placeholder ) . '" class="regular-text" autocomplete="off"' . $params . '>';
				
				if ( ! empty( $default ) && $purge_button ) {
					echo '<a href="#" class="button button-primary purge" data-confirm="' . $confirm_dialog . '">' . $purge_button_text . '</a>';
				}

				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'editor':
				$default = get_option( $field['id'] );

				wp_editor( $default, esc_attr( $field['id'] ) );
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				break;
			case 'message':
				if ( ! empty( $field['description'] ) ) {
					echo '<div class="description">' . $field['description'] . '</div>';
				}
				
				break;
			case 'text_group':
				echo '<fieldset>';
				
				if ( ! empty( $field['options'] ) ) {
					$saved = get_option( $field['id'], [] );
					
					$i = 0;
					foreach ( $field['options'] as $key => $value ) {
						$default = $saved[ $key ] ?? '';
						
						if ( ++$i > 1 ) {
							echo '<br>';
						}
						
						echo '<label for="' . esc_attr( $field['id'] ) . '-' . $i . '" class="text-group">' . $value . '</label>';
						echo '<input name="' . esc_attr( $field['id'] ) . '[' . $key . ']" id="' . esc_attr( $field['id'] ) . '-' . $i . '" type="text" value="' . esc_attr( $default ) . '" class="regular-text">';
					}
				}
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
				
				echo '</fieldset>';
				
				break;
			default:
				$default = get_option( $field['id'], $field['default'] );
				
				echo '<input name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $default ) . '" class="regular-text">';
				
				if ( ! empty( $field['description'] ) ) {
					echo '<p class="description" id="' . esc_attr( $field['id'] ) . '-description">' . $field['description'] . '</p>';
				}
		}
	}

	public function skip_password_fields( $value, $option, $old_value ) {
		if ( is_string( $value ) ) {
			$value_length = mb_strlen( $value );

			if ( $value === str_repeat( '*', $value_length ) ) {
				$value = $old_value;
			}
		}

		return $value;
	}
	
	public function purge_option() {
		$action = filter_input( INPUT_POST, 'action' );

		if ( $action !== 'purge-option' ) {
			return;
		}

		$option = htmlspecialchars( filter_input( INPUT_POST, 'option' ) );
		$deleted = (bool) delete_option( $option );
		$message = ! $deleted ? __( 'We were unable to remove the value.', BOILERPLATE_TEXT_DOMAIN ) : '';

		wp_send_json( [ 'success' => $deleted, 'message' => $message ] );
	}

	public function show_updated_notice() {
		if ( ! is_admin() ) {
			return;
		}

		$settings_pages = array_keys( $this->settings );
		
		$page = htmlspecialchars( filter_input( INPUT_GET, 'page' ) ?: '' );
		if ( ! in_array( $page, $settings_pages ) ) {
			return;
		}
		
		$updated = filter_input( INPUT_GET, 'settings-updated' );
		if ( empty( $updated ) ) {
			return;
		}
		
		echo '<div class="notice notice-success">
			<p>' . __( 'Settings saved', BOILERPLATE_TEXT_DOMAIN ) . '</p>
		</div>';
	}
	
}
