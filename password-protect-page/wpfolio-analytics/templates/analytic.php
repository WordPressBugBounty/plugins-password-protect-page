<?php
/**
 * Settings Page
 *
 * @package WPFolio Ppwp Analytic
 * @since 1.0.0
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

if( isset($_GET['my_notice']) && $_GET['my_notice'] === 'error' ) {
     echo '<div class="notice wpfolio-custom notice-error is-dismissible"><p>❌ Something went wrong. Please try again.</p></div>';
}


?>

<style type="text/css">
	.notice, .error, div.fs-notice.updated, div.fs-notice.success, div.fs-notice.promotion{display:none !important;}
	.wpfolio-custom.notice{ display:block !important; }
</style>

<div class="wrap wpfolio-ppwp-anylc-optin">

	<?php if( isset($_GET['error']) && $_GET['error'] == 'wpfolio_ppwp_anylc_error' ) { ?>
	<div class="error">
		<?php echo wp_kses_post( '<p><strong>Sorry, something went wrong. Please contact us at <a href="mailto:support@ppwp-live.local">support@ppwp-live.local</a>.</strong></p>' );
		?>
	</div>
	<?php } ?>

	<form method="POST" action="<?php echo WPFOLIO_PPWP_ACTION_URL;?>">
		<div class="wpfolio-ppwp-anylc-optin-wrap" style="width: 650px; margin: 0 auto; margin-top: 70px;">

			<div>
				<div style="height:50px; text-align: center; background-color: rgba(180,185,190, 0.2);">
					<img style="position: relative; top:-40px;" src="<?php echo esc_url( $analy_product['icon'] ); ?>" alt="Icon" />
				</div>
				<div style="padding: 10px;">
					<div style="margin-top:50px; margin-bottom: 30px; text-align: center; font-weight: 700; font-size: 24px;"><?php echo esc_html__( 'Never miss an important update', 'password-protect-page' );?></div>

					<div style="font-size: 20px; font-weight: 500; line-height:25px; margin: 10px 12px; color:#646970;"><?php echo esc_html__( 'Opt-in to get email notifications for security & feature updates, and to share some basic WordPress environment info. This will help us make the plugin more compatible with your site and better at doing what you need it to.', 'password-protect-page' );?></div>
				</div>
			</div>

			<?php if( ! empty( $analy_product['promotion'] ) ) { ?>
			<div class="wpfolio-ppwp-anylc-promotion-wrap">
				<?php foreach( $analy_product['promotion'] as $promotion_key => $promotion_data ) { ?>
				<div><label><input type="checkbox" value="<?php echo esc_attr( $promotion_key ); ?>" name="promotion[]" checked="checked" /> <?php echo esc_html( $promotion_data['name'] ); ?></label></div>
				<?php } ?>
			</div>
			<?php } ?>

			<div class="wpfolio-ppwp-anylc-optin-action wpfolio-ppwp-anylc-clearfix" style="background-color: rgba(180,185,190, 0.3);">

				<button type="submit" name="wpfolio_ppwp_anylc_optin" class="button button-primary button-large wpfolio-ppwp-anylc-allow-btn" value="wpfolio_ppwp_anylc_optin"><?php echo esc_html__( 'Allow and Continue','password-protect-page' );?></button>

				<?php if( is_null( $opt_in ) ) { ?>
				<button type="submit" name="wpfolio_ppwp_anylc_action" class="button button-secondary button-large right wpfolio-ppwp-anylc-skip-btn" value="skip" style="padding: 0 !important;background: transparent;border: none;"><?php echo esc_html__( 'Skip', 'password-protect-page' );?></button>
				<?php }

				if( ! empty( $optin_form_data ) ) {
				 	foreach ($optin_form_data as $data_key => $data_value) {
				 		echo '<input type="hidden" name="'.esc_attr( $data_key ).'" value="'.esc_attr( $data_value ).'" />';
				 	}
				} ?>
			</div>
			<div class="wpfolio-ppwp-anylc-optin-permission">
				<a class="wpfolio-ppwp-anylc-permission-toggle" href="javascript:void(0);"><?php echo esc_html__( 'What permissions are being granted?', 'password-protect-page' );?></a>

				<div class="wpfolio-ppwp-anylc-permission-wrap wpfolio-ppwp-anylc-hide">
					<div class="wpfolio-ppwp-anylc-permission">
						<i class="dashicons dashicons-admin-users"></i>
						<div>
							<span class="wpfolio-ppwp-anylc-permission-name"><?php echo esc_html__( 'Your Profile Overview', 'password-protect-page' );?></span>
							<span class="wpfolio-ppwp-anylc-permission-info"><?php echo esc_html__( 'Name and email address', 'password-protect-page' );?></span>
						</div>
					</div>
					<div class="wpfolio-ppwp-anylc-permission">
						<i class="dashicons dashicons-admin-settings"></i>
						<div>
							<span class="wpfolio-ppwp-anylc-permission-name"><?php echo esc_html__( 'Your Site Overview','password-protect-page' );?></span>
							<span class="wpfolio-ppwp-anylc-permission-info"><?php echo esc_html__( 'Site URL, WP version, PHP info & Theme', 'password-protect-page' );?></span>
						</div>
					</div>
					<div class="wpfolio-ppwp-anylc-permission">
						<i class="dashicons dashicons-admin-plugins"></i>
						<div>
							<span class="wpfolio-ppwp-anylc-permission-name"><?php echo esc_html__( 'Current Plugin Events','password-protect-page' );?></span>
							<span class="wpfolio-ppwp-anylc-permission-info"><?php echo esc_html__( 'Activation, Deactivation and Uninstall','password-protect-page' );?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="wpfolio-ppwp-anylc-terms">
				<a href="<?php echo WPFOLIO_PPWP_PRIVACY_URL; ?>" target="_blank"><?php echo esc_html__( 'Privacy Policy','password-protect-page' );?></a> - <a href="<?php echo WPFOLIO_PPWP_TERM_URL; ?>" target="_blank"><?php echo esc_html__( 'Terms of Service','password-protect-page' );?></a>
			</div>
		</div>
	</form>
</div><!-- end .wrap -->