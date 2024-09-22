<?php 
/**
 * PPWP Lite: Category Protection 
 */
?>

<h2 class="ppw-cat__title"> <?php echo esc_html__('PPWP Lite: Category Protection',PPW_Constants::DOMAIN);?></h2>
<form method="post" id="ppwp_protect_category_form" class="ppw-cat__form">
	<input type="hidden" id="ppw_category_form_nonce" value="<?php echo esc_attr( wp_create_nonce(PPW_Constants::GENERAL_FORM_NONCE) ); ?>" />
	<div class="form__option-wrapper">
		<span class="form__option-switch-btn">
			<label class="pda_switch ppw-switch__wrapper" for="ppwp_is_protect_category">
				<input type="checkbox" id="ppwp_is_protect_category" <?php echo $is_protect ? 'checked' : '' ?> />
				<span class="pda-slider round ppw-switch--btn"></span>
			</label>
		</span>
		<span class="form__option-label">
			<?php echo esc_html__('Password Protect Categories', PPW_Constants::DOMAIN) ?>
		</span>
	</div>
	<p class="form__desc"><?php echo wp_kses_post( sprintf( '<a target="_blank" rel="noreferrer noopener" href="%1$s">%2$s</a> %3$s <a href="%4$s">%5$s</a>.', 'https://passwordprotectwp.com/docs/password-protect-wordpress-categories/?utm_source=user-website&utm_medium=category-protection&utm_campaign=ppwp-free', __( 'Protect all posts under protected categories', PPW_Constants::DOMAIN ), __( 'with a single password. Customize the password form using', PPW_Constants::DOMAIN ), admin_url( 'customize.php?autofocus[panel]=ppwp' ), __( 'WordPress Customizer', PPW_Constants::DOMAIN ) ) ); ?></p>
	<p class="form__select-wrapper form__input-wrapper">
		<label class="form__label form__label-cats" for="ppwp-cat-select"><?php echo esc_html__('Select your private categories', PPW_Constants::DOMAIN) ?></label>
		<select class="form__select" id="ppwp_protected_categories_selected" multiple="multiple">
			<?php
			foreach ($categories as $category) {
				$selected = in_array($category->term_id, $protected_categories) ? 'selected' : '';
				echo '<option ' . esc_attr( $selected ) . ' value="' . esc_attr( $category->term_id ) . '">' . esc_html( $category->name ) . '</option>';
			}
			?>
		</select>
		<div id="ppwp_error_protected_categories_selected" style="display: none; color: red; position: absolute; font-size: 12px;">This field is required.</div>
	</p>
	<p class="form__input-wrapper">
		<label class="form__label form__label__pass" for="ppwp_categories_password"><?php echo esc_html__('Set a password', PPW_Constants::DOMAIN) ?></label>
		<input class="form__input" id="ppwp_categories_password" type="text" placeholder="Enter a password" value="<?php echo esc_html( $password ) ?>">
		<div id="ppwp_error_categories_password" style="display: none; color: red; position: absolute; font-size: 12px;">This field is required.</div>
	</p>
	<p class="form__btn-wrapper">
		<input type="submit" name="submit" id="ppwp-submit" class="button button-primary" value="Save Changes">
	</p>
</form>
