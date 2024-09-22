<?php
/**
 * PPWP General Settings
 */
$using_recaptcha       = PPW_Recaptcha::get_instance()->using_recaptcha() ? 'checked' : '';
$recaptcha_type        = PPW_Recaptcha::get_instance()->get_recaptcha_type();
$password_types        = PPW_Recaptcha::get_instance()->get_password_types();
$type_options          = array(
	PPW_Recaptcha::RECAPTCHA_V3_TYPE          => __( 'reCAPTCHA v3', PPW_Constants::DOMAIN ),
	PPW_Recaptcha::RECAPTCHA_V2_CHECKBOX_TYPE => __( 'reCAPTCHA v2 - Checkbox', PPW_Constants::DOMAIN ),
);
$password_type_options = array(
	PPW_Recaptcha::SINGLE_PASSWORD   => __( 'Single password form', PPW_Constants::DOMAIN ),
	PPW_Recaptcha::SITEWIDE_PASSWORD => __( 'Sitewide login form', PPW_Constants::DOMAIN ),
	PPW_Recaptcha::PCP_PASSWORD      => __( 'PCP password form', PPW_Constants::DOMAIN ),
);

?>
<div class="ppw_main_container" id="ppw_shortcodes_form">
	<form id="wpp_external_form" method="post">
		<input type="hidden" id="ppw_general_form_nonce"
		       value="<?php echo esc_attr( wp_create_nonce( PPW_Constants::GENERAL_FORM_NONCE ) ); ?>"/>
		<table class="ppwp_settings_table" cellpadding="4">
			<tr>
				<td>
					<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::USING_RECAPTCHA ); ?>">
						<input type="checkbox"
						       id="<?php echo esc_attr( PPW_Constants::USING_RECAPTCHA ); ?>" <?php echo esc_html( $using_recaptcha ); ?>>
						<span class="pda-slider round"></span>
					</label>
				</td>
				<td>
					<p style="margin-bottom: 6px;">
						<label><?php esc_attr_e( 'Enable Google reCAPTCHA Protection', PPW_Constants::DOMAIN ) ?></label>
						<a rel="noopener" target="_blank" href="https://passwordprotectwp.com/docs/add-google-recaptcha-wordpress-password-form/?utm_source=user-website&utm_medium=integration-recaptcha&utm_campaign=ppwp-free"><?php echo esc_html__('Protect
							your password form',PPW_Constants::DOMAIN)?></a>
							<?php echo esc_html__('from abuse and spam while allowing real user access only',PPW_Constants::DOMAIN);?>
					</p>
					<div
						<?php echo $using_recaptcha ? '' : 'style="display: none"'; ?>
						id="wpp_recaptcha_options">
						<div>
							<p><?php esc_attr_e( 'Choose reCAPTCHA type', PPW_Constants::DOMAIN ); ?></p>
							<select
								class="ppw_main_container select"
								id="wpp_recaptcha_type">
								<?php
								foreach ( $type_options as $key => $value ) {
									$selected = $key === $recaptcha_type ? 'selected="selected"' : '';
									echo '<option value="' . esc_attr( $key ) . '" ' . esc_html( $selected ) . '>' . esc_html( $value ) . '</option>';
								}
								?>
								<option value="recaptcha_v2_invisible"
								        disabled><?php echo esc_html__( 'reCAPTCHA v2 - Invisible', PPW_Constants::DOMAIN ); ?></option>
							</select>
						</div>
						<div style="max-width: 25rem;">
							<p><?php esc_attr_e( 'Choose which password form to apply reCAPTCHA', PPW_Constants::DOMAIN ); ?></p>
							<select id="wpp_recaptcha_password_types" class="ppw_main_container select ppw_select_types" required multiple="multiple">
								<?php
								foreach ( $password_type_options as $key => $value ) {
									$selected = in_array( $key, $password_types) ? 'selected="selected"' : '';
									echo '<option value="' . esc_attr( $key ) . '" ' . esc_html( $selected ) . '>' . esc_html( $value ) . '</option>';
								}
								?>
							</select>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</td>
			</tr>
		</table>
	</form>
</div>
