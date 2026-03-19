<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="ppwp_free_version ppwp-gray-out">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Form Message', 'password-protect-page' ) ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/pricing/">
						<span class="ppwp_dashicons dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Upgrade to Gold', 'password-protect-page' ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php 
			// phpcs:disable
			/* translators: %1$s opens emphasis tag, %2$s closes emphasis tag. Indicates Pro-only availability notice. */
			printf(
				    wp_kses_post(
				        __( 'Customize the message which displays above the password field.%1$s Available in Pro version only.%2$s', 'password-protect-page' )
				    ),
				    '<em>',
				    '</em>'
				);
			// phpcs:enable 
			?>
		</p>
		<input type="text" disabled value="<?php echo esc_html( PPW_Constants::DEFAULT_FORM_MESSAGE ); ?>"/>
	</td>
</tr>
