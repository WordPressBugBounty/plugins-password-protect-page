( function( $, api ) {

	api.controlConstructor['datetime'] = api.Control.extend( {

		ready: function() {
			var control = this;
			this.container.on( 'change', 'input[type="datetime-local"]', function() {
				if (control.params.id === 'ppwp_sitewide_start_time' && this.value ) {
					var endTime = document.getElementById("datetime-ppwp_sitewide_end_time");
					endTime.min = this.value;
				}
				if (control.params.id === 'ppwp_sitewide_start_time' && !this.value ) {
					var endTime = document.getElementById("datetime-ppwp_sitewide_end_time");
					endTime.min = this.min;
				}
				if (control.params.id === 'ppwp_sitewide_end_time' ) {
					document.getElementById('datetime-ppwp_sitewide_end_time-error-message').style.display = 'none';
					var startTime = document.getElementById("datetime-ppwp_sitewide_start_time").value;
					var countDownDateStart = new Date(startTime).getTime(); 
					var countDownDateEnd = new Date(this.value).getTime();
					if ( countDownDateStart > countDownDateEnd ) {
						document.getElementById('datetime-ppwp_sitewide_end_time-error-message').style.display = 'block';
					}
				}
				value = this.value;
				control.setting.set( value );
			} );
		}

	} );

} )( jQuery, wp.customize );