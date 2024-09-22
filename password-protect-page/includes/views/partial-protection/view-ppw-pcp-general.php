<?php

// translators: %s: Link to documentation.
$page_builder_link = sprintf(
	'<a target="_blank" rel="noopener" href="%s">work seamlessly with top page builders</a>',
	'https://passwordprotectwp.com/docs/protect-partial-content-page-builders/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free'
);
$page_builder_desc = sprintf(
	'Alternatively, use our built-in blocks for popular page builders, e.g. %s and %s.',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/password-protect-partial-content-elementor/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free">Elementor</a>',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/protect-partial-content-page-builders/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free#bb">Beaver Builder</a>'
);

// translators: %s: Link to documentation.
$pcp_desc = sprintf(
	'To track Partial Content Protection (PCP) password usage, please get %s and use %s instead.',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/extensions/password-statistics/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free">Statistics addon</a>',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free">PCP global passwords</a>'
);

// translators: %s: Link to documentation.
$pcp_notice = sprintf(
	'Use %s to %s.',
	'<a target="_blank" rel="noopener" href="' . admin_url( 'customize.php?autofocus[panel]=ppwp_pcp' ) . '">WordPress Customizer</a>',
	'<a target="_blank" href="https://passwordprotectwp.com/docs/customize-pcp-form-wordpress-customizer/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free" rel="noopener">customize PCP password form</a>'
);
$_get                       = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We no need to handle nonce verification for render UI.
$page                       = isset( $_get['page'] ) ? $_get['page'] : null;
$tab                        = isset( $_get['tab'] ) ? $_get['tab'] : null;
$message 					= esc_html__( 'Great! You’ve successfully copied the shortcode to clipboard.', PPW_Constants::DOMAIN );
$use_shortcode_page_builder = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_SHORTCODE_PAGE_BUILDER, PPW_Constants::SHORTCODE_OPTIONS ) ? 'checked' : '';
?>
<div class="ppw_main_container" id="ppw_shortcodes_form">
	<form id="wpp_shortcode_form" method="post">
		<table class="ppw-pcp-settings ppwp_settings_table" cellpadding="4">
			<tr>
				<td>
					<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::USE_SHORTCODE_PAGE_BUILDER ); ?>">
						<input type="checkbox"
						       id="<?php echo esc_attr( PPW_Constants::USE_SHORTCODE_PAGE_BUILDER ); ?>" <?php echo esc_html( $use_shortcode_page_builder ); ?>>
						<span class="pda-slider round"></span>
					</label>
				</td>
				<td>
					<p>
						<label><?php esc_html_e( 'Use Shortcode within Page Builders', PPW_Constants::DOMAIN ) ?></label>
						<?php esc_html_e( 'Allow our shortcode to', PPW_Constants::DOMAIN ) ?>
						<?php echo $page_builder_link; // phpcs:ignore -- There are no value to escape on $page_builder_link ?><?php esc_html_e( ' without breaking the page structure.', PPW_Constants::DOMAIN ) ?>
					</p>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
				</td>
			</tr>
		</table>
	</form>
	<?php if ( PPW_Asset_Services::is_partial_protection_submenu( $page, $tab ) ) { ?>
		<div>
			<div>
				<h2 style="margin-top: 0;">[ppwp] Shortcode</h2>
				<p>
					<?php esc_html_e( 'Use the following shortcode to', PPW_Constants::DOMAIN ) ?>
					<a target="_blank" rel="noopener"
					   href="https://passwordprotectwp.com/docs/password-protect-wordpress-content-sections/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free">
						<?php esc_html_e( 'lock parts of your content', PPW_Constants::DOMAIN ) ?></a>.
					<?php echo $page_builder_desc; // phpcs:ignore -- There are no value to escape on $page_builder_desc ?>
				</p>
				<p><?php echo $pcp_desc; // phpcs:ignore -- There are no value to escape on $pcp_desc ?></p>
				<p><?php echo $pcp_notice; // phpcs:ignore -- There are no value to escape on $pcp_notice?></p>
				<div class="ppwp-shortcodes-wrap">
					<textarea
							onclick="ppwUtils.copy('ppwp-shortcode', '<?php echo esc_attr__( $message, PPW_Constants::DOMAIN ); ?>', '<?php echo esc_html__('PPWP Lite', PPW_Constants::DOMAIN); ?>')"
							id="ppwp-shortcode" style="width: 100%" rows="3" cols="50" readonly>[ppwp passwords="password1 password2" whitelisted_roles="administrator, editor"]&#13;&#10;<?php  esc_html_e('Your protected content',PPW_Constants::DOMAIN); ?>&#13;&#10;[/ppwp]</textarea>
				</div>
			</div>
			<div>
				<h2><?php esc_html_e('Shortcode Attributes',PPW_Constants::DOMAIN)?></h2>
				<p> <?php esc_html_e('Below are all attributes available with this shortcode. It\'s important to note that the shortcode is
					valid as long as it includes ',PPW_Constants::DOMAIN)?><b><?php esc_html_e('at least',PPW_Constants::DOMAIN)?></b> <?php esc_html_e('one of the',PPW_Constants::DOMAIN)?>  <code><?php esc_html_e('required*',PPW_Constants::DOMAIN);?></code> <?php esc_html_e('attributes.',PPW_Constants::DOMAIN);?></p>
				<div>
					<table class="ppw-shortcode-opt-table wp-list-table widefat fixed striped posts">
						<thead>
						<tr>
							<th><?php esc_html_e('Attribute name',PPW_Constants::DOMAIN);?></th>
							<th><?php esc_html_e('Possible & Default values',PPW_Constants::DOMAIN)?></th>
							<td></td>
						</thead>
						<tbody>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('passwords',PPW_Constants::DOMAIN)?></code>
								<p class="description"> <?php esc_html_e('Shortcode',PPW_Constants::DOMAIN);?> <a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#define">
										<?php esc_html_e('Inline passwords,',PPW_Constants::DOMAIN)?></a><?php esc_html_e(' which are used to unlock the protected section',PPW_Constants::DOMAIN)?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e( 'Each password is case-sensitive and no more than 100 characters, but does not contain [, ], ", \' and space characters.', PPW_Constants::DOMAIN ); ?>

									</li>
									<li><?php esc_html_e('Password(s) are separated by space(s)',PPW_Constants::DOMAIN);?></li>
								</ul>
							</td>
							<td><?php esc_html_e('required*',PPW_Constants::DOMAIN)?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><? esc_html_e('pwd',PPW_Constants::DOMAIN);?></code>
								<p class="description"><?php esc_html_e('ID-based ',PPW_Constants::DOMAIN);?><a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#id"><?php esc_html_e('Shortcode Global passwords',PPW_Constants::DOMAIN);?></a></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Available in PPWP Pro only',PPW_Constants::DOMAIN);?></li>
									<li><?php esc_html_e('ID(s) are separated by comma(s)','');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('required*',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('pwd_label',PPW_Constants::DOMAIN);?></code>
								<p class="description"><?php esc_html_e('Label-based',PPW_Constants::DOMAIN)?> <a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#label"><?php esc_html_e('shortcode Global passwords',PPW_Constants::DOMAIN);?></a></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Available in PPWP Pro only',PPW_Constants::DOMAIN);?></li>
									<li><?php esc_html_e('Label(s) separated by comma(s)',PPW_Constants::DOMAIN)?></li>
								</ul>
							</td>
							<td><?php esc_html_e('required*',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('whitelisted_roles',PPW_Constants::DOMAIN)?></code>
								<p class="description"><?php esc_html_e('Define who can access protected sections directly without entering a password',PPW_Constants::DOMAIN)?></p>
							</td>
							<td><?php esc_html_e('Options: administrator, editor, author, contributor, subscriber',PPW_Constants::DOMAIN);?></td>
							<td><?php esc_html_e('optional',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('whitelisted_users',PPW_Constants::DOMAIN);?></code>
								<p class="description"><?php esc_html_e('Define who can access protected sections directly without entering a password',PPW_Constants::DOMAIN);?></p>
							</td>
							<td><?php esc_html_e('Options: By username',PPW_Constants::DOMAIN);?></td>
							<td><?php esc_html_e('optional',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('hidden_form_text',PPW_Constants::DOMAIN);?></code>
								<p class="description"><a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#label"><?php esc_html_e('Hide	password form',PPW_Constants::DOMAIN);?></a><?php esc_html_e(' or display a text instead');?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Available in PPWP Pro only',PPW_Constants::DOMAIN);?></li>
									<li><?php esc_html_e('Empty value or text',PPW_Constants::DOMAIN);?></li>
									<li><?php esc_html_e('Accept HTML tags',PPW_Constants::DOMAIN)?></li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">on</code>
								<p class="description"><?php esc_html_e('Show protected content automatically at a set time until the “off” time',PPW_Constants::DOMAIN);?></p>
							</td>
							<td>
								<ul>
								<li><?php esc_html_e('Format:')?> <code><?php esc_html_e('Y-m-d h:i:sa',PPW_Constants::DOMAIN);?></code></li>
									<li><?php esc_html_e('Sample: 2020/10/20 14:00:00',PPW_Constants::DOMAIN);?></li>
									<li><?php esc_html_e('Without "off" attribute,  the content will be public since the “on” time',PPW_Constants::DOMAIN);?> </li>
								</ul>
							</td>
							<td><?php esc_html_e('optional',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('off',PPW_Constants::DOMAIN);?></code>
								<p class="description"><?php esc_html_e('Stop showing protected content without entering passwords','passwordprotectwp');?></p>
							</td>

							<td>
								<ul>
									<li><?php esc_html_e('Format:',PPW_Constants::DOMAIN);?> <code><?php esc_html_e('Y-m-d h:i:sa',PPW_Constants::DOMAIN); ?></code></li>
									<li><?php esc_html_e('Sample: 2020/10/30 14:00:00',PPW_Constants::DOMAIN);?></li>
									<li><?php esc_html_e('Require "on" attribute',PPW_Constants::DOMAIN);?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('headline');?></code>
								<p class="description"><?php esc_html_e('Headline of the password form',PPW_Constants::DOMAIN);?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Default: ',PPW_Constants::DOMAIN); ?><code><?php esc_html_e('Restricted Content',PPW_Constants::DOMAIN);?></code></li>
									<li><?php  esc_html_e('Accept HTML tags',PPW_Constants::DOMAIN);?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional',PPW_Constants::DOMAIN);?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('description',PPW_Constants::DOMAIN)?></code>
								<p class="description"><?php esc_html_e('Description above password form',PPW_Constants::DOMAIN);?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Default: ',PPW_Constants::DOMAIN);?><code><?php esc_html_e('To view this protected content, enter the password below:',PPW_Constants::DOMAIN);?></code>
								
									<li><?php esc_html_e('Accept HTML tags',PPW_Constants::DOMAIN);?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('desc_below_form',PPW_Constants::DOMAIN);?></code>
								<p class="description"><?php esc_html_e('Description below password form',PPW_Constants::DOMAIN);?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Default:',PPW_Constants::DOMAIN);?> <code><?php esc_html_e('empty',PPW_Constants::DOMAIN);?></code></li>
									<li><?php esc_html_e('Accept HTML tags',PPW_Constants::DOMAIN);?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('desc_above_btn',PPW_Constants::DOMAIN); ?> </code>
								<p class="description"><?php esc_html_e('Description above password form submit button',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e(' Default:', PPW_Constants::DOMAIN);?>  <code><?php  esc_html_e('empty', 'password-protect-page ');?> </code></li>
									<li><?php  esc_html_e('Accept HTML tags (Inline)', PPW_Constants::DOMAIN); ?></li>
								</ul>
							</td>
							<td><?php  esc_html_e('optional', PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('label', PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Label of the password field',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<?php  esc_html_e('Default:', PPW_Constants::DOMAIN); ?> <code><?php  esc_html_e('Password:',PPW_Constants::DOMAIN); ?></code>
							</td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('placeholder',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Placeholder of the password field',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<?php  esc_html_e('Default:',PPW_Constants::DOMAIN); ?> <code><?php  esc_html_e('empty',PPW_Constants::DOMAIN); ?></code>
							</td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('loading',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Loading text of the password form',PPW_Constants::DOMAIN); ?>?></p>
							</td>
							<td>
								<?php  esc_html_e('Default: ',PPW_Constants::DOMAIN); ?><code><?php  esc_html_e('Loading...',PPW_Constants::DOMAIN); ?></code>
							</td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('button',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Button text of the password form',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<?php  esc_html_e('Default: ',PPW_Constants::DOMAIN); ?><code><?php  esc_html_e('Enter',PPW_Constants::DOMAIN); ?></code>
							</td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('error_msg',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('The message which is shown when users enter a wrong password',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Default:',PPW_Constants::DOMAIN); ?> <code><?php  esc_html_e('Please enter the correct password!',PPW_Constants::DOMAIN); ?></code></li>
									<li><?php  esc_html_e('Accept HTML tags',PPW_Constants::DOMAIN); ?></li>
								</ul>
							</td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('cookie',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Set cookie expiration time',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Available in PPWP Pro only',PPW_Constants::DOMAIN); ?></li>
									<li><?php  esc_html_e('Count by hours',PPW_Constants::DOMAIN); ?></li>
								</ul>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('download_limit',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Set the maximum number of times users can',PPW_Constants::DOMAIN); ?> <a target="_blank"  href="https://passwordprotectwp.com/docs/how-to-password-protect-files-in-content/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#download-limit"><?php  esc_html_e('download a file embedded into content',PPW_Constants::DOMAIN); ?></a></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Available in PPWP Pro only',PPW_Constants::DOMAIN); ?></li>
									<li><?php  esc_html_e('Count by clicks',PPW_Constants::DOMAIN); ?></li>
								</ul>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('class',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Style the password form based on class',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td><?php  esc_html_e('CSS class name(s) separated by space(s)',PPW_Constants::DOMAIN); ?></td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('id',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Style the password form based on id',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td><?php  esc_html_e('Default: ',PPW_Constants::DOMAIN); ?><code><?php  esc_html_e('empty',PPW_Constants::DOMAIN); ?></code></td>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('acf_field',PPW_Constants::DOMAIN); ?></code>
								<p class="description"><?php  esc_html_e('Add',PPW_Constants::DOMAIN); ?> <a target="_blank" href="https://passwordprotectwp.com/docs/add-additional-fields-pcp-form/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free"><?php  esc_html_e('additional fields',PPW_Constants::DOMAIN); ?></a><?php  esc_html_e(' to PCP password form',PPW_Constants::DOMAIN); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Default: ',PPW_Constants::DOMAIN); ?><code><?php  esc_html_e('empty',PPW_Constants::DOMAIN); ?></code></li>
									<li><?php  esc_html_e('Available in PPWP Suite only',PPW_Constants::DOMAIN); ?></li>
								</ul>
							<td><?php  esc_html_e('optional',PPW_Constants::DOMAIN); ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
