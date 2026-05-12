<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="ppw-feedback-overlay">
	<div id="ppw-feedback-modal">
		<h3>
			<?php esc_html_e( "Before you go, could you tell us why you're deactivating", 'password-protect-page' ); ?>
			<span class="ppw-plugin-name"></span>?
		</h3>

		<label>
			<input type="radio" name="reason" value="not_needed" checked>
			<?php esc_html_e( 'I no longer need the plugin', 'password-protect-page' ); ?>
		</label>

		<label>
			<input type="radio" name="reason" value="better_plugin">
			<?php esc_html_e( 'I found a better plugin', 'password-protect-page' ); ?>
		</label>
		<div class="better_plugin_name" style="display:none;">
			<input type="text" name="better_plugin_name" id="better_plugin_name" placeholder="<?php esc_attr_e( 'Would you mind sharing which plugin you chose instead?', 'password-protect-page' ); ?>">
		</div>
		<label>
			<input type="radio" name="reason" value="not_working">
			<?php esc_html_e( 'It didn’t work as expected', 'password-protect-page' ); ?>
		</label>
		<div class="ppw-reason-extra" style="display:none;">
			<textarea name="not_working_reason" id="not_working_reason" placeholder="<?php esc_attr_e( 'Please explain in detail so we can improve our plugin', 'password-protect-page' ); ?>"></textarea>
		</div>
		<label>
			<input type="radio" name="reason" value="temporary">
			<?php esc_html_e( 'Temporary deactivation', 'password-protect-page' ); ?>
		</label>

		<label>
			<input type="radio" name="reason" value="other">
			<?php esc_html_e( 'Other', 'password-protect-page' ); ?>
		</label>

		<textarea name="optional_detail" id="optional_detail" placeholder="<?php esc_attr_e( 'Optional details', 'password-protect-page' ); ?>"></textarea>

		<div class="ppw-actions">
			<button class="button button-primary ppw-submit">
				<span class="ppw-btn-text">
					<?php esc_html_e( 'Submit & Deactivate', 'password-protect-page' ); ?>
				</span>
				<span class="spinner"></span>
			</button>

			<button class="button ppw-skip">
				<?php esc_html_e( 'Skip & Deactivate', 'password-protect-page' ); ?>
			</button>
		</div>
	</div>
</div>