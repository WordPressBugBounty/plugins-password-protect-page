<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * PPWP Settings
 */
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
// phpcs:disable
if ( ! class_exists( "PPW_Settings" ) ) {
	class PPW_Settings {
		/**
		 * Render UI settings page
		 */
		public function render_ui() {
			$_get        = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We no need to handle nonce verification for render UI.
			$plugin_info = apply_filters(
				PPW_Constants::HOOK_PLUGIN_INFO,
				array(
					'name'    => __( 'Password Protect WordPress - PPWP', 'password-protect-page' ),
					'version' => PPW_VERSION,
				)
			);

			?>
			<div class="wrap">
				<div id="icon-themes" class="icon32"></div>
				<h2>
					<?php echo esc_html( $plugin_info['name'] ); ?>
					<span class="ppwp_version"><?php echo esc_html( $plugin_info['version'] ); ?></span>
				</h2>
				<?php
				$default_tab  = apply_filters( PPW_Constants::HOOK_DEFAULT_TAB, 'general' );
				$activate_tab = isset( $_get['tab'] ) ? $_get['tab'] : $default_tab;
				$this->render_tabs( $activate_tab );
				$this->render_content( $activate_tab );
				?>
			</div>
			<?php			
		}

		/**
		 * Render tab for settings page
		 *
		 * @param string $active_tab Active tab.
		 */
		private function render_tabs( $active_tab ) {
			$tabs = apply_filters(
				PPW_Constants::HOOK_ADD_NEW_TAB,
				array(
					array(
						'tab'      => 'general',
						'tab_name' => __( 'General', 'password-protect-page' ),
					),
					array(
						'tab'      => 'misc',
						'tab_name' => __( 'Advanced', 'password-protect-page' ),
					),
					array(
						'tab'      => 'entire_site',
						'tab_name' => __( 'Sitewide', 'password-protect-page' ),
					),
					array(
						'tab'      => 'shortcodes',
						'tab_name' => __( 'Shortcodes', 'password-protect-page' ),
					),
					array(
						'tab'      => 'master_passwords',
						'tab_name' => __( 'Master Passwords', 'password-protect-page' ),
					),
					array(
						'tab'      => 'troubleshooting',
						'tab_name' => __( 'Troubleshooting', 'password-protect-page' ),
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
					<a href="?page=<?php echo esc_html( PPW_Constants::MENU_NAME ); ?>&tab=<?php echo esc_attr( $tab['tab'] ); ?>"
					   class="nav-tab <?php echo esc_attr( $active_tab === $tab['tab'] ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html( $tab['tab_name'] ); ?></a>
				<?php } ?>
			</h2>
			<?php
		}

		/**
		 * Render content for settings page
		 *
		 * @param string $active_tab Active tab.
		 */
		private function render_content( $active_tab ) {
			$tabs = apply_filters( PPW_Constants::HOOK_CUSTOM_TAB, array( 'general', 'misc', 'entire_site', 'shortcodes', 'master_passwords', 'troubleshooting' ) );

			foreach ( $tabs as $tab ) {
				if ( $active_tab === $tab ) {
					do_action( PPW_Constants::HOOK_RENDER_CONTENT_FOR_TAB . $tab );
					break;
				}
			}
		}
	}
}
