<?php
/**
 * PPWP Exclude Protected page
 */

$all_page_post = ppw_free_get_all_page_post();
?>
<tr class="ppwp_free_version ppwp_logic_show_input_password <?php echo esc_attr( $is_display ); ?>">
	<td></td>
	<td class="ppwp_set_height_for_password_entire_site">
		<div class="ppwp_wrap_new_password">
			<!-- <span class="feature-input"></span> -->
			<span class="ppwp-set-new-password-text">
				Exclude certain pages, posts and custom post types from site-wide protection. Available in Pro version.
			</span>
		</div>
		<div class="ppwp_free_wrap_select_exclude_page ppwp-hidden-password">
			<select multiple="multiple" class="ppwp_select2">
				<option value="ppwp_home_page">Home Page</option>
				<?php foreach ( $all_page_post as $page ) { ?>
					<option><?php echo esc_html( $page->post_title ); ?></option>
				<?php } ?>
			</select>
		</div>
	</td>
</tr>
