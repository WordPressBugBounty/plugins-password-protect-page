<?php
/**
 * Analytic Optout Popup
 *
 * @package WPFolio Ppwp Analytic
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Generate per-request state and store 10 min (bind to site_uid if available)
$state = bin2hex( random_bytes(16) );
$site_uid = isset( $optin_form_data['site_uid'] ) ? sanitize_text_field( $optin_form_data['site_uid'] ) : 'default';
set_transient( 'wpfolio_ppwp_state_' . $site_uid, $state, 10 * MINUTE_IN_SECONDS );
// pass to template as hidden field (add to $optin_form_data)
$optin_form_data['state'] = $state;
?>
<div class="wpfolio-ppwp-anylc-popup wpfolio-ppwp-anylc-popup-wrp wpfolio-ppwp-anylc-hide" id="wpfolio-ppwp-anylc-optout-<?php echo esc_attr( $module['id'] ); ?>">
	<div class="wpfolio-ppwp-anylc-popup-inr-wrp">
		<div class="wpfolio-ppwp-anylc-popup-block">

			<div class="wpfolio-ppwp-anylc-popup-header"><?php echo esc_html__( 'Opt Out', 'password-protect-page' );?></div>
			<div class="wpfolio-ppwp-anylc-popup-body">
				<p class="wpfolio-ppwp-anylc-popup-heading"><?php echo esc_html__( 'We appreciate your help to make the plugin better by letting us track some usage data.', 'password-protect-page' );?></p>
				<p><?php echo esc_html__( 'Usage tracking is done in the name of making','password-protect-page' );?> <b><?php echo esc_html( $module['name'] ); ?></b> <?php echo esc_html__( 'better', 'password-protect-page' );?>. <?php echo esc_html__('Making a better user experience, prioritizing new features, and more good things. We’d really appreciate it if you could reconsider letting us continue with the tracking.', 'password-protect-page');?>
			    </p>
				<p><?php echo esc_html__('By clicking "Opt Out", we will stop sending any data from WordPress.','password-protect-page');?> <b><?php echo esc_html( $module['name'] ); ?></b> to <a href="<?php echo WPFOLIO_PPWP_ACTION_URL;?>" target="_blank"><?php echo WPFOLIO_PPWP_ACTION_URL;?></a>.</p>
			</div>
			<div class="wpfolio-ppwp-anylc-popup-footer">
				<form method="POST" action="<?php echo WPFOLIO_PPWP_ACTION_URL;?>">
					<?php
					if( ! empty( $optin_form_data ) ) {
						foreach ($optin_form_data as $data_key => $data_value) {
							echo '<input type="hidden" name="'.esc_attr( $data_key ).'" value="'.esc_attr( $data_value ).'" />';
						}
					}
					?>
					<button type="submit" name="wpfolio_ppwp_anylc_action" class="button button-secondary" value="optout"><?php echo esc_html__('Opt Out','password-protect-page');?></button>
					<button type="button" class="button button-primary wpfolio-ppwp-anylc-popup-close"><?php echo esc_html__('Sure, Let Me Continue Helping','password-protect-page');?></button>
				</form>
			</div>

		</div><!-- end .wpfolio-ppwp-anylc-popup-block -->
	</div><!-- end .wpfolio-ppwp-anylc-popup-inr-wrp -->
</div><!-- end .wpfolio-ppwp-anylc-popup-wrp -->
<div class="wpfolio-ppwp-anylc-popup-overlay" id="wpfolio-ppwp-anylc-optout-overlay-<?php echo esc_attr( $module['id'] ); ?>"></div>