<?php
$message = array(
	'label'       => 'Get Passwords in Batches',
	'description' => 'By default, PPWP retrieves all passwords to display on the Password Management pop-up. This might cause a timeout error when you have a huge number of passwords per post. Turn on this option to load the passwords in batches instead.'
);

?>
<form id="ppwp_advanced_form">
	<input type="hidden" id="ppwp_advanced_form_nonce"
	       />
	<table class="ppwp_settings_table" cellpadding="4">
		<hr>
		<tr class="ppwp-gray-out">
			<td>
				<label class="pda_switch" >
					<input type="checkbox" disabled
					       id="ppwp_using_pagination" />
					<span class="pda-slider round"></span>
				</label>
			</td>
			<td>

				<p>
					<label>
						<?php echo esc_html__( $message['label'] ) ?>
						<span class="ppwp_upgrade_advice">
							<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/pricing/">
								<span class="ppwp_dashicons dashicons dashicons-lock">
									<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Upgrade to Gold', 'password-protect-page' ) ?></span>
								</span>
							</a>
						</span>
					</label>
					<?php echo _e( $message['description'] ) ?>
				</p>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input id="advanced-submit" class="button button-primary" type="submit" value="Save Changes" disabled ></td>
		</tr>
	</table>
</form>
