<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="ppwp_free_version ppwp-gray-out">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Error Message', 'password-protect-page' ) ?>
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
	              /* translators: %1$s opens emphasis tag, %2$s closes emphasis tag. Explains Pro-only availability. */
				printf(
				    wp_kses_post(
				        __( 'Customize the error message when users enter wrong passwords.%1$s Available in Pro version only.%2$s', 'password-protect-page' )
				    ),
				    '<em>',
				    '</em>'
				);
			// phpcs:enable	 
			?>
		</p>
		<span>
            <input type="text" disabled
                   value="<?php echo esc_html( PPW_Constants::DEFAULT_WRONG_PASSWORD_MESSAGE ); ?>"/>
        </span>
	</td>
</tr>
