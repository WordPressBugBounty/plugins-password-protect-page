<?php
/**
 * General Configuration Settings
 */
$api_key       = PPW_Recaptcha::get_instance()->get_recaptcha_v3_api_key();
$api_key_v2    = PPW_Recaptcha::get_instance()->get_recaptcha_v2_api_key();
$api_secret    = PPW_Recaptcha::get_instance()->get_recaptcha_v3_api_secret();
$api_secret_v2 = PPW_Recaptcha::get_instance()->get_recaptcha_v2_api_secret();
$score         = PPW_Recaptcha::get_instance()->get_limit_score();

?>
<div class="ppw_main_container" id="ppw_shortcodes_form">
	<table class="ppwp_settings_table" cellpadding="4">
		<td colspan="2">
			<div style="margin-bottom: 1rem">
				<h3 style="text-transform: none; margin-bottom: 0.5rem">
				<?php echo esc_html__('Configure reCAPTCHA key',PPW_Constants::DOMAIN); ?></h3>
				<a rel="noopener" target="_blank" href="https://g.co/recaptcha/v3"><?php echo esc_html__('Get the Site Key and Secret Key',PPW_Constants::DOMAIN);?></a><?php echo esc_html__('from Google',PPW_Constants::DOMAIN)?> 
			</div>
		</td>
	</table>
	<form id="wpp_external_v3_form" method="post">
		<input type="hidden" id="ppw_general_form_nonce"
		       value="<?php echo esc_attr( wp_create_nonce( PPW_Constants::GENERAL_FORM_NONCE ) ); ?>"/>
		<table class="ppwp_settings_table" cellpadding="4">
			<tr id="wpp_recaptcha_configs">
				<td class="feature-input">
					<span class="feature-input"></span>
				</td> 
				<td>
					<p>
						<label><?php echo esc_html__( 'reCAPTCHA v3', PPW_Constants::DOMAIN ) ?></label>
					</p>
					<span>
					<p>
						<label><?php echo esc_html__( 'Site Key', PPW_Constants::DOMAIN ) ?></label>
					</p>
					<span class="ppwp-recaptcha-input">
                        <input id="<?php echo esc_attr( PPW_Constants::RECAPTCHA_API_KEY ); ?>" type="text"
                               value="<?php echo esc_attr( $api_key ); ?>"/>
							   <div id="ppwp-error-require-v3-key" style="display: none; color: red; position: absolute; font-size: 12px;">This field is required.</div>
					</span>
					<p>
						<label class="ppwp-title"><?php echo esc_html__( 'Secret Key', PPW_Constants::DOMAIN ) ?></label>
					</p>
					<span class="ppwp-recaptcha-input">
                        <input id="<?php echo esc_attr( PPW_Constants::RECAPTCHA_API_SECRET ); ?>" type="text"
                               value="<?php echo esc_attr( $api_secret ); ?>"/>
					</span>
					<div id="ppwp-error-require-v3-secret" style="display: none; color: red; position: absolute; font-size: 12px;">This field is required.</div>
					<p id="recaptcha-score-container">
						<label class="ppwp-title"><?php echo esc_html__( 'Threshold', PPW_Constants::DOMAIN ) ?></label>
							Define users' score that will pass reCAPTCHA protection
						<span class="ppw-recaptcha-score">
							<select class="ppw_main_container select"
							        id="<?php echo esc_attr( PPW_Constants::RECAPTCHA_SCORE ); ?>">
							  <?php
							  for ( $i = 0; $i <= 10; $i ++ ) {
								  $s        = number_format( ( $i / 10 ), 1 );
								  $selected = (double) $s === $score ? 'selected="selected"' : '';
								  echo '<option value="' . esc_attr( $s ) . '"' . esc_html( $selected ) . '>' . esc_html( $s ) . '</option>';
							  }
							  ?>
							</select>
						</span>
					</p>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<input type="submit" name="v3_submit_btn" id="submit" class="button button-primary v3_submit_btn" value="Save Changes">
				</td>
			</tr>
		</table>
	</form>
	<form id="wpp_external_v2_form" method="post">
		<table class="ppwp_settings_table" cellpadding="4">
			<tr id="wpp_recaptcha_configs">
				<td class="feature-input">
					<span class="feature-input"></span>
				</td>
				<td>
					<p>
						<label><?php echo esc_html__( 'reCAPTCHA v2 - Checkbox', PPW_Constants::DOMAIN ); ?></label>
					</p>
					<span>
					<p>
						<label><?php echo esc_html__( 'Site Key', PPW_Constants::DOMAIN ) ?></label>
					</p>
					<span class="ppwp-recaptcha-input">
                        <input id="<?php echo esc_attr( PPW_Constants::RECAPTCHA_V2_CHECKBOX_API_KEY ); ?>" type="text"
                               value="<?php echo esc_attr( $api_key_v2 ); ?>"/>
					</span>
					<div id="ppwp-error-require-v2-key" style="display: none; color: red; position: absolute; font-size: 12px;"><?php echo esc_html__('This field is required.',PPW_Constants::DOMAIN);?> </div>
					<p>
						<label class="ppwp-title"><?php echo esc_html__( 'Secret Key', PPW_Constants::DOMAIN ) ?></label>
					</p>
					<span class="ppwp-recaptcha-input">
                        <input id="<?php echo esc_attr( PPW_Constants::RECAPTCHA_V2_CHECKBOX_API_SECRET ); ?>" type="text"
                               value="<?php echo esc_attr( $api_secret_v2 ); ?>"/>
					</span>
					<div id="ppwp-error-require-v2-secret" style="display: none; color: red; position: absolute; font-size: 12px;"><?php echo esc_html__('This field is required.',PPW_Constants::DOMAIN);?></div>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<input type="submit" name="v2_submit_btn" id="submit" class="button button-primary recaptcha-btn" value="Save Changes">
				</td>
			</tr>
		</table>
	</form>
</div>
