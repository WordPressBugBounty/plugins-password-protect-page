<?php
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
			<label><?php echo esc_html__( 'Restore Default WordPress Passwords', PPW_Constants::DOMAIN ); ?></label>
			<a target="_blank" rel="noopener noreferrer"
			   href="https://passwordprotectwp.com/docs/password-migration/?utm_source=user-website&utm_medium=settings-advanced-tab&utm_campaign=ppwp-free#backup">Restore all your backup
				passwords</a> to maintain your content's protection status after plugin deactivation.
			<br>
			The process runs in the background. You will get a notification once it’s completed.
		<p>
			<?php
			if ( $is_running ) {
				echo esc_html__( 'Restoring ', PPW_Constants::DOMAIN ) . '<b>' . esc_html( $num_wp_passwords ) . '</b>' . esc_html__( ' backup password(s)...', PPW_Constants::DOMAIN );
			} else {
				echo esc_html__( 'There are ', PPW_Constants::DOMAIN ) . '<b>' . esc_html( $num_wp_passwords ) . '</b>' . esc_html__( ' backup password(s).', PPW_Constants::DOMAIN );
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
				<strong><?php echo esc_html__( 'Warning', PPW_Constants::DOMAIN ) ?></strong>: <?php echo esc_html__( 'Do not restore default WordPress
				passwords unless you are to deactivate our plugins', PPW_Constants::DOMAIN ) ?>
			</div>
		</p>
	</td>
</tr>
