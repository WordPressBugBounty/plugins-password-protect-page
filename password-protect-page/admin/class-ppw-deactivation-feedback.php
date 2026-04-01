<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class PPW_Deactivation_Feedback {

    private static $instance = null;

    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        
        define( 'PDA_API_SECRET', 'pda_1006a8a0fb312e92885d641701076333' );
        define( 'PPW_PRODUCT_ID', 11 );
        define( 'PPW_DEACTIVATION_ENDPOINT', 'https://analytics.madeforwp.com/wp-json/pda/v1/deactivation' );
        
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
        add_action( 'admin_footer', [ $this, 'render_modal' ] );
        add_action( 'wp_ajax_ppw_store_deactivation_feedback', [ $this, 'ppw_store_deactivation_feedback' ] );
        
    }

    /**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0
	 */
	private function includes() {

		// Functions file
		require_once WPFOLIO_PDA_ANYLC_DIR .'/includes/wpfolio-ppw-anylc-function.php';

	}

    public function enqueue( $hook ) {
        if ( $hook !== 'plugins.php' ) {
            return;
        }
        
        // Prevent double load (free + pro active)
        
        if ( defined( 'PPW_FEEDBACK_LOADED' ) ) {
            return;
        }
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
        define( 'PPW_FEEDBACK_LOADED', true );

        wp_enqueue_style(
            'ppw-deactivation-feedback',
            PPW_DIR_URL . 'admin/css/ppw-deactivation.css',
            [],
            PPW_VERSION
        );

        wp_enqueue_script(
            'ppw-deactivation-feedback',
            PPW_DIR_URL . 'admin/js/ppw-deactivation.js',
            [ 'jquery' ],
            PPW_VERSION,
            true
        );

        wp_localize_script( 'ppw-deactivation-feedback', 'PPWDeactivate', [
            'ajax'   => admin_url( 'admin-ajax.php' ),
            'nonce'  => wp_create_nonce( 'ppw_deactivate' ),
        ] );
    }

    public function render_modal() {
        if ( defined( 'PPW_FEEDBACK_RENDERED' ) ) {
            return;
        }
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
        define( 'PPW_FEEDBACK_RENDERED', true );

        $screen = get_current_screen();
        if ( $screen->id !== 'plugins' ) {
            return;
        }

        include __DIR__ . '/views/deactivation-modal.php';
    }

    public function ppw_store_deactivation_feedback() {
        check_ajax_referer( 'ppw_deactivate', 'nonce' );
        $reason = array();
        //$site_details = wpfolio_ppw_analytics_load();
       
        $ppw_user_site_data = $this->ppw_user_site_data();
        $reason['reason_data'] = [
            'reason' => sanitize_text_field( wp_unslash( $_POST['reason'] ?? '' ) ),
            'not_working_reason'  => sanitize_textarea_field( wp_unslash( $_POST['not_working_reason'] ?? '' ) ),
            'better_plugin_name'  => sanitize_textarea_field( wp_unslash( $_POST['better_plugin_name'] ?? '' ) ),
            'optional_detail'  => sanitize_textarea_field( wp_unslash( $_POST['optional_detail'] ?? '' ) ),
            'product_type' => sanitize_textarea_field( wp_unslash( $_POST['pluginType'] ?? '' ) ),
            'site'   => home_url(),
            'user'   => get_current_user_id(),
            'time'   => current_time( 'mysql' ),
        ];

        $data = array_merge( $ppw_user_site_data, $reason );
       
        $this->ppw_send_deactivation_data( $data );

        wp_send_json_success([
            'success' => true,
            'message' => __('Feedback sent successfully','prevent-direct-access')
        ]);

        
    }

    public function ppw_send_deactivation_data( array $data ) {
        
        if ( empty( $data['site_uid'] ) || empty( $data['reason_data'] ) ) {
            return false;
        }

        $endpoint = PPW_DEACTIVATION_ENDPOINT;

        if( !empty( $data['reason_data']['product_type'] ) ){
            $product_type = ( $data['reason_data']['product_type'] == 'free' ) ? 'Free' : 'Paid';
        }else{
            $product_type = '';
        }

        $payload = array_merge(
            $data,
            [
                // Flatten reason_data for DB compatibility
                'reason'             => $data['reason_data']['reason'] ?? '',
                'not_working_reason'              => $data['reason_data']['not_working_reason'] ?? '',
                'better_plugin_name'              => $data['reason_data']['better_plugin_name'] ?? '',
                'optional_detail'    => $data['reason_data']['optional_detail'] ?? '',
                'product_type'       => $product_type,
                'reason_site'        => $data['reason_data']['site'] ?? '',
                'reason_user'        => (int) ( $data['reason_data']['user'] ?? 0 ),
                'reason_time'        => $data['reason_data']['time'] ?? current_time( 'mysql' ),
            ]
        );
        
        /* ========= SIGNATURE ========= */
        $api_key = hash_hmac(
            'sha256',
            $payload['site_uid'],
            PDA_API_SECRET
        );

        unset( $payload['reason_data'] ); // remove nested array

        $response = wp_remote_post( $endpoint, [
            'timeout' => 20,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-PDA-KEY'    => $api_key,
            ],
            'body' => wp_json_encode( $payload ),
        ] );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        return true;
    }

    public function ppw_user_site_data(){
        
        $current_user = wp_get_current_user();
        $theme        = wp_get_theme();

        // Plugin info (current plugin)
        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugin_file = PPW_DIR_PATH.'wp-protect-password.php';
        
        $plugin_data = file_exists( $plugin_file ) ? get_plugin_data( $plugin_file ) : [];
        
        $details = [
            'site_url'          => get_site_url(),
            'site_name'         => get_bloginfo( 'name' ),
            'wp_version'        => get_bloginfo( 'version' ),
            'language'          => get_locale(),
            'php_version'       => PHP_VERSION,

            // SDK / plugin info
            'plugin_name'       => $plugin_data['Name'] ?? '',
            'plugin_version'    => $plugin_data['Version'] ?? '',
            'product_id'        => defined( 'PPW_PRODUCT_ID' ) ? PPW_PRODUCT_ID : 9,

            // Theme info
            'theme_name'        => $theme->get( 'Name' ),
            'theme_uri'         => $theme->get( 'ThemeURI' ),
            'theme_author'      => $theme->get( 'Author' ),
            'theme_author_uri'  => $theme->get( 'AuthorURI' ),
            'theme_version'     => $theme->get( 'Version' ),

            // User info
            'user_firstname'    => $current_user->first_name ?? '',
            'user_lastname'     => $current_user->last_name ?? '',
            'user_nickname'     => $current_user->nickname ?? '',
            'user_email'        => $current_user->user_email ?? '',

            // IP address
            'ip_address'        => $this->ppw_get_user_ip(),

            // Unique site ID
            'site_uid'          => $this->ppw_get_site_uid(),
        ];

        return $details;
    }
    
    public function ppw_get_user_ip() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $forwarded = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
            return sanitize_text_field( trim( $forwarded[0] ) );
        }
        return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
    }

    public function ppw_get_site_uid() {
        $uid = get_option( 'ppw_site_uid' );

        if ( empty( $uid ) ) {
            $uid = wp_generate_uuid4();
            update_option( 'ppw_site_uid', $uid );
        }

        return $uid;
    }
}

PPW_Deactivation_Feedback::instance();
?>