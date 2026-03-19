<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr>
	<td colspan="2">
		<hr>
	</td>
</tr>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Caching Plugins & Server-side Caching', 'password-protect-page' ); ?></label>
			<?php
			echo wp_kses_post(
				__( 'If you’re using a caching plugin or server-side caching, you’ll need to <a rel="noopener noreferrer" target="_blank" href="https://passwordprotectwp.com/docs/caching-plugins-cache-servers-integration/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free">update your caching configurations</a> for our Password Protect Wordpress plugin to work properly.', 'password-protect-page' )
			);
			?>
		</p>
	</td>
</tr>
