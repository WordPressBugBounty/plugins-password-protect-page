<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
$post_types = ppw_core_get_all_post_types();
unset( $post_types['post'] );
unset( $post_types['page'] );
?>
<tr class="ppwp_free_version">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Post Type Protection', 'password-protect-page' ); ?>
			</label>
			<?php 
			// phpcs:disable
			printf(
					    wp_kses_post(
					        __( '%1$sSelect which custom post types%2$s you want to password protect. Default: Pages & Posts.', 'password-protect-page' )
					    ),
					    '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free#cpt">',
					    '</a>'
					); 
			// phpcs:enable
			?>
		</p>
		<div class="ppw_wrap_select_protection_selected">
			<div class="ppw_wrap_protection_selected">
				<span class="ppw_protection_selected"><?php echo esc_html__( 'Pages', 'password-protect-page' ); ?></span>
				<span class="ppw_protection_selected"><?php echo esc_html__( 'Posts', 'password-protect-page' ); ?></span>
				<p><?php echo esc_html__('For support with Custom Post Types, consider using our Pro plugin.', 'password-protect-page'); // phpcs:ignore -- there is no value to escape. ?></p>
			</div>
		</div>
	</td>
</tr>
