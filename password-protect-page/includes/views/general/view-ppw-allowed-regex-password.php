<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="ppwp-gray-out">
	<td>
		<label class="pda_switch" for="ppwp_allowed_regex_password">
			<input type="checkbox"
			       id="ppwp_allowed_regex_password" disabled />
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Wildcard Passwords', 'password-protect-page' ) ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/pricing/">
						<span class="ppwp_dashicons dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Upgrade to Gold', 'password-protect-page' ) ?></span>
						</span>
					</a>
				</span>	
			</label>
			<?php esc_html_e( 'Allow users to access your protected content using ', 'password-protect-page' ) ?>
			<a rel="noreferrer noopener" href="https://passwordprotectwp.com/docs/create-wildcard-passwords/?utm_source=user-website&utm_medium=settings-general&utm_campaign=ppwp-pro" target="_blank">
				<?php echo esc_html__( 'wildcard passwords', 'password-protect-page' ) ?>
			</a>
		</p>
	</td>
</tr>
