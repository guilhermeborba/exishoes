(function($) {
    'use strict';

    var $document = $(document),
        $menu = $('#menu-to-edit');

    $(function(){

        function update_menu_dependency( elem ){
            setTimeout(function(){
                if(elem.hasClass('menu-item-depth-1')){
                    $('.lasf-mm-menu-type select', $('#menu-item-' + $('.menu-item-data-parent-id', elem).val())).trigger('la_admin_menu_event:update_dependency');
                }
                if(elem.hasClass('menu-item-depth-0')){
                    $('.lasf-mm-menu-type select', elem).trigger('la_admin_menu_event:update_dependency');
                }
            },200);
        }

        function build_modal( link ){
            if(!$('#lastudio-menu-template-edit-modal').length){
                var _html = '' +
                    '<div class="dialog-widget dialog-lightbox-widget dialog-type-buttons dialog-type-lightbox" id="lastudio-menu-template-edit-modal">\n' +
                    '    <div class="dialog-widget-content dialog-lightbox-widget-content">\n' +
                    '        <div class="dialog-close-button dialog-lightbox-close-button"><i class="lastudioicon-e-remove"></i></div>\n' +
                    '        <div class="dialog-header dialog-lightbox-header"></div>\n' +
                    '        <div class="dialog-message dialog-lightbox-message"></div>\n' +
                    '        <div class="dialog-buttons-wrapper dialog-lightbox-buttons-wrapper"></div>\n' +
                    '    </div>\n' +
                    '</div>';

                $('body').append(_html);
            }
            var $iframe,
                $loader,
                $modal;

            $( '#lastudio-menu-template-edit-modal .dialog-message').html( '<iframe src="' + link + '" id="lastudio-menu-edit-frame" width="100%" height="100%"></iframe>' );
            $( '#lastudio-menu-template-edit-modal .dialog-message').append( '<div id="lastudio-menu-loading"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>' );
            $modal = $('#lastudio-menu-template-edit-modal');
            $iframe = $( '#lastudio-menu-edit-frame');
            $loader = $( '#lastudio-menu-loading');
            $modal.addClass('la-active');
            $iframe.on( 'load', function() {
                $loader.fadeOut( 300 );
            } );
        }

        function render_edit_button(){
            if(typeof elementorAdminConfig === "undefined"){
                return;
            }
            if($('#menu-to-edit').length){
                $('#menu-to-edit').find('.menu-item .menu-item-bar .item-controls').prepend('<button class="btn-open-mm button button-primary" type="button">Edit MegaMenu</button>');
            }
            $('#menu-to-edit li.menu-item .lasf-mm-menu-type select').each(function (){
                $(this).closest('li.menu-item').attr('data-menutype', $(this).val());
            });
        }

        render_edit_button();

        $document
            .on('sortstart', '#menu-to-edit', function(e, ui){
                $('.menu-item-settings', ui.item).hide();
            })
            .on( 'sortstop','#menu-to-edit', function( event, ui ) {
                ui.item.removeClass('menu-item-edit-active').addClass('menu-item-edit-inactive');
                update_menu_dependency(ui.item);
            })
            .on( 'la_admin_menu_event:update_dependency', '.lasf-mm-menu-type select', function(e){
                var _select = $(this),
                    $li = _select.closest('li.menu-item'),
                    $sub = $('.menu-item-data-parent-id[value="'+$li.attr('id').replace('menu-item-','')+'"]').closest('.menu-item');
                if(_select.val() == 'wide'){
                    $('.lasf-mm-popup-column,.lasf-mm-popup-max-width,.lasf-mm-popup-background,.lasf-mm-force-full-width', $li).addClass('show');
                    $('.lasf-mm-item-column,.lasf-mm-block,.lasf-mm-block2', $sub).addClass('show');
                }
                else{
                    $('.lasf-mm-popup-column,.lasf-mm-popup-max-width,.lasf-mm-popup-background,.lasf-mm-force-full-width', $li).removeClass('show');
                    $('.lasf-mm-item-column,.lasf-mm-block,.lasf-mm-block2', $sub).removeClass('show');
                }
            })
            .on('click', '#lastudio-menu-template-edit-modal .dialog-close-button', function (e){
                e.preventDefault();
                if($('#lastudio-menu-template-edit-modal').length){
                    $('#lastudio-menu-template-edit-modal').remove();
                }
            })
            .on('menu-item-added', function (e, data){
                if(typeof elementorAdminConfig !== "undefined"){
                    data.find('.menu-item-bar .item-controls').prepend('<button class="btn-open-mm button button-primary" type="button">Edit MegaMenu</button>');
                }
            })
        $menu
            .on( 'click', 'a.item-edit', function(){
                var $menu_item = $(this).closest('li.menu-item');

                $('.lastudio-megamenu-custom-fields', $menu_item).lasf_reload_script();
                setTimeout(function(){
                    update_menu_dependency($menu_item);
                }, 100);
            })
            .on( 'change', '.lasf-mm-menu-type select', function(e){
                $(this).closest('li.menu-item').attr('data-menutype', $(this).val());
                $(this).trigger('la_admin_menu_event:update_dependency');
            })
            .on( 'click', '.menus-move', function(){
                update_menu_dependency($(this).closest('li.menu-item'));
            })
            .on( 'click', '.btn-open-mm', function (e){
                e.preventDefault();
                var _id = $(this).closest('li.menu-item').find('.menu-item-data-db-id').val();
                build_modal( luxsa_admin_vars.edit_post_link.replace('{post_id}', _id) );
            })
    });

})(jQuery);

(function($) {
    'use strict';

    $(function () {

        if(typeof pagenow !== "undefined" && pagenow === "widgets"){
            $( '.widget-liquid-right' ).append( luxsa_admin_vars.widget_info );

            var $create_box = $( '#la_pb_widget_area_create' ),
                $widget_name_input = $create_box.find( '#la_pb_new_widget_area_name' ),
                $la_pb_sidebars = $( 'div[id^=luxsa_widget_area_]' );

            $create_box.find( '.la_pb_create_widget_area' ).on('click', function( event ) {
                var $this_el = $(this);

                event.preventDefault();

                if ( $widget_name_input.val() === '' ) return;

                $.ajax( {
                    type: "POST",
                    url: luxsa_admin_vars.ajaxurl,
                    data:
                        {
                            action : 'luxsa_core_action',
                            router : 'add_sidebar',
                            admin_load_nonce : luxsa_admin_vars.admin_load_nonce,
                            widget_area_name : $widget_name_input.val()
                        },
                    success: function( data ){
                        if(data.success){
                            $this_el.closest( '#la_pb_widget_area_create' ).find( '.la_pb_widget_area_result' ).hide().html( data.data.message ).slideToggle();
                        }
                    }
                } );
            } );

            $la_pb_sidebars.each( function() {
                if ( $(this).is( '#la_pb_widget_area_create' ) || $(this).closest( '.inactive-sidebar' ).length ) return true;

                $(this).closest('.widgets-holder-wrap').find('.sidebar-name h2, .sidebar-name h3').before( '<a href="#" class="la_pb_widget_area_remove">' + luxsa_admin_vars.delete_string + '</a>' );

                $( '.la_pb_widget_area_remove' ).on('click' ,function( event ) {
                    var $this_el = $(this);

                    event.preventDefault();

                    if(confirm(luxsa_admin_vars.confirm_delete_string)){
                        $.ajax( {
                            type: "POST",
                            url: luxsa_admin_vars.ajaxurl,
                            data:
                                {
                                    action : 'luxsa_core_action',
                                    router : 'remove_sidebar',
                                    admin_load_nonce : luxsa_admin_vars.admin_load_nonce,
                                    widget_area_name : $this_el.closest( '.widgets-holder-wrap' ).find( 'div[id^=luxsa_widget_area_]' ).attr( 'id' )
                                },
                            success: function( data ){
                                if(data.success){
                                    $( '#' + data.data.sidebar_id ).closest( '.widgets-holder-wrap' ).remove();
                                }
                            }
                        } );
                    }

                    return false;
                } );
            } );
        }
    })

})(jQuery);