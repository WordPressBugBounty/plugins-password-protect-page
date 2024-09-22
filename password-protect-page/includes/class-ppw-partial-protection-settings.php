<?php
/**
 * PPWP Partial Protection Settings
 */

if ( ! class_exists( 'PPW_Partial_Protection_Settings' ) ) {
	class PPW_Partial_Protection_Settings {

		/**
		 * Render UI for Partial Protection page.
		 */
		public function render_ui() {
			$_get       = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- We no need to handle nonce verfication for render UI.
			$head_title = is_pro_active_and_valid_license() ? 'PPWP Pro' : 'PPWP Lite';
			?>
			<div class="wrap">
				<h2><?php echo esc_html__( $head_title . ': Partial Protection', PPW_Constants::DOMAIN ) ?></h2>
				<?php
				$default_tab = apply_filters( PPW_Constants::HOOK_PCP_TAB, 'general' );
				$active_tab  = isset( $_get['tab'] ) ? $_get['tab'] : $default_tab;
				$this->render_tabs( $active_tab );
				$this->render_content( $active_tab );
				?>
			</div>
			<?php
		}

		/**
		 * Render tab for Partial Protection page.
		 *
		 * @param string $active_tab Active tab.
		 */
		public function render_tabs( $active_tab ) {
			$tabs = apply_filters(
				PPW_Constants::HOOK_ADD_NEW_PCP_SUBMENU,
				array(
					array(
						'tab'      => 'general',
						'tab_name' => 'General',
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

					if ( isset( $tab['link'] ) ) {
						$link = $tab['link'];
					} else {
						$link = '?page=' . esc_html( PPW_Constants::PCP_PAGE_PREFIX ) . '&tab=' . esc_attr( $tab['tab'] );
					}

					?>
					<a href="<?php echo esc_url( $link ); ?>"
					   class="nav-tab <?php echo $active_tab === $tab['tab'] ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( $tab['tab_name'], PPW_Constants::DOMAIN ); ?></a>
				<?php } ?>
			</h2>
			<?php
		}

		/**
		 * Render content for Partial Protection page.
		 *
		 * @param string $active_tab Active tab.
		 */
		public function render_content( $active_tab ) {
			$tabs = apply_filters( PPW_Constants::HOOK_CUSTOM_PCP_TAB, array( 'general' ) );

			foreach ( $tabs as $tab ) {
				if ( $active_tab === $tab ) {
					do_action( PPW_Constants::HOOK_RENDER_CONTENT_FOR_PCP_TAB . $tab );
					break;
				}
			}
		}
	}
}
