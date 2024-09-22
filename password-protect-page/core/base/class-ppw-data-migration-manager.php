<?php
/**
 * Abstract Class PPWP Migration Manager
 */

if ( ! class_exists( 'PPW_Migration_Manager' ) ) {
	abstract class PPW_Migration_Manager extends PPW_Background_Task_Manager {

		abstract public function get_migrations_class();

		abstract public function get_migration_label();

		abstract public function get_success_message();

		public function __construct() {
			$this->handle_admin_notices();
			parent::__construct();
		}

		/**
		 * Admin Notice Handling
		 */
		protected function handle_admin_notices() {
			$action = 'admin_notices';

			if ( is_admin() && $this->get_flag( 'completed' ) ) {
				add_action( $action, array( $this, 'admin_notice_upgrade_is_completed' ) );
			}

			$migration = $this->get_task_runner();

			if ( $migration->is_running() ) {
				add_action( $action, array( $this, 'admin_notice_upgrade_is_running' ) );
			}
		}

		/**
		 * @return PPW_Migration
		 */
		public function get_task_runner_class() {
			return 'PPW_Migration';
		}

		/**
		 * @return Query Limit
		 */
		public function get_query_limit() {
			return 100;
		}

		/**
		 * @return Boolian value of Migration event
		 */
		public function should_migrate() {
			return true;
		}

		/**
		 * Runner Complete
		 * @param boolian $did_task Deafult is False
		 */
		public function on_runner_complete( $did_tasks = false ) {
			// Implement log here
			if ( $did_tasks ) {
				$this->add_flag( 'completed' );
			}
		}

		/**
		 * Start Run
		 */
		public function start_run() {
			$updater = $this->get_task_runner();

			if ( $updater->is_running() ) {
				return;
			}

			$upgrade_callbacks = $this->get_migration_callbacks();

			if ( empty( $upgrade_callbacks ) ) {
				$this->on_runner_complete();

				return;
			}

			foreach ( $upgrade_callbacks as $callback ) {
				$updater->push_to_queue(
					array(
						'callback' => $callback,
					)
				);
			}

			$updater->save()->dispatch();

			// Use log here
		}

		/**
		 * Migration Callback
		 * @return array $callbacks Callbacks
		 */
		public function get_migration_callbacks() {
			$prefix               = 'migrate_v_';
			$migrations_class     = $this->get_migrations_class();
			$migration_reflection = new ReflectionClass( $migrations_class );
			$callbacks            = array();

			$methods = $migration_reflection->getMethods();
			foreach ( $methods as $method ) {
				$method_name = $method->getName();
				if ( false === strpos( $method_name, $prefix ) ) {
					continue;
				}

				if ( ! preg_match_all( "/$prefix(\d+_\d+_\d+)/", $method_name, $matches ) ) {
					continue;
				}

				$method_version = str_replace( '_', '.', $matches[1][0] );

				if ( ! version_compare( $method_version, PPW_VERSION, '>' ) ) {
					continue;
				}

				$callbacks[] = array( $migrations_class, $method_name );
			}

			return $callbacks;
		}

		/**
		 * Admin Notice
		 */
		public function admin_notice_upgrade_is_running() {
			$upgrade_link = $this->get_continue_action_url();
			$message      = '<p>' . sprintf( __( '%s To keep password protecting your private content, we have to <a href="https://passwordprotectwp.com/password-migration/" target="_blank"  rel="noopener noreferrer">migrate your passwords</a> to our plugin. The migration process is running in the background.', PPW_Constants::DOMAIN ), $this->get_updater_label() ) . '</p>';
			$message      .= '<p>' . sprintf( 'Taking a while? <a href="%s" class="button-primary">Click here to run it now</a>', $upgrade_link ) . '</p>';
			echo '<div class="notice notice-warning">' . $message . '</div>'; // phpcs:ignore -- we don’t need to escape for this
		}

		/**
		 * Admin Notice success message
		 */
		public function admin_notice_upgrade_is_completed() {
			$this->delete_flag( 'completed' );
			$message = $this->get_success_message();
			if ( ! empty( $message ) ) {
				echo '<div class="notice notice-success">' . $message . '</div>'; // phpcs:ignore -- we don’t need to escape for this
			}
		}

	}
}
