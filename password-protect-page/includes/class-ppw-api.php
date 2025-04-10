<?php
/**
 * Registered PPW API
 */

if ( ! class_exists( 'PPW_API' ) ) {
	/**
	 * API definitions
	 */
	class PPW_API {
		/**
		 * Messages.
		 */
		const MESSAGES = array(
			'PASSWORD_UPDATE_SUCCESSFULLY' => 'Great! You’ve updated the password successfully.',
			'PASSWORD_UPDATE_FAILURE'      => 'Opps! Something went wrong. Please try again.',
		);

		/**
		 * Register rest routes
		 */
		public function register_rest_routes() {
			register_rest_route(
				'wppp/v1',
				'check-content-password/(?P<id>\d+)',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'ppwp_check_content_password',
					),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Post ID' ),
							'sanitize_callback' => 'absint',
							'type'              => 'integer',
						),
						'page' => array(
							'description'       => __( 'Page index' ),
							'sanitize_callback' => 'absint',
							'type'              => 'integer',
						),
						'idx' => array(
							'description'       => __( 'Form index' ),
							'sanitize_callback' => 'absint',
							'type'              => 'integer',
						),
					),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'wppp/v1',
				'master-passwords',
				array(
					'methods'             => 'GET',
					'callback'            => array(
						$this,
						'ppwp_get_master_passwords',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);

			register_rest_route(
				'wppp/v1',
				'master-passwords',
				array(
					'methods'             => 'DELETE',
					'callback'            => array(
						$this,
						'delete_password',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);

			register_rest_route(
				'wppp/v1',
				'master-passwords',
				array(
					'methods'             => 'PUT',
					'callback'            => array(
						$this,
						'update_password',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);

			register_rest_route(
				'wppp/v1',
				'master-passwords/status',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'change_status',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);

			register_rest_route(
				'wppp/v1',
				'master-passwords',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'add_new_master_password',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);

			register_rest_route(
				'wppp/v1',
				'/master-passwords/bulk-delete',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'bulk_delete_master_passwords',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);

			register_rest_route(
				'wppp/v1',
				'/master-passwords/all-expired-delete',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'all_expired_delete_master_passwords',
					),
					'permission_callback' => array( $this, 'can_access' ),
				)
			);


			register_rest_route(
				'wppp/v1',
				'validate-password',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'validate_password',
					),
					'permission_callback' => '__return_true',
					'show_in_index'       => false
				)
			);

			register_rest_route(
				'wppp/v1',
				'pcp/(?P<id>\d+)/settings',
				array(
					'methods'             => 'GET',
					'callback'            => array(
						$this,
						'get_pcp_settings',
					),
					'permission_callback' => array( $this, 'can_access' ),
					'show_in_index'       => false,
				)
			);

			register_rest_route(
				'wppp/v1',
				'pcp/(?P<id>\d+)/settings',
				array(
					'methods'             => 'POST',
					'callback'            => array(
						$this,
						'update_pcp_settings',
					),
					'permission_callback' => array( $this, 'can_access' ),
					'show_in_index'       => false,
				)
			);
		}

		public function can_access() {
			return ppw_allow_manage_passwords();
		}

		/**
		 * Get Master Passwords.
		 */
		public function ppwp_get_master_passwords() {
			$ppwp_db = new PPW_Repository_Passwords();
			wp_send_json(
				array(
					'result'  => $ppwp_db->get_master_passwords_info(),
					'success' => true,
				),
				200
			);
		}

		/**
		 * Get expired time stamp
		 *
		 * @param string $days_to_expired Number of days.
		 *
		 * @return int
		 * @throws Exception Emits Exception in case of an error with DateTime.
		 */
		private function get_expired_time_stamp( $days_to_expired ) {
			$curr_date    = new DateTime();
			$expired_date = $curr_date->modify( intval( $days_to_expired ) . ' day' );

			return $expired_date->getTimestamp();
		}


		/**
		 * Add new variable.
		 *
		 * @param WP_REST_Request $request The REST API request to process.
		 *
		 * @return WP_REST_Response The REST response.
		 * @throws Exception Exception.
		 */
		public function add_new_master_password( $request ) {
			$passwords        = $request->get_param( 'password' );
			$usage_limit      = $request->get_param( 'usage_limit' );
			$expired_dates    = $request->get_param( 'expired_dates' );
			$role_type        = $request->get_param( 'role_type' );
			$roles_selected   = $request->get_param( 'roles_selected' );
			$label            = $request->get_param( 'label' );
			$post_types       = $request->get_param( 'post_types' );
			$protection_types = $request->get_param( 'protection_types' );

			$ppwp_repo = new PPW_Repository_Passwords();

			foreach ( $passwords as $password ) {
				if ( $ppwp_repo->find_by_master_password( $password ) || '' === $password ) {
					return wp_send_json(
						array(
							'result'  => array(),
							'success' => false,
						),
						400
					);
				}
			}

			$roles = PPW_Constants::PPW_MASTER_GLOBAL;
			if ( 'roles' === $role_type ) {
				$roles = $roles_selected;
			}

			try {
				$is_added = false;
				foreach ( $passwords as $password ) {
					$is_added = $ppwp_repo->add_new_password(
						array(
							'password'          => $password,
							'created_time'      => time(),
							'campaign_app_type' => $roles,
							'usage_limit'       => $usage_limit ? $usage_limit : null,
							'expired_date'      => $expired_dates ? $this->get_expired_time_stamp( $expired_dates ) : null,
							'label'             => $label,
							'post_types'        => $post_types,
							'protection_types'  => $protection_types
						)
					);
				}

				if ( $is_added ) {
					return wp_send_json(
						array(
							'result'  => $is_added,
							'success' => true,
						),
						200
					);
				}
			} catch ( Exception $exception ) {
				return wp_send_json(
					array(
						'result'  => array(),
						'success' => false,
						'message' => $exception->getMessage(),
					),
					400
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
				),
				400
			);
		}

		/**
		 * Delete password by id.
		 *
		 * @param object $request Request from body.
		 *
		 * @return WP_REST_Response The REST response.
		 */
		public function delete_password( $request ) {
			$id         = $request->get_param( 'id' );
			$ppwp_repo  = new PPW_Repository_Passwords();
			$is_deleted = $ppwp_repo->delete( $id );
			if ( $is_deleted ) {
				return wp_send_json(
					array(
						'result'  => $is_deleted,
						'success' => true,
					),
					200
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
				),
				400
			);
		}

		/**
		 * Bulk delete master password.
		 *
		 * @param object $request Request from body.
		 *
		 * @return WP_REST_Response The REST response.
		 */
		public function bulk_delete_master_passwords( $request ) {
			$ids        = $request->get_param( 'ids' );
			$ppwp_repo  = new PPW_Repository_Passwords();
			$is_deleted = $ppwp_repo->bulk_delete_passwords( $ids );
			if ( $is_deleted ) {
				return wp_send_json(
					array(
						'result'  => $is_deleted,
						'success' => true,
						'message' => 'Great! You’ve deleted the passwords successfully.'
					),
					200
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
					'message' => ''
				),
				400
			);
		}

		/**
		 * All Expired delete master password.
		 *
		 * @param object $request Request from body.
		 *
		 * @return WP_REST_Response The REST response.
		 */
		public function all_expired_delete_master_passwords( $request ) {
			$ids        = $request->get_param( 'ids' );
			$campaign_app_type='master_';
			$ppwp_repo  = new PPW_Repository_Passwords();
			$is_deleted = $ppwp_repo->delete_all_expired_password($ids, $campaign_app_type);

			if ( $is_deleted ) {
				return wp_send_json(
					array(
						'result'  => $is_deleted,
						'success' => true,
						'message' => 'Great! You’ve deleted all the expired passwords successfully.'
					),
					200
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
					'message' => 'An error occurred, or no expired passwords were detected.'
				),
				400
			);
		}
		
		/**
		 * Update password by id.
		 *
		 * @param object $request Request from body.
		 *
		 * @return WP_REST_Response The REST response.
		 */
		public function update_password( $request ) {
			$data       = $request->get_param( 'data' );
			$id         = $request->get_param( 'id' );
			$ppwp_repo  = new PPW_Repository_Passwords();
			$is_updated = $ppwp_repo->update_password(
				$id,
				$data
			);
			if ( $is_updated ) {
				return wp_send_json(
					array(
						'result'  => $is_updated,
						'success' => true,
						'message' => self::MESSAGES['PASSWORD_UPDATE_SUCCESSFULLY'],
					),
					200
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
					'message' => self::MESSAGES['PASSWORD_UPDATE_FAILURE'],
				),
				400
			);
		}

		/**
		 * Change status by id.
		 *
		 * @param object $request Request from body.
		 *
		 * @return WP_REST_Response The REST response.
		 */
		public function change_status( WP_REST_Request $request ) {
			$id           = $request->get_param( 'id' );
			$is_activated = $request->get_param( 'is_activated' );
			$ppwp_repo    = new PPW_Repository_Passwords();
			$is_updated   = $ppwp_repo->update_password(
				$id,
				array(
					'is_activated' => $is_activated,
				)
			);
			if ( $is_updated ) {
				return wp_send_json(
					array(
						'result'  => $is_updated,
						'success' => true,
						'message' => self::MESSAGES['PASSWORD_UPDATE_SUCCESSFULLY'],
					),
					200
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
					'message' => self::MESSAGES['PASSWORD_UPDATE_FAILURE'],
				),
				400
			);
		}

		/**
		 * Checking the content passwords
		 *
		 * @param array $data Post data.
		 *
		 * @return bool
		 */
		public function ppwp_check_content_password( $data ) {
			do_action( PPW_Constants::HOOK_RESTRICT_CONTENT_BEFORE_CHECK_PWD, $data );

			$result = array(
				'isValid' => false,
				'message' => _x( apply_filters( PPW_Constants::HOOK_RESTRICT_CONTENT_ERROR_MESSAGE, PPW_Constants::DEFAULT_WRONG_PASSWORD_MESSAGE ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, PPW_Constants::DOMAIN ),
			);

			$is_valid_data = apply_filters( PPW_Constants::HOOK_SHORT_CODE_VALID_POST_DATA, $this->is_valid_data_content_password( $data ) );

			if ( ! $is_valid_data ) {
				return wp_send_json(
					$result,
					400
				);
			}

			$post = get_post( $data['id'] );
			if ( is_null( $post ) ) {
				return wp_send_json(
					$result,
					400
				);
			}

			$content = apply_filters( PPW_Constants::HOOK_SHORTCODE_CONTENT_SOURCE, $post->post_content, $post, $data );
			if ( false === $content ) {
				return wp_send_json(
					$result,
					400
				);
			}

			if ( ! has_shortcode( $content, PPW_Constants::PPW_HOOK_SHORT_CODE_NAME ) ) {
				return wp_send_json(
					$result,
					400
				);
			}

			$matches = ppw_free_search_shortcode_content( $content );
			$matches = $this->filter_short_code_matches( $matches, PPW_Constants::PPW_HOOK_SHORT_CODE_NAME );

			if ( ! isset( $matches[ $data['idx'] ] ) ) {
				return wp_send_json(
					$result,
					400
				);
			}

			// Get all arguments of shortcode.
			$shortcode = $matches[ $data['idx'] ];

			if ( PPW_Recaptcha::get_instance()->using_pcp_recaptcha()
			     && ! PPW_Recaptcha::get_instance()->is_valid_recaptcha() ) {
				$result['message'] = PPW_Recaptcha::get_instance()->get_error_message();

				return wp_send_json(
					$result,
					400
				);
			}

			// Valid passwords.
			$array_values = ppw_free_valid_pcp_password( $shortcode, $data['pss'] );
			if ( $array_values['is_valid_password'] ) {
				$atts                          = $array_values['atts'];
				$result['cookie_expired_time'] = $atts['cookie'];
				$result['isValid']             = true;
				$result['message']             = '';
				do_action( PPW_Constants::HOOK_RESTRICT_CONTENT_AFTER_VALID_PWD, $post, $data['pss'] );

				if ( ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::NO_RELOAD_PAGE, PPW_Constants::MISC_OPTIONS ) ) {
					$post_content = ppw_get_pcp_post_content_with_third_party( $post, $data['idx'] );

					$result['content']  = ! empty( $post_content ) ? $post_content : $shortcode[5];
					$result['noReload'] = true;
				}
			}

			// Allow custom error message from error_msg shortcode's attribute.
			if ( isset( $array_values['message'] ) ) {
				$result['message'] = _x( wp_kses_post( $array_values['message'] ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, PPW_Constants::DOMAIN );
			}

			$result = apply_filters( 'ppw_pcp_api_result', $result, $data['pss'], $post );

			return wp_send_json(
				$result,
				200
			);
		}

		/**
		 * Validate input data.
		 *
		 * @param array $data POST data.
		 *
		 * @return bool
		 */
		private function is_valid_data_content_password( $data ) {
			return isset( $data['id'] ) && isset( $data['page'] ) && $data['page'] > 0 && isset( $data['formType'] );
		}

		/**
		 * Checking the password is valid in short code attribute.
		 * Sample data:
		 * Array
		 * (
		 *   [0] => [ppwp passwords="123456 123"]This is the content under Group2[/ppwp]
		 *   [1] =>
		 *   [2] => ppwp
		 *   [3] =>  passwords="123456 123"
		 *   [4] =>
		 *   [5] => This is the content under Group2
		 *   [6] =>
		 *  ).
		 *
		 * @param array  $shortcode The found short codes in the content.
		 *
		 * @param string $password  Password from request.
		 *
		 * @return array
		 * @deprecated 1.5.2
		 *
		 */
		private function handle_valid_password( $shortcode, $password ) {
			$default_args = array(
				'is_valid_password' => false,
				'atts'              => array(),
			);
			// Check ppwp shortcode exist.
			if ( PPW_Constants::PPW_HOOK_SHORT_CODE_NAME !== $shortcode[2] || ! isset( $shortcode[3] ) ) {
				return $default_args;
			}
			// Parse shortcode string to array.
			$parsed_atts = shortcode_parse_atts( trim( $shortcode[3] ) );

			// Get attributes from shortcode.
			$atts      = PPW_Shortcode::get_instance()->get_attributes( $parsed_atts );
			$passwords = apply_filters( PPW_Constants::HOOK_SHORTCODE_PASSWORDS, array_filter( $atts['passwords'], 'strlen' ), $parsed_atts );

			// Check password exist.
			if ( in_array( $password, $passwords, true ) ) {
				$default_args['is_valid_password'] = true;
				$default_args['atts']              = $atts;
			}

			if ( isset( $parsed_atts['error_msg'] ) ) {
				$default_args['message'] = wp_kses_post( $parsed_atts['error_msg'] );
			}

			return $default_args;
		}

		/**
		 * Search shortcode content
		 *
		 * @param string $content The post content.
		 *
		 * @return mixed
		 * @deprecated 1.5.2
		 *
		 */
		private function search_shortcode_content( $content ) {
			preg_match_all( '/' . get_shortcode_regex( array( 'ppwp' ) ) . '/', $content, $matches, PREG_SET_ORDER );

			return $matches;
		}

		/**
		 * Filter short code result by name
		 *
		 * @param array  $result         The result need to filter.
		 * @param string $shortcode_name Short code name.
		 *
		 * @return array
		 */
		private function filter_short_code_matches( $result, $shortcode_name ) {
			return array_values(
				array_filter(
					$result,
					function ( $match ) use ( $shortcode_name ) {
						return isset( $match[2] ) && $shortcode_name === $match[2];
					}
				)
			);
		}

		/**
		 * Generate post data.
		 *
		 * @param WP_Post|object|int $post WP_Post instance or Post ID/object.
		 *
		 * @return array|bool $elements Elements of post or false on failure.
		 * @since 5.2.0
		 *
		 */
		private function generate_postdata( $post ) {

			if ( ! ( $post instanceof WP_Post ) ) {
				$post = get_post( $post );
			}

			if ( ! $post ) {
				return false;
			}

			$id = (int) $post->ID;

			$authordata = get_userdata( $post->post_author );

			$currentday   = mysql2date( 'd.m.y', $post->post_date, false );
			$currentmonth = mysql2date( 'm', $post->post_date, false );
			$numpages     = 1;
			$multipage    = 0;
			$page         = $this->get( 'page' );
			if ( ! $page ) {
				$page = 1;
			}

			/*
			 * Force full post content when viewing the permalink for the $post,
			 * or when on an RSS feed. Otherwise respect the 'more' tag.
			 */
			if ( $post->ID === get_queried_object_id() && ( is_page() || is_single() ) ) {
				$more = 1;
			} elseif ( is_feed() ) {
				$more = 1;
			} else {
				$more = 0;
			}

			$content = $post->post_content;
			if ( false !== strpos( $content, '<!--nextpage-->' ) ) {
				$content = str_replace( "\n<!--nextpage-->\n", '<!--nextpage-->', $content );
				$content = str_replace( "\n<!--nextpage-->", '<!--nextpage-->', $content );
				$content = str_replace( "<!--nextpage-->\n", '<!--nextpage-->', $content );

				// Remove the nextpage block delimiters, to avoid invalid block structures in the split content.
				$content = str_replace( '<!-- wp:nextpage -->', '', $content );
				$content = str_replace( '<!-- /wp:nextpage -->', '', $content );

				// Ignore nextpage at the beginning of the content.
				if ( 0 === strpos( $content, '<!--nextpage-->' ) ) {
					$content = substr( $content, 15 );
				}

				$pages = explode( '<!--nextpage-->', $content );
			} else {
				$pages = array( $post->post_content );
			}

			/**
			 * Filters the "pages" derived from splitting the post content.
			 *
			 * "Pages" are determined by splitting the post content based on the presence
			 * of `<!-- nextpage -->` tags.
			 *
			 * @param string[] $pages Array of "pages" from the post content split by `<!-- nextpage -->` tags.
			 * @param WP_Post  $post  Current post object.
			 *
			 * @since 4.4.0
			 *
			 */
			$pages = apply_filters( 'content_pagination', $pages, $post );

			$numpages = count( $pages );

			if ( $numpages > 1 ) {
				if ( $page > 1 ) {
					$more = 1;
				}
				$multipage = 1;
			} else {
				$multipage = 0;
			}

			$elements = compact( 'id', 'authordata', 'currentday', 'currentmonth', 'page', 'pages', 'multipage', 'more', 'numpages' );

			return $elements;
		}

		/**
		 * @param $request
		 *
		 * @return WP_REST_Response
		 */
		public function validate_password( $request ) {
			$post_id  = $request->get_param( 'post_id' );
			$password = $request->get_param( 'post_password' );
			$post_id  = absint( $post_id );
			$password = wp_unslash( $password ); // phpcs:ignore -- not sanitize password because we allow all character.

			if ( empty( $post_id ) ) {
				return new WP_REST_Response(
					array(
						'success' => false,
						'message' => 'Post ID is empty',
					),
					400
				);
			}

			$post = get_post( $post_id );
			if ( empty( $post ) ) {
				return new WP_REST_Response(
					array(
						'success' => false,
						'message' => 'Post not found',
					),
					400
				);
			}

			$post_content     = apply_filters( 'the_content', $post->post_content );
			$password_service = new PPW_Password_Services();
			$is_valid         = $password_service->is_valid_password_from_request( $post_id, $password );

			if ( ! $is_valid ) {
				return new WP_REST_Response(
					array(
						'success' => false,
						'message' => ppw_core_get_error_msg( $post_id ),
					),
					400
				);
			}

			// Don not check post type in PPWP shortcode.
			add_filter( 'ppw_shortcode_allow_bypass_valid_post_type', '__return_true' );

			return new WP_REST_Response(
				array(
					'success'      => true,
					'post_content' => '<div>' . do_shortcode( $post_content ) . '</div>',
					'message'      => 'The password you entered is correct',
				),
				200
			);
		}

		public function get_pcp_settings( $request ) {
			$ppwp_db   = new PPW_Repository_Passwords();
			$post_id   = $request->get_param( 'id' );
			$passwords = $ppwp_db->get_passwords_with_type_and_post_id( PPW_Content_Protection::PASSWORD_GLOBAL_TYPE, $post_id, 'password' );
			$setting   = array();
			if ( count( $passwords ) > 0 ) {
				$passwords            = array_map(
					function ( $password_info ) {
						return $password_info->password;
					},
					$passwords
				);
				$setting['passwords'] = $passwords;
			} else {
				$setting['passwords'] = array();
			}

			return new WP_REST_Response(
				$setting,
				200
			);
		}

		public function update_pcp_settings( $request ) {
			$ppwp_db   = new PPW_Repository_Passwords();
			$ppwp_area = new PPW_Content_Protection();
			$post_id   = $request->get_param( 'id' );
			$passwords = $request->get_param( 'passwords' );

			$post = get_post( $post_id );
			if ( ! $ppwp_area->check_area_exist( $post ) ) {
				return new WP_REST_Response(
					array(
						'success' => false,
					),
					400
				);
			}

			if ( empty( $passwords ) ) {
				$ppwp_db->delete_passwords_by_post_id( $post_id );

				return new WP_REST_Response(
					array(
						'success' => true,
					),
					200
				);
			}

			$raw_password_dbs = $ppwp_db->get_passwords_with_type_and_post_id( PPW_Content_Protection::PASSWORD_GLOBAL_TYPE, $post_id, 'id, password' );
			if ( count( $raw_password_dbs ) > 0 ) {
				$password_dbs = array_map(
					function ( $password_info ) {
						return $password_info->password;
					},
					$raw_password_dbs
				);

				$passwords_to_add    = array_values( array_diff( $passwords, $password_dbs ) );
				$passwords_to_delete = array_values( array_diff( $password_dbs, $passwords ) );

				foreach ( $passwords_to_add as $password_to_add ) {
					$password = trim( $password_to_add );
					if ( '' === $password ) {
						continue;
					}
					$password_data = array(
						'post_id'           => $post_id,
						'created_time'      => time(),
						'campaign_app_type' => PPW_Content_Protection::PASSWORD_GLOBAL_TYPE,
						'password'          => $password,
						'usage_limit'       => null,
						'expired_date'      => null,
					);

					$ppwp_db->add_new_password( $password_data );
				}

				$ids = array();
				foreach ( $raw_password_dbs as $raw_password_db ) {
					if ( in_array( $raw_password_db->password, $passwords_to_delete ) ) {
						$ids[] = $raw_password_db->id;
					}
				}
				$ids = array_unique( $ids );
				$ppwp_db->delete_passwords( $ids, $post_id );
			} else {
				foreach ( $passwords as $password ) {
					$password = trim( $password );
					if ( '' === $password ) {
						continue;
					}
					$password_data = array(
						'post_id'           => $post_id,
						'created_time'      => time(),
						'campaign_app_type' => PPW_Content_Protection::PASSWORD_GLOBAL_TYPE,
						'password'          => $password,
						'usage_limit'       => null,
						'expired_date'      => null,
					);

					$ppwp_db->add_new_password( $password_data );
				}
			}

			return new WP_REST_Response(
				array(
					'success' => true,
				),
				200
			);
		}
	}
}
