(function($) {
    "use strict";

    function liveUpdateCSS(id, css){
        var _tmp_id = id.replace('[', '_').replace(']', '_').replace(/_*$/, '');

        var styleSelector = document.getElementById( _tmp_id );
        if(styleSelector){
            styleSelector.innerHTML = css;
        }
        else{
            var tmpDiv = document.createElement("div");
            tmpDiv.innerHTML = "<style id='"+_tmp_id+"'>" + css + "</style>";
            document.getElementsByTagName("head")[0].appendChild(tmpDiv.childNodes[0])
        }
    }
    wp.customize( 'luxsa_options[la_custom_css]', function( value ) {
        value.bind( function( new_value ) {
            liveUpdateCSS('luxsa-custom-css', new_value);
        });
    });

})(jQuery);