<?php
/**
 * Script Class
 *
 * Handles the script and style 
 *
 * @package WPFolio Ppwp Analytic
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WPFolio_Ppwp_Anylc_Script {

	function __construct() {

        // Action to add style backend
		add_action( 'admin_enqueue_scripts', array($this, 'wpfolio_ppwp_anylc_admin_script_style') );
	}

     /**
	 * Enqueue script for backend
	 * 
	 * @package WPFolio Ppwp Analytic
	 * @since 1.0
	 */
    function wpfolio_ppwp_anylc_admin_script_style( $hook ) {

		// Process Promotion Data
		if( !empty($_GET['message']) && $_GET['message'] == 'wpfolio_ppwp_anylc_promotion' && !empty($_GET['wpfolio_ppwp_anylc_pdt']) && !empty($_GET['wpfolio_ppwp_anylc_promo_pdt']) ) {
			global $wpfolio_ppwp_analytics_product;

			$promotion 				= 1;
			$wpfolio_ppwp_anylc_promo_pdt	= sanitize_text_field( $_GET['wpfolio_ppwp_anylc_promo_pdt'] );
			$promotion_pdt 			= explode( ',', $wpfolio_ppwp_anylc_promo_pdt );

			$anylc_pdt 		= sanitize_text_field( $_GET['wpfolio_ppwp_anylc_pdt'] );
			$anylc_pdt_data = isset( $wpfolio_ppwp_analytics_product[ $anylc_pdt ] ) ? $wpfolio_ppwp_analytics_product[ $anylc_pdt ] : false;

			if( !empty($promotion_pdt) ) {
				foreach ($promotion_pdt as $pdt_key => $pdt) {
					if( isset( $anylc_pdt_data['promotion'][$pdt]['file'] ) ) {
						$promotion_pdt_data[] = $anylc_pdt_data['promotion'][$pdt]['file'];
					}
				}
			}
		}

    	// Registring admin Style
		wp_register_style( 'wpfolio-ppwp-anylc-admin-style', WPFOLIO_PPWP_ANYLC_URL.'assets/css/wpfolio-ppwp-anylc-admin.css', null, WPFOLIO_PPWP_ANYLC_VERSION );
		wp_enqueue_style( 'wpfolio-ppwp-anylc-admin-style' );

		// Registring admin script
		wp_register_script( 'wpfolio-ppwp-anylc-admin-script', WPFOLIO_PPWP_ANYLC_URL.'assets/js/wpfolio-ppwp-anylc-admin.js', array('jquery'), WPFOLIO_PPWP_ANYLC_VERSION, true );
		wp_localize_script( 'wpfolio-ppwp-anylc-admin-script', 'WPFolioAnylc', array(
																		'promotion' 	=> isset($promotion) ? 1 : 0,
																		'promotion_pdt' => isset( $promotion_pdt_data ) ? $promotion_pdt_data : 0,
																	));
		wp_enqueue_script( 'wpfolio-ppwp-anylc-admin-script' );
    }
}

$wpfolio_ppwp_anylc_script = new WPFolio_Ppwp_Anylc_Script();