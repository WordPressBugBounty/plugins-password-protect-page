<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
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
$message 					= esc_html__( 'Great! You’ve successfully copied the shortcode to clipboard.', 'password-protect-page' );
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
						<label><?php esc_html_e( 'Use Shortcode within Page Builders', 'password-protect-page' ) ?></label>
						<?php esc_html_e( 'Allow our shortcode to', 'password-protect-page' ) ?>
						<?php echo $page_builder_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( ' without breaking the page structure.', 'password-protect-page' ) ?>
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
					<?php esc_html_e( 'Use the following shortcode to', 'password-protect-page' ) ?>
					<a target="_blank" rel="noopener"
					   href="https://passwordprotectwp.com/docs/password-protect-wordpress-content-sections/?utm_source=user-website&utm_medium=pcp-general-tab&utm_campaign=ppwp-free">
						<?php esc_html_e( 'lock parts of your content', 'password-protect-page' ) ?></a>.
					<?php echo $page_builder_desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
				<p><?php echo $pcp_desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<p><?php echo $pcp_notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?></p>
				<div class="ppwp-shortcodes-wrap">
					<textarea
							onclick="ppwUtils.copy('ppwp-shortcode', '<?php echo esc_attr($message); ?>', '<?php echo esc_html__('PPWP Lite', 'password-protect-page'); ?>')"
							id="ppwp-shortcode" style="width: 100%" rows="3" cols="50" readonly>[ppwp passwords="password1 password2" whitelisted_roles="administrator, editor"]&#13;&#10;<?php  esc_html_e('Your protected content','password-protect-page'); ?>&#13;&#10;[/ppwp]</textarea>
				</div>
			</div>
			<div>
				<h2><?php esc_html_e('Shortcode Attributes','password-protect-page')?></h2>
				<p> <?php esc_html_e('Below are all attributes available with this shortcode. It\'s important to note that the shortcode is
					valid as long as it includes ','password-protect-page')?><b><?php esc_html_e('at least','password-protect-page');?></b> <?php esc_html_e('one of the','password-protect-page');?>  <code><?php esc_html_e('required*','password-protect-page');?></code> <?php esc_html_e('attributes.','password-protect-page');?></p>
				<div>
					<table class="ppw-shortcode-opt-table wp-list-table widefat fixed striped posts">
						<thead>
						<tr>
							<th><?php esc_html_e('Attribute name','password-protect-page');?></th>
							<th><?php esc_html_e('Possible & Default values','password-protect-page');?></th>
							<td></td>
						</thead>
						<tbody>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('passwords','password-protect-page');?></code>
								<p class="description"> <?php esc_html_e('Shortcode','password-protect-page');?> <a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#define">
										<?php esc_html_e('Inline passwords,','password-protect-page');?></a><?php esc_html_e(' which are used to unlock the protected section','password-protect-page');?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e( 'Each password is case-sensitive and no more than 100 characters, but does not contain [, ], ", \' and space characters.', 'password-protect-page' ); ?>

									</li>
									<li><?php esc_html_e('Password(s) are separated by space(s)','password-protect-page');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('required*','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('pwd','password-protect-page');?></code>
								<p class="description"><?php esc_html_e('ID-based ','password-protect-page');?><a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#id"><?php esc_html_e('Shortcode Global passwords','password-protect-page');?></a></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Available in PPWP Pro only','password-protect-page');?></li>
									<li><?php esc_html_e('ID(s) are separated by comma(s)','password-protect-page');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('required*','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('pwd_label','password-protect-page');?></code>
								<p class="description"><?php esc_html_e('Label-based','password-protect-page');?> <a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#label"><?php esc_html_e('shortcode Global passwords','password-protect-page');?></a></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Available in PPWP Pro only','password-protect-page');?></li>
									<li><?php esc_html_e('Label(s) separated by comma(s)','password-protect-page')?></li>
								</ul>
							</td>
							<td><?php esc_html_e('required*','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('whitelisted_roles','password-protect-page')?></code>
								<p class="description"><?php esc_html_e('Define who can access protected sections directly without entering a password','password-protect-page')?></p>
							</td>
							<td><?php esc_html_e('Options: administrator, editor, author, contributor, subscriber','password-protect-page');?></td>
							<td><?php esc_html_e('optional','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('whitelisted_users','password-protect-page');?></code>
								<p class="description"><?php esc_html_e('Define who can access protected sections directly without entering a password','password-protect-page');?></p>
							</td>
							<td><?php esc_html_e('Options: By username','password-protect-page');?></td>
							<td><?php esc_html_e('optional','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('hidden_form_text','password-protect-page');?></code>
								<p class="description"><a target="_blank" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#label"><?php esc_html_e('Hide	password form','password-protect-page');?></a><?php esc_html_e(' or display a text instead','password-protect-page');?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Available in PPWP Pro only','password-protect-page');?></li>
									<li><?php esc_html_e('Empty value or text','password-protect-page');?></li>
									<li><?php esc_html_e('Accept HTML tags','password-protect-page')?></li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">on</code>
								<p class="description"><?php esc_html_e('Show protected content automatically at a set time until the “off” time','password-protect-page');?></p>
							</td>
							<td>
								<ul>
								<li><?php esc_html_e('Format:','password-protect-page')?> <code><?php esc_html_e('Y-m-d h:i:sa','password-protect-page');?></code></li>
									<li><?php esc_html_e('Sample: 2020/10/20 14:00:00','password-protect-page');?></li>
									<li><?php esc_html_e('Without "off" attribute,  the content will be public since the “on” time','password-protect-page');?> </li>
								</ul>
							</td>
							<td><?php esc_html_e('optional','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('off','password-protect-page');?></code>
								<p class="description"><?php esc_html_e('Stop showing protected content without entering passwords','password-protect-page');?></p>
							</td>

							<td>
								<ul>
									<li><?php esc_html_e('Format:','password-protect-page');?> <code><?php esc_html_e('Y-m-d h:i:sa','password-protect-page'); ?></code></li>
									<li><?php esc_html_e('Sample: 2020/10/30 14:00:00','password-protect-page');?></li>
									<li><?php esc_html_e('Require "on" attribute','password-protect-page');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('headline','password-protect-page');?></code>
								<p class="description"><?php esc_html_e('Headline of the password form','password-protect-page');?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Default: ','password-protect-page'); ?><code><?php esc_html_e('Restricted Content','password-protect-page');?></code></li>
									<li><?php  esc_html_e('Accept HTML tags','password-protect-page');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('description','password-protect-page')?></code>
								<p class="description"><?php esc_html_e('Description above password form','password-protect-page');?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Default: ','password-protect-page');?><code><?php esc_html_e('To view this protected content, enter the password below:','password-protect-page');?></code>
								
									<li><?php esc_html_e('Accept HTML tags','password-protect-page');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('desc_below_form','password-protect-page');?></code>
								<p class="description"><?php esc_html_e('Description below password form','password-protect-page');?></p>
							</td>
							<td>
								<ul>
									<li><?php esc_html_e('Default:','password-protect-page');?> <code><?php esc_html_e('empty','password-protect-page');?></code></li>
									<li><?php esc_html_e('Accept HTML tags','password-protect-page');?></li>
								</ul>
							</td>
							<td><?php esc_html_e('optional','password-protect-page');?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php esc_html_e('desc_above_btn','password-protect-page'); ?> </code>
								<p class="description"><?php esc_html_e('Description above password form submit button','password-protect-page'); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e(' Default:', 'password-protect-page');?>  <code><?php  esc_html_e('empty', 'password-protect-page');?> </code></li>
									<li><?php  esc_html_e('Accept HTML tags (Inline)', 'password-protect-page'); ?></li>
								</ul>
							</td>
							<td><?php  esc_html_e('optional', 'password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('label', 'password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Label of the password field','password-protect-page'); ?></p>
							</td>
							<td>
								<?php  esc_html_e('Default:', 'password-protect-page'); ?> <code><?php  esc_html_e('Password:','password-protect-page'); ?></code>
							</td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('placeholder','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Placeholder of the password field','password-protect-page'); ?></p>
							</td>
							<td>
								<?php  esc_html_e('Default:','password-protect-page'); ?> <code><?php  esc_html_e('empty','password-protect-page'); ?></code>
							</td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('loading','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Loading text of the password form','password-protect-page'); ?>?></p>
							</td>
							<td>
								<?php  esc_html_e('Default: ','password-protect-page'); ?><code><?php  esc_html_e('Loading...','password-protect-page'); ?></code>
							</td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('button','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Button text of the password form','password-protect-page'); ?></p>
							</td>
							<td>
								<?php  esc_html_e('Default: ','password-protect-page'); ?><code><?php  esc_html_e('Enter','password-protect-page'); ?></code>
							</td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('error_msg','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('The message which is shown when users enter a wrong password','password-protect-page'); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Default:','password-protect-page'); ?> <code><?php  esc_html_e('Please enter the correct password!','password-protect-page'); ?></code></li>
									<li><?php  esc_html_e('Accept HTML tags','password-protect-page'); ?></li>
								</ul>
							</td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('cookie','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Set cookie expiration time','password-protect-page'); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Available in PPWP Pro only','password-protect-page'); ?></li>
									<li><?php  esc_html_e('Count by hours','password-protect-page'); ?></li>
								</ul>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('download_limit','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Set the maximum number of times users can','password-protect-page'); ?> <a target="_blank"  href="https://passwordprotectwp.com/docs/how-to-password-protect-files-in-content/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free#download-limit"><?php  esc_html_e('download a file embedded into content','password-protect-page'); ?></a></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Available in PPWP Pro only','password-protect-page'); ?></li>
									<li><?php  esc_html_e('Count by clicks','password-protect-page'); ?></li>
								</ul>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('class','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Style the password form based on class','password-protect-page'); ?></p>
							</td>
							<td><?php  esc_html_e('CSS class name(s) separated by space(s)','password-protect-page'); ?></td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('id','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Style the password form based on id','password-protect-page'); ?></p>
							</td>
							<td><?php  esc_html_e('Default: ','password-protect-page'); ?><code><?php  esc_html_e('empty','password-protect-page'); ?></code></td>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr"><?php  esc_html_e('acf_field','password-protect-page'); ?></code>
								<p class="description"><?php  esc_html_e('Add','password-protect-page'); ?> <a target="_blank" href="https://passwordprotectwp.com/docs/add-additional-fields-pcp-form/?utm_source=user-website&utm_medium=pcp-shortcode-attributes-list&utm_campaign=ppwp-free"><?php  esc_html_e('additional fields','password-protect-page'); ?></a><?php  esc_html_e(' to PCP password form','password-protect-page'); ?></p>
							</td>
							<td>
								<ul>
									<li><?php  esc_html_e('Default: ','password-protect-page'); ?><code><?php  esc_html_e('empty','password-protect-page'); ?></code></li>
									<li><?php  esc_html_e('Available in PPWP Suite only','password-protect-page'); ?></li>
								</ul>
							<td><?php  esc_html_e('optional','password-protect-page'); ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
