<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
$checked = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_CUSTOM_FORM_ACTION, PPW_Constants::MISC_OPTIONS ) ? 'checked' : '';
$message = array(
	'label' => __( 'Use Custom Form Action', 'password-protect-page' ),
	'description' => __(
		'Enable this option when <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/custom-login-page-compatibility/">the password protection doesn\'t work</a>, e.g. users get redirected to homepage or 404 error page.',
		'password-protect-page'
	),
);


?>
<tr <?php echo $checked === 'checked' ? 'style="color: gray;"' : ''; ?>>
	<td>
		<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::USE_CUSTOM_FORM_ACTION ); ?>">
			<input type="checkbox"
			       id="<?php echo esc_attr( PPW_Constants::USE_CUSTOM_FORM_ACTION ); ?>" <?php echo esc_attr( $checked ); ?> <?php echo $checked === 'checked' ? 'disabled' : ''; ?>/>
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
