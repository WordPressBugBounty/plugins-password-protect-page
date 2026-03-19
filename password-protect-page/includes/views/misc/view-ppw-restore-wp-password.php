<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
global $password_recovery_service;

$num_wp_passwords = PPW_Repository_Passwords::get_instance()->count_wp_post_passwords();
$is_running       = $password_recovery_service->is_running();

?>
<tr>
	<td>
		<span class="feature-input"></span>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Restore Default WordPress Passwords', 'password-protect-page' ); ?></label>
			<a target="_blank" rel="noopener noreferrer"
			   href="https://passwordprotectwp.com/docs/password-migration/?utm_source=user-website&utm_medium=settings-advanced-tab&utm_campaign=ppwp-free#backup"><?php esc_html_e( 'Restore all your backup passwords', 'password-protect-page' ); ?></a> <?php esc_html_e('to  maintain your content protection status after plugin deactivation.','password-protect-page'
				); ?>
			<br>
			<?php esc_html_e('The process runs in the background. You will get a notification once it’s completed.','password-protect-page'); ?>
		<p>
			<?php
			if ( $is_running ) {
				echo esc_html__( 'Restoring ', 'password-protect-page' ) . '<b>' . esc_html( $num_wp_passwords ) . '</b>' . esc_html__( ' backup password(s)...', 'password-protect-page' );
			} else {
				echo esc_html__( 'There are ', 'password-protect-page' ) . '<b>' . esc_html( $num_wp_passwords ) . '</b>' . esc_html__( ' backup password(s).', 'password-protect-page' );
			}
			?>
		</p>
		</p>
		<p>
			<input id="ppw-restore-passwords" <?php echo ! $num_wp_passwords || $is_running ? 'disabled="true"' : '' ?>
			       type="button"
			       class="button button-primary"
			       value="Restore Now"
			>
			<div class="ppw-warning">
				<strong><?php echo esc_html__( 'Warning', 'password-protect-page' ); ?></strong>: <?php echo esc_html__( 'Do not restore default WordPress
				passwords unless you are to deactivate our plugins', 'password-protect-page' ); ?>
			</div>
		</p>
	</td>
</tr>
