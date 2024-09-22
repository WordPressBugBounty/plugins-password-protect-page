<?php
$post_types = ppw_core_get_all_post_types();
unset( $post_types['post'] );
unset( $post_types['page'] );
?>
<tr class="ppwp_free_version">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Post Type Protection', PPW_Constants::DOMAIN ); ?>
			</label>
			<?php echo _e( '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free#cpt">Select which custom post types</a> you want to password protect. Default: Pages & Posts.', PPW_Constants::DOMAIN ); // phpcs:ignore -- there is no value to escape. ?>
		</p>
		<div class="ppw_wrap_select_protection_selected">
			<div class="ppw_wrap_protection_selected">
				<span class="ppw_protection_selected">Pages</span>
				<span class="ppw_protection_selected">Posts</span>
				<p><?php echo _e('For support with Custom Post Types, consider using our Pro plugin.', PPW_Constants::DOMAIN); // phpcs:ignore -- there is no value to escape. ?></p>
			</div>
		</div>
	</td>
</tr>
