<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://passwordprotectwp.com
 * @since      1.0.0
 *
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/admin
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class PPW_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @var PPW_Password_Services
	 * @since 1.2.2
	 */
	private $free_services;


	/**
	 * Subscribe services
	 *
	 * @var PPW_Password_Subscribe
	 */
	private $subscribe_services;

	/**
	 * Asset service in Free version
	 *
	 * @var PPW_Asset_Services
	 */
	private $free_asset_services;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name         = $plugin_name;
		$this->version             = $version;
		$this->free_services       = new PPW_Password_Services();
		$this->subscribe_services  = new PPW_Password_Subscribe();
		$this->free_asset_services = new PPW_Asset_Services( null, null );
	}

	/**
	 * Register the stylesheets and javascript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_assets() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Password_Protect_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Password_Protect_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( function_exists( 'get_current_screen' ) ) {
			$is_pro_activated = apply_filters( PPW_Constants::HOOK_IS_PRO_ACTIVATE, false );
			$screen           = get_current_screen();
			$assert_services  = new PPW_Asset_Services( $screen->id, $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We no need to verify nonce for enqueue assets
			if ( ! $is_pro_activated ) {
				$assert_services->load_assets_for_entire_site_tab();
				$assert_services->load_assets_for_general_tab();
				$assert_services->load_assets_for_entire_site_page();
			}
			$assert_services->load_assets_for_shortcode_page();
			$assert_services->load_assets_for_external_page();
			$assert_services->load_assets_for_external_configuration();
			$assert_services->load_assets_for_shortcodes();
			$assert_services->load_css_hide_feature_set_password_wp();
			$assert_services->load_js_show_notice_deactivate_plugin();
			$assert_services->load_assets_for_misc_tab();
			$assert_services->load_assets_for_category_page();
			$assert_services->load_assets_for_troubleshoot_tab();
			$assert_services->load_assets_for_shortcode_setting();

			wp_enqueue_style( 'ppw-pro-sidebar-css', PPW_DIR_URL . 'admin/css/ppw-pro-sidebar.css', array(), PPW_VERSION, 'all');
		}
	}

	/**
	 * Add metabox to set password in page and post
	 */
	public function ppw_free_add_custom_meta_box_to_edit_page() {
		include PPW_DIR_PATH . 'includes/views/meta-box/view-ppw-meta-box.php';
	}

	/**
	 * Save password
	 */
	public function ppw_free_set_password() {
		$setting_keys = array( 'save_password', 'id_page_post', 'is_role_selected', 'ppwp_multiple_password' );
		if ( ppw_free_error_before_create_password( $_REQUEST, $setting_keys ) ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification in this function
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}
		if ( ! isset( $_REQUEST['settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification above.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		$data_settings        = $_REQUEST['settings']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We no need to verify nonce for enqueue assets, Don't need use wp_unslash(), and no need to sanitize settings params.
		$new_role_password    = $data_settings['save_password'];
		$id                   = $data_settings['id_page_post'];
		$role_selected        = $data_settings['is_role_selected'];
		$new_global_passwords = is_array( $data_settings['ppwp_multiple_password'] ) ? $data_settings['ppwp_multiple_password'] : array();

		$free_services          = new PPW_Password_Services();
		$current_roles_password = $free_services->create_new_password( $id, $role_selected, $new_global_passwords, $new_role_password );
		wp_send_json( $current_roles_password );
		wp_die();
	}

	/**
	 * Check when user enter password
	 */
	public function ppw_handle_enter_password() {
		if ( ! array_key_exists( 'post_password', $_POST ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- This request for default login form postpass action of WordPress with the action 'post_password' for the hook 'login_form_ppw_postpass', will handle on others.
			wp_safe_redirect( wp_get_referer() );
			exit();
		}

		// Get post_id from referer url if Post data is not exist post_id.
		$post_id = ppw_get_post_id_from_request();

		if ( empty( $post_id ) ) {
			wp_safe_redirect( wp_get_referer() );
			exit();
		}

		$password = wp_unslash( $_POST['post_password'] ); // phpcs:ignore -- not sanitize password because we allow all character.

		$this->free_services->handle_after_enter_password_in_password_form( $post_id, $password );
	}

	/**
	 * This feature will support some user which use postpass and enable protection type of plugin.
	 */
	public function ppw_handle_enter_password_for_default_action() {
		if ( ! array_key_exists( 'post_password', $_POST ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- This request for default login form postpass action of WordPress for the hook 'login_form_postpass', will handle on others.
			return;
		}

		// Get post id from request.
		$post_id = ppw_get_post_id_from_request();
		if ( empty( $post_id ) ) {
			return;
		}

		// Get post type to check post type selected in setting page.
		$post_type = get_post_type( $post_id );

		$password = wp_unslash( $_POST['post_password'] ); // phpcs:ignore -- not sanitize password because we allow all character and verify nonce for the hook 'login_form_postpass'.

		if ( ! empty( $post_type ) && ppw_is_post_type_selected_in_setting( $post_type ) ) {
			$this->free_services->handle_after_enter_password_in_password_form( $post_id, $password );
		}
	}

	/**
	 * Handle redirect after enter password
	 *
	 * @param $is_valid
	 *
	 * @return mixed|void
	 */
	public function ppw_handle_redirect_after_enter_password( $is_valid ) {
		$free_service = new PPW_Password_Services();

		$free_service->handle_redirect_after_enter_password( $is_valid );
	}

	/**
	 * Add row action protect/unprotect posts and pages
	 *
	 * @param array    $actions An array of row action.
	 * @param stdClass $post    The post object.
	 *
	 * @return array
	 */
	public function ppw_custom_row_action( $actions, $post ) {
		$post_status = $post->post_status;
		$post_type   = $post->post_type;
		$post_id     = $post->ID;

		if ( ! in_array( $post_type, array( 'page', 'post' ), true ) || 'trash' === $post_status || ! current_user_can( 'edit_post', $post_id ) ) {
			return $actions;
		}

		wp_enqueue_style( 'ppw-row-action-css', PPW_DIR_URL . 'admin/css/ppw-row-action.css', array(), PPW_VERSION, 'all');
		wp_enqueue_script( 'ppw-row-action-js', PPW_DIR_URL . 'admin/js/dist/ppw-row-action.js', array( 'jquery' ), PPW_VERSION, true );
		wp_localize_script(
			'ppw-row-action-js',
			'ppw_row_action_data',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( PPW_Constants::ROW_ACTION_NONCE ),
				'plugin_name' => 'Password Protect WordPress Lite',
			)
		);
		$this->free_asset_services->load_toastr_lib();

		return $this->free_services->generate_custom_row_action( $actions, $post );
	}

	/**
	 * Handle feature update post status in row action.
	 */
	public function handle_update_post_status() {
		$_request = wp_unslash( $_REQUEST );
		if ( ! isset( $_request['nonce'] ) || ! wp_verify_nonce( $_request['nonce'], PPW_Constants::ROW_ACTION_NONCE ) ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}

		return $this->free_services->update_post_status( $_request );
	}

	/**
	 * Add menu
	 */
	public function ppw_add_menu() {
		$setting_page = new PPW_Settings();
		add_menu_page( 'Protect Password Settings', 'Password Protect WordPress', ppw_get_allowed_capability(), PPW_Constants::MENU_NAME, array(
			$setting_page,
			'render_ui'
		), PPW_DIR_URL . 'admin/images/ppw-icon-20x20.png' );
		add_submenu_page( PPW_Constants::MENU_NAME, __( 'PPWP › Settings', PPW_Constants::DOMAIN ), __( 'Settings', PPW_Constants::DOMAIN ), ppw_get_allowed_capability(), PPW_Constants::MENU_NAME );
		$this->partial_protection_submenu();

		// Hide sitewide when Pro activate.
		if ( ! is_pro_active_and_valid_license() ) {
			$this->sitewide_submenu();
		}

		$this->load_external_submenu();
	}

	/**
	 * Add sitewide submenu
	 */
	public function sitewide_submenu() {
		$setting_page = new PPW_Sitewide_Settings();

		add_submenu_page( PPW_Constants::MENU_NAME, __( 'PPWP › Sitewide', PPW_Constants::DOMAIN ), __( 'Sitewide Protection', PPW_Constants::DOMAIN ), ppw_get_allowed_capability(), PPW_Constants::SITEWIDE_PAGE_PREFIX, array(
			$setting_page,
			'render_ui',
		) );
	}


	/**
	 * Add external submenu.
	 */
	public function load_external_submenu() {
		$setting_page = new PPW_External_Settings();

		add_submenu_page(
			PPW_Constants::MENU_NAME,
			__( 'PPWP › Integrations', PPW_Constants::DOMAIN ),
			__( 'Integrations', PPW_Constants::DOMAIN ),
			ppw_get_allowed_capability(),
			PPW_Constants::EXTERNAL_SERVICES_PREFIX,
			array(
				$setting_page,
				'render_ui',
			)
		);
	}

	/**
	 * Add Partial Protection submenu.
	 */
	public function partial_protection_submenu() {
		$setting_page = new PPW_Partial_Protection_Settings();
		add_submenu_page( PPW_Constants::MENU_NAME, __( 'PPWP › Partial Protection', PPW_Constants::DOMAIN ), __( 'Partial Protection', PPW_Constants::DOMAIN ),
			ppw_get_allowed_capability(), PPW_Constants::PCP_PAGE_PREFIX, array(
				$setting_page,
				'render_ui'
			)
		);
	}

	/**
	 * Hide sitewide tab content in Free version.
	 */
	public function ppw_handle_custom_tab( $tabs ) {
		$tab_key = array_search( 'entire_site', $tabs, true );
		if ( false !== $tab_key ) {
			unset( $tabs[ $tab_key ] );
		}

		return $tabs;
	}

	/**
	 * Hide sitewide tab in Free version.
	 */
	public function ppw_handle_add_new_tab( $tabs ) {
		$tab_key = array_search( 'entire_site', array_column( $tabs, 'tab' ), true );
		if ( false !== $tab_key ) {
			unset( $tabs[ $tab_key ] );
		}

		return $tabs;
	}

	/**
	 * Handle hide shortcode tab in Free version.
	 *
	 * @param array $tabs List of tabs in setting page.
	 *
	 * @return array
	 */
	public function ppw_handle_hide_shortcode_tab( $tabs ) {
		foreach ( $tabs as $key => $tab ) {
			if ( array( 'tab' => 'shortcodes', 'tab_name' => 'Shortcodes' ) === $tab ) {
				unset( $tabs[ $key ] );
			}
		}

		return $tabs;
	}

	/**
	 * Handle hide shortcode content in Free version.
	 *
	 * @param array $tabs List of tabs in setting page.
	 *
	 * @return array
	 */
	public function ppw_handle_hide_shortcode_content( $tabs ) {
		$tab_key = array_search( 'shortcodes', $tabs, true );
		if ( false !== $tab_key ) {
			unset( $tabs[ $tab_key ] );
		}

		return $tabs;
	}

	/**
	 * Render General tab
	 */
	public function ppw_free_render_content_general() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/general/view-ppw-general.php';
			include PPW_DIR_PATH . 'includes/views/sidebar/view-ppw-sidebar.php';
			?>
		</div>
		<?php
	}

	/**
	 * Render entire site tab
	 */
	public function ppw_free_render_content_entire_site() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/entire-site/view-ppw-entire-site.php';
			include PPW_DIR_PATH . 'includes/views/sidebar/view-ppw-sidebar.php';
			?>
		</div>
		<?php
	}

	/**
	 * Render shortcodes content.
	 */
	public function ppw_free_render_content_shortcodes() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/shortcode/view-ppw-shortcode-settings.php';
			ppw_free_render_sidebar();
			?>
		</div>
		<?php
	}

	public function ppw_free_render_content_pcp_general_tab() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/partial-protection/view-ppw-pcp-general.php';
			ppw_free_render_sidebar();
			?>
		</div>
		<?php
	}

	public function ppw_free_render_content_external_recaptcha() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/external/view-ppw-general.php';
			ppw_free_render_sidebar();
			?>
		</div>
		<?php
	}

	public function ppw_free_render_content_external_configuration() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/external/view-ppw-general-configuration.php';
			ppw_free_render_sidebar();
			?>
		</div>
		<?php
	}

	/**
	 * Render Master Passwords tab
	 */
	public function ppw_free_render_content_master_passwords() {
		wp_enqueue_script( 'ppw-master-passwords-js', PPW_DIR_URL . 'includes/views/master-passwords/assets/ppw-master-passwords.js', array( 'jquery' ), PPW_VERSION, true );
		wp_enqueue_style( 'ppw-master-passwords-css', PPW_DIR_URL . 'includes/views/master-passwords/assets/ppw-master-passwords.css', array(), PPW_VERSION, 'all' );
		$post_types_selected     = $this->free_services->get_protection_post_types_select();
		$protection_types        = apply_filters( 'ppw_master_password_protection_types', array() );
		$allowed_protection_type = ppw_allowed_master_protection_type();
		wp_localize_script(
			'ppw-master-passwords-js',
			'ppwMasterPasswords',
			array(
				'restUrl'               => get_rest_url(),
				'nonce'                 => wp_create_nonce( 'wp_rest' ),
				'roles'                 => array_keys( get_editable_roles() ),
				'postTypes'             => $post_types_selected,
				'pro'                   => is_pro_active_and_valid_license(),
				'protectionTypes'       => $protection_types,
				'allowedProtectionType' => $allowed_protection_type,
			)
		);
		include PPW_DIR_PATH . 'includes/views/master-passwords/view-ppw-master-passwords.php';
	}

	/**
	 * Render Advanced tab
	 */
	public function ppw_free_render_content_misc() {
		$misc_options = get_option( PPW_Constants::MISC_OPTIONS , false );
		if ( !$misc_options ) {
			update_option( PPW_Constants::MISC_OPTIONS, wp_json_encode(array(PPW_Constants::USE_CUSTOM_FORM_ACTION => true)));
		} else if ( !ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_CUSTOM_FORM_ACTION, PPW_Constants::MISC_OPTIONS ) ) {
			$data = json_decode($misc_options);
			$data->wpp_use_custom_form_action = true;
			update_option( PPW_Constants::MISC_OPTIONS,  wp_json_encode( $data ) );
		}
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/misc/view-ppw-misc.php';
			ppw_free_render_sidebar();
			?>
		</div>
		<?php
	}

	/**
	 * Render Advanced tab
	 */
	public function ppw_free_render_content_troubleshooting() {
		?>
		<div class="ppw_setting_page">
			<?php
			include PPW_DIR_PATH . 'includes/views/troubleshooting/view-ppw-troubleshooting.php';
			ppw_free_render_sidebar();
			?>
		</div>
		<?php
	}

	/**
	 * Update settings
	 */
	public function ppw_free_update_general_settings() {
		$setting_keys = array(
			PPW_Constants::COOKIE_EXPIRED,
			PPW_Constants::REMOVE_DATA,
		);
		if ( ppw_free_is_setting_data_invalid( $_REQUEST, $setting_keys ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification in this function.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}

		if ( ! isset( $_REQUEST['settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification above.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}

		$data_settings = wp_unslash( $_REQUEST['settings'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We handle nonce verification above and no need to sanitize settings.
		update_option( PPW_Constants::GENERAL_OPTIONS, wp_json_encode( $data_settings ), 'no' );
		wp_die( true );
	}

	/**
	 * Update settings
	 */
	public function ppw_free_update_external_settings() {
		if ( ! isset( $_REQUEST['settings'] ) || ! is_array( $_REQUEST['settings'] ) || ppw_free_is_setting_data_invalid( $_REQUEST, array(), false ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification in ppw_free_is_setting_data_invalid() function.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		$option = get_option( PPW_Constants::EXTERNAL_OPTIONS );
		if ( empty( $option ) ) {
			$option = array();
		} else {
			$option = (array) json_decode( $option );
		}

		$setting_keys = array(
			PPW_Constants::RECAPTCHA_SCORE,
			PPW_Constants::RECAPTCHA_API_KEY,
			PPW_Constants::RECAPTCHA_V2_CHECKBOX_API_KEY,
			PPW_Constants::RECAPTCHA_API_SECRET,
			PPW_Constants::RECAPTCHA_V2_CHECKBOX_API_SECRET,
			PPW_Constants::USING_RECAPTCHA,
			PPW_Constants::RECAPTCHA_TYPE,
			PPW_Constants::RECAPTCHA_PASSWORD_TYPES,
		);
		$settings     = wp_unslash( $_REQUEST['settings'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We handle nonce verification above and no need to sanitize settings params.
		foreach ( $settings as $key => $value ) {
			if ( in_array( $key, $setting_keys, true ) ) {
				$option[ $key ] = $settings[ $key ];
			}
		}

		update_option(
			PPW_Constants::EXTERNAL_OPTIONS,
			wp_json_encode( $option ),
			'no'
		);
		wp_die( true );
	}

	/**
	 * Update settings
	 */
	public function ppw_free_update_misc_settings() {
		$setting_keys = apply_filters(
			PPW_Constants::HOOK_ADVANCED_VALID_INPUT_DATA,
			array(
				PPW_Constants::PROTECT_EXCERPT,
				PPW_Constants::USE_CUSTOM_FORM_ACTION,
				PPW_Constants::NO_RELOAD_PAGE,
			)
		);
		if ( ppw_free_is_setting_data_invalid( $_REQUEST, $setting_keys, false ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- We handle nonce verification in this function.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		if ( ! isset( $_REQUEST['settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification above.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}
		$data_settings = wp_unslash( $_REQUEST['settings'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We handle nonce verification above and no need to sanitize settings params.

		update_option( PPW_Constants::MISC_OPTIONS, wp_json_encode( $data_settings ), 'no' );

		wp_die( true );
	}


	/**
	 * Update shortcode settings.
	 */
	public function ppw_free_update_shortcode_settings() {
		$setting_keys = apply_filters(
			'ppw_shortcode_valid_input_data',
			array(
				PPW_Constants::USE_SHORTCODE_PAGE_BUILDER,
			)
		);
		if ( ppw_free_is_setting_data_invalid( $_REQUEST, $setting_keys, false ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification in ppw_free_is_setting_data_invalid() function.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		if ( ! isset( $_REQUEST['settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We handle nonce verification above.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		$data_settings = wp_unslash( $_REQUEST['settings'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We handle nonce verification above and no need to sanitize settings params.

		update_option( PPW_Constants::SHORTCODE_OPTIONS, wp_json_encode( $data_settings ), 'no' );

		wp_die( true );
	}

	/**
	 * Update category settings.
	 */
	public function ppw_free_update_category_settings() {
		$nonce_verification = check_ajax_referer( PPW_Constants::GENERAL_FORM_NONCE, 'security_check' );
		if ( ! $nonce_verification ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}
		if ( isset( $_REQUEST['settings'], $_REQUEST['settings']['ppwp_is_protect_category'] ) && 'false' === $_REQUEST['settings']['ppwp_is_protect_category'] ) {
			$data = get_option( PPW_Category_Service::OPTION_NAME, false );
			if ( $data ) {
				$data                           = json_decode( $data );
				$data->ppwp_is_protect_category = false;
				update_option( PPW_Category_Service::OPTION_NAME, wp_json_encode( $data ) );
			} else {
				update_option( PPW_Category_Service::OPTION_NAME, wp_json_encode( array( 'ppwp_is_protect_category' => false ) ) );
			}

			return wp_die( true );
		}

		$setting_keys = apply_filters(
			'ppw_category_keys',
			array(
				'ppwp_is_protect_category',
				'ppwp_categories_password',
				'ppwp_protected_categories_selected',
			)
		);

		$data_settings = apply_filters( 'ppw_category_data_settings', wp_unslash( $_REQUEST['settings'] ), $setting_keys ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We no need to sanitize settings params.
		if ( ppw_free_is_setting_data_invalid( $_REQUEST, $setting_keys, false ) ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		do_action( 'ppw_before_update_category_settings', $setting_keys, $data_settings );

		$passwords = PPW_Repository_Passwords::get_instance()->get_all_shared_categories_password();

		// Add new shared password if password is not exist
		// Update password if it is exist.
		if ( count( $passwords ) > 0 ) {
			$password_id = $passwords[0]->id;
			PPW_Repository_Passwords::get_instance()->update_password(
				$password_id,
				array(
					'password' => $data_settings['ppwp_categories_password'],
				)
			);
		} else {
			PPW_Repository_Passwords::get_instance()->add_new_password(
				array(
					'password'          => $data_settings['ppwp_categories_password'],
					'post_id'           => 0,
					'contact_id'        => 0,
					'campaign_app_type' => PPW_Category_Service::SHARED_CATEGORY_TYPE,
					'hits_count'        => 0,
					'created_time'      => time(),
				)
			);
		}
		unset( $data_settings['ppwp_categories_password'] );

		update_option( PPW_Category_Service::OPTION_NAME, wp_json_encode( $data_settings ), 'no' );

		wp_die( true );
	}

	/**
	 * Update Tag settings.
	 */
	public function ppw_free_update_tag_settings() {
	
		$nonce_verification = check_ajax_referer( PPW_Constants::GENERAL_FORM_NONCE, 'security_check' );

		if ( ! $nonce_verification ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}
		if ( isset( $_REQUEST['settings'], $_REQUEST['settings']['ppwp_is_protect_tag'] ) && 'false' === $_REQUEST['settings']['ppwp_is_protect_tag'] ) {
			$data = get_option( PPW_Tag_Service::OPTION_NAME, false );
			if ( $data ) {
				$data                           = json_decode( $data );
				$data->ppwp_is_protect_tag = false;
				update_option( PPW_Tag_Service::OPTION_NAME, wp_json_encode( $data ) );
			} else {
				update_option( PPW_Tag_Service::OPTION_NAME, wp_json_encode( array( 'ppwp_is_protect_tag' => false ) ) );
			}

			return wp_die( true );
		}

		$setting_keys = apply_filters(
			'ppw_tag_keys',
			array(
				'ppwp_is_protect_tag',
				'ppwp_tags_password',
				'ppwp_protected_tags_selected',
			)
		);

		$data_settings = apply_filters( 'ppw_tag_data_settings', wp_unslash( $_REQUEST['settings'] ), $setting_keys ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We no need to sanitize settings params.
		if ( ppw_free_is_setting_data_invalid( $_REQUEST, $setting_keys, false ) ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);

			wp_die();
		}

		do_action( 'ppw_before_update_tag_settings', $setting_keys, $data_settings );

		$passwords = PPW_Repository_Passwords::get_instance()->get_all_shared_tags_password();

		// Add new shared password if password is not exist
		// Update password if it is exist.
		if ( count( $passwords ) > 0 ) {
			$password_id = $passwords[0]->id;
			PPW_Repository_Passwords::get_instance()->update_password(
				$password_id,
				array(
					'password' => $data_settings['ppwp_tags_password'],
				)
			);
		} else {
			PPW_Repository_Passwords::get_instance()->add_new_password(
				array(
					'password'          => $data_settings['ppwp_tags_password'],
					'post_id'           => 0,
					'contact_id'        => 0,
					'campaign_app_type' => PPW_Tag_Service::SHARED_TAG_TYPE,
					'hits_count'        => 0,
					'created_time'      => time(),
				)
			);
		}
		unset( $data_settings['ppwp_tags_password'] );

		update_option( PPW_Tag_Service::OPTION_NAME, wp_json_encode( $data_settings ), 'no' );

		wp_die( true );
	}

	public function ppw_free_update_entire_site_settings() {
		$request = wp_unslash( $_REQUEST );
		if ( ppw_free_is_entire_site_settings_data_invalid( $request ) ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}

		$nonce = $request['security_check'];
		if ( ! wp_verify_nonce( $nonce, PPW_Constants::ENTIRE_SITE_FORM_NONCE ) ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}
		$data_settings        = $request['settings'];
		$entire_site_services = new PPW_Entire_Site_Services();
		$entire_site_services->handle_before_update_settings( $data_settings );
		wp_die( true );
	}

	/**
	 * Feature entire site
	 */
	public function ppw_render_form_entire_site() {
		if ( ppw_free_has_bypass_sitewide_protection() ) {
			return;
		}

		$is_protect = ppw_core_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
		if ( ! $is_protect ) {
			return;
		}

		$is_render_form = apply_filters( PPW_Constants::HOOK_BEFORE_RENDER_FORM_ENTIRE_SITE, true );
		if ( ! $is_render_form ) {
			return;
		}

		$entire_site_service = new PPW_Entire_Site_Services();
		if ( $entire_site_service->validate_auth_cookie_entire_site() ) {
			return;
		}

		$password = ppw_core_get_setting_entire_site_type_string( PPW_Constants::PASSWORD_ENTIRE_SITE );
		if ( empty( $password ) ) {
			return;
		}

		do_action( 'ppw_sitewide_before_validate_password', $password );

		$password_is_valid = $entire_site_service->entire_site_is_valid_password( $password );
		if ( $password_is_valid ) {
			$entire_site_service->entire_site_set_password_to_cookie( $password );
//			$free_cache = new PPW_Cache_Services();
//			$free_cache->clear_cache_super_cache();
			$entire_site_service->entire_site_redirect_after_enter_password();
			die();
		}

		include PPW_DIR_PATH . 'includes/views/entire-site/view-ppw-form-password.php';
		die();
	}

	/**
	 * Handle protected short code content.
	 *
	 * @return string
	 */
	public function handle_content_protect_short_code() {
		$content = <<<_end_
		<div>
			This feature only runs on free
		</div>
_end_;

		return $content;
	}

	/**
	 * Handle admin init
	 */
	public function handle_admin_init() {
		if ( is_pro_active_and_valid_license() || PPW_Options_Services::get_instance()->get_flag( PPW_Constants::MIGRATED_DEFAULT_PW ) ) {
			return;
		}
		global $migration_free_service;
		$migration_free_service->start_run();
	}

	/**
	 * Handle rest API
	 */
	public function rest_api_init() {
		$api = new PPW_Api();
		$api->register_rest_routes();
	}

	public function ppwp_sitewide_authentication_errors($result) {

		$request_uri = !empty( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
		
		$validate = false;
		if (strpos($request_uri, '/wp/v2/pages') !== false || strpos($request_uri, '/wp/v2/posts') !== false) {
	        $validate = true;
	    }
		
		// Apply a filter to allow bypassing the sitewide authentication check
    	$validate = apply_filters('ppwp_bypass_sitewide_authentication_check', $validate, $request_uri);

		$entire_site_service = new PPW_Entire_Site_Services();
		$is_protect = ppw_core_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
		if( class_exists( 'PPWP_Pro_SideWide' ) ){
			$PPWP_Pro_SideWide = new PPWP_Pro_SideWide();
			$is_should_render = $PPWP_Pro_SideWide->should_render_sc_content();	
			if( $validate && !$is_should_render && !is_user_logged_in() && $is_protect  ){
				return new WP_Error('Protected', 'Error: Access denied. This API is protected with sitewide protection.', array('status' => 401));
			}
		}else{
			if( $validate && !is_user_logged_in() && $is_protect && !$entire_site_service->validate_auth_cookie_entire_site() ){
				return new WP_Error('Protected', 'Error: Access denied. This API is protected with sitewide protection.', array('status' => 401));
			}		
		}

	    return $result;
	}

   
	/**
	 * Set post pass cookie to prevent cache.
	 *
	 * @param object $post The post data.
	 * @param string $pass The password.
	 */
	public function set_postpass_cookie_to_prevent_cache( $post, $pass ) {
		$free_service = new PPW_Password_Services();
		$free_service->set_password_to_cookie( $pass . $post->ID, PPW_Constants::WP_POST_PASS );
	}

	/**
	 * Handle a post requires the user to supply a password.
	 *
	 * @param bool    $required Whether the user needs to supply a password. True if password has not been
	 *                          provided or is incorrect, false if password has been supplied or is not required.
	 * @param WP_Post $post     Post data.
	 *
	 * @return bool  A post requires the user to supply a password.
	 */
	public function ppw_handle_post_password_required( $required, $post ) {
		if ( empty( $post->ID ) ) {
			return $required;
		}

		if ( empty( $post->post_type ) || ! ppw_is_post_type_selected_in_setting( $post->post_type ) ) {
			return $required;
		}

		if ( ppw_free_has_bypass_single_protection() ) {
			return $required;
		}

		return $this->free_services->is_valid_permission( $required, $post->ID );
	}

	/**
	 * Handle content shortcode for multiple pages.
	 *
	 * @param string $content The post content.
	 * @param object $post    The post data.
	 * @param array  $data    Data from client.
	 *
	 * @return bool|string
	 */
	public function handle_content_shortcode_for_multiple_pages( $content, $post, $data ) {
		if ( ! empty( $data['formType'] ) ) {
			return $content;
		}

		return PPW_Shortcode::get_instance()->get_content_by_page_number( $post, $data['page'] );
	}

	/**
	 * Create passwords table when PPWP Pro is not activated.
	 */
	public function handle_admin_init_when_pro_is_not_activate() {
		PPW_Repository_Passwords::get_instance()->install();
	}

	/**
	 * Handle post password required from Pro version.
	 *
	 * @param array  $protection_data Protection data.
	 * @param string $post_id         Post ID.
	 *
	 * @return array Protection data after checked.
	 */
	public function ppwp_post_password_required( $protection_data, $post_id ) {
		if ( ! isset( $protection_data['is_post_protected'] ) || ! isset( $protection_data['is_content_unlocked'] ) ) {
			return $protection_data;
		}

		if ( true !== $protection_data['is_post_protected'] ) {
			return $protection_data;
		}

		if ( false === $protection_data['is_content_unlocked'] ) {
			$protection_data['is_content_unlocked'] = $this->free_services->check_master_password_is_valid( $post_id );
		}

		return $protection_data;
	}

	/**
	 * Update label and post types column for PPWP Pro.
	 */
	public function update_column_for_ppwp_pro() {
		PPW_Repository_Passwords::get_instance()->update_label_and_post_types_column();
	}

	/**
	 * Handle subscriber request
	 */
	public function handle_subscribe_request() {
		if ( ppw_free_is_setting_keys_and_nonce_invalid( $_REQUEST, PPW_Constants::SUBSCRIBE_FORM_NONCE ) || ! isset( $_REQUEST['settings']['ppw_email'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We already verify nonce in this function.
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
			wp_die();
		}
		$request = wp_unslash( $_REQUEST ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We already verify nonce in above.
		$result  = $this->subscribe_services->handle_subscribe_request( $request['settings']['ppw_email'] );
		wp_send_json(
			array(
				'is_error' => isset( $result['error_message'] ) ? true : false,
				'message'  => isset( $result['error_message'] ) ? $result['error_message'] : '',
			),
			isset( $result['error_message'] ) ? 400 : 200
		);
		wp_die();
	}

	/*
	 * Handle plugin loaded
	 */
	public function handle_plugin_loaded() {
		if ( ! defined( 'PPW_PRO_VERSION' ) ) {
			return;
		}
		if ( version_compare( PPW_PRO_VERSION, '1.1.5.1', '>=' ) ) {
			add_action( 'ppwp_post_password_required', array( $this, 'ppwp_post_password_required' ), 5, 2 );
		}
	}

	/**
	 * Handle admin notices.
	 */
	public function handle_admin_notices() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$screen_display = array(
			'post',
			'edit-post',
			'toplevel_page_wp_protect_password_options',
			'plugins',
		);
		if ( ! in_array( get_current_screen()->id, $screen_display, true ) ) {
			return;
		}
		$class   = 'notice notice-warning';
		$message = 'Please update Password Protect WordPress Pro to version 1.1.5.1 in order for Master Passwords to work properly.';
		if ( defined( 'PPW_PRO_VERSION' ) && version_compare( PPW_PRO_VERSION, '1.1.5.1', '<' ) ) {
			printf( '<div class="%1$s"><p><b>Password Protect WordPress: </b>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
		}
	}

	public function add_custom_column( $columns ) {
		global $post_status;

		if ( 'trash' === $post_status ) {
			return $columns;
		}

		$columns[ PPW_Constants::CUSTOM_TABLE_COLUMN_NAME ] = PPW_Constants::CUSTOM_TABLE_COLUMN_TITLE;

		return $columns;
	}

	public function render_content_custom_column ( $column, $post_id ) {
		if ( PPW_Constants::CUSTOM_TABLE_COLUMN_NAME === $column ) {
			include PPW_DIR_PATH . 'includes/views/column/view-ppw-column.php';
		}
	}

	/**
	 * Restore WP Passwords.
	 */
	public function ppw_free_restore_wp_passwords() {
		if ( ! isset( $_POST['security_check'] ) ) {
			wp_send_json(
				array(
					'is_error' => true,
					'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
				),
				400
			);
		}
		check_ajax_referer( PPW_Constants::GENERAL_FORM_NONCE, 'security_check' );

		global $password_recovery_service;
		$password_recovery_service->start_run();

		wp_send_json(
			array(
				'is_error' => false,
				'message'  => 'Start restoring backup passwords successfully.',
			),
			200
		);
	}

	/**
	 * Filters the array of row meta for each plugin in the Plugins list table.
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 *
	 * @return array
	 */
	public function register_plugins_links( $plugin_meta, $plugin_file ) {
		if ( PPW_PLUGIN_BASE_NAME === $plugin_file ) {
			$misc_setting = admin_url( 'admin.php?page=wp_protect_password_options&tab=misc' );
			$plugin_meta[] = '<a href="' . $misc_setting . '">' . __( 'Restore passwords', PPW_Constants::DOMAIN ) . '</span>';
		}

		return $plugin_meta;
	}


	/**
	 * Render custom description below password form
	 *
	 * @param $password_form
	 *
	 * @return string
	 */
	public function render_custom_below_description( $password_form ) {
		$below_desc = wp_kses_post( get_theme_mod( 'ppwp_form_instructions_below_text' ) );

		$password_form .= sprintf('<div class="ppw-ppf-desc-below">%s</div>', $below_desc);

		return $password_form;
	}

	public function handle_plugin_links( $links ) {
		$setting_url = esc_url( admin_url( 'admin.php?page=' . PPW_Constants::MENU_NAME ) );
		$plugin_link = '<a href="' . $setting_url . '">' . __( 'Settings', PPW_Constants::DOMAIN ) . '</a>';
		array_unshift( $links, $plugin_link );

		return $links;
	}

	public function ppw_sitewide_countdown() {
		$is_show_countdown   = get_theme_mod( 'ppwp_sitewide_is_shown_countdown', '' );
		if ( $is_show_countdown || $is_show_countdown === '' ) {
			include PPW_DIR_PATH . 'includes/views/entire-site/view-ppw-countdown.php';
		}
	}
	
	public function ppw_sitewide_hide_password_form () {
		$enable_form   		 = get_theme_mod( 'ppwp_hide_sitewide_password_form' );
		if ( $enable_form ) {
			?>
				.ppw-swp-form {
					display: none !important;
				}
				.pda-form-login form {
					display: none !important;
				}
			<?php
		}
	}

	public function ppw_customizer_custom_fields ($wp_customize) {
		if ( ! class_exists( 'PPW_Datetime_Control' ) ) {
			include PPW_DIR_PATH . 'includes/customizers/class-ppw-datetime.php';
		}

		$wp_customize->register_control_type( 'PPW_Datetime_Control' );

		/* hide password form */
		$wp_customize->add_setting( 'ppwp_hide_sitewide_password_form' );
		$wp_customize->add_control(
			new PPW_Toggle_Control(
				$wp_customize,
				'ppwp_hide_sitewide_password_form_control', array(
				'label'       => __( 'Disable Password Form', PPW_Constants::DOMAIN ),
				'section'     => 'ppwp_pro_form_instructions',
				'type'        => 'toggle',
				'settings'    => 'ppwp_hide_sitewide_password_form',
			) )
		);

		/* countdown section group */
		$wp_customize->add_setting( 'ppwp_sitewide_countdown' );
		$wp_customize->add_control(
			new PPW_Title_Group_Control(
				$wp_customize,
				'ppwp_sitewide_countdown', array(
				'label'			=> __( 'COUNTDOWN TIMER', PPW_Constants::DOMAIN ),
				'section'  		=> 'ppwp_sitewide_countdown',
				'settings' 		=> 'ppwp_sitewide_countdown',
				'type'     		=> 'control_title',
			) )
		);

		$wp_customize->add_section( 'ppwp_sitewide_countdown', array(
			'title'    => __( 'Countdown Timer', PPW_Constants::DOMAIN ),
			'panel'    => 'ppwp_sitewide',
			'priority' => 500,
		) );

		$wp_customize->add_setting( 'ppwp_sitewide_is_shown_countdown', array(
			'default' => 0,
		) );

		$wp_customize->add_control(
			new PPW_Toggle_Control(
				$wp_customize,
				'ppwp_sitewide_is_shown_countdown', array(
				'label'       => __( 'Enable Countdown Timer', PPW_Constants::DOMAIN ),
				'section'     => 'ppwp_sitewide_countdown',
				'type'        => 'toggle',
				'description'  => __( 'Time zone: '.ppw_get_utc(), PPW_Constants::DOMAIN ),
				'settings'    => 'ppwp_sitewide_is_shown_countdown',
			))
		);

		// $wp_customize->add_setting( 'ppwp_sitewide_is_show_day', array(
		// 	'default' => 0,
		// ) );

		// $wp_customize->add_control(
		// 	new PPW_Toggle_Control(
		// 		$wp_customize,
		// 		'ppwp_sitewide_is_show_day', array(
		// 		'label'       => __( 'Show Day in Countdown', PPW_Constants::DOMAIN ),
		// 		'section'     => 'ppwp_sitewide_countdown',
		// 		'type'        => 'toggle',
		// 		'settings'    => 'ppwp_sitewide_is_show_day',
		// 	))
		// );

		$date = current_time( 'timestamp' );
		$wp_customize->add_setting( 'ppwp_sitewide_start_time', array(
			'default' => '',
			'min'     => date('Y-m-d\TH:i', $date),
		) );

		$wp_customize->add_control(
			new PPW_Datetime_Control(
				$wp_customize,
				'ppwp_sitewide_start_time', array(
				'label'       => __( 'Start Time (Optional)', PPW_Constants::DOMAIN ),
				'section'     => 'ppwp_sitewide_countdown',
				'type'        => 'datetime',
				'settings'    => 'ppwp_sitewide_start_time',
			))
		);

		$start_date 		= get_theme_mod( 'ppwp_sitewide_start_time', '' ) ? get_theme_mod( 'ppwp_sitewide_start_time' ) : date('Y-m-d\TH:i', $date);

		$wp_customize->add_setting( 'ppwp_sitewide_end_time', array(
			'default' => $start_date,
			'min'     => $start_date,
		) );

		$wp_customize->add_control(
			new PPW_Datetime_Control(
				$wp_customize,
				'ppwp_sitewide_end_time', array(
				'label'       => __( 'End Time', PPW_Constants::DOMAIN ),
				'section'     => 'ppwp_sitewide_countdown',
				'type'        => 'datetime',
				'settings'    => 'ppwp_sitewide_end_time',
			))
		);

		/* time unit section group */
		$wp_customize->add_setting( 'ppwp_countdown_time_unit' );
		$wp_customize->add_control(
			new PPW_Title_Group_Control(
				$wp_customize,
				'ppwp_countdown_time_unit', array(
				'label'			=> __( 'COUNTDOWN TIMER STYLES', PPW_Constants::DOMAIN ),
				'section'  		=> 'ppwp_sitewide_countdown',
				'settings' 		=> 'ppwp_countdown_time_unit',
				'type'     		=> 'control_title',
			) )
		);

		$wp_customize->add_setting( 'ppwp_countdown_day_text', array(
			'default' => __( 'Days', PPW_Constants::DOMAIN ),
		) );
		$wp_customize->add_control( 'ppwp_countdown_day_text', array(
			'label'    => __( 'Days Label', PPW_Constants::DOMAIN ),
			'section'  => 'ppwp_sitewide_countdown',
			'settings' => 'ppwp_countdown_day_text',
			'type'     => 'text',
		) );

		$wp_customize->add_setting( 'ppwp_countdown_hour_text', array(
			'default' => __( 'Hours ', PPW_Constants::DOMAIN ),
		) );
		$wp_customize->add_control( 'ppwp_countdown_hour_text', array(
			'label'    => __( 'Hours Label', PPW_Constants::DOMAIN ),
			'section'  => 'ppwp_sitewide_countdown',
			'settings' => 'ppwp_countdown_hour_text',
			'type'     => 'text',
		) );

		$wp_customize->add_setting( 'ppwp_countdown_minute_text', array(
			'default' => __( 'Minutes', PPW_Constants::DOMAIN ),
		) );
		$wp_customize->add_control( 'ppwp_countdown_minute_text', array(
			'label'    => __( 'Minutes Label', PPW_Constants::DOMAIN ),
			'section'  => 'ppwp_sitewide_countdown',
			'settings' => 'ppwp_countdown_minute_text',
			'type'     => 'text',
		) );

		$wp_customize->add_setting( 'ppwp_countdown_second_text', array(
			'default' => __( 'Seconds', PPW_Constants::DOMAIN ),
		) );
		$wp_customize->add_control( 'ppwp_countdown_second_text', array(
			'label'    => __( 'Seconds Label', PPW_Constants::DOMAIN ),
			'section'  => 'ppwp_sitewide_countdown',
			'settings' => 'ppwp_countdown_second_text',
			'type'     => 'text',
		) );

		/* coutdown font size */
		$wp_customize->add_setting( 'ppwp_countdown_font_size' );
		$wp_customize->add_control( 'ppwp_countdown_font_size_control', array(
			'label'			=> __( 'Font Size', PPW_Constants::DOMAIN ),
			'section'  		=> 'ppwp_sitewide_countdown',
			'settings' 		=> 'ppwp_countdown_font_size',
			'description'	=> 'Font size in px',
			'type'     		=> 'number',
		) );

		/* password form background color */
		$wp_customize->add_setting( 'ppwp_countdown_text_color', array(
			'default' => '',
		) );

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ppwp_countdown_text_color_control', array(
				'label'    => __( 'Text Color', PPW_Constants::DOMAIN ),
				'section'  => 'ppwp_sitewide_countdown',
				'settings' => 'ppwp_countdown_text_color',
			) )
		);

		/* descript section group */
		$wp_customize->add_setting( 'ppwp_sitewide_above_countdown_text' );
		$wp_customize->add_control(
			new PPW_Title_Group_Control(
				$wp_customize,
				'ppwp_sitewide_above_countdown_text', array(
				'label'			=> __( 'DESCRIPTION ABOVE TIMER', PPW_Constants::DOMAIN ),
				'section'  		=> 'ppwp_sitewide_countdown',
				'settings' 		=> 'ppwp_sitewide_above_countdown_text',
				'type'     		=> 'control_title',
			) )
		);

		/* Text above sitewide */
		$wp_customize->add_setting( 'ppwp_sitewide_above_countdown', array(
			'default' => __( '', PPW_Constants::DOMAIN ),
		) );
		$wp_customize->add_control(
			new PPW_Text_Editor_Custom_Control(
				$wp_customize,
				'ppwp_sitewide_above_countdown',
				array(
					'label'    => __( 'Description', PPW_Constants::DOMAIN ),
					'section'  => 'ppwp_sitewide_countdown',
					'settings' => 'ppwp_sitewide_above_countdown',
					'type'     => 'textarea',
				)
			)
		);

		/* Text below font size */
		$wp_customize->add_setting( 'ppwp_text_above_font_size' );
		$wp_customize->add_control( 'ppwp_text_above_font_size_control', array(
			'label'			=> __( 'Font Size', PPW_Constants::DOMAIN ),
			'section'  		=> 'ppwp_sitewide_countdown',
			'settings' 		=> 'ppwp_text_above_font_size',
			'description'	=> 'Font size in px',
			'type'     		=> 'number',
		) );

		/* Text above background color */
		$wp_customize->add_setting( 'ppwp_text_above_color', array(
			'default' => '',
		) );

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ppwp_text_above_color_control', array(
				'label'    => __( 'Text Color', PPW_Constants::DOMAIN ),
				'section'  => 'ppwp_sitewide_countdown',
				'settings' => 'ppwp_text_above_color',
			) )
		);

		/* descript section group */
		$wp_customize->add_setting( 'ppwp_sitewide_below_countdown_text' );
		$wp_customize->add_control(
			new PPW_Title_Group_Control(
				$wp_customize,
				'ppwp_sitewide_below_countdown_text', array(
				'label'			=> __( 'DESCRIPTION BELOW TIMER', PPW_Constants::DOMAIN ),
				'section'  		=> 'ppwp_sitewide_countdown',
				'settings' 		=> 'ppwp_sitewide_below_countdown_text',
				'type'     		=> 'control_title',
			) )
		);

		/* Text below sitewide */
		$wp_customize->add_setting( 'ppwp_sitewide_below_countdown', array(
			'default' => __( '', PPW_Constants::DOMAIN ),
		) );
		$wp_customize->add_control(
			new PPW_Text_Editor_Custom_Control(
				$wp_customize,
				'ppwp_sitewide_below_countdown',
				array(
					'label'    => __( 'Description', PPW_Constants::DOMAIN ),
					'section'  => 'ppwp_sitewide_countdown',
					'settings' => 'ppwp_sitewide_below_countdown',
					'type'     => 'textarea',
				)
			)
		);

		/* Text below font size */
		$wp_customize->add_setting( 'ppwp_text_below_font_size' );
		$wp_customize->add_control( 'ppwp_text_below_font_size_control', array(
			'label'			=> __( 'Font Size', PPW_Constants::DOMAIN ),
			'section'  		=> 'ppwp_sitewide_countdown',
			'settings' 		=> 'ppwp_text_below_font_size',
			'description'	=> 'Font size in px',
			'type'     		=> 'number',
		) );

		/* Text below background color */
		$wp_customize->add_setting( 'ppwp_text_below_color', array(
			'default' => '',
		) );

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ppwp_text_below_color_control', array(
				'label'    => __( 'Text Color', PPW_Constants::DOMAIN ),
				'section'  => 'ppwp_sitewide_countdown',
				'settings' => 'ppwp_text_below_color',
			) )
		);

		return $wp_customize;
	}

	public function register_countdown_timer_style() {
		$sw_custom_css = '
			.ppwp-sitewide-countdown {
				font-size: ' . esc_attr( get_theme_mod( 'ppwp_countdown_font_size' ) ) . 'px!important;
				color: ' . esc_attr( get_theme_mod( 'ppwp_countdown_text_color' ) ) . '!important;
				display: flex;
				justify-content: center;
			}

			#ppwp_desc_above_countdown {
				font-size: ' . esc_attr( get_theme_mod( 'ppwp_text_above_font_size' ) ) . 'px!important;
				color: ' . esc_attr( get_theme_mod( 'ppwp_text_above_color' ) ) . '!important;
			}

			#ppwp_desc_below_countdown {
				font-size: ' . esc_attr( get_theme_mod( 'ppwp_text_below_font_size' ) ) . 'px!important;
				color: ' . esc_attr(  get_theme_mod( 'ppwp_text_below_color' ) ) . '!important;
			}
			.ppwp-countdown-container {
				text-align: center;
			}
			#ppwp_desc_above_countdown,
			#ppwp_desc_below_countdown {
				display: none;
			}
			.ppwp_countdown_timer_day,
			.ppwp_countdown_timer_hour,
			.ppwp_countdown_timer_minute,
			.ppwp_countdown_timer_second {
				text-align: center;
				padding: 0px 10px;
			}
			.ppwp_coundown_colon_spacing {
				display: flex;
				align-items: center;
			}
		';
		echo $sw_custom_css; // phpcs:ignore -- we already escase inside the css
	}
}
