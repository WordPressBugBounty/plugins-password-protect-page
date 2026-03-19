<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * PPWP External Settings
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
class PPW_External_Settings {
	/**
	 * Render UI external submenu settings page.
	 */
	public function render_ui() {
		// phpcs:disable
		$_get       = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We no need to handle nonce verfication for render UI.
		$head_title = is_pro_active_and_valid_license() ? __( 'PPWP Pro', 'password-protect-page' ) : __( 'PPWP Lite', 'password-protect-page' );
		?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2>
				<?php 
					printf(
						esc_html__( '%s: Integrations', 'password-protect-page' ),
						esc_html( $head_title )
					);
	
				?>
			</h2>
			<?php
			$activated_tab = isset( $_get['tab'] ) ? $_get['tab'] : 'recaptcha';
			$this->render_tabs( $activated_tab );
			$this->render_content( $activated_tab );
			?>
		</div>
		<?php
		// phpcs:enable
	}

	/**
	 * Get external tabs.
	 *
	 * @return array
	 */
	public function get_tabs() {
		// phpcs:disable
	    return apply_filters(
	        'ppw_external_tabs',
	        array(
	            array(
	                'tab'      => 'recaptcha',
	                'tab_name' => __( 'reCAPTCHA', 'password-protect-page' ), // translatable
	            ),
	            array(
	                'tab'      => 'configuration',
	                'tab_name' => __( 'Configuration', 'password-protect-page' ), // translatable
	            ),
	        )
	    );
	    // phpcs:enable
	}


	/**
	 * Render tab for external page.
	 *
	 * @param string $active_tab Activate tab
	 */
    public function render_tabs( $active_tab ) {
    	$tabs = $this->get_tabs();

		?>
		<h2 class="ppwp_wrap_tab_title nav-tab-wrapper">
			<?php
			if ( ! is_array( $tabs ) ) {
				return;
			}

			foreach ( $tabs as $tab ) {
				if ( ! is_array( $tab ) ) {
					continue;
				}

				if ( empty( $tab['tab'] ) || empty( $tab['tab_name'] ) ) {
					continue;
				}
				?>
				<a href="?page=<?php echo esc_html( PPW_Constants::EXTERNAL_SERVICES_PREFIX ); ?>&tab=<?php echo esc_attr( $tab['tab'] ); ?>"
				   class="nav-tab <?php echo esc_attr( $active_tab === $tab['tab'] ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html( $tab['tab_name'] ); ?></a>
			<?php } ?>
		</h2>
		<?php
    }

    /**
     * Render content
     * @param string $active_tab Active Tab
     */

    public function render_content( $active_tab ) {
    	// phpcs:disable
    	$tabs = $this->get_tabs();
	    foreach ( $tabs as $tab ) {
		    if ( $active_tab === $tab['tab'] ) {
			    do_action( 'ppw_render_external_content_' . $tab['tab'] );
			    break;
		    }
	    }
	    // phpcs:enable
    }
}
