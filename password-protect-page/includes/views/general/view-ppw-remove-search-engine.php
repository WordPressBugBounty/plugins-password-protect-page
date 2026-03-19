<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="ppwp_free_version">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Block Search Indexing', 'password-protect-page' ); ?>
			</label>
			<?php 
				// phpcs:disable
				/* translators: %1$s opens documentation link, %2$s closes the link. */
				printf(
				    wp_kses_post(
				        __( '%1$sPrevent search engines from indexing%2$s your password protected content. Available in Pro version.', 'password-protect-page' )
					    ),
					    '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free#block-indexing">',
					    '</a>'
				); 
				// phpcs:enable
			?>
		</p>
	</td>
</tr>

