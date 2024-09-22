<?php
/**
 * PPWP Countdown
 */
$text_above             = get_theme_mod('ppwp_sitewide_above_countdown');
$text_below             = get_theme_mod('ppwp_sitewide_below_countdown');
$start_date             = get_theme_mod('ppwp_sitewide_start_time');
$end_date               = get_theme_mod('ppwp_sitewide_end_time');
$is_date_countdown      = get_theme_mod('ppwp_sitewide_is_show_day');
$time_unit 				= get_theme_mod( 'ppwp_sitewide_time_unit_countdown', 'default');
$day_of_countdown 		= strip_tags(get_theme_mod( 'ppwp_countdown_day_text', 'Days' ));
$hour_of_countdown	 	= strip_tags(get_theme_mod( 'ppwp_countdown_hour_text', 'Hours' ));
$minute_of_countdown	= strip_tags(get_theme_mod( 'ppwp_countdown_minute_text', 'Mimutes' ));
$second_of_countdown	= strip_tags(get_theme_mod( 'ppwp_countdown_second_text', 'Seconds' ));
?>
<div class="ppwp-countdown-container">
    <div id="ppwp_desc_above_countdown"><?php echo wp_kses_post( $text_above ); ?></div>
    <div id="ppwp_sitewide_countdown" class="ppwp-sitewide-countdown"></div>
    <div id="ppwp_desc_below_countdown"><?php echo wp_kses_post( $text_below ); ?></div>
</div>
<script>
    const getCountdown = () => { 
        const datetime = new Date().toString();
        const utcTime = (function() {
            let utc = '';
            let operator = '';
            if (datetime.indexOf('+') >= 0) {
                utc = datetime.split('+');
                operator = '+';
            } else {
                utc = datetime.split('-');
                operator = '-';
            }
            const utcNumber = utc[1].split(' ')[0];
            const hour = Math.floor(parseInt(utcNumber)/100);
            const min  = (parseInt(utcNumber)%(hour*100))/60*100;
            return parseFloat(hour + '.' + min) - parseFloat("<?php echo get_option('gmt_offset'); ?>");
        })();
        const time_unit = "<?php echo esc_attr( $time_unit ); ?>"
        const end_date = "<?php echo esc_attr( $end_date ); ?>";
        const countDownDate = new Date(end_date).getTime();
        const start_date = "<?php echo esc_attr( $start_date ); ?>";
        const countDownDateStart = new Date(start_date).getTime();
        var getNow = new Date().getTime() - utcTime*3600*1000;
        const isShowCountdown = "<?php echo esc_attr( $is_show_countdown ); ?>";
        const isShowDateCountdown = "<?php echo esc_attr( $is_date_countdown ); ?>";
        let checkvalue = false;
        if (getNow < countDownDateStart) {
            const x = setInterval(function() {
                if( getNow < countDownDateStart ) {
                    getNow = new Date().getTime() - utcTime*3600*1000;
                } else {
                    checkvalue = true;
                    if ( end_date ) {
                        getDay();
                    }
                    clearInterval(x);
                }
            }, 1000);
        } else {
            if ( end_date ) {
                getDay();
            }
        }

        function getDay() {
            const x = setInterval(function() {
            const now = new Date().getTime() - utcTime*3600*1000;
            const distance = countDownDate - now;

            const { hours, days, minutes, seconds } = (function(distance){
                const hours = Math.floor(distance % (1000 * 60 * 60 * 24) / (1000 * 60 * 60));
                const days = Math.floor(distance / (1000 * 60 * 60 *24));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                return { hours, days, minutes, seconds };
            })(distance);

            if (isShowCountdown || isShowCountdown === '') {
                if (distance > 0) {
                    if(days > 0) {
                        document.getElementById("ppwp_sitewide_countdown").innerHTML =  '<div class="ppwp_countdown_timer_day"><div class="ppwp_countdown_time">' + days + "</div><div class='ppwp_countdown_timer_unit'> <?php echo esc_attr(  $day_of_countdown ); ?></div>" + '</div><div class="ppwp_coundown_colon_spacing">:</div><div class="ppwp_countdown_timer_hour"><div class="ppwp_countdown_time">' + hours + " </div><div class='ppwp_countdown_timer_unit'><?php echo esc_attr( $hour_of_countdown ); ?></div>"+ '</div><div class="ppwp_coundown_colon_spacing">:</div><div class="ppwp_countdown_timer_minute"><div class="ppwp_countdown_time">' + minutes + " </div><div class='ppwp_countdown_timer_unit'><?php echo esc_attr( $minute_of_countdown ); ?></div>"+ '</div><div class="ppwp_coundown_colon_spacing">:</div><div class="ppwp_countdown_timer_second"><div class="ppwp_countdown_time">' + seconds + " </div><div class='ppwp_countdown_timer_unit'><?php echo esc_attr( $second_of_countdown ); ?></div>" + '</div>';
                    } else {
                        const hours = Math.floor(distance / (1000 * 60 * 60));
                        document.getElementById("ppwp_sitewide_countdown").innerHTML =  '<div class="ppwp_countdown_timer_hour"><div class="ppwp_countdown_time">' + hours + " </div><div class='ppwp_countdown_timer_unit'><?php echo esc_attr( $hour_of_countdown ); ?></div>"+ '</div><div class="ppwp_coundown_colon_spacing">:</div><div class="ppwp_countdown_timer_minute"><div class="ppwp_countdown_time">' + minutes + " </div><div class='ppwp_countdown_timer_unit'><?php echo esc_attr( $minute_of_countdown ); ?></div>"+ '</div><div class="ppwp_coundown_colon_spacing">:</div><div class="ppwp_countdown_timer_second"><div class="ppwp_countdown_time">' + seconds + " </div><div class='ppwp_countdown_timer_unit'><?php echo esc_attr( $second_of_countdown ); ?></div>" + '</div>';
                    }
                    document.getElementById('ppwp_desc_above_countdown').style.display = 'block';
                    document.getElementById('ppwp_desc_below_countdown').style.display = 'block';
                } else {
                    clearInterval(x);
                    document.getElementById('ppwp_sitewide_countdown').style.display = 'none';
                    document.getElementById('ppwp_desc_above_countdown').style.display = 'none';
                    document.getElementById('ppwp_desc_below_countdown').style.display = 'none';
                }
            }
            }, 1000);	
        }
    };
    getCountdown();
</script>