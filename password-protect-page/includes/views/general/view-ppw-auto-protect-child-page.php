<?php 
/**
 * Password Protect Child Pages
 */
?>
<tr class="ppwp_free_version ppwp-gray-out">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Password Protect Child Pages', PPW_Constants::DOMAIN ); ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/pricing/">
						<span class="ppwp_dashicons dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Upgrade to Gold', 'password-protect-page' ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo esc_html__( 'Automatically protect all child pages once their parent is protected. Available in Pro version.', PPW_Constants::DOMAIN ); ?>
		</p>
	</td>
</tr>
