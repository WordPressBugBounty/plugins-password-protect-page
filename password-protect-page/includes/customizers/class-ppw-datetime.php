<?php
/**
 * datetime Customizer Control
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if WP_Customize_Control does not exsist.
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * This class is for the datetime control in the Customizer.
 *
 * @access public
 */

class PPW_Datetime_Control extends WP_Customize_Control {

	/**
	 * The type of customize control.
	 *
	 * @access public
	 * @since  1.3.4
	 * @var    string
	 */
	public $type = 'datetime';

	/**
	 * Enqueue scripts and styles.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'ppw-designer-datetime-control-styles', PPW_DIR_URL . 'includes/customizers/assets/ppw-datetime.css', false, PPW_VERSION, 'all' );
		wp_enqueue_script( 'ppw-designer-datetime-control-scripts', PPW_DIR_URL . 'includes/customizers/assets/ppw-datetime.js', array( 'jquery' ), PPW_VERSION, true );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// The setting value.
		$this->json['id']           = $this->id;
		$this->json['value']        = $this->value();
		$this->json['min']          = date('Y-m-d\TH:i', current_time( 'timestamp' ));
	}

	/**
	 * Don't render the content via PHP.  This control is handled with a JS template.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function render_content() {}

	/**
	 * An Underscore (JS) template for this control's content.
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see    WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since  1.3.4
	 * @return void
	 */
	protected function content_template() {
		?>
		<label class="datetime">
			<div class="datetime--wrapper">
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{ data.label }}</span>
				<# } #>
				<# if ( data.description ) { #>
					<span class="customize-control-description"><span>Note:</span> {{ data.description }}</span>
				<# } #>
				<input type="datetime-local" id="datetime-{{ data.id }}" name="datetime" min="{{ data.min }}" value="{{ data.value }}">
				<label for="datetime-{{ data.id }}" class="datetime-label"></label>
			</div>

			<span id="datetime-{{ data.id }}-error-message" class="customize-control-error-message">Input wrong value.</span>
		</label>
		<?php
	}
}