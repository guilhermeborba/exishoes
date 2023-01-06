jQuery(document).ready(function( $ ) {
    // Make the New Whatsapp Button Draggable with jQuery UI
    const $buttonContainer = $( '#wab_cont' ),
        buttonToggleHeightInPx = $( '#whatsAppButton' ).outerHeight() - 12 + 'px', // String
        chevron = $( '#wab_close img.wab-chevron' );

    let buttonToggleWidthInPx = '';

    if ( 'wab-side-rectangle' === php_vars.button_type ) {
        buttonToggleWidthInPx = $buttonContainer.outerWidth() - 12 + 'px';
    }

    if ( 'wab-icon-plain' === php_vars.button_type ) {
        buttonToggleWidthInPx = $buttonContainer.outerWidth() - 14 + 'px';
    }

	$buttonContainer.draggable({ 
		axis: 'y', 
		scroll: false, 
	});

    // Attach hide action to wab_close button
	$( '#wab_close' ).click( function() {
		if ( php_vars.hideButtonType === 'full' )
			$buttonContainer.hide();

		if ( php_vars.hideButtonType === 'hide' ) {
			$buttonContainer.toggleClass( 'wab-hidden' );

			// Change chevrons when hiding/showing button when the button is a bottom rectangle
			if ( php_vars.button_type === 'wab-bottom-rectangle' ) {
				chevron.toggleClass( 'wab-down wab-up' );

				if ( chevron.hasClass( 'wab-down' ) ) {
					chevron.attr( 'src', php_vars.plugins_url+'/add-whatsapp-button/img/chevron-down.svg' );
					$buttonContainer.css( 'bottom', 0 );
				}
				else if ( chevron.hasClass( 'wab-up' ) ) {
					console.log( 'clicked to hide, ' + buttonToggleHeightInPx );
					chevron.attr( 'src', php_vars.plugins_url+'/add-whatsapp-button/img/chevron-up.svg' );
					$buttonContainer.css( 'bottom', '-' + buttonToggleHeightInPx );
				}
			}
			
			// Change chevrons when hiding/showing button when the button is an icon or side button
			if ( php_vars.button_type === 'wab-side-rectangle' || php_vars.button_type === 'wab-icon-plain' ) {
				chevron.toggleClass( 'wab-left wab-right' );

				if ( $buttonContainer.hasClass( 'wab-hidden' ) ) {
					if ( php_vars.button_location === 'left' ) {
						chevron.attr( 'src', php_vars.plugins_url + '/add-whatsapp-button/img/chevron-right.svg' );
					}

					if ( php_vars.button_location === 'right' ) {
						chevron.attr( 'src', php_vars.plugins_url + '/add-whatsapp-button/img/chevron-left.svg' );
					}

					$buttonContainer.css( php_vars.button_location, '-' + buttonToggleWidthInPx );
				}

				if ( ! $buttonContainer.hasClass( 'wab-hidden' ) ) {
					if ( php_vars.button_location === 'left' ) {
						chevron.attr( 'src', php_vars.plugins_url + '/add-whatsapp-button/img/chevron-left.svg' );
					}

					if ( php_vars.button_location === 'right' ) {
						chevron.attr( 'src', php_vars.plugins_url + '/add-whatsapp-button/img/chevron-right.svg' );
					}

					$buttonContainer.css( php_vars.button_location, 0 );
				}
			}
		}
	});
});

// Limit Button Display to certain hours
(function(){
    document.addEventListener( 'DOMContentLoaded', function() {
        const isLimited = php_vars.limitHours;
        const displayTimes = {
            'startHour': php_vars.startHour,
            'endHour': php_vars.endHour
        }

        let currentSystemTime, currentSystemHour;

        currentSystemTime = new Date();
        currentSystemHour = currentSystemTime.getHours();

        if (isLimited == true && (currentSystemHour < displayTimes.startHour === false && currentSystemHour > displayTimes.endHour === false) === false) {
            document.getElementById( 'whatsAppButton' ).style.display = 'none';
        }
    });
})();