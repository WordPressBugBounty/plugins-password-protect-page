<?php
$ppw_post_types   = ppw_core_get_post_type_for_hide_protect_content();
$link_description = sprintf( '<a target="_blank" rel="noopener" href="%s">Hide your password protected content</a>', 'https://passwordprotectwp.com/docs/how-to-hide-password-protected-wordpress-content/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free' );
// translators: %s: Link to documentation.
$link_description_rss = sprintf( '<a target="_blank" rel="noopener" href="%s">show protected content in RSS feeds</a>', 'https://passwordprotectwp.com/docs/display-protected-content-rss-feed/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free' );

$description = sprintf( esc_html__( '%s from selected views. Learn how to %s.', PPW_Constants::DOMAIN ), $link_description, $link_description_rss );
$is_pro_activated = apply_filters( PPW_Constants::HOOK_IS_PRO_ACTIVATE, false );
$disabled_class = !$is_pro_activated ? 'ppwp-gray-out' : ''; 
?>
<tr class="<?php echo esc_attr( $disabled_class ); ?>">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Protected Content Visibility', PPW_Constants::DOMAIN ); ?>
			<?php if(!$is_pro_activated){ ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/pricing/">
						<span class="ppwp_dashicons dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Upgrade to Gold', 'password-protect-page' ) ?></span>
						</span>
					</a>
				</span>	
			<?php } ?>	
			</label>
			<?php echo $description; // phpcs:ignore -- could not escape html ?>
		<p>Switch post types to customize their own visibility. Only Pages & Posts are available on Free version.</p>
		<select class="ppw_select_custom_post_type_edit" id="ppw_select_custom_post_type_edit">
			<?php
			foreach ( $ppw_post_types as $ppw_type ) {
				$ppw_disabled = apply_filters( PPW_Constants::HOOK_CUSTOM_OPTION_HIDE_PROTECT_CONTENT, 'page_post' === $ppw_type['value'] ? '' : 'disabled', $ppw_type['value'] );
				?>
				<option <?php echo esc_attr( $ppw_disabled ); ?>
						value="<?php echo esc_attr( $ppw_type['value'] ); ?>"><?php echo esc_attr( $ppw_type['label'] ); ?></option>
				<?php
			}
			?>
		</select>
		</p>
	</td>
</tr>
<?php
ppw_core_check_logic_before_render_ui( $ppw_post_types );
?>
