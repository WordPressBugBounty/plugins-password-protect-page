<?php
/**
*
* Dynamic Sidebar
*
*/

?>
<div class="ppw_wrap_subscribe_form" id="ppwp_right_column_metaboxes">
	<?php 
		
		/* if transient is empty  */
		if ( false === ( $ppwp_sidebar_content = get_transient( 'ppwp_sidebar_content' ) ) ) {

			$response = wp_remote_get( PPWP_SIDEBAR_API );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {	

				$json = json_decode( $response['body'] );

				$section_1 		= !empty( $json->section_1 ) ? stripslashes( $json->section_1 ) : '';
				$section_2 		= !empty( $json->section_2 ) ? stripslashes( $json->section_2 ) : '';
				$section_3 		= !empty( $json->section_3 ) ? stripslashes( $json->section_3 ) : '';
				$ppwp_fss_expire = !empty( $json->ppwp_fss_expire ) ? (int) $json->ppwp_fss_expire : 1;

				/* set the transient by api response  */
				set_transient( 'ppwp_sidebar_content', $response['body'], DAY_IN_SECONDS * $ppwp_fss_expire );

				if( !empty( $section_1 ) ){
					echo '<div class="main_container ppwp-section-1">'.$section_1.'</div>';	
				}

				if( !empty( $section_2 ) ){
					echo '<div class="main_container ppwp-section-2">'.$section_2.'</div>';	
				}

				if( !empty( $section_3 ) ){
					echo '<div class="main_container ppwp-section-3">'.$section_3.'</div>';	
				}
			}

		} else {

			/* if transient is not empty  */
		 	$response = get_transient( 'ppwp_sidebar_content' );
			$json = json_decode( $response );

			if ( !empty( $json ) ) {	

				$section_1 = !empty( $json->section_1 ) ? stripslashes( $json->section_1 ) : '';
				$section_2 = !empty( $json->section_2 ) ? stripslashes( $json->section_2 ) : '';
				$section_3 = !empty( $json->section_3 ) ? stripslashes( $json->section_3 ) : '';

				if( !empty( $section_1 ) ){
					echo '<div class="main_container ppwp-section-1">'.$section_1.'</div>';	
				}

				if( !empty( $section_2 ) ){
					echo '<div class="main_container ppwp-section-2">'.$section_2.'</div>';	
				}

				if( !empty( $section_3 ) ){
					echo '<div class="main_container ppwp-section-3">'.$section_3.'</div>';	
				}
			}

		}
		?>
</div>
