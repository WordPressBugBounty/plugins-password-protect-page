<?php
/**
 * WPFolio Ppwp Analytic
 *
 * @author WP Online Support
 * @package WPFolio Ppwp Analytic
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPFolio_Ppwp_Analytics' ) ) :

/**
 * Main Analytics Class By WP Online Support.
 *
 * @since 1.0
 */
final class WPFolio_Ppwp_Analytics {

	/**
	 * @var Instance
	 * @since 1.0
	 */
	protected static $instance = null;

	/**
	 * Main Analytics Instance.
	 *
	 * Insures that only one instance of Analytics exists in memory at any one time.
	 * Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @uses WPFOLIO_PPWP_ANYLC::setup_constants() Setup the constants needed.
	 * @uses WPFOLIO_PPWP_ANYLC::includes() Include the required files.
	 * @uses WPFOLIO_PPWP_ANYLC::wpfolio_ppwp_anylc_plugins_loaded() load the language files.
	 * @see PWPC()
	 * @return object The one true Analytics
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pwpc' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'pwpc' ), '1.0' );
	}

	/**
	 * Plugin Constructor.
	 */
	public function __construct() {
		$this->setup_constants();
		$this->includes();

		do_action( 'wpfolio_ppwp_anylc_loaded' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Setup plugin constants. Basic plugin definitions
	 *
	 * @access private
	 * @since 1.0
	 */
	private function setup_constants() {

		$this->define( 'WPFOLIO_PPWP_ANYLC_VERSION', '1.1' );
		$this->define( 'WPFOLIO_PPWP_ANYLC_DIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'WPFOLIO_PPWP_ANYLC_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'WPFOLIO_PPWP_ACTION_URL', "https://analytics.madeforwp.com/" );
		$this->define( 'WPFOLIO_PPWP_PRIVACY_URL', "https://passwordprotectwp.com/privacy-policy/" );
		$this->define( 'WPFOLIO_PPWP_TERM_URL', "https://passwordprotectwp.com/terms-conditions/" );
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0
	 */
	private function includes() {

		// Functions file
		require_once WPFOLIO_PPWP_ANYLC_DIR .'/includes/wpfolio-ppwp-anylc-function.php';

		// Script Class
		require_once WPFOLIO_PPWP_ANYLC_DIR .'/includes/class-anylc-script.php';

		// Admin Class
		require_once WPFOLIO_PPWP_ANYLC_DIR .'/includes/class-anylc-admin.php';
	}
}

/**
 *
 * The main function responsible for returning the one true Analytics
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: $wpfolio_ppwp_anylc = WPFOLIO_PPWP_ANYLC();
 *
 * @since 1.0
 * @return object The one true Analytics Instance.
 */
function WPFOLIO_PPWP_ANYLC() {
	return WPFolio_Ppwp_Analytics::instance();
}

/**
 *
 * Initialize Analytics Module
 *
 * @since 1.0
 * @return object The one true Analytics Instance.
 */
function wpfolio_ppwp_anylc_init_module( $args = array() ) {

	global $wpfolio_ppwp_analytics_module, $wpfolio_ppwp_analytics_product;

	$defaul_args = array(
						'id'			=> null,
						'file'			=> null,
						'name'  		=> null,
						'slug'  		=> null,
						'type'			=> 'plugin',
						'menu'			=> false,
						'icon'			=> '',
						'text_domain'	=> 'password-protect-page',
					);

	$args = wp_parse_args( $args, $defaul_args );

	// If required data is not there then simply return
	if( empty($args['id']) || empty( $args['file'] ) || empty( $args['slug'] ) ) {
		return false;
	}

	// Additional args
	$promotion 				= array();
	$args['dir'] 			= pathinfo($args['file'], PATHINFO_DIRNAME);
	$args['icon']			= empty( $icon ) ? trailingslashit( WP_PLUGIN_URL ).$args['dir'].'/wpfolio-analytics/assets/images/ppwp-logo-2025.png' : $args['icon'];
	$args['brand_icon']		= plugin_dir_url( __FILE__ ).'assets/images/wpfolio-logo.png';
	$args['anylc_optin']	= 'wpfolio_ppwp_anylc_pdt_'.$args['id'];

	if( isset( $args['promotion'] ) ) {
		foreach ($args['promotion'] as $promotion_key => $promotion_data) {
			if( empty( $promotion_data['name'] ) || empty( $promotion_data['file'] ) ) {
				continue;
			}

			$promotion[$promotion_key] = $promotion_data;
		}
	}
	$args['promotion'] = $promotion;

	// Taking some variables
	$wpfolio_ppwp_analytics_module 	= !empty( $wpfolio_ppwp_analytics_module ) 	? $wpfolio_ppwp_analytics_module 	: array();
	$wpfolio_ppwp_analytics_product = !empty( $wpfolio_ppwp_analytics_product ) ? $wpfolio_ppwp_analytics_product 	: array();

	if( is_array( $wpfolio_ppwp_analytics_module ) ) {
		$wpfolio_ppwp_analytics_module[ $args['file'] ] = $args;
	}

	if( is_array( $wpfolio_ppwp_analytics_product ) ) {
		$wpfolio_ppwp_analytics_product[ $args['slug'] ] = $args;
	}

	return $wpfolio_ppwp_analytics_module;
}

/**
 *
 * Function on any plugin activation
 *
 * @since 1.0
 * @return object The one true Analytics Instance.
 */
function wpfolio_ppwp_anylc_plugin_activation( $plugin, $network_activation ) {

	// return if activating from network, or bulk
	if ( is_network_admin() ) {
		return;
	}

	global $wpfolio_ppwp_analytics_module;

	if( isset( $wpfolio_ppwp_analytics_module[ $plugin ] ) ) {

		$opt_in_data 	= get_option( $wpfolio_ppwp_analytics_module[ $plugin ]['anylc_optin'] );
		$optin_status 	= isset( $opt_in_data['status'] ) ? $opt_in_data['status'] : -1;

		if( $optin_status == -1 ) {
			
			$redirect_link = add_query_arg( array( 'page' => $wpfolio_ppwp_analytics_module[ $plugin ]['tempslug'], 'anylc_nonce' => wp_create_nonce( 'wpfolio-ppwp-anylc-redirect-nonce' ) ), admin_url('admin.php') );
			update_option( 'wpfolio_ppwp_anylc_redirect', $redirect_link );

		} elseif( ! empty( $wpfolio_ppwp_analytics_module[ $plugin ]['redirect_page'] ) ) {

			if( $optin_status == 1 || $optin_status == 2) {
				$redirect_page	= $wpfolio_ppwp_analytics_module[ $plugin ]['slug'];
			}else{
				$redirect_page	= $wpfolio_ppwp_analytics_module[ $plugin ]['tempslug'];
			}

			$pos 			= strpos( $redirect_page, '?post_type' );

			$redirect_link 	= ( $pos !== false ) ? admin_url( $redirect_page ) : add_query_arg( array( 'page' => $redirect_page, 'anylc_nonce' => wp_create_nonce( 'wpfolio-ppwp-anylc-redirect-nonce' ) ), admin_url('admin.php') );

			update_option( 'wpfolio_ppwp_anylc_redirect', $redirect_link );	
		}
	}
}
add_action( 'activated_plugin', 'wpfolio_ppwp_anylc_plugin_activation', 10, 2 );

/**
 *
 * Initialize Analytics Class Once all stuff has been loaded
 *
 * @since 1.0
 * @return object The one true Analytics Instance.
 */
function wpfolio_ppwp_anylc_plugins_loaded() {
	
	// Get Analytics Running.
	WPFOLIO_PPWP_ANYLC();
}
add_action( 'plugins_loaded', 'wpfolio_ppwp_anylc_plugins_loaded', 12 );

endif; // End if class_exists check.