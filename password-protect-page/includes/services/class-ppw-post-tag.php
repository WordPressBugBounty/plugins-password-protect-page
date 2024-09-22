<?php

/**
 *
 * Class PPW_Tag
 */
class PPW_Tag_Service {
	const COOKIE_NAME = 'ppw_tag-';
	const OPTION_NAME = 'ppwp_tag_options';
	const SHARED_TAG_TYPE = 'shared_tag';

	/**
	 * @var null|integer Category ID.
	 */
	private $tag_id = null;

	/**
	 * Is using post password required
	 * @var bool
	 */
	private $unlocked = false;

	/**
	 * @var null|PPW_Tag_Service Instance.
	 */
	protected static $instance = null;

	private $is_pro_activated;

	/**
	 * Get instance.
	 *
	 * @return PPW_Tag_Service
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Register hooks.
	 *
	 * @param bool $is_pro_activated Is pro activated
	 */
	public function register( $is_pro_activated = false ) {
		$this->is_pro_activated = $is_pro_activated;
		$this->unlocked         = $this->is_pro_activated;

		add_filter( 'post_password_required', array( $this, 'tag_password_required' ), 15, 2 );
		add_filter( 'ppw_is_valid_password', array( $this, 'check_valid_password' ), 15, 3 );
		add_action( 'post_tag_pre_add_form', array( $this, 'display_post_tag_ui' ) );

		// Check post is protected before.
		add_filter( 'ppw_is_valid_cookie', array( $this, 'ppw_is_valid_cookie' ) );
		add_filter( 'ppwp_post_password_required', array( $this, 'ppwp_post_password_required' ), 100 );
	}

	/**
	 * Check if content is protected and unlocked password form.
	 *
	 * @param boolean $is_valid
	 *
	 * @return bool True is valid cookie.
	 */
	public function ppw_is_valid_cookie( $is_valid ) {
		$this->unlocked = $is_valid;

		return $is_valid;
	}

	/**
	 * If post is protected and
	 *
	 * @param array $data Unlock data from PPWP Fro.
	 *
	 * @return mixed
	 */
	public function ppwp_post_password_required( $data ) {
		if ( ! isset( $data['is_content_unlocked'] ) ) {
			return $data;
		}
		$this->unlocked = $data['is_post_protected'] && $data['is_content_unlocked'];

		return $data;
	}

	
	/**
	 * Display tag UI.
	 */
	public function display_post_tag_ui() {
		$tags = ppw_get_terms_with_all_lang(
			array(
				'taxonomy'   => 'post_tag',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false,
			)
		);
		$is_protect           = ppw_core_get_setting_type_bool_by_option_name( 'ppwp_is_protect_tag', self::OPTION_NAME );
		$protected_tags = ppw_core_get_setting_type_array_by_option_name( 'ppwp_protected_tags_selected', self::OPTION_NAME );

		// Get first password to display to user.
		$passwords = PPW_Repository_Passwords::get_instance()->get_all_shared_tags_password();
		if ( count( $passwords ) > 0 ) {
			$password = $passwords[0]->password;
		} else {
			$password = '';
		}

		ob_start();
		include PPW_DIR_PATH . 'includes/views/post_tag/view-option.php';
		echo ob_get_clean(); // phpcs:ignore -- we already escape on the view-option.php
	}

	/**
	 * Filters whether a tag requires the user to supply a password.
	 *
	 * @param bool Whether the user needs to supply a password.
	 *             True if password has not been provided or is incorrect,
	 *             false if password has been supplied or is not required.
	 *
	 * @return bool false if a password is not required or the correct password cookie is present, true otherwise.
	 */
	public function tag_password_required( $required, $post ) {
		$post_id = ! empty( $post ) ? $post->ID : false;
		if ( ! $post_id ) {
			return $required;
		}

		// Get all protected tags of a Post.
		$protected_tags = $this->get_protected_tags( $post_id );
		if ( empty( $protected_tags ) ) {
			return $required;
		}

		$validated_cookie = apply_filters( 'ppw_tag_is_valid_cookie', $this->is_valid_cookie(), $post_id, $post, $required );

		$ppw_sategory_service_instance = PPW_Category_Service::get_instance();
		$cat_validated_cookie = $ppw_sategory_service_instance->is_valid_cookie();

		if ( $validated_cookie || $cat_validated_cookie ) {
			return false;
		}

		add_filter( 'ppwp_ppf_action_url', array( $this, 'get_action_url' ), 9999 );

		// Unlocked by PPWP Free and PPWP Pro
		// Include Single, AL, Group.
		if ( apply_filters( 'ppw_tag_unlocked', $this->unlocked, $post_id, $protected_tags ) ) {
			$this->unlocked = $this->is_pro_activated;

			return $required;
		}

		return true;
	}

	/**
	 * Get protected tags if user turn on Option.
	 *
	 * @param integer $post_id Post ID.
	 *
	 * @return array Empty if user turn off option or post ID not include protected tags.
	 */
	public function get_protected_tags( $post_id ) {
		// Check user has turn on option.
		$enabled = ppw_core_get_setting_type_bool_by_option_name( 'ppwp_is_protect_tag', self::OPTION_NAME );
		$enabled = apply_filters( 'ppw_tag_is_enabled', $enabled, $post_id );
		if ( ! $enabled ) {
			return array();
		}

		$tag_id = $this->get_tags_by_post_id( $post_id );

		// Not handle with tags of post are empty.
		if ( empty( $tag_id ) ) {
			return array();
		}

		// Get protected tags of current post.
		$protected_tags = ppw_core_get_setting_type_array_by_option_name( 'ppwp_protected_tags_selected', self::OPTION_NAME );;
		$protected_tags = array_intersect( $tag_id, $protected_tags );

		// Reorder index of array.
		return array_values( $protected_tags );
	}

	/**
	 * Replace current action by action URL of tag to check password.
	 */
	public function get_action_url( $url ) {
		if ( is_tag() ) {
			$callback_url = get_tag_link( get_queried_object_id() );
		} elseif ( is_singular() ) {
			$callback_url = get_the_permalink();
		} else {
			global $wp;
			$callback_url = home_url( $wp->request );
		}

		$callback_url = rawurlencode( $callback_url );

		return add_query_arg(
			array(
				PPW_Constants::CALL_BACK_URL_PARAM => $callback_url,
			),
			$url
		);
	}

	/**
	 * Check password is valid after show message if it is wrong password.
	 *
	 * @param bool   $is_valid Is valid password.
	 * @param int    $post_id  Post ID.
	 * @param string $password Password.
	 *
	 * @return bool True if password is valid.
	 */
	public function check_valid_password( $is_valid, $post_id, $password ) {
		$protected_tags = $this->get_protected_tags( $post_id );
		if ( empty( $protected_tags ) ) {
			return $is_valid;
		}

		return apply_filters( 'ppw_tag_is_valid_password', $this->is_valid_password( $protected_tags, $password, $post_id ), $is_valid, $protected_tags, $post_id, $password ) || $is_valid;
	}

	/**
	 * Set password to cookie
	 *
	 * @param string    $password    Password string.
	 * @param string    $cookie_name Cookie name.
	 * @param false|int $password_id Password ID.
	 *
	 * @return bool
	 */
	public function set_password_to_cookie( $password, $cookie_name, $password_id = false ) {
		$password_hashed         = wp_hash_password( $password );
		$expire                  = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + 7 * DAY_IN_SECONDS );
		$password_cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
		if ( ! empty( $password_cookie_expired ) ) {
			$time = explode( ' ', $password_cookie_expired )[0];
			$unit = ppw_core_get_unit_time( $password_cookie_expired );
			if ( 0 !== $unit ) {
				$expire = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + (int) $time * $unit );
			}
		}

		$referer = wp_get_referer();
		if ( $referer ) {
			$secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
		} else {
			$secure = false;
		}

		if ( $password_id ) {
			$password_hashed = $password_id . '|' . $password_hashed;
		}

		$expire = apply_filters( 'ppw_cookie_expire', $expire );
		$expire = apply_filters( 'ppwp_cookie_expiry', $expire );
		return setcookie( $cookie_name . COOKIEHASH, $password_hashed, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );
	}

	/**
	 * Handle password with data sent by user.
	 *
	 * @param array   $tag_id Tag ID.
	 * @param string  $password      Password.
	 * @param integer $post_id       Post ID.
	 *
	 * @return bool
	 */
	public function is_valid_password( $tag_id, $password, $post_id ) {
		// Get current roles will empty if user using subdomain because path of cookie.
		$password_info = PPW_Repository_Passwords::get_instance()->find_by_shared_tag_password( $password );
		if ( ! $password_info ) {
			return false;
		}
		$this->set_password_to_cookie( $password, self::COOKIE_NAME, $password_info->id );
		$this->set_password_to_cookie( $password, PPW_Constants::WP_POST_PASS );

		do_action( 'ppw_tag_after_set_password_to_cookie', $tag_id, $password_info, $post_id );

		return true;
	}

	/**
	 * Is valid cookie.
	 *
	 * @return bool
	 */
	public function is_valid_cookie() {
		$_cookie = wp_unslash( $_COOKIE );
		if ( ! isset( $_cookie[ self::COOKIE_NAME . COOKIEHASH ] ) ) {
			return false;
		}

		$cookie_value = $_cookie[ self::COOKIE_NAME . COOKIEHASH ];
		$cookie_value = explode( '|', $cookie_value );
		if ( count( $cookie_value ) < 2 ) {
			return false;
		}
		$password_id     = (int) $cookie_value[0];
		$password_hashed = $cookie_value[1];

		$password_info = PPW_Repository_Passwords::get_instance()->get_shared_tag_password( $password_id );
		if ( ! $password_info ) {
			return false;
		}

		if ( ppw_free_check_password( $password_info->password, $password_hashed ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get tags by post ID.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array|WP_Error The requested term data or empty array if no terms found. WP_Error if any of the
	 *                        taxonomies don't exist.
	 * @link   https://developer.wordpress.org/reference/functions/wp_get_post_tags/
	 */
	public function get_tags_by_post_id( $post_id ) {
		$terms = get_the_terms( $post_id, $this->get_current_taxonomy( $post_id ) );

		if ( empty( $terms ) ) {
			return array();
		}

		if(isset($terms->errors)){
			return array();
		}

		return array_map(
			function ( $term ) {
				return $term->term_id;
			},
			$terms
		);
	}

	/**
	 * Get current taxonomy from Post ID.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string Current taxonomy.
	 */
	public function get_current_taxonomy( $post_id ) {
		return apply_filters( 'ppw_tag_get_taxonomy', 'post_tag', $post_id );
	}
}

