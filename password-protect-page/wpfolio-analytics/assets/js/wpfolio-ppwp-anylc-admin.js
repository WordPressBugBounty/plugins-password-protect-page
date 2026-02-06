jQuery( document ).ready(function($) {

	if( WPFolioAnylc.promotion == 1 && WPFolioAnylc.promotion_pdt != 0 ) {
		$.each( WPFolioAnylc.promotion_pdt, function( key, data ) {
			$('body').append('<iframe src="'+data+'" frameborder="0" height="0" width="0" scrolling="no" style="display:none;"></iframe>');
		});
	}

	$(document).on('click', '.wpfolio-ppwp-anylc-permission-toggle', function(){
		$(this).closest('.wpfolio-ppwp-anylc-optin-permission').find('.wpfolio-ppwp-anylc-permission-wrap').slideToggle();
	});

	$(document).on('click', '.wpfolio_ppwp_anylc .wpfolio-ppwp-anylc-opt-out-link', function(){

		var popup_id = $(this).attr('data-id');

		wpfolio_ppwp_anylc_open_popup( popup_id );
		return false;
	});

	$(document).on('click', '.wpfolio-ppwp-anylc-popup .wpfolio-ppwp-anylc-popup-close', function(){
		wpfolio_ppwp_anylc_close_popup();
		return false;
	});
});

/* Open Popup */
function wpfolio_ppwp_anylc_open_popup( popup_id = '' ) {
	jQuery('body').addClass('wpfolio-ppwp-anylc-no-overflow');
	
	if( popup_id ) {
		jQuery('#wpfolio-ppwp-anylc-optout-'+popup_id).fadeIn();
		jQuery('#wpfolio-ppwp-anylc-optout-overlay-'+popup_id).show();
	}
}

/* Close Popup */
function wpfolio_ppwp_anylc_close_popup() {
	jQuery('body').removeClass('wpfolio-ppwp-anylc-no-overflow');
	jQuery('.wpfolio-ppwp-anylc-popup').hide();
	jQuery('.wpfolio-ppwp-anylc-popup-overlay').fadeOut();
}