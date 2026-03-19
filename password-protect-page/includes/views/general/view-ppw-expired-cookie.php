<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
$password_cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
$time                    = 7;
$units                   = 'days';
$one_year                = 365;
$max                     = $one_year;
if ( ! empty( $password_cookie_expired ) ) {
	$tmp = explode( ' ', $password_cookie_expired );
	if ( count( $tmp ) === 2 ) {
		$time  = (int) $tmp[0];
		$units = $tmp[1];
		switch ( $units ) {
			case 'hours':
				$max = $one_year * 24;
				break;
			case 'minutes':
				$max = $one_year * 24 * 60;
				break;
			case 'seconds':
				$max = $one_year * 24 * 60 * 60;
				break;
			default:
				$max = $one_year;
		}
	}
}
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Cookie Expiration Time', 'password-protect-page' ); ?></label>
			<?php 
			// phpcs:disable
            /* translators: %1$s opens the link tag, %2$s closes the link tag. Explains session cookie behavior. */
			printf(
				    wp_kses_post(
				        __( 'By default, users won’t have to re-enter passwords until its cookie expires. You can also %1$suse session cookies%2$s to log users out right after they close the browser.', 'password-protect-page' )
					    ),
					    '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/?utm_source=user-website&utm_medium=settings-general-tab&utm_campaign=ppwp-free#cookies">',
					    '</a>'
				); 
			// phpcs:enable
			?>
		</p>
		<input required value="<?php echo esc_attr( $time ); ?>" class="wpp_time_number" type="number"
		       id="wpp_password_cookie_times" min="1" max="<?php echo esc_attr( $max ); ?>"/>
		<select id="wpp_password_cookie_units" class="wpp_password_cookie_units">
			<option value="days" <?php if ( 'days' === $units ) {
				echo 'selected';
			} ?>><?php echo esc_html__( 'Days', 'password-protect-page' ); ?></option>
			<option value="hours" <?php if ( 'hours' === $units ) {
				echo 'selected';
			} ?> ><?php echo esc_html__( 'Hours', 'password-protect-page' ); ?>
			</option>
			<option value="minutes" <?php if ( 'minutes' === $units ) {
				echo 'selected';
			} ?> ><?php echo esc_html__( 'Minutes', 'password-protect-page' ); ?>
			</option>
			<option value="seconds" <?php if ( 'seconds' === $units ) {
				echo 'selected';
			} ?> ><?php echo esc_html__( 'Seconds', 'password-protect-page' ); ?>
			</option>
		</select>
	</td>
</tr>
