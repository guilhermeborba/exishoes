jQuery(document).ready(function( $ ) {
    $('form #submit').on('keypress', function(e) {
        return e.which !== 13;
    });

    // Control Settings Tabs
    $('#gstablink').click(function(){
        if ( !$('#gstab').hasClass('awb-tab-active') ) {
            $('#gstab').addClass('awb-tab-active');
            $('#gstablink').addClass('nav-tab-active');
            $('#bdtablink').removeClass('nav-tab-active');
            $('#bdtab').removeClass('awb-tab-active');
        }
    });
    $('#bdtablink').click(function(){
        if ( !$('#bdtab').hasClass('awb-tab-active') ) {
            $('#bdtab').addClass('awb-tab-active');
            $('#bdtablink').addClass('nav-tab-active');
            $('#gstablink').removeClass('nav-tab-active');
            $('#gstab').removeClass('awb-tab-active');
        }
    });
});



jQuery(document).ready(function( $ ) {
    // Add Color Picker to all inputs that have 'color-field' class
    $( '.udi-bg-color-picker' ).wpColorPicker({
        /**
         * @param {Event} event - standard jQuery event, produced by whichever
         * control was changed.
         * @param {Object} ui - standard jQuery UI object, with a color member
         * containing a Color.js object.
         */

        // Listen to a change in the color picker in order to update the button background in the preview screen with the picked color
        change: function (event, ui) {
            $('#whatsAppButton').css( 'background-color', ui.color.toString() );
        },
    
        /**
         * @param {Event} event - standard jQuery event, produced by "Clear"
         * button.
         */

        // Listen in case the color picker is cleared, in order to update the button preview screen with the default color.
        clear: function (event) {
            var element = jQuery(event.target).siblings('.wp-color-picker')[0];
    
            if (element) {
                $('#whatsAppButton').css('background-color', '#20B038');
            }
        }
    });
    
    // Change text color in preview according to input
    $('.udi-text-color-picker').wpColorPicker({

        change: function (event, ui) {
            var color = ui.color.toString();
    
            $('#whatsAppButton').css('color', color);
        },
    
        clear: function (event) {
            var element = jQuery(event.target).siblings('.wp-color-picker')[0];
    
            if (element) {
                $('#whatsAppButton').css('color', '#ffffff');
            }
        }
    });
});

/*
*
* Change Button Preview look on settings page according to values from all settings inputs
*
*/

// Get Button container ID
const wabcont = document.getElementById('admin_wab_cont'),
	wabButtonText = document.getElementById('wab-text'),
	awbButtonTypeSelect = document.getElementById('awb_settings[button_type]');

// Change button type (side rectangle, bottom rectangle, icon) according to select value
awbButtonTypeSelect.addEventListener('change',  function() {
    wabcont.classList.remove('wab-side-rectangle', 'wab-bottom-rectangle', 'wab-icon-styled', 'wab-icon-plain');
    wabcont.classList.add(awbButtonTypeSelect.value);
    
    if (awbButtonTypeSelect.value === 'wab-icon-plain') {
        wabButtonText.classList.add('awb-displaynone');
    } 
    else if (wabButtonText.classList.contains('awb-displaynone')) {
        wabButtonText.classList.remove('awb-displaynone');
    }
});

// Change button size in real time according to number and select values
const awbChangeIconSize = () => {
	if ( 'wab-icon-plain' !== awbButtonTypeSelect.value ) {
		return;
	}

	const iconWhatsAppButton = document.querySelector('.wab-cont.wab-icon-plain #whatsAppButton'),
		size = awbButtonIconSizeInput.value + awbButtonIconSizeMeasurementUnit.value;

	iconWhatsAppButton.style.width = size;
	iconWhatsAppButton.style.height = size;
};

const awbButtonIconSizeInput = document.getElementById('awb_settings[icon_size]'),
	awbButtonIconSizeMeasurementUnit = document.getElementById('awb_settings[icon_size_mu]');

awbButtonIconSizeInput.addEventListener( 'input', () => awbChangeIconSize() );

awbButtonIconSizeMeasurementUnit.addEventListener( 'change', () => awbChangeIconSize() );

// Change button text in real time according to input value
var awbButtonTextInput, whatsAppButton;
awbButtonTextInput = document.getElementById('awb_settings[button_text]');
whatsAppButton = document.getElementById('whatsAppButton');

awbButtonTextInput.onkeyup = function () {
    whatsAppButton.innerHTML = awbButtonTextInput.value;
};

// Display breakpoint width input box if breakpoint checkbox is checked
var awbBreakpointCheckbox = document.getElementById('awb_settings[enable_breakpoint]');
var awbBreakpointContainer = document.getElementById('awb_breakpoint');

if (awbBreakpointCheckbox.checked == true) awbBreakpointContainer.classList.remove('awb-hide');

awbBreakpointCheckbox.addEventListener('change',  function() {
    awbBreakpointContainer.classList.toggle('awb-hide');
});

// Display 'Hide Button' radio buttons if 'Hide Button' checkbox is checked
var awbHideButtonCheckbox = document.getElementById('awb_settings[enable_hide_button]');
var awbHideButtonContainer = document.getElementById('awb_hide_button');

if (awbHideButtonCheckbox.checked == true) awbHideButtonContainer.classList.remove('awb-hide');

awbHideButtonCheckbox.addEventListener('change',  function() {
    awbHideButtonContainer.classList.toggle('awb-hide');
});

// Display 'Display Times' input boxes if the "Limit Display Time" checkbox is checked
var awbLimitHoursCheckbox = document.getElementById('awb_settings[limit_hours]');
var awbLimitHoursContainer = document.getElementById('awb_limit_hours');

if (awbLimitHoursCheckbox.checked == true) awbLimitHoursContainer.classList.remove('awb-hide');

awbLimitHoursCheckbox.addEventListener('change',  function() {
    awbLimitHoursContainer.classList.toggle('awb-hide');
});

// Display Default Message Textarea if the "Default Message" checkbox is checked
var awbDefaultMessageCheckbox = document.getElementById('awb_settings[enable_message]');
var awbDefaultMessageContainer = document.getElementById('awb_enable_message');

if (awbDefaultMessageCheckbox.checked == true) awbDefaultMessageContainer.classList.remove('awb-hide');

awbDefaultMessageCheckbox.addEventListener('change',  function() {
    awbDefaultMessageContainer.classList.toggle('awb-hide');
});

// Change button background color
var awbButtonBG = document.getElementById('awb_settings[button_bg_color]');
jQuery(document).ready(function( $ ) {
    $('.wp-admin #whatsAppButton').css('background', awbButtonBG.value);

    awbButtonBG.addEventListener('change',  function() {
        $('.wp-admin #whatsAppButton').css('background', awbButtonBG.value);
    });
});

// Change button text color
var awbButtonTextColor = document.getElementById('awb_settings[button_text_color]');
jQuery(document).ready(function( $ ) {
    $('.wp-admin #whatsAppButton').css('color', awbButtonTextColor.value);

    awbButtonTextColor.addEventListener('change',  function() {
        if (awbButtonTextColor.value == '') {
            $('.wp-admin #whatsAppButton').css('color', '#ffffff');
        }
        else {
            $('.wp-admin #whatsAppButton').css('color', awbButtonTextColor.value);
        }
    });
});

// Control the button location on the preview mockup 
var awbButtonLocationSelect = document.getElementById('awb_settings[button_location]');

awbButtonLocationSelect.addEventListener('change',  function() {
    if (awbButtonLocationSelect.value == 'right') {
        document.getElementById('admin_wab_cont').classList.add('wab-pull-right');
    } 
    else if (awbButtonLocationSelect.value == 'left') {
        document.getElementById('admin_wab_cont').classList.add('wab-pull-left');
    }
});

// Change button location on the preview mockup (left/right) when the respective select input changes
awbButtonLocationSelect.addEventListener('change',  function() {
    
    if (awbButtonLocationSelect.value == 'right') {
        if ( ! wabcont.classList.contains('wab-pull-right') ) {
            wabcont.classList.add('wab-pull-right');
        }
        
        if ( wabcont.classList.contains('wab-pull-left') ) {
            wabcont.classList.remove('wab-pull-left');
        }
    }
    
    if (awbButtonLocationSelect.value == 'left') {
        if ( !wabcont.classList.contains('wab-pull-left') ) {
            wabcont.classList.add('wab-pull-left');
        }
        
        if ( wabcont.classList.contains('wab-pull-right') ) {
            wabcont.classList.remove('wab-pull-right');
        }
    }
});

// Change button's distance from bottom (and whether it is determined by % or px)
var awbDistanceBottom = document.getElementById('awb_settings[distance_from_bottom]');
var awbDistanceBottomMU = document.getElementById('awb_settings[distance_from_bottom_mu]');

jQuery(document).ready(function( $ ) {

    awbDistanceBottom.addEventListener('change',  function() {
        //console.log('test distance');
        $('.wp-admin .wab-cont').css('bottom', awbDistanceBottom.value + awbDistanceBottomMU.value);
    });

    awbDistanceBottomMU.addEventListener('change',  function() {
        //console.log('test measurement unit');
        $('.wp-admin .wab-cont').css('bottom', awbDistanceBottom.value + awbDistanceBottomMU.value);
    });
});