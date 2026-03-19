<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
$remove_checked = ppw_core_get_setting_type_bool( PPW_Constants::REMOVE_DATA ) ? 'checked' : '';
$message = apply_filters(
    PPW_Constants::HOOK_CUSTOM_TEXT_FEATURE_REMOVE_DATA,
    array(
        'label'       => __( 'Remove Data Upon Uninstall', 'password-protect-page' ),
        'description' => __(
            'Remove all your data created by Password Protect WordPress upon uninstall. You should <b>NOT</b> remove our Free when upgrading to our Pro version.',
            'password-protect-page'
        ),
    )
);

?>
<tr>
	<td>
		<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::REMOVE_DATA ); ?>">
			<input type="checkbox"
			       id="<?php echo esc_attr( PPW_Constants::REMOVE_DATA ); ?>" <?php echo esc_attr( $remove_checked ); ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html( $message['label'] ); ?></label>
			<?php echo wp_kses_post( $message['description'] ); ?>
		</p>
	</td>
</tr>
