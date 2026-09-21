/* PPWP Customizer Js */

(function ($, api) {
  var customizePrefix = '_customize-input-';
  var themePrefix = 'ppw_customize_presets_settings';
  var defaultShowLogo = null;
  var defaultData = {
    'default1': {
	  '#customize-control-ppwp_pro_form_instructions_background_color_control input.wp-color-picker' : ''
	},
	'default2': {
	  '#customize-control-ppwp_pro_form_instructions_background_color_control input.wp-color-picker' : ''
	},
	'default3': {
	  '#customize-control-ppwp_pro_form_instructions_background_color_control input.wp-color-picker' : ''
	},
  };

  // Editor control.
  $(document).ready(function($) {

	// wp_editor() controls: an editor's id is also its setting id.
	var lastBookmark = {};
	var restoreTimers = {};

	function editorHasFocus( id ) {
	  try {
		return !!( document.activeElement && document.activeElement.id === id + '_ifr' );
	  } catch ( e ) {
		return false;
	  }
	}

	// Writing the setting is what marks the changeset dirty and enables Publish.
	function updateSetting( id, content ) {
	  var setting = api && api( id );

	  if ( setting && setting.get() !== content ) {
		setting.set( content );
	  }
	}

	function captureBookmark( editor ) {
	  try {
		lastBookmark[ editor.id ] = editor.selection.getBookmark( 2, true );
	  } catch ( e ) {}
	}

	// Writing a setting reloads the preview, which wipes the caret inside a focused
	// TinyMCE editor (core behaviour), so put it back once the reload has settled.
	function restoreCaret( editor ) {
	  if ( ! editorHasFocus( editor.id ) || ! lastBookmark[ editor.id ] ) {
		return;
	  }
	  if ( editor.isHidden && editor.isHidden() ) {
		return;
	  }

	  try {
		editor.selection.moveToBookmark( lastBookmark[ editor.id ] );
	  } catch ( e ) {}
	}

	function scheduleCaretRestore( editor ) {
	  window.clearTimeout( restoreTimers[ editor.id ] );
	  restoreTimers[ editor.id ] = window.setTimeout( function () {
		restoreCaret( editor );
	  }, 250 );
	}

	// Text / Code tab.
	function syncTextarea( id ) {
	  var $textarea = $( '#' + id );

	  if ( ! id || ! $textarea.length || $textarea.data( 'ppwpSynced' ) || ! ( api && api( id ) ) ) {
		return;
	  }
	  $textarea.data( 'ppwpSynced', true );

	  $textarea.on( 'input.ppwp keyup.ppwp change.ppwp paste.ppwp cut.ppwp', function () {
		updateSetting( id, $textarea.val() );
	  } );
	}

	// Visual tab. Listens only - never initialises, destroys or re-renders the editor.
	function syncEditor( editor ) {
	  if ( ! editor || editor.ppwpSynced || ! ( api && api( editor.id ) ) ) {
		return;
	  }
	  editor.ppwpSynced = true;

	  editor.on( 'keyup nodechange', function () {
		captureBookmark( editor );
	  } );

	  editor.on( 'input keyup change SetContent ExecCommand Undo Redo', function () {
		editor.save(); // Keeps the backing textarea in sync for Visual <-> Text switching.

		var content = editor.getContent(),
		  setting = api && api( editor.id );

		if ( ! setting || setting.get() === content ) {
		  return;
		}

		captureBookmark( editor );
		setting.set( content );
		scheduleCaretRestore( editor );
	  } );

	  syncTextarea( editor.id );
	}

	// WordPress fires this on document for every TinyMCE instance it creates.
	$( document ).on( 'tinymce-editor-init.ppwp', function ( event, editor ) {
	  syncEditor( editor );
	} );

	$( 'textarea.wp-editor-area' ).each( function () {
	  syncTextarea( $( this ).attr( 'id' ) );
	} );

	if ( window.tinymce && tinymce.editors ) {
	  $.each( tinymce.editors, function ( i, editor ) {
		syncEditor( editor );
	  } );
	}

	$('.customize-control-ppw-presets input[type="radio"]').on('change', function () {
	  var theme = $(this).val();
	  Object.keys(defaultData).forEach(function(theme) {
	    if ( $('#' + themePrefix + theme).is(':checked') ) {
	      	var themeData = defaultData[theme];
			Object.keys(themeData).forEach(function(themeKey){
			  changeInput(themeKey, themeData[themeKey], 'change');
			});
		}
	  });
	  var checkbox_values = $(this)
		.parents('.customize-control')
		.find('input[type="radio"]:checked')
		.val();
	  $(this)
		.parents('.customize-control')
		.find('input[type="hidden"]')
		.val(checkbox_values)
		.delay(500)
		.trigger('change');
	  $logo = $('#toggle-ppwp_pro_logo_disable_control');
	  if (defaultShowLogo !== null) {
		defaultShowLogo = $logo.is(':checked');
	  }
	  if ( 'default0' !== theme && $logo.length > 0) {
		$logo.prop('checked', true).trigger('input');
	  } else {
		$logo.prop('checked', defaultShowLogo).trigger('input');
	  }
	});

	function changeInput( key, value, type = 'input' ) {
	  $(key).val(value).delay(500).trigger(type);
	}

  });


})(jQuery, wp.customize);
