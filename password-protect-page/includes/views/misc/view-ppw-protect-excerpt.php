<?php
$checked = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::PROTECT_EXCERPT, PPW_Constants::MISC_OPTIONS ) ? 'checked' : '';
?>
<tr>
	<td>
		<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::PROTECT_EXCERPT ); ?>">
			<input type="checkbox" id="<?php echo esc_attr( PPW_Constants::PROTECT_EXCERPT ); ?>" <?php echo esc_html( $checked ); ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Show Post Excerpt', PPW_Constants::DOMAIN ); ?></label>
			<?php echo wp_kses_post( __( '<a target="_blank" href="https://passwordprotectwp.com/docs/display-featured-image-password-protected-excerpt/?utm_source=user-website&utm_medium=settings-advanced-tab&utm_campaign=ppwp-free">Display excerpt</a> of password protected posts. You can also <a target="_blank" href="https://passwordprotectwp.com/docs/display-featured-image-password-protected-excerpt/?utm_source=user-website&utm_medium=settings-advanced-tab&utm_campaign=ppwp-free#customize-default">customize the default excerpt</a> using a custom code snippet.', PPW_Constants::DOMAIN ) ); ?>
		</p>
	</td>
</tr>
