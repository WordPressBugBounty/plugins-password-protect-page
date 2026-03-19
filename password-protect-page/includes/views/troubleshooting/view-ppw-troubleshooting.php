<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Troubleshooting Tab  
 */
?>
<div class="ppw_main_container wp_troubleshoot_tab">
    <table class="ppwp_settings_table" cellpadding="4">
        <tbody>
            <tr>
                <td colspan="2">
                    <div class="ppw-troubleshoot-header">
                        <h3><?php esc_html_e( 'WordPress password protected page not working', 'password-protect-page' ); ?></h3>
                        <span><?php esc_html_e( 'Please follow the troubleshooting process below in case the password protection is not working on your website.', 'password-protect-page' ); ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="feature-input"><span class="feature-input"></span></td>
                <td>
                    <h2><?php esc_html_e( 'Which types of content do you want to password protect?', 'password-protect-page' ); ?></h2>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="ppw-radio-container">
                        <input type="radio" name="level0" value="A" id="A" />
                        <label for="A">Entire site</label>
                        <div class="sub">
	                        <p><?php esc_html_e( 'This feature is available on both PPWP Free and Pro versions.', 'password-protect-page' ); ?></p>
                            <p><?php esc_html_e( 'Check out ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                    href="https://passwordprotectwp.com/docs/password-protect-entire-wordpress-site/">
                                    <?php esc_html_e( 'Password Protect Entire WordPress Site', 'password-protect-page' ); ?></a>
                                    <?php esc_html_e( ' for more information.', 'password-protect-page' ); ?>
                            </p>
                        </div>
                    </div>
                    <div class="ppw-radio-container">
                        <input type="radio" name="level0" value="B" id="B" />
                        <label for="B"><?php esc_html_e( 'Individual content, e.g. posts and pages', 'password-protect-page' );  ?></label>
                        <div class="sub">
                            <h3><?php esc_html_e( 'Which post types do you want to protect?', 'password-protect-page' ); ?></h3>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="B0" id="B0" />
                                <label for="B0">Posts</label>
                                <div class="sub">
                                    <p><?php esc_html_e( 'Available on PPWP Free.', 'password-protect-page' ); ?></p>
                                </div>
                            </div>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="" id="CPT" />
                                <label for="CPT">Custom post types</label>
                                <div class="sub">
                                    <p><?php esc_html_e( 'Available on PPWP Pro.', 'password-protect-page' ); ?></p>
                                    <p><?php esc_html_e( 'Check out how to ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                            href="https://passwordprotectwp.com/docs/password-protect-wordpress-custom-post-types/">
                                            <?php esc_html_e( 'Password protect WordPress custom post types', 'password-protect-page' ); ?></a>.
                                    </p>
                                </div>
                            </div>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="B3" id="B3" />
                                <label for="B3"><?php esc_html_e( 'Pages or content created by Page builder plugins', 'password-protect-page' ); ?></label>
                                <div class="sub">
                                    <h3><?php esc_html_e( 'Which template are you using?', 'password-protect-page' ); ?></h3>
                                    <p style="margin-bottom: 1rem"><?php esc_html_e( 'To determine what template you\'re using, check out selected template under “Page Attributes” section in editor page.', 'password-protect-page' ); ?></p>
                                    <div class="sub-detail">
                                        <input type="radio" name="level3" value="B5" id="B5" />
                                        <label for="B5">Custom template</label>
                                        <div class="sub">
	                                        <p><?php esc_html_e( 'WordPress and our plugin’s password protection feature works well with the default
                                                WordPress template, making use of the_content(), but not on a custom page template. Check out ', 'password-protect-page' ); ?>
                                                <a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/password-protect-wordpress-custom-page-template/">
                                                    <?php esc_html_e( 'how to password protect WordPress custom page template', 'password-protect-page' ); ?></a>
                                                    <?php esc_html_e( ' for more information.', 'password-protect-page' ); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="sub-detail">
                                        <input type="radio" name="level3" value="B6" id="B6" />
                                        <label for="B6"><?php esc_html_e( 'WordPress default template', 'password-protect-page' ); ?></label>
                                        <div class="sub">
                                            <p><?php esc_html_e( 'It should be working with our PPWP Free version. Otherwise, please ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                                    href="https://passwordprotectwp.com/support/"><?php esc_html_e( 'drop us a support request', 'password-protect-page' ); ?></a>.
                                                <?php esc_html_e( ' We will be more than happy to help.', 'password-protect-page' ); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ppw-radio-container">
                        <input type="radio" name="level0" value="B0" id="ACF" />
                        <label for="ACF"><?php esc_html_e( 'ACF & WordPress custom fields', 'password-protect-page' ); ?></label>
                        <div class="sub">
                            <h3><?php esc_html_e( 'How do you want to protect your custom fields content?', 'password-protect-page' ); ?></h3>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="C0" id="C0" />
                                <label for="C0"><?php esc_html_e( 'Hide the entire custom fields under password form', 'password-protect-page' ); ?></label>
                                <div class="sub2">
                                    <p><?php esc_html_e( 'This feature is available on both PPWP Free and Pro versions.', 'password-protect-page' ); ?></p>
                                    <p>
                                        <?php esc_html_e( 'By default, WordPress in general and our PPWP plugin in particular only protect
                                        a post’s content and excerpt. Their custom field data is not protected. In order
                                        to hide custom fields under password form, check out', 'password-protect-page' ); ?>
                                        <a target="_blank" rel="noopener"
                                            href="https://passwordprotectwp.com/docs/password-protect-wordpress-custom-page-template/">
                                            <?php esc_html_e( 'Password Protect WordPress Custom Fields', 'password-protect-page' ); ?></a><?php esc_html_e( ' for more information.', 'password-protect-page' ); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="C1" id="C1" />
                                <label for="C1"><?php esc_html_e( 'Protect part of custom field content', 'password-protect-page' ); ?></label>
                                <div class="sub2">
                                    <p><?php esc_html_e( 'This feature is available on PPWP Pro only.', 'password-protect-page' ); ?></p>
                                    <p><?php esc_html_e( 'You will need to use our shortcode under your custom fields. Check out ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                            href="https://passwordprotectwp.com/docs/how-to-password-protect-partial-content-custom-fields/">
                                            <?php esc_html_e( ' How to Password Protect Partial Content under Custom Fields', 'password-protect-page'); ?></a>
                                            <?php esc_html_e( ' for more information.', 'password-protect-page' ); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ppw-radio-container">
                        <input type="radio" name="level0" value="C" id="C" />
                        <label for="C"><?php esc_html_e( 'Part of content, e.g. some content sections', 'password-protect-page' ); ?></label>
                        <div class="sub1">
                            <h3><?php esc_html_e( 'Where do you use our shortcode?', 'password-protect-page' ); ?></h3>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="C0" id="PC0" />
                                <label for="PC0">Page builders</label>
                                <div class="sub2">
                                    <p><?php esc_html_e( 'Our shortcode is supported on top page builders such as Elementor, Beaver Builder
                                        and Divi Builder. Check out ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                            href="https://passwordprotectwp.com/docs/protect-partial-content-page-builders/">
                                            <?php esc_html_e( 'how to password protect parts of content with WordPress page builders', 'password-protect-page' ); ?></a>
                                            <?php esc_html_e( ' for more information.', 'password-protect-page' ); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="C1" id="PC1" />
                                <label for="PC1">Custom post types</label>
                                <div class="sub2">
                                    <p><?php esc_html_e( 'Available on PPWP Pro.', 'password-protect-page' ); ?></p>
                                    <p><?php esc_html_e( 'Check this out on how to ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                            href="https://passwordprotectwp.com/docs/password-protect-wordpress-custom-post-types/">
                                            <?php esc_html_e( 'Password protect WordPress custom post types', 'password-protect-page' ); ?></a>.
                                    </p>
                                </div>
                            </div>
                            <div class="sub-detail">
                                <input type="radio" name="level1" value="C2" id="C2" />
                                <label for="C2"><?php esc_html_e( 'ACF or WordPress custom fields', 'password-protect-page' ); ?></label>
                                <div class="sub2">
                                    <p><?php esc_html_e( 'Available on PPWP Pro.','password-protect-page' ); ?></p>
                                    <p><?php esc_html_e( 'In order to place our shortcode under custom fields, check out ', 'password-protect-page' ); ?><a target="_blank" rel="noopener"
                                            href="https://passwordprotectwp.com/docs/how-to-password-protect-partial-content-custom-fields/">
                                            <?php esc_html_e( 'How to Password Protect Partial Content under Custom Fields', 'password-protect-page' ); ?></a>
                                            <?php esc_html_e( ' for more information.', 'password-protect-page' ); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="feature-input"><span class="feature-input"></span></td>
                <td>
                    <h2>Checklist</h2>
                </td>
            <tr>
                <td></td>
                <td>
                    <div class="ppw_troubleshoot_checklist">
                        <ul>
                            <li><?php echo wp_kses_post( __( 'Are you using <strong>the latest version</strong> of both Password Protect WordPress (PPWP) Free and Pro?', 'password-protect-page' ) ); ?>
                            </li>
                            <li><?php esc_html_e( 'Are you using any caching methods?', 'password-protect-page' ); ?></li>
                            <li style="list-style: none"><?php esc_html_e( 'By default, both our Free and Pro versions ', 'password-protect-page' ); ?>
                                <a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/caching-plugins-cache-servers-integration/?utm_source=user-website&utm_medium=settings-troubleshooting-tab&utm_campaign=ppwp-free">
                                    <?php esc_html_e( 'work well with popular cache plugins', 'password-protect-page' ); ?></a>,
                                    <?php esc_html_e( 'including W3 Total Cache, WP Super Cache, and WP Fastest Cache.
                                    If you are using other cache plugins or server caching, please exclude our plugins
                                    cookies from being cached or ','password-protect-page' ) ?><a target="_blank" rel="noopener" href="https://passwordprotectwp.com/support/?utm_source=user-website&utm_medium=settings-troubleshooting-tab&utm_campaign=ppwp-free">
                                    <?php esc_html_e( 'contact us for support', 'password-protect-page' ); ?></a>.
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="feature-input"><span class="feature-input"></span></td>
                <td>
                    <h2><?php esc_html_e( 'Other Questions', 'password-protect-page' ); ?></h2>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="ppw-collapse-question">
                        <div class="ppw-collapse-title">
                            <?php esc_html_e( 'What if I want to show excerpts & featured images of protected content?' , 'password-protect-page' ); ?>
                        </div>
                        <div class="ppw-collapse-content">
                            <p><?php esc_html_e( 'Available on PPWP Pro. You will need to modfiy your child theme code with our Free version.', 'password-protect-page' ); ?>
                            </p>
                            <p><?php esc_html_e( 'Most of WordPress themes will hide the post excerpt and featured image of password protected content by default. Check out ', 'password-protect-page' ); ?>
                                <a target="_blank" rel="noopener"
                                    href="https://passwordprotectwp.com/docs/display-featured-image-password-protected-excerpt/">
                                    <?php esc_html_e( 'how to show exceprt and featured image of password protected content', 'password-protect-page' ); ?></a>.
                            </p>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>