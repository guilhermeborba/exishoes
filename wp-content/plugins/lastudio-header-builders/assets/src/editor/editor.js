window.onload = function () {
    document.body.style.display = "block";
};

(function ($) {

    "use strict";

    $(function(){

        $(document).on('keyup', '.lastudio-icons-list-wrapper .la-icon-wrap-search', function () {
            var value  = $(this).val(),
                $icons = $(this).next('.lastudio-icons-list').find('.la-icon-wrap');

            $icons.each(function() {
                var $ico = $(this);
                if ( $ico.data('name').search( new RegExp( value, 'i' ) ) < 0 ) {
                    $ico.hide();
                }
                else {
                    $ico.show();
                }

            });
        });

        function addQueryArg( url, key, value ){
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = url.indexOf('?') !== -1 ? "&" : "?";
            if (url.match(re)){
                return url.replace(re, '$1' + key + "=" + value + '$2');
            }
            else{
                return url + separator + key + "=" + value;
            }
        }

        function removeQueryArg ( url, parameter ) {
            var urlparts= url.split('?');
            if (urlparts.length>=2) {
                var prefix= encodeURIComponent(parameter)+'=';
                var pars= urlparts[1].split(/[&;]/g);
                //reverse iteration as may be destructive
                for (var i= pars.length; i-- > 0;) {
                    //idiom for string.startsWith
                    if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                        pars.splice(i, 1);
                    }
                }
                url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
                return url;
            }
            else {
                return url;
            }
        }

        function stripHtml(html) {
            let tmp = document.createElement("DIV");
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || "";
        }

        function getUniqueID(){
            var text = "";
            var possible = "abcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < 13; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }


            /**
             * Header Builder - Global Variables
             * @author    LaStudio
             * @version    1.0.0
             */
                // DOM variables
            let $body = $('body');
            let $wrap = $('#lastudio-backend-header-builder');
            let $sortablePlaces = $wrap.find('.lahb-elements-place');
            let $sortableRows = $wrap.find('.lahb-row-sortable');
            let $desktopSortablePlaces = $wrap.find('.lahb-desktop-panel').find('.lahb-row[data-labhrow="row1"]').find('.lahb-elements-place');
            let $currentElement;
            let $currentModalEdit;

            $body.addClass('lahb-desktop-device');

            // Data variables
            let components = lahb_localize.components ? lahb_localize.components : {};
            let editorComponents = lahb_localize.editor_components ? lahb_localize.editor_components : {};
            let frontendComponents = lahb_localize.frontend_components ? lahb_localize.frontend_components : {};
            const platforms = {
                "desktop-view": {},
                "tablets-view": {},
                "mobiles-view": {}
            };

            // Position variables
            let currentCell;
            let currentRow;
            let currentPanel;

            // Import button flag
            let importButtonFlag = false;

            // Clipboard element
            let $clipboardElem;


            $body.removeClass('admin-bar');

            /**
             * Header Builder - Helper Functions
             * @author    LaStudio
             * @version    1.0.0
             */
            function lahbDebug() {
                return;
                console.log('%c Components:', 'font-size: 18px; background: #EC9787; color: #fff;', components);
                console.log('%c Editor Components:', 'font-size: 18px; background: #6F9FD8; color: #fff;', editorComponents);
                console.log('%c Frontend Components:', 'font-size: 18px; background: #ECDB54; color: #fff;', frontendComponents);
            }

            lahbDebug();

            // check string is json
            function lahbIsJson(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            }

            function lahbIsObject(obj) {
                return obj === Object(obj);
            }

            // Check for emptiness object
            function lahbIsEmptyObj(obj) {
                for (let key in obj) {
                    return false;
                }
                return true;
            }

            function lahbIsFrontendBuilder() {
                if ($body.hasClass('lastudio-frontend-builder-wrap')) {
                    return true;
                }
                return false;
            }


            /**
             * Header Builder - Editor Preview
             * @author    LaStudio
             * @version    1.0.0
             */

            for (let key in components) {
                if (components.hasOwnProperty(key)) {
                    const element = components[key];
                    if (key.search('logo') != -1) {
                        let elemUniqueID = key.slice('5');
                        $wrap.find('.lahb-elements-item[data-element="logo"][data-unique-id="' + elemUniqueID + '"]').each(function () {
                            let $logo = $(this);
                            wp.media.attachment(element.logo).fetch().done(function () {
                                $logo.children('a').hide().after('<img class="lahb-img-placeholder-el" src="' + this.attributes.url + '" alt="">');
                            });
                        });
                    }
                }
            }


            $(document).on('click', '#lahb-cleardata', function (e) {
                e.preventDefault();
                if (confirm(lahb_localize.i18n.clear_data_text) ) {
                    let tmp_editor_components = $.extend(true, {}, lahb_localize.default_data.editor_components);
                    components = {};
                    lahb_localize.components = {};
                    editorComponents = tmp_editor_components;
                    frontendComponents = lahb_localize.default_data.frontend_components;
                    $wrap.find('.lahb-col .lahb-elements-item').remove();
                    $wrap.find('[data-unique-id]').map(function () {
                        $(this).attr('data-unique-id', lahb_localize.default_data.uniqid + $(this).attr('data-unique-id').substr(13));
                    });
                    $('.lahb-tab-panel .lahb-row:not([data-labhrow="row1"])').removeClass('row-order-2 row-order-3 row-order-2').addClass('row-order-1 lahb-row-hidden').css('opacity', '0.45');
                    $('.lahb-tab-panel .lahb-row[data-labhrow="row1"]').removeClass('lahb-row-hidden row-order-2 row-order-3 row-order-2').addClass('row-order-1').css('opacity', '1');
                    lahbSaveAllData();
                    lahbDebug();
                }
            });

            /**
             * Header Builder - Frontend Builder Iframe Height
             * @author    LaStudio
             * @version    1.0.0
             */
            $('#LaStudo_Iframe').css('height', window.innerHeight - 48);


            /**
             * Header Builder - Nice Scroll
             * @author    LaStudio
             * @version    1.0.0
             */

            $('.lahb-frontend-builder').niceScroll({
                cursorborder: "0",
                background: "#e7e7e7",
                cursorcolor: "#91989e"
            });


            /**
             * Header Builder - Tab
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            $wrap.find('.lahb-tab-panel' + $wrap.find('.lahb-tabs-list').find('li.w-active').find('a').attr('href')).addClass('tab-active').show();
            $(document).on('click', '.lahb-tabs-list a', function (event) {
                event.preventDefault();

                let $this = $(this);
                let $currentTab = $this.parent();
                let $tabs = $this.closest('ul').find('li');
                let $tabUl = $this.closest('ul');

                if($tabUl.hasClass('lahb-tabs-device-controls')){
                    return;
                }

                // active current navigation
                $tabs.removeClass('w-active');
                $currentTab.addClass('w-active');

                // show panel
                if ($tabUl.hasClass('lahb-element-groups')) {
                    $wrap.find('.lahb-group-panel').hide().end().find('.lahb-group-panel[data-id="' + $this.attr('href') + '"]').show();
                }
                else if ($tabUl.hasClass('lahb-styling-groups')) {
                    $tabUl.siblings('.lahb-styling-group-panel').hide().end().siblings('.lahb-styling-group-panel[data-id="' + $this.attr('href') + '"]').show();
                }
                else if ($tabUl.hasClass('lahb-styling-screens')) {
                    $wrap.find('.lahb-styling-screen-panel').hide().end().find('.lahb-styling-screen-panel[data-id="' + $this.attr('href') + '"]').show();
                }
                else {
                    $wrap.find('.lahb-tab-panel:not(.lahb-group-panel)').hide().end().find('.lahb-tab-panel' + $this.attr('href')).fadeIn(300);
                }
            });

            $(document).on('click', '.lahb-tabs-list.lahb-tabs-device-controls a', function (e) {
                e.preventDefault();
                let currentDevice = $(this).attr('data-device-mode');
                let $activeLI = $('.lahb-tabs-device-controls a[data-device-mode="'+currentDevice+'"]').closest('li');
                let $otherLI = $activeLI.siblings('li');

                $otherLI.removeClass('w-active');
                $activeLI.addClass('w-active');

                $('a', $activeLI).each(function () {
                    let _panel_id = $(this).attr('href');
                    $('.lahb-tab-panel.lahb-styling-screen-panel[data-id="'+_panel_id+'"]').show();
                    $(_panel_id + '.lahb-tab-panel').addClass('tab-active').show();
                });

                $('a', $otherLI).each(function () {
                    let _panel_id = $(this).attr('href');
                    $('.lahb-tab-panel.lahb-styling-screen-panel[data-id="'+_panel_id+'"]').hide();
                    $(_panel_id + '.lahb-tab-panel').removeClass('tab-active').hide();
                });

                switch (currentDevice) {
                    case 'tablets':
                        $body.removeClass('lahb-desktop-device lahb-mobiles-device').addClass('lahb-tablets-device');
                        break;

                    case 'mobiles':
                        $body.removeClass('lahb-desktop-device lahb-tablets-device').addClass('lahb-mobiles-device');
                        break;

                    default:
                        $body.removeClass('lahb-tablets-device lahb-mobiles-device').addClass('lahb-desktop-device');
                }
            });

            /**
             * Header Builder - Full Modal
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            // Show full modal
            $(document).on('click', '.lahb-full-modal-btn', function (e) {
                e.preventDefault();

                let $this = $(this);
                let modalTarget = $this.data('modal-target');
                let $modal = $wrap.find('.lahb-full-modal[data-modal="' + modalTarget + '"]');

                $body.css('overflow', 'hidden');
                $modal.find('textarea').val(' ');
                $modal.fadeIn(200);
            });
            // Hide full modal
            $(document).on('click', '.lahb-full-modal-close', function (e) {
                e.preventDefault();

                $body.css('overflow', 'initial');
                $wrap.find('.lahb-full-modal').hide();
            });


            /**
             * Header Builder - Add Element Button
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            // Show add element modal
            $(document).on('click', '.w-add-element', function (event) {
                event.preventDefault();

                let $this = $(this);

                $wrap.find('.lahb-elements').show();
                currentPanel = $this.closest('.lahb-tab-panel').attr('id'); // desktop-view, tablets-view, mobiles-view, sticky-view
                currentRow = $this.closest('.lahb-row').attr('data-labhrow'); // topbar, row1, row2, row3
                currentCell = $this.attr('data-align-col'); // left, center, right
            });
            // Hide add element modal
            $(document).on('click', '.lahb-modal-header i, .lahb_close', function (event) {
                event.preventDefault();

                // Color picker
                try{
                    $wrap.find('.lahb-color-picker').data('wpWpColorPicker').close();
                }catch (e) {

                }
                $wrap.find('.lahb-modal-wrap').hide();
                $wrap.find('.lahb-modal-wrap[data-element-target]').remove();
            });
            // Append new element to editor
            $(document).on('click', '.lahb-modal-wrap .lahb-elements-item a', function (event) {
                event.preventDefault();

                let $this = $(this);
                let uniqueID = new Date().valueOf();
                let editorIcon = $(this).find('i').attr('class');
                let controlsHtml = `<span class="lahb-controls"><span class="lahb-tooltip tooltip-on-top" data-tooltip="Copy to Clipboard"><i class="lahb-control lahb-copy-btn dashicons dashicons-admin-page"></i></span><span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings"><i class="lahb-control lahb-edit-btn dashicons dashicons-edit"></i></span><span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide"><i class="lahb-control lahb-hide-btn dashicons dashicons-visibility"></i></span><span class="lahb-tooltip tooltip-on-top" data-tooltip="Remove"><i class="lahb-control lahb-delete-btn dashicons dashicons-trash"></i></span></span>`;

                uniqueID += $this.parent().attr('data-element').replace('-', '');

                if ($this.closest('.lahb-elements-item').hasClass('lahb-clipboard-item')) {
                    let $elem = $this.parent();
                    let elemID = $elem.data('unique-id').toString();

                    for (const key in components) {
                        if (components.hasOwnProperty(key)) {
                            const element = $.extend(true, {}, components[key]);
                            if (elemID == key) {
                                components[uniqueID] = element;
                            }
                        }
                    }
                    $currentElement = $clipboardElem.clone().removeClass('w-col-sm-4').attr({'data-unique-id': uniqueID}).prepend(controlsHtml);
                }
                else {
                    $currentElement = $this.parent().clone().removeClass('w-col-sm-4').attr({
                        'data-unique-id': uniqueID,
                        'data-hidden_element': false,
                        'data-editor_icon': editorIcon
                    }).prepend(controlsHtml);
                }

                $wrap.find('.lahb-row[data-labhrow="' + currentRow + '"]').find('.lahb-col.col-' + currentCell).find('.lahb-elements-place').append($currentElement);
                $wrap.find('.lahb-modal-wrap').hide();
                lahbElementSettings($currentElement, currentPanel, true);
            });


            /**
             * Header Builder - Copy Button
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            $(document).on('click', '.lahb-copy-btn', function (event) {
                event.preventDefault();

                let $elem = $(this).closest('.lahb-elements-item').clone();

                $clipboardElem = $elem.clone();

                // Remove all clipboard item
                $('.lahb-clipboard-item').remove();

                // Create new clipboard item
                $elem.removeClass('ui-sortable-handle').addClass('w-col-sm-4 lahb-clipboard-item');
                $elem.children('.lahb-controls').remove().end().children('img').remove();
                $elem.children('a').css({'display': 'block', 'background-color': '#e3e3e3'});
                $elem.find('i').removeClass('dashicons dashicons-admin-page').addClass('dashicons dashicons-admin-page').css('color', '#f60');
                $elem.find('.lahb-element-name').text('Paste (Clipboard)');

                // Append clipboard item to elements box
                $('.lahb-modal-wrap.lahb-elements').find('.lahb-modal-contents').prepend($elem);
            });


            /**
             * Header Builder - Delete Button
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            $(document).on('click', '.lahb-delete-btn', function (event) {
                event.preventDefault();

                if (confirm('Press OK to delete element, Cancel to leave')) {
                    let $elem = $(this).closest('.lahb-elements-item');
                    let elemName = $elem.data('element');
                    let elemUniqueID = $elem.data('unique-id').toString();
                    let elemID = elemUniqueID;

                    currentPanel = $elem.closest('.lahb-tab-panel').attr('id'); // desktop-view, tablets-view, mobiles-view, sticky-view
                    currentRow = $elem.closest('.lahb-row').attr('data-labhrow'); // topbar, row1, row2, row3
                    currentCell = $elem.closest('.lahb-col').attr('data-align-col'); // left, center, right

                    // delete from components
                    delete components[elemID];

                    // delete from editor components
                    if (currentPanel == 'sticky-view') {
                        let cell = editorComponents[currentPanel][currentRow][currentCell];

                        for (let i = 0; i < cell.length; i++) {
                            if (cell[i].uniqueId == elemUniqueID) {
                                cell.splice(i, 1);
                            }
                        }
                    } else {
                        for (let platform_key in platforms) {
                            let findInCell = false;
                            let panel = editorComponents[platform_key];

                            (function () {
                                for (let rowKey in panel) {
                                    let row = panel[rowKey];

                                    for (let cell_key in row) {
                                        let cell = row[cell_key];

                                        for (let i = 0; i < cell.length; i++) {
                                            if (cell[i].uniqueId == elemUniqueID) {
                                                cell.splice(i, 1);
                                                return; // return to anonymous function
                                            }
                                        }
                                    }
                                }
                            })();
                        }
                    }

                    lahbCreateFrontendComponents();
                    lahbSaveAllData();
                    lahbDebug();

                    $wrap.find('.lahb-elements-item[data-element="' + elemName + '"][data-unique-id="' + elemUniqueID + '"]').remove();
                }
            });


            $(document).on('click', '#lahb-saveastpl', function (e) {
                e.preventDefault();

                let $currentSaveModal = $wrap.find('.lahb-modal-save-header');
                $currentModalEdit = $currentSaveModal;
                $currentSaveModal.show();

                lahbFields();

                // Dependency
                $currentSaveModal.find('.lahb-dependency').on('change', function () {
                    lahbFieldDependency($(this));
                });
                $currentSaveModal.find('.lahb-dependency').trigger('change');
            });

            $(document).on('click', '.btn-complete-edits', function (e) {
                e.preventDefault();
                var presetObj = $(this).data('preset');
                var data_send = {
                    action: 'lahb_ajax_action',
                    router: 'save_header_as_template',
                    nonce: lahb_localize.nonce,
                    frontendComponents: JSON.stringify(frontendComponents),
                    header_name : presetObj.header_name,
                    header_key: presetObj.header_key,
                    header_image : presetObj.header_image
                }

                if ($wrap.children('.lahb-spinner-wrap').length == 0) {
                    $wrap.prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                }

                $.ajax({
                    type: 'POST',
                    url: lahb_localize.ajaxurl,
                    data: data_send,
                    dataType: "json",
                    success: function (data) {
                        $wrap.children('.lahb-spinner-wrap').remove();
                        if(data.success){
                            alert('Saved successfully!');
                        }
                        else{
                            alert('Sorry, please try again!');
                        }
                    }
                });
            });

            $(document).on('click', '.lahb_save_as_template', function (e) {
                e.preventDefault();
                let $currentSaveModal = $wrap.find('.lahb-modal-save-header');
                let $ch_type = $currentSaveModal.find('select[data-field-name="lahb_save_header_type"]');
                let $ch_existing = $currentSaveModal.find('select[data-field-name="lahb_save_header_type_existing"]');
                let $ch_name = $currentSaveModal.find('input[data-field-name="lahb_save_header_type_new"]');
                let $ch_image = $currentSaveModal.find('input[data-field-name="lahb_save_header_custom_image"]');
                if($ch_type.val() == 'add_new' && $ch_name.val() == ''){
                    alert('Please enter the new header name !');
                    return false;
                }
                if($ch_type.val() == 'update' && $('option', $ch_existing).length == 0){
                    alert('Sorry currently you haven\'t any data yet - please use Add New method');
                    return false;
                }
                let data_send = {
                    action: 'lahb_ajax_action',
                    router: 'save_header_as_template',
                    nonce: lahb_localize.nonce,
                    frontendComponents: JSON.stringify(frontendComponents),
                    header_name : '',
                    header_key: '',
                    header_image : ''
                }
                if($ch_image.val() != ''){
                    data_send.header_image = $ch_image.val();
                }
                if($ch_type.val() == 'add_new'){
                    data_send.header_name = $ch_name.val();
                }
                if($ch_type.val() == 'update'){
                    data_send.header_name = $('option:selected', $ch_existing).text();
                    data_send.header_key = $ch_existing.val();
                }

                $.ajax({
                    type: 'POST',
                    url: lahb_localize.ajaxurl,
                    data: data_send,
                    dataType: "json",
                    success: function (data) {
                        if(data.success){
                            let __html = '';
                            let __option_html = '';
                            for ( let key in data.data ){
                                let obj = data.data[key];
                                __option_html += '<option value="'+key+'">'+ obj.name +'</option>';

                                let _query_args = addQueryArg(window.location.href.replace('#',''), 'prebuild_header', key);

                                if(typeof obj.image !== "undefined" ){
                                    __html += '<a class="lahb-prebuild-item" data-saved-name="'+key+'" href="'+_query_args+'"><img src="'+obj.image+'" alt="'+obj.name+'"/><i class="dashicons dashicons-trash"></i></a>'
                                }
                                else{
                                    __html += '<a class="lahb-prebuild-item" data-saved-name="'+key+'" href="'+_query_args+'"><span>'+obj.name+'</span><i class="dashicons dashicons-trash"></i></a>'
                                }
                            }
                            $('.lahb-predefined-modal-inner-content').html(__html);
                            $('.lahb-modal-save-header select[data-field-name="lahb_save_header_type_existing"]').html(__option_html);
                            alert('Saved successfully!');
                            $currentSaveModal.hide();
                        }
                        else{
                            alert('Sorry, please try again!');
                        }
                    }
                });

            });


            /**
             * Header Builder - Edit Button
             *
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            $(document).on('click', '.lahb-edit-btn', function (event) {
                event.preventDefault();

                let $this = $(this);

                $wrap.find('.lahb-modal-wrap').hide();
                $wrap.find('.lahb-modal-wrap[data-element-target]').remove();
                $currentElement = $this.closest('.lahb-elements-item');
                currentPanel = $this.closest('.lahb-tab-panel').attr('id');
                lahbElementSettings($currentElement, currentPanel, false);
            });


            /**
             * Header Builder - Edit Column Button
             *
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            $(document).on('click', '.w-edit-column', function (event) {
                event.preventDefault();
                let $this = $(this);
                $wrap.find('.lahb-modal-wrap').hide();
                $wrap.find('.lahb-modal-wrap[data-element-target]').remove();
                $currentElement = $this;
                currentPanel = $this.closest('.lahb-tab-panel').attr('id');
                lahbElementSettings($currentElement, currentPanel, false);
            });


            /**
             * Header Builder - Hidden Button
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            // row
            $wrap
                .find('.lahb-tabs-panels').find('.lahb-row')
                .children('.lahb-elements-item[data-hidden_element="true"]')
                .find('i.lahb-hide-btn').removeClass('dashicons dashicons-visibility').addClass('dashicons dashicons-hidden').closest('.lahb-tooltip').attr('data-tooltip', 'Show')
                .closest('.lahb-row').addClass('lahb-row-hidden').css('opacity', '0.45');

            // elements
            $wrap
                .find('.lahb-elements-place').find('.lahb-elements-item[data-hidden_element="true"]')
                .find('i.lahb-hide-btn').removeClass('dashicons dashicons-visibility').addClass('dashicons dashicons-hidden').closest('.lahb-tooltip').attr('data-tooltip', 'Show');

            $(document).on('click', '.lahb-hide-btn', function (event) {
                event.preventDefault();

                // Get variables
                let $this = $(this);
                let $elem = $this.closest('.lahb-elements-item');
                let elemName = $elem.data('element');
                let elemUniqueID = $elem.data('unique-id').toString();
                let elemID = elemUniqueID;
                let hidden_element;
                let mustBeHidden;

                // Position of the Current Element
                currentPanel = $elem.closest('.lahb-tab-panel').attr('id'); // desktop-view, tablets-view, mobiles-view, sticky-view
                currentRow = $elem.closest('.lahb-row').attr('data-labhrow'); // topbar, row1, row2, row3
                currentCell = $elem.closest('.lahb-col').attr('data-align-col'); // left, center, right

                if (elemName == 'header-area' || elemName == 'sticky-area') {
                    // Toggle hidden_element value : ( true | false )
                    let hidden_element = editorComponents[currentPanel][currentRow]['settings']['hidden_element'];
                    editorComponents[currentPanel][currentRow]['settings']['hidden_element'] = !hidden_element;
                    mustBeHidden = !hidden_element;
                    $elem.attr('data-hidden_element', !hidden_element).data('hidden_element', !hidden_element);

                    // Change row opacity and eye icon
                    let $row = $elem.closest('.lahb-row');
                    if (mustBeHidden) {
                        $this.removeClass('dashicons dashicons-visibility').addClass('dashicons dashicons-hidden').closest('.lahb-tooltip').attr('data-tooltip', 'Show');
                        $row.addClass('lahb-row-hidden').css('opacity', '0.45');
                    }
                    else {
                        $this.removeClass('dashicons dashicons-hidden').addClass('dashicons dashicons-visibility').closest('.lahb-tooltip').attr('data-tooltip', 'Hide');
                        $row.removeClass('lahb-row-hidden').css('opacity', '1');
                    }
                }
                else {
                    // Toggle hidden_element value : ( true | false )
                    const cell = editorComponents[currentPanel][currentRow][currentCell];
                    for (let i = 0; i < cell.length; i++) {
                        let element = cell[i];
                        if (element.uniqueId == elemUniqueID) {

                            element.hidden_element = !element.hidden_element;
                            mustBeHidden = element.hidden_element;
                            $elem.attr('data-hidden_element', element.hidden_element).data('hidden_element', element.hidden_element);
                            break;
                        }
                    }

                    // Change row opacity and eye icon
                    if (mustBeHidden) {
                        $this.removeClass('dashicons dashicons-visibility').addClass('dashicons dashicons-hidden').closest('.lahb-tooltip').attr('data-tooltip', 'Show');
                        $elem.find('a').css('color', '#888');
                        $elem.addClass('lahb-row-hidden').css('opacity', '0.45');
                    }
                    else {
                        $this.removeClass('dashicons dashicons-hidden').addClass('dashicons dashicons-visibility').closest('.lahb-tooltip').attr('data-tooltip', 'Hide');
                        $elem.find('a').css('color', '#0073aa');
                        $elem.removeClass('lahb-row-hidden').css('opacity', '1');
                    }
                }

                lahbCreateFrontendComponents();
                lahbSaveAllData();
                lahbDebug();
            });


            /**
             * Header Builder - Save Button
             * @author    LaStudio
             * @version    1.0.0
             * @event    Click
             */
            $(document).on('click', '.lahb_save', function (event) {
                event.preventDefault();

                let $save_btn = $(this);
                let elemName = $currentElement.data('element');
                let elemUniqueID = $currentElement.data('unique-id');
                let elemID = elemUniqueID;

                // Set field value
                $currentModalEdit.find('.lahb-field').each(function () {
                    let $this = $(this);
                    let $fieldInput = $this.find('.lahb-field-input');
                    let fieldName = $fieldInput.data('field-name');
                    let fieldStd = $fieldInput.data('field-std');
                    let fieldValue = $fieldInput.val();

                    if(fieldName && fieldName.search(/_md|_sm|_xs/i) === -1){
                        //console.log(`fieldName: ${fieldName} | fieldValue: ${fieldValue} | fieldStd: ${fieldStd}`);
                    }

                    if( $fieldInput.hasClass('lahb-switcher-field')){
                        if(fieldStd){
                            fieldStd = 'true';
                        }
                        else{
                            fieldStd = 'false'
                        }
                    }

                    if (fieldValue == '') {
                        if (components[elemID].hasOwnProperty(fieldName)) {
                            if(fieldName.indexOf('background_color') === 0 || fieldStd == fieldValue){
                                delete components[elemID][fieldName];
                            }
                            else{
                                components[elemID][fieldName] = fieldValue;
                            }
                        }
                    }
                    else {
                        if (fieldValue !== undefined) {
                            if (typeof fieldValue == 'string' && fieldValue.indexOf('"') != -1) {
                                fieldValue = fieldValue.replace(/"/g, "'");
                            }

                            if(typeof fieldValue == 'string' && fieldValue == fieldStd){
                                delete components[elemID][fieldName];
                            }
                            else{
                                components[elemID][fieldName] = fieldValue;
                            }
                        }
                    }
                });

                try {
                    delete components[elemID]['box_shadow_statusBox_md'];
                    delete components[elemID]['box_shadow_statusBox_sm'];
                    delete components[elemID]['box_shadow_statusBox_xs'];
                }catch (ex){}

                lahbCreateFrontendComponents();
                lahbSaveAllData();
                lahbDebug();

                // Copy element in all platforms
                let currentElemHtml = $currentElement[0].outerHTML;
                let $currentElements = $wrap.find('.lahb-elements-item[data-element="' + elemName + '"][data-unique-id="' + elemUniqueID + '"]');
                $currentElements.each(function () {
                    $(this).replaceWith(currentElemHtml);
                });

                // Create preview for text and image field in editor
                $currentElements = $wrap.find('.lahb-elements-item[data-element="' + elemName + '"][data-unique-id="' + elemUniqueID + '"]');
                $currentModalEdit.find('.lahb-field.lahb-placeholder').each(function () {
                    let $this = $(this);

                    if ($this.hasClass('lahb-img-placeholder')) {
                        let imageID = $this.find('.lahb-field-input').val();

                        if (imageID) {
                            wp.media.attachment(imageID).fetch().done(function () {
                                if (!$currentElements.children('.lahb-img-placeholder-el').length > 0) {
                                    $currentElements.attr('data-img-placeholder', imageID).children('a').hide().after('<img class="lahb-img-placeholder-el" src="' + this.attributes.url + '" alt="" />');
                                } else {
                                    $currentElements.children('.lahb-img-placeholder-el').attr('src', this.attributes.url);
                                }
                            });
                        } else {
                            $currentElements.removeAttr('data-img-placeholder').find('.lahb-img-placeholder-el').remove();
                        }
                    }

                    if ($this.hasClass('lahb-text-placeholder')) {
                        let textValue = $this.find('.lahb-field-input').val();
                        textValue = stripHtml(textValue).trim();

                        if (textValue) {

                            if($('.lahb-text-placeholder-el', $currentElements).length){
                                $('.lahb-text-placeholder-el', $currentElements).html(textValue);
                            }
                            else{
                                $('.lahb-element-name', $currentElements).hide().after('<span class="lahb-text-placeholder-el">' + textValue + '</span>');
                            }
                        }
                        else {
                            $('.lahb-element-name', $currentElements).show();
                            $('.lahb-text-placeholder-el', $currentElements).remove();
                        }
                    }
                });

                // Hide modal
                if (!lahbIsFrontendBuilder()) {
                    $wrap.find('.lahb-modal-wrap').hide();
                    $wrap.find('.lahb-modal-wrap[data-element-target]').remove();
                }
            });


            /**
             * Header Builder - Element Settings Function
             * @author    LaStudio
             * @version    1.0.0
             */
            function lahbElementSettings($currentElement, currentPanel, new_el) {
                let elemName = $currentElement.attr('data-element');
                let elemUniqueID = $currentElement.attr('data-unique-id');
                let elemID = elemUniqueID;

                if (!components.hasOwnProperty(elemID)) {
                    components[elemID] = {
                        component_name: elemName
                    };
                }

                // Preloader
                if (!$wrap.children('.lahb-spinner-wrap').length > 0) {
                    $wrap.prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                }

                $.ajax({
                    type: 'POST',
                    url: lahb_localize.ajaxurl,
                    data: {
                        action: 'lahb_ajax_action',
                        router: 'get_component_field',
                        nonce: lahb_localize.nonce,
                        el_name: elemName
                    },
                    success: function (data) {
                        if ($wrap.children('.lahb-spinner-wrap').length > 0) {
                            $wrap.children('.lahb-spinner-wrap').remove();
                        }

                        $('.lastudio-backend-header-builder-wrap').append(data);

                        $currentModalEdit = $wrap.find('.lahb-modal-wrap[data-element-target="' + elemName + '"]');

                        // Init tab
                        $currentModalEdit.find('.lahb-tabs-list').find('li').removeClass('w-active');
                        $currentModalEdit.find('.lahb-tab-panel').hide();
                        $currentModalEdit.find('.lahb-tabs-list').find('li:first').addClass('w-active');
                        $currentModalEdit.find('.lahb-modal-contents').children('.lahb-tab-panel:first').show();


                        // Styling tab
                        var $tabPanels = $currentModalEdit.find('.lahb-modal-contents').children('.lahb-tab-panel');
                        if (currentPanel == 'desktop-view') {
                            $tabPanels.find('.lahb-tab-panel[data-id="#all"]').show();
                            $tabPanels.find('.lahb-styling-screens a[data-device-mode="all"]').closest('li').addClass('w-active').siblings('li').removeClass('w-active');
                        }
                        else if (currentPanel == 'tablets-view') {
                            $tabPanels.find('.lahb-tab-panel[data-id="#tablets"]').show();
                            $tabPanels.find('.lahb-styling-screens a[data-device-mode="tablets"]').closest('li').addClass('w-active').siblings('li').removeClass('w-active');
                        }
                        else if (currentPanel == 'mobiles-view') {
                            $tabPanels.find('.lahb-tab-panel[data-id="#mobiles"]').show();
                            $tabPanels.find('.lahb-styling-screens a[data-device-mode="mobiles"]').closest('li').addClass('w-active').siblings('li').removeClass('w-active');
                        }
                        else if (currentPanel == 'sticky-view') {
                            $tabPanels.find('.lahb-tab-panel[data-id="#all"]').show();
                        }
                        // Show first panel
                        $tabPanels.find('.lahb-tab-panel').find('.lahb-tab-panel:first').show();

                        // Show current modal edit
                        $currentModalEdit.show();

                        // Set fields values
                        $currentModalEdit.find('.lahb-field').each(function () {
                            let $this = $(this);
                            let $fieldInput = $this.find('.lahb-field-input');
                            let fieldName = $fieldInput.data('field-name');
                            let fieldStd = $fieldInput.data('field-std');

                            fieldStd = (typeof fieldStd !== 'undefined') ? fieldStd : '';

                            // if statement: update field value
                            // else statement: set default data to field

                            if (typeof components[elemID][fieldName] !== 'undefined') {
                                $fieldInput.val(components[elemID][fieldName]);
                            }
                            else {
                                $fieldInput.val(fieldStd);
                                if (fieldStd) {
                                    components[elemID][fieldName] = fieldStd;
                                }
                            }
                        });

                        if (new_el) {
                            let elemEditorIcon = $currentElement.find('a').find('i').attr('class');
                            if (currentPanel == 'sticky-view') {
                                editorComponents[currentPanel][currentRow][currentCell].push({
                                    name: elemName,
                                    uniqueId: elemUniqueID,
                                    hidden_element: false,
                                    editor_icon: elemEditorIcon
                                });
                            }
                            else {
                                for (var platform_key in platforms) {
                                    editorComponents[platform_key][currentRow][currentCell].push({
                                        name: elemName,
                                        uniqueId: elemUniqueID,
                                        hidden_element: false,
                                        editor_icon: elemEditorIcon
                                    });
                                }
                            }
                        }

                        // Fields
                        lahbFields();

                        // Dependency
                        $currentModalEdit.find('.lahb-dependency').on('change', function () {
                            lahbFieldDependency($(this));
                        });
                        $currentModalEdit.find('.lahb-dependency').trigger('change');

                        // Create|Update element in all devices (desktop, tablets, mobiles)
                        if (new_el) {
                            var currentElemHtml = $currentElement[0].outerHTML;
                            $('.lahb-elements-item[data-element="' + elemName + '"][data-unique-id="' + elemUniqueID + '"]').each(function () {
                                $(this).replaceWith(currentElemHtml);
                            });
                        }

                        lahbModalDraggable($currentModalEdit[0]);
                    }
                });

                lahbDebug();
            }


            /**
             * Header Builder - Field Dependency Function
             * @author    LaStudio
             * @version    1.0.0
             */
            function lahbFieldDependency($parent) {
                let dependencyData = $parent.data('dependency');
                let parentValue = $parent.find('.lahb-field-input').val();

                $.each(dependencyData, function (val, els) {
                    for (let i = 0; i < els.length; i++) {

                        let $elem = $currentModalEdit.find('.lahb-field-input[data-field-name="' + els[i] + '"]').closest('.lahb-field').hide();
                        let haveDependency = $elem.attr('class') == 'lahb-field w-col-sm-12 lahb-dependency' ? true : false;

                        if (val == parentValue) {
                            $elem.show();
                            if (haveDependency) {
                                lahbFieldDependency($elem);
                            }
                        }
                        else {
                            if (haveDependency) {
                                $.each($elem.data('dependency'), function (elem_value, elems) {
                                    for (let i = 0; i < elems.length; i++) {
                                        $currentModalEdit.find('.lahb-field-input[data-field-name="' + elems[i] + '"]').closest('.lahb-field').hide();
                                    }
                                });
                            }
                        }
                    } // end for
                });
            }


            /**
             * Header Builder - Fields Function
             * @author    LaStudio
             * @version    1.0.0
             */
            function lahbFields() {
                let $modalWrap = $('.lahb-modal-wrap');

                // Switcher field
                $modalWrap.find('.lahb-switcher').find('.lahb-field-input').on('change', function () {
                    let $this = $(this);

                    if ($this.is(':checked')) {
                        $this.attr('value', 'true').val('true');
                        $this.prop('checked', true);
                    } else {
                        $this.attr('value', 'false').val('false');
                        $this.prop('checked', false);
                    }
                });

                // Attach Image field
                $modalWrap.find('.lahb-attach-image').each(function () {
                    let frame;
                    let $this = $(this);
                    let $addImgLink = $this.find('.lahb-add-image');
                    let $delImgLink = $this.find('.lahb-remove-image');
                    let $imgContainer = $this.find('.lahb-preview-image');

                    // ADD IMAGE LINK
                    $addImgLink.on('click', function (event) {
                        event.preventDefault();

                        let $imgIdInput = $this.find('input.lahb-attach-image');
                        let value = $imgIdInput.val();

                        value = value ? value : '';

                        // If the media frame already exists, reopen it.
                        if (frame) {
                            frame.open();
                            return;
                        }

                        // Create a new media frame
                        frame = wp.media({
                            multiple: false  // Set to true to allow multiple files to be selected
                        });

                        // When an image is selected in the media frame...
                        frame.on('select', function () {
                            // Get media attachment details from the frame state
                            let attachment = frame.state().get('selection').first().toJSON();

                            // Send the attachment URL to our custom image input field.
                            $imgContainer.html('').append('<img src="' + attachment.url + '" alt="">').css('display', 'block');

                            // Send the attachment id to our hidden input
                            $imgIdInput.attr('value', attachment.id).val(attachment.id);

                            // Unhide the remove image link
                            $delImgLink.show();
                        });

                        // Finally, open the modal on click
                        frame.open();
                    });

                    // Delete image link
                    $delImgLink.on('click', function (event) {
                        event.preventDefault();

                        let $imgIdInput = $this.find('input.lahb-attach-image');

                        // Clear out the preview image
                        $imgContainer.html('').hide();

                        // Hide the delete image link
                        $delImgLink.hide();

                        // Delete the image id from the hidden input
                        $imgIdInput.attr('value', '').val('');
                    });
                });

                // Number Unit field
                $modalWrap.find('.lahb-number-unit').each(function () {
                    let $numberUnit = $(this);
                    let $inputNumber = $numberUnit.find('input[type="number"]');
                    let $option = $numberUnit.find('.lahb-opts').children('span');
                    let $fieldInput = $numberUnit.find('.lahb-field-input');

                    $option.on('click', function (event) {
                        event.preventDefault();

                        let $this = $(this);
                        let unit = $this.data('value');
                        let num_val = $inputNumber.val();

                        $option.removeClass('lahb-active');
                        $this.addClass('lahb-active');
                        if (num_val) {
                            $fieldInput.attr('value', num_val + unit).val(num_val + unit);
                        }
                    });

                    $inputNumber.on('change', function (event) {
                        event.preventDefault();

                        let $this = $(this);
                        let unit = $numberUnit.find('.lahb-opts').children('span.lahb-active').data('value');
                        let num_val = $inputNumber.val();

                        if (num_val) {
                            $fieldInput.attr('value', num_val + unit).val(num_val + unit);
                        } else {
                            $fieldInput.attr('value', '').val('');
                        }
                    });
                });

                // Custom Select field
                $modalWrap.find('.lahb-custom-select').find('.lahb-opts').find('span').on('click', function () {
                    let $this = $(this);
                    let $customSelect = $this.closest('.lahb-custom-select');
                    let $option = $customSelect.find('.lahb-opts').children('span');
                    let $fieldInput = $customSelect.find('.lahb-field-input');
                    let value = $this.data('value');

                    $option.removeClass('lahb-active');
                    $this.addClass('lahb-active');
                    $fieldInput.attr('value', value).val(value);
                });

                // Icons field
                $modalWrap.find('.lahb-field-icons-wrap').each(function () {
                    let $iconsWrap = $(this);
                    let $icon = $iconsWrap.find('.la-icon-wrap').find('label');
                    let $fieldInput = $iconsWrap.find('.lahb-field-input');
                    $icon.on('click', function (event) {
                        event.preventDefault();
                        let $this = $(this);
                        let iconClass = $this.attr('for');
                        $icon.removeClass('lahb-active');
                        $this.addClass('lahb-active');
                        $fieldInput.attr('value', $('#' + iconClass).val()).val($('#' + iconClass).val());
                    });
                });
                // Icons field 2
                try{
                    $modalWrap.find('.lasf-field-icon').each(function () {
                        let $fieldInput = $(this).find('.lahb-field-input');
                        if($fieldInput.val()){
                            $(this).find('.lasf-icon-preview').removeClass('hidden').find('i').attr('class', $fieldInput.val());
                            $(this).find('.lasf-icon-remove').removeClass('hidden');
                        }
                    });
                    $modalWrap.find('.lasf-field-icon').lasf_field_icon();
                }catch (e) {
                    console.log('Cannot find lasf_field_icon()');
                }

                // Color picker
                $modalWrap.find('.lahb-color-picker').wpColorPicker();

                /**
                 * Header Builder - Set Value of Field
                 * @version    1.0.0
                 */
                $modalWrap.find('.lahb-field').find('.lahb-field-input').each(function () {
                    let $fieldInput = $(this);

                    // Switcher
                    if ($fieldInput.hasClass('lahb-switcher-field')) {
                        if ($fieldInput.val() == 'true') {
                            $fieldInput.prop('checked', true);
                        } else {
                            $fieldInput.prop('checked', false);
                        }
                    }

                    // Custom Select
                    if ($fieldInput.hasClass('lahb-field-custom-select')) {
                        $fieldInput.siblings('.lahb-opts').find('span').each(function () {
                            let $this = $(this);
                            $this.removeClass('lahb-active');
                            if ($this.data('value') == $fieldInput.val()) {
                                $this.addClass('lahb-active');
                            }
                        });
                    }

                    // Number Unit
                    if ($fieldInput.hasClass('lahb-field-number-unit')) {
                        let value = $fieldInput.val();

                        if (value) {
                            let $numberUnit = $fieldInput.closest('.lahb-number-unit');
                            let $inputNumber = $numberUnit.find('input[type="number"]');
                            let $option = $numberUnit.find('.lahb-opts').children('span');
                            let numberValue = parseFloat(value);
                            let valueUnit = value.split(numberValue)[1];

                            $inputNumber.val(numberValue);
                            $option.each(function () {
                                let $this = $(this);
                                let unit = $this.data('value');

                                $this.removeClass('lahb-active');
                                if (unit == valueUnit) {
                                    $this.addClass('lahb-active');
                                }
                            });
                        } else {
                            $fieldInput.closest('.lahb-number-unit').find('input[type="number"]').val('');
                        }
                    }

                    // Icon
                    if ($fieldInput.hasClass('lahb-icon-field')) {
                        let value = $fieldInput.val();
                        let $iconsWrap = $fieldInput.closest('.lahb-field-icons-wrap');
                        let $icon = $iconsWrap.find('.la-icon-wrap').find('label');
                        let $listIconUL = $iconsWrap.find('.lastudio-icons-list');
                        if (value) {
                            $icon.removeClass('lahb-active');
                            $iconsWrap.find('.la-icon-wrap input[value="'+value+'"]').next('label').addClass('lahb-active');
                            $iconsWrap.find('.la-icon-wrap input[value="'+value+'"]').closest('.la-icon-wrap').prependTo($listIconUL);
                        }
                        else {
                            $icon.removeClass('lahb-active');
                        }
                    }

                    // Attach Image
                    if ($fieldInput.hasClass('lahb-attach-image')) {
                        let val = $fieldInput.val();
                        let $delImgLink = $fieldInput.siblings('.lahb-remove-image');
                        let $imgContainer = $fieldInput.siblings('.lahb-preview-image');

                        if (val && !wp.media.attachment(val).destroyed) {

                            if (!$currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').length > 0) {
                                $currentModalEdit.find('.lahb-modal-contents').prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                            }

                            try{
                                wp.media.attachment(val).fetch().done(function () {
                                    if ($currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').length > 0) {
                                        $currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').remove();
                                    }
                                    // Send the attachment URL to our custom image input field.
                                    $imgContainer.html('').append('<img src="' + this.attributes.url + '" alt="">').css('display', 'block');
                                    // Unhide the remove image link
                                    $delImgLink.show();
                                }).fail(function(){
                                    if ($currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').length > 0) {
                                        $currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').remove();
                                    }
                                    // Clear out the preview image
                                    $imgContainer.html('').hide();
                                    // Hide the delete image link
                                    $delImgLink.hide();
                                    $fieldInput.val('');
                                });
                            }
                            catch (ex) {
                                if ($currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').length > 0) {
                                    $currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').remove();
                                }
                                // Clear out the preview image
                                $imgContainer.html('').hide();
                                // Hide the delete image link
                                $delImgLink.hide();
                                $fieldInput.val('');
                            }
                        }
                        else {

                            if ($currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').length > 0) {
                                $currentModalEdit.find('.lahb-modal-contents').find('.lahb-spinner-wrap').remove();
                            }

                            // Clear out the preview image
                            $imgContainer.html('').hide();
                            // Hide the delete image link
                            $delImgLink.hide();
                        }
                    }
                });
            }


            /**
             * Header Builder - Drag and Drop Functions
             * @author    LaStudio
             * @version    1.0.0
             */
            // Editor draggable elements
            function lahbEditorDraggable() {
                $sortablePlaces.sortable({
                    connectWith: '.lahb-elements-place',
                    placeholder: 'ui-sortable-placeholder',
                    forcePlaceholderSize: true,
                    tolerance: 'pointer',
                    start: function (event, ui) {
                        let $elem = $(ui.item);

                        currentPanel = $elem.closest('.lahb-tab-panel').attr('id'); // desktop-view, tablets-view, mobiles-view, sticky-view
                        currentRow = $elem.closest('.lahb-row').attr('data-labhrow'); // topbar, row1, row2, row3
                        currentCell = $elem.closest('.lahb-col').attr('data-align-col'); // left, center, right
                    },
                    beforeStop: function (event, ui) {
                        let $elem = $(ui.item);
                        let $els_place = $elem.closest('.lahb-elements-place');
                        let elemName = $elem.data('element');
                        let elemUniqueID = $elem.data('unique-id').toString();
                        let elemID = elemUniqueID;
                        let elemFromCell;

                        // Remove element from start cell
                        let start_cell_obj = editorComponents[currentPanel][currentRow][currentCell];
                        for (let i = 0; i < start_cell_obj.length; i++) {
                            if (start_cell_obj[i].uniqueId == elemUniqueID) {
                                elemFromCell = start_cell_obj.splice(i, 1);
                            }
                        }

                        // Add element to received cell
                        currentRow = $elem.closest('.lahb-row').attr('data-labhrow'); // topbar, row1, row2, row3
                        currentCell = $elem.closest('.lahb-col').attr('data-align-col'); // left, center, right
                        let new_cell_objs = $els_place.children('.lahb-elements-item').map(function (i, el) {
                            $elem = $(el);
                            return {
                                name: $elem.data('element'),
                                uniqueId: $elem.data('unique-id').toString(),
                                hidden_element: JSON.parse($elem.data('hidden_element')),
                                editor_icon: $elem.data('editor_icon'),
                            };
                        }).get();

                        // Update editor components
                        editorComponents[currentPanel][currentRow][currentCell] = new_cell_objs;

                        lahbCreateFrontendComponents();
                        lahbSaveAllData();
                        lahbDebug();
                    }
                }).disableSelection();
            }
            lahbEditorDraggable();

            function lahbEditorRowDraggable() {

                $sortableRows.sortable({
                    placeholder: 'ui-sortable-placeholder',
                    forcePlaceholderSize: true,
                    tolerance: 'pointer',
                    start: function (event, ui) {

                    },
                    beforeStop: function (event, ui) {
                        let $elem = $(ui.item);
                        let $parentRow = $elem.closest('.lahb-row-sortable');
                        let _panelKey = $parentRow.attr('id');

                        $parentRow.find('>.lahb-row').each(function (idx) {
                            let _rowKey = $(this).data('labhrow');
                            let __order = idx + 1;
                            $(this).removeClass('row-order-1 row-order-2 row-order-3 row-order-4').addClass('row-order-' + __order);
                            editorComponents[_panelKey][_rowKey]['settings']['order'] = __order;
                        });

                        lahbCreateFrontendComponents();
                        lahbSaveAllData();
                        lahbDebug();
                    }
                }).disableSelection();
            }
            if(!$wrap.find('.lahb-tabs-panels').hasClass('lahb-vertical-header-panel')){
                lahbEditorRowDraggable();
            }

            // Modal draggable
            function lahbModalDraggable(element) {

                if (typeof element === "undefined") {
                    return;
                }

                let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

                if (element.getElementsByClassName('lahb-modal-header')) {
                    element.getElementsByClassName('lahb-modal-header')[0].onmousedown = lahb_drag_mouse_down;
                } else {
                    element.onmousedown = lahb_drag_mouse_down;
                }

                function lahb_drag_mouse_down(e) {
                    e = e || window.event;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = lahbCloseModalDraggable;
                    document.onmousemove = lahbElementDrag;
                }

                function lahbElementDrag(e) {
                    e = e || window.event;
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    element.style.top = (element.offsetTop - pos2) + "px";
                    element.style.left = (element.offsetLeft - pos1) + "px";
                }

                function lahbCloseModalDraggable() {
                    document.onmouseup = null;
                    document.onmousemove = null;
                }
            }

            lahbModalDraggable($('.lahb-modal-wrap')[0]);


            /**
             * Header Builder - Publish Button
             * @author    LaStudio
             * @version    1.0.0
             */
            $(document).on('click', '#lahb-publish', function (e) {

                e.preventDefault();

                // Editor Preloader
                if ($wrap.children('.lahb-spinner-wrap').length == 0) {
                    $wrap.prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                }

                if(document.getElementById('LaStudo_Iframe')){
                    let $LaStudoIframeFrontend = document.getElementById('LaStudo_Iframe').contentWindow.jQuery('#lastudio-header-builder');
                    if ($LaStudoIframeFrontend.children('.lahb-spinner-wrap').length == 0) {
                        $LaStudoIframeFrontend.prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                    }
                }

                $.ajax({
                    type: 'POST',
                    url: lahb_localize.ajaxurl,
                    data: {
                        action: 'lahb_ajax_action',
                        router: 'publish_header',
                        nonce: lahb_localize.nonce,
                        frontendComponents: JSON.stringify(frontendComponents)
                    },
                    success: function (res) {
                        $wrap.children('.lahb-spinner-wrap').remove();
                        if(document.getElementById('LaStudo_Iframe')) {
                            let $LaStudoIframeFrontend = document.getElementById('LaStudo_Iframe').contentWindow.jQuery('#lastudio-header-builder');
                            $LaStudoIframeFrontend.children('.lahb-spinner-wrap').remove();
                        }

                        if($('.btn-complete-edits').length > 0){
                            var new_url = $('.btn-complete-edits').data('current_url');
                            history.pushState(null, null, new_url);
                            $wrap.find('>h4').remove();
                            $('.btob-button, #lahb-export').each(function () {
                                var _old_url = $(this).attr('href');
                                $(this).attr('href', removeQueryArg(_old_url, 'prebuild_header') );
                            })
                        }

                        if (importButtonFlag) {
                            location.reload();
                        }
                    }
                });
            });


            /**
             * Header Builder - Collapse Button
             * @author    LaStudio
             * @version    1.0.0
             */
            $(document).on('click', '.lahb-action-collapse', function () {
                let $elem = $(this);
                let $editor = $('.lastudio-backend-header-builder-wrap.lahb-frontend-builder').css('bottom', '-40%');
                let $actions = $('.lahb-actions').css('bottom', '0');

                // if: close
                // else: open
                if ($elem.hasClass('lahb-open')) {
                    $editor.css('bottom', '-40%');
                    $actions.css('bottom', '0');
                    $elem.removeClass('lahb-open');
                    $elem.children('i').attr('class', 'dashicons dashicons-arrow-up-alt');
                } else {
                    $editor.css('bottom', '0');
                    $actions.css('bottom', '40%');
                    $elem.addClass('lahb-open');
                    $elem.children('i').attr('class', 'dashicons dashicons-arrow-down-alt');
                }
            });


            /**
             * Header Builder - Get Multi Scripts
             * @author    LaStudio
             * @version    1.0.0
             */
            $.lahbGetMultiScripts = function (scripts, path) {
                let _scripts = $.map(scripts, function (scr) {
                    return $.getScript((path || "") + scr);
                });

                _scripts.push($.Deferred(function (deferred) {
                    $(deferred.resolve);
                }));

                return $.when.apply($, _scripts);
            }


            /**
             * Header Builder - Get Stylesheet
             * @author    LaStudio
             * @version    1.0.0
             */
            $.getStylesheet = function (href) {
                var $d = $.Deferred();
                var $link = $('<link/>', {
                    rel: 'stylesheet',
                    type: 'text/css',
                    href: href
                }).appendTo('head');
                $d.resolve($link);
                return $d.promise();
            };


            /**
             * Header Builder - Save All Data Function
             * @author    LaStudio
             * @version    1.0.0
             */

            function lahbSaveAllData() {

                let $LaStudo_Iframe = $('#LaStudo_Iframe'),
                    $LaStudoIframeFrontend,
                    $lahbSaveBtn,
                    __fn;

                if($LaStudo_Iframe.length){
                    __fn = document.getElementById('LaStudo_Iframe').contentWindow.jQuery;
                    $LaStudoIframeFrontend = __fn('#lastudio-header-builder');
                    if ($LaStudoIframeFrontend.children('.lahb-spinner-wrap').length == 0) {
                        $LaStudoIframeFrontend.prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                    }
                }

                $lahbSaveBtn = $('.lahb_save');

                if($lahbSaveBtn.length){
                    $lahbSaveBtn.val( lahb_localize.i18n.saved_text );
                }

                $.ajax({
                    type: 'POST',
                    url: lahb_localize.ajaxurl,
                    data: {
                        action: 'lahb_ajax_action',
                        router: 'save_header_data',
                        nonce: lahb_localize.nonce,
                        headerPreset: lahb_localize.prebuild_header_key,
                        frontendComponents: JSON.stringify(frontendComponents)
                    },
                    success: function (data) {
                        if(lahbIsFrontendBuilder()){

                            var $data = $(data),
                                $new_header = $data[0],
                                $new_style = $data[1];

                            // Remove old dynamic style
                            //$LaStudo_Iframe.contents().find('#lahb-frontend-styles-inline-css').replaceWith( $new_style );
                            __fn('#lahb-frontend-styles-inline-css').replaceWith( $new_style );

                            // Update header html
                            $LaStudoIframeFrontend.replaceWith( $new_header );

                            // check header vertical style
                            let _header_vertical_setting = frontendComponents['desktop-view'].row1.settings;

                            let __is_vertical = _header_vertical_setting['header_type'];
                            let __is_vertical_toggle = (typeof _header_vertical_setting['vertical_toggle'] !== "undefined" ? _header_vertical_setting['vertical_toggle'] : 'false');

                            if(__is_vertical == 'vertical'){
                                __fn('body').addClass('header-type-vertical');
                                if(__is_vertical_toggle == 'true'){
                                    __fn('body').removeClass('header-type-vertical--default').addClass('header-type-vertical--toggle');
                                }
                                else{
                                    __fn('body').removeClass('header-type-vertical--toggle').addClass('header-type-vertical--default');
                                }
                            }
                            else{
                                __fn('body').removeClass('header-type-vertical header-type-vertical--toggle header-type-vertical--default');
                            }

                            if($LaStudo_Iframe.length) {
                                // Fire javascript action once again
                                document.getElementById('LaStudo_Iframe').contentWindow.LaStudio.component.HeaderBuilder.reloadAllEvents();
                            }

                            // Remove preloader
                            $lahbSaveBtn.val(lahb_localize.i18n.save_text);
                            $LaStudoIframeFrontend.children('.lahb-spinner-wrap').remove();
                        }
                    },
                    complete: function () {

                    }
                })
                    .done(function() {
                        if (importButtonFlag) {
                            $('#lahb-publish').trigger('click');
                        }
                    });
            }


            /**
             * Header Builder - Create Frontend Components Function
             * @author    LaStudio
             * @version    1.0.0
             */

            function _lahbCreateFrontendComponents() {
                frontendComponents = $.extend(true, {}, editorComponents);
                for (let screen_key in frontendComponents) {
                    for (let rowKey in frontendComponents[screen_key]) {
                        for (let cell_key in frontendComponents[screen_key][rowKey]) {
                            let cell = frontendComponents[screen_key][rowKey][cell_key];

                            if (cell_key == 'settings' || cell_key == 'left_settings' || cell_key == 'center_settings' || cell_key == 'right_settings') {
                                let component_key = cell.uniqueId;

                                if (component_key in components) {
                                    Object.assign(cell, components[component_key]);
                                }
                                for (const key in cell) {
                                    if (cell.hasOwnProperty(key)) {
                                        const value = cell[key];
                                        if (key != 'hidden_element' && typeof value === 'boolean') {
                                            cell[key] = String(value);
                                        }
                                    }
                                }
                            }
                            else {
                                if (cell.length != 0) {

                                    for (let el of cell) {
                                        let component_key = el.uniqueId;
                                        if (component_key in components) {
                                            Object.assign(el, components[component_key]);
                                        }
                                        for (const key in el) {
                                            if (el.hasOwnProperty(key)) {
                                                const value = el[key];
                                                if (key != 'hidden_element' && typeof value === 'boolean') {
                                                    el[key] = String(value);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                frontendComponents['desktop-view'].row1.settings.element = editorComponents['desktop-view'].row1.settings.element;
                frontendComponents['desktop-view'].row1.settings.header_type = editorComponents['desktop-view'].row1.settings.header_type;
            }

            function lahbCreateFrontendComponents() {
                frontendComponents = $.extend(true, {}, editorComponents);

                for (let screen_key in frontendComponents) {
                    for (let rowKey in frontendComponents[screen_key]) {
                        for (let cell_key in frontendComponents[screen_key][rowKey]) {
                            let cell = frontendComponents[screen_key][rowKey][cell_key];

                            if (cell_key == 'settings' || cell_key == 'left_settings' || cell_key == 'center_settings' || cell_key == 'right_settings') {
                                let component_key = cell.uniqueId;

                                if (component_key in components) {
                                    Object.assign(cell, components[component_key]);
                                }
                                for (const key in cell) {
                                    if (cell.hasOwnProperty(key)) {
                                        if (key != 'hidden_element' && key != 'order' && typeof cell[key] === 'boolean') {
                                            cell[key] = String(cell[key]);
                                        }
                                        if(key == 'order'){
                                            cell[key] = editorComponents[screen_key][rowKey][cell_key][key];
                                        }
                                    }
                                }
                            }
                            else {
                                if (cell.length != 0) {

                                    for (let el of cell) {
                                        let component_key = el.uniqueId;
                                        if (component_key in components) {
                                            Object.assign(el, components[component_key]);
                                        }
                                        for (const key in el) {
                                            if (el.hasOwnProperty(key)) {
                                                if (key != 'hidden_element' && typeof el[key] === 'boolean') {
                                                    el[key] = String(el[key]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                frontendComponents['desktop-view'].row1.settings.element = editorComponents['desktop-view'].row1.settings.element;
                frontendComponents['desktop-view'].row1.settings.header_type = editorComponents['desktop-view'].row1.settings.header_type;
            }


            /**
             * Header Builder - Import Button
             * @author    LaStudio
             * @version    1.0.0
             */
            // import button
            $("#lahb-import").change(function (e) {
                var file = e.target.files[0];
                var reader = new FileReader();
                reader.onload = onReaderLoad;
                reader.readAsText(file);
            });

            function onReaderLoad(event) {
                lahbImport(event.target.result);
            }

            // Import function
            function lahbImport(content) {
                if (content && (lahbIsJson(content) || lahbIsObject(content))) {
                    $body.css({
                        'height': '1px',
                        'overflow': 'hidden'
                    }).prepend('<div class="lahb-spinner-wrap"><div class="lahb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
                    $('#wpwrap').css('display', 'none');
                    content = lahbIsJson(content) ? JSON.parse(content) : content;
                    components = content.lahb_data_components;
                    editorComponents = content.lahb_data_editor_components;
                    frontendComponents = content.lahb_data_frontend_components;
                    importButtonFlag = true;
                    lahbSaveAllData();
                }
                else {
                    alert('Header import input is empty! That\'s why no data can import.');
                }
            }


            /**
             * Header Builder - Prebuild Button
             * @author    LaStudio
             * @version    1.0.0
             */
            $(document).on('click', '.lahb-prebuild-item', function (event) {
                event.preventDefault();

                if( typeof $(this).attr('data-file-name') !== "undefined" ) {
                    if (confirm('Your selected header pre-defined will apply on current elements and settings. Are you sure you want to overwrite?')) {
                        let fileName = $(this).attr('data-file-name');
                        let url = lahb_localize.prebuilds_url + fileName;

                        $.ajax({
                            dataType: "json",
                            type: 'POST',
                            url: url,
                            success: function (data) {
                                lahbImport(data);
                            }
                        });
                    }
                }
                else{
                    window.location.href = $(this).attr('href');
                }
            });

            $(document).on('click', '.lahb-full-modal .lahb-prebuild-item i.dashicons-trash', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this preset ?')) {
                    $.ajax({
                        type: 'POST',
                        url: lahb_localize.ajaxurl,
                        data: {
                            action: 'lahb_ajax_action',
                            router: 'delete_header_preset_template',
                            nonce: lahb_localize.nonce,
                            header_key: $(this).parent().data('saved-name')
                        },
                        dataType: "json",
                        success: function (data) {
                            if(data.success){
                                let __html = '';
                                let __option_html = '';
                                for ( let key in data.data ){
                                    let obj = data.data[key];
                                    let _query_args = addQueryArg(window.location.href, 'prebuild_header', key);
                                    __option_html += '<option value="'+key+'">'+ obj.name +'</option>';
                                    if(typeof obj.image !== "undefined" ){
                                        __html += '<a class="lahb-prebuild-item" data-saved-name="'+key+'" href="'+_query_args+'"><img src="'+obj.image+'" alt="'+obj.name+'"/><i class="dashicons dashicons-trash"></i></a>'
                                    }
                                    else{
                                        __html += '<a class="lahb-prebuild-item" data-saved-name="'+key+'" href="'+_query_args+'"><span>'+obj.name+'</span><i class="dashicons dashicons-trash"></i></a>'
                                    }
                                }
                                $('.lahb-predefined-modal-inner-content').html(__html);
                                $('.lahb-modal-save-header select[data-field-name="lahb_save_header_type_existing"]').html(__option_html);
                                alert('Deleted successfully!');
                            }
                            else{
                                alert('Sorry, please try again!');
                            }
                        }
                    });
                }
                return false;
            })

            /**
             * Header Builder - Header Type Switcher Button (Vertical|Horizontal)
             * @author    LaStudio
             * @version    1.0.0
             */
            if (editorComponents['desktop-view']['row1']['settings']['header_type'] == 'vertical') {
                let $panels = $wrap.find('.lahb-tabs-panels');
                let $settings = $panels.find('#desktop-view').children('.lahb-row[data-labhrow="row1"]').children('.lahb-elements-item');
                let $headerSwitcher = $('#lahb-vertical-header');

                $settings.attr('data-element', 'vertical-area');
                $headerSwitcher.find('span').text('Horizontal Header');
                $panels.removeClass('lahb-horizontal-header-panel').addClass('lahb-vertical-header-panel');
                $desktopSortablePlaces.sortable({
                    axis: ''
                });
            }

            $(document).on('click', '#lahb-vertical-header', function (event) {
                event.preventDefault();

                let $this = $(this);
                let $tabs = $('.lahb-tabs-list');
                let $panels = $wrap.find('.lahb-tabs-panels');
                let $settings = $panels.find('#desktop-view').children('.lahb-row[data-labhrow="row1"]').children('.lahb-elements-item');
                let desktopRow1Settings = editorComponents['desktop-view'].row1.settings;
                let header_type = desktopRow1Settings.header_type;

                if (header_type == 'horizontal') {
                    desktopRow1Settings.element = 'vertical-area';
                    $settings.attr('data-element', 'vertical-area');
                    desktopRow1Settings.header_type = 'vertical';
                    $this.find('span').text( lahb_localize.i18n.horizontal_header_text );
                    $panels.removeClass('lahb-horizontal-header-panel').addClass('lahb-vertical-header-panel');
                    $desktopSortablePlaces.sortable({
                        axis: ''
                    });
                    $this.attr('data-header_type', 'vertical');
                }
                else {
                    desktopRow1Settings.element = 'header-area';
                    $settings.attr('data-element', 'header-area');
                    desktopRow1Settings.header_type = 'horizontal';
                    $this.find('span').text(lahb_localize.i18n.vertical_header_text);
                    $panels.removeClass('lahb-vertical-header-panel').addClass('lahb-horizontal-header-panel');
                    $desktopSortablePlaces.sortable({
                        axis: ''
                    });
                    $('#wrap').removeClass('lahb-header-vertical-toggle');
                    $this.attr('data-header_type', 'horizontal');
                }

                frontendComponents['desktop-view'].row1.settings.element = editorComponents['desktop-view'].row1.settings.element;
                frontendComponents['desktop-view'].row1.settings.header_type = editorComponents['desktop-view'].row1.settings.header_type;


                lahbCreateFrontendComponents();
                lahbSaveAllData();
                lahbDebug();

                // show desktop panel
                $tabs.find('li').removeClass('w-active');
                $tabs.find('li:first-child').addClass('w-active');
                $('.lahb-tab-panel').hide();
                $('#desktop-view').css('display', 'block');

            });

    }); // end document ready
})(jQuery);