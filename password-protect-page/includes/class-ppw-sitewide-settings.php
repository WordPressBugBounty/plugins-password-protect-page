<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * PPWP Sitewide Settings
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
if ( ! class_exists( 'PPW_Sitewide_Settings' ) ) {
    class PPW_Sitewide_Settings {
		/**
		 * Render UI sitewide submenu settings page
		 */
        public function render_ui() {
        	// phpcs:disable
	        $_get       = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We no need to handle nonce verification for render UI.
	        $head_title = is_pro_active_and_valid_license() ? 'PPWP Pro' : 'PPWP Lite';
			?>
			<div class="wrap">
				<div id="icon-themes" class="icon32"></div>
				<h2>
					<?php 
					printf(
							esc_html__( '%s: Sitewide Protection', 'password-protect-page' ),
							esc_html( $head_title )
					);
					?>
				</h2>
				<?php
				$general_tab   = apply_filters( PPW_Constants::HOOK_SITEWIDE_TAB, 'general' );
				$activated_tab = isset( $_get['tab'] ) ? $_get['tab'] : $general_tab;
				$this->render_tabs( $activated_tab );
				$this->render_content( $activated_tab );
				?>
			</div>
			<?php
			
		}

		/**
		 * Render tab for sitewide page
		 *
		 * @param string $active_tab Activate tab
		 */
        public function render_tabs( $active_tab ) {
			$tabs = apply_filters(
				PPW_Constants::HOOK_ADD_NEW_SITEWIDE_SUBMENU,
				array(
					array(
						'tab'      => 'general',
						'tab_name' => __( 'General', 'password-protect-page' ),
					),
				)
			);
			
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
					<a href="?page=<?php echo esc_html( PPW_Constants::SITEWIDE_PAGE_PREFIX ); ?>&tab=<?php echo esc_attr( $tab['tab'] ); ?>"
					   class="nav-tab <?php echo esc_attr( $active_tab === $tab['tab'] ? 'nav-tab-active' : '' ); ?>"><?php echo esc_attr( $tab['tab_name'] ); ?></a>
				<?php } ?>
			</h2>
			<?php
        }

        /**
         * Render the content
         * @param string $active_tab Active Tab
         */
	    public function render_content( $active_tab ) {
		    $tabs = apply_filters( PPW_Constants::HOOK_CUSTOM_SITEWIDE_TAB, array( 'general' ) );

		    foreach ( $tabs as $tab ) {
			    if ( $active_tab === $tab ) {
				    do_action( PPW_Constants::HOOK_RENDER_CONTENT_FOR_SITEWIDE_TAB . $tab );
				    break;
			    }
		    }
	    }
    }
}
