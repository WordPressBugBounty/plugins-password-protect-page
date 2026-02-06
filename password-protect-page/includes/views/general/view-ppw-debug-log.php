<tr class="ppwp-gray-out">
	<td>
		<label class="pda_switch" for="ppwp_debug_log_enabled">
			<input type="checkbox"
			       id="ppwp_debug_log_enabled" disabled />
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Enable Debug Logs', PPW_Constants::DOMAIN ); ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/pricing/">
						<span class="ppwp_dashicons dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Upgrade to Gold', 'password-protect-page' ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo _e( 'Log (fatal) errors of your entire website which speeds up the troubleshooting process when problems occur', PPW_Constants::DOMAIN ); ?>
		</p>
	</td>
</tr>
