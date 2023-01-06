(function ($) {
    "use strict";

    var $window = $(window),
        $document = $(document),
        $htmlbody = $('html,body'),
        $body = $('body'),
        $masthead = $('#lastudio-header-builder');

    // Initialize global variable

    var LaStudioWooCommerce = function (){
        // Bind functions to this.
        this.General = this.General.bind(this);
        this.QuickView = this.QuickView.bind(this);
        this.Wishlist = this.Wishlist.bind(this);
        this.Compare = this.Compare.bind(this);
        this.AjaxFilter = this.AjaxFilter.bind(this);
        this.AutoInit = this.AutoInit.bind(this);
    }

    LaStudioWooCommerce.prototype.AutoInit = function (){
        this.General();
        this.QuickView();
        this.Wishlist();
        this.Compare();
        this.AjaxFilter();
    }

    LaStudioWooCommerce.prototype.General = function (){
        /*
           * Initialize all galleries on page.
           */
        function init_woo_gallery(){
            var init_woo_gallery_cb = function(){
                $( '.la-woo-product-gallery' ).each( function() {
                    $( this ).lastudio_product_gallery();
                } );
            }
            if("function" === typeof $.fn.lastudio_product_gallery){
                init_woo_gallery_cb();
            }
            else{
                LaStudio.global.loadDependencies([ LaStudio.global.loadJsFile('product-gallery') ], init_woo_gallery_cb );
            }
        }

        init_woo_gallery();

        $document.trigger('reinit_la_swatches');

        $document.on('click','.product_item .la-swatch-control .swatch-wrapper', function(e){
            e.preventDefault();
            var $swatch_control = $(this),
                $image = $swatch_control.closest('.product_item').find('.p_img-first img').first(),
                $btn_cart = $swatch_control.closest('.product_item').find('.la-addcart');

            if($swatch_control.hasClass('selected')) return;
            $swatch_control.addClass('selected').siblings().removeClass('selected');
            if(!$image.hasClass('--has-changed')){
                $image.attr('data-o-src', $image.attr('src')).attr('data-o-sizes', $image.attr('sizes')).attr('data-o-srcset', $image.attr('srcset')).addClass('--has-changed');
            }
            $image.attr('src', ($swatch_control.attr('data-thumb') ? $swatch_control.attr('data-thumb') : $image.attr('data-o-src'))).removeAttr('sizes srcset');
            if($btn_cart.length > 0){
                var _href = $btn_cart.attr('href');
                _href = LaStudio.global.addQueryArg(_href, 'attribute_' + $swatch_control.attr('data-attribute'), $swatch_control.attr('data-value'));
                $btn_cart.attr('href', _href);
            }
        })

        /**
         * Lazyload image for cart widget
         */
        var cart_widget_timeout = null;
        $(document.body).on('wc_fragments_refreshed updated_wc_div wc_fragments_loaded', function(e){
            clearTimeout( cart_widget_timeout );
            cart_widget_timeout = setTimeout( function(){
                LaStudio.global.eventManager.publish('LaStudio:Component:LazyLoadImage', [$('.widget_shopping_cart_content')]);
            }, 100);
        });
        $document.on('click', '.la_com_action--cart', function(e){
            if(!$(this).hasClass('force-display-on-mobile')){
                if($window.width() > 767){
                    e.preventDefault();
                    $body.toggleClass('open-cart-aside');
                }
            }
            else{
                e.preventDefault();
                $body.toggleClass('open-cart-aside');
            }
        });

        /**
         * Cart Plus & Minus action
         */
        $document.on('click', '.quantity .qty-minus', function(e){
            e.preventDefault();
            var $qty = $(this).siblings('.qty'),
                val = parseInt($qty.val());
            $qty.val( val > 1 ? val-1 : 1).trigger('change');
        })
        $document.on('click', '.quantity .qty-plus', function(e){
            e.preventDefault();
            var $qty = $(this).siblings('.qty'),
                val = parseInt($qty.val());
            $qty.val( val > 0 ? val+1 : 1 ).trigger('change');
        })

        /**
         * View mode toggle
         */
        $document
            .on('click','.wc-view-item a',function(){
                var _this = $(this),
                    _col = _this.data('col'),
                    $parentWrap = _this.closest('.woocommerce');
                if(!_this.hasClass('active')){
                    $('.wc-view-item a').removeClass('active');
                    _this.addClass('active');
                    _this.closest('.wc-view-item').find('>button>span').html(_this.text());
                    var $ul_products = $parentWrap.find('ul.products[data-grid_layout]');

                    $ul_products.each(function () {
                        $(this).removeClass('products-list').addClass('products-grid');
                        var _classname = $(this).attr('class').replace(/(\sblock-grid-\d)/g, ' block-grid-' + _col).replace(/(\slaptop-block-grid-\d)/g, ' laptop-block-grid-' + _col);
                        $(this).attr('class', _classname);
                    });

                    if( $parentWrap.closest('.elementor-widget-wc-archive-products').length ){
                        var _classname = $parentWrap.attr('class').replace(/(\scolumns-\d)/g, ' columns-' + _col);
                        $parentWrap.attr('class', _classname);
                    }
                    Cookies.set('luxsa_wc_product_per_row', _col, { expires: 2 });
                }
            })
            .on('click','.wc-view-toggle button',function(){
                var _this = $(this),
                    _mode = _this.data('view_mode'),
                    $parentWrap = _this.closest('.woocommerce');
                if(!_this.hasClass('active')){
                    $('.wc-view-toggle button').removeClass('active');
                    _this.addClass('active');

                    var $ul_products = $parentWrap.find('ul.products[data-grid_layout]'),
                        _old_grid = $ul_products.attr('data-grid_layout');
                    if(_mode == 'grid'){
                        $ul_products.removeClass('products-list').addClass('products-grid').addClass(_old_grid);
                    }
                    else {
                        $ul_products.removeClass('products-grid').addClass('products-list').removeClass(_old_grid);
                    }
                    Cookies.set('luxsa_wc_catalog_view_mode', _mode, { expires: 2 });
                }
            })
            .on('mouseover', '.lasf-custom-dropdown', function (e) {
                $(this).addClass('is-hover');
            })
            .on('mouseleave', '.lasf-custom-dropdown', function (e) {
                $(this).removeClass('is-hover');
            })
        /**
         * Ajax add-to-cart
         */
        $document.on('adding_to_cart', function (e, $button, data) {
            if( $button && $button.closest('.la_wishlist_table').length ) {
                data.la_remove_from_wishlist_after_add_to_cart = data.product_id;
            }
            $body.removeClass('open-search-form').addClass('open-cart-aside');
            $('.cart-flyout').addClass('cart-flyout--loading');
            $('i.cart-i_icon').each(function () {
                var _old_icon = $(this).data('icon');
                $(this).removeClass(_old_icon).addClass('la-loading-spin');
            });
        });
        $document.on('added_to_cart', function( e, fragments, cart_hash, $button ){
            $('.cart-flyout').removeClass('cart-flyout--loading');
            $('i.cart-i_icon').each(function () {
                $(this).removeClass('la-loading-spin').addClass($(this).data('icon'));
            })
        } );

        /**
         * Ajax add-to-cart - Single Page
         */

        if( la_theme_config.single_ajax_add_cart ) {
            $document.on('submit', '.la-p-single-wrap:not(.product-type-external) .entry-summary form.cart', function(e){
                e.preventDefault();
                $document.trigger('adding_to_cart');

                var form = $(this),
                    product_url = form.attr('action') || window.location.href,
                    action_url = LaStudio.global.addQueryArg(product_url, 'product_quickview', '1');

                $.post(action_url, form.serialize() + '&_wp_http_referer=' + product_url, function (result) {
                    // Show message
                    if($(result).eq(0).hasClass('woocommerce-message') || $(result).eq(0).hasClass('woocommerce-error')){
                        $('.woocommerce-message, .woocommerce-error').remove();
                        $('.la-p-single-wrap.type-product').eq(0).before($(result).eq(0));
                    }

                    $document.trigger('LaStudio:Component:Popup:Close');

                    // update fragments
                    $.ajax({
                        url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                        type: 'POST',
                        success: function( data ) {
                            if ( data && data.fragments ) {
                                $.each( data.fragments, function( key, value ) {
                                    $( key ).replaceWith( value );
                                });
                                $( document.body ).trigger( 'wc_fragments_refreshed' );
                                $('.cart-flyout').removeClass('cart-flyout--loading');
                                $('i.cart-i_icon').each(function () {
                                    $(this).removeClass('la-loading-spin').addClass($(this).data('icon'));
                                })
                            }
                        }
                    });
                });
            });

            $document.on('submit', '.product_item:not(.product-type-external) form.cart', function(e){
                e.preventDefault();
                $document.trigger('adding_to_cart');

                var form = $(this),
                    product_url = form.attr('action') || window.location.href,
                    action_url = LaStudio.global.addQueryArg(product_url, 'product_quickview', '1');

                $.post(action_url, form.serialize() + '&_wp_http_referer=' + product_url, function (result) {
                    // Show message
                    if($(result).eq(0).hasClass('woocommerce-message') || $(result).eq(0).hasClass('woocommerce-error')){
                        $('.woocommerce-message, .woocommerce-error').remove();
                        $('.la-p-single-wrap.type-product').eq(0).before($(result).eq(0));
                    }

                    $document.trigger('LaStudio:Component:Popup:Close');

                    // update fragments
                    $.ajax({
                        url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
                        type: 'POST',
                        success: function( data ) {
                            if ( data && data.fragments ) {
                                $.each( data.fragments, function( key, value ) {
                                    $( key ).replaceWith( value );
                                });
                                $( document.body ).trigger( 'wc_fragments_refreshed' );
                                $('.cart-flyout').removeClass('cart-flyout--loading');
                                $('i.cart-i_icon').each(function () {
                                    $(this).removeClass('la-loading-spin').addClass($(this).data('icon'));
                                })
                            }
                        }
                    });
                });
            });

            $document.on('click', '.product_item .la-addcart.product_type_variable', function (e){
                var $cart = $(this).closest('.product_item').find('form.cart');
                if($cart.length && $cart.find('.wc-variation-selection-needed').length == 0){
                    e.preventDefault();
                    $cart.find('.single_add_to_cart_button').trigger('click');
                    return false;
                }
            })
        }

        if($('.la-p-single-wrap .s_product_content_top > .product--summary .product-nextprev').length){
            $('.la-p-single-wrap .s_product_content_top > .product--summary .product-nextprev').clone().prependTo($('.la-p-single-wrap .s_product_content_top >.product-main-image'));
        }

        /**
         * Sticky panel for product layout 03
         */
        function init_sp_la_sticky(){
            $('.la-p-single-wrap.la-p-single-3 .la-custom-pright,.la-p-single-wrap.la-p-single-4 .la-custom-pright').la_sticky({
                parent: $('.la-single-product-page'),
                offset_top: ($masthead.length ? parseInt($masthead.height()) + 30 : 30)
            });


            $('.la-p-single-wrap.la-p-single-3 .woocommerce-product-gallery__actions, .la-p-single-wrap.la-p-single-4 .woocommerce-product-gallery__actions').la_sticky({
                parent: $('.la-woo-product-gallery'),
                offset_top: $window.height() - 150,
            });
        }

        if(($('.la-p-single-wrap.la-p-single-3, .la-p-single-wrap.la-p-single-4').length)){
            if( typeof $.fn.la_sticky === "undefined" ){
                LaStudio.global.loadDependencies([ LaStudio.global.loadJsFile('lasticky')], init_sp_la_sticky );
            }
            else{
                init_sp_la_sticky();
            }
        }

        /**
         * My Account toggle
         */

        if(location.hash == '#register' && $('#customer_login .u-column2.col-2').length){
            $('#customer_login .u-column2.col-2').addClass('active');
        }
        else{
            $('#customer_login .u-column1.col-1').addClass('active');
        }

        $document.on('click', '#customer_login h2', function (e) {
            e.preventDefault();
            var $parent = $(this).parent();
            if(!$parent.hasClass('active')){
                $parent.addClass('active').siblings('div').removeClass('active');
            }
        });

        /**
         * WooCommerce Tabs
         */
        $('.woocommerce-tabs .wc-tab-title a').on('click', function(e){
            e.preventDefault();
            var $this = $(this),
                $wrap = $this.closest('.woocommerce-tabs'),
                $wc_tabs = $wrap.find('.wc-tabs'),
                $panel = $this.closest('.wc-tab');

            $wc_tabs.find('a[href="'+ $this.attr('href') +'"]').parent().toggleClass('active').siblings().removeClass('active');
            $panel.toggleClass('active').siblings().removeClass('active');
        });
        $('.woocommerce-Tabs-panel--description').addClass('active');
    }
    LaStudioWooCommerce.prototype.QuickView = function (){
        $document.on('click','.la-quickview-button',function(e){
            if($window.width() > 900){
                e.preventDefault();
                var $this = $(this);
                var show_popup = function(){
                    if($.featherlight.close() !== undefined){
                        $.featherlight.close();
                    }
                    $.featherlight($this.data('href'), {
                        openSpeed:      0,
                        closeSpeed:     0,
                        type:{
                            wc_quickview: true
                        },
                        background: '<div class="featherlight featherlight-loading is--qvpp"><div class="featherlight-outer"><button class="featherlight-close-icon featherlight-close" aria-label="Close"><i class="lastudioicon-e-remove"></i></button><div class="featherlight-content"><div class="featherlight-inner"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div></div><div class="custom-featherlight-overlay"></div></div>',
                        contentFilters: ['wc_quickview'],
                        ajaxSetup: {
                            cache: true,
                            ajax_request_id: LaStudio.global.getUrlParameter('product_quickview', $this.data('href'))
                        },
                        beforeOpen: function (evt) {
                            $body.addClass('open-quickview-product');
                        },
                        afterOpen: function (evt) {
                            var $woo_gallery = $('.la-woo-product-gallery', this.$content);
                            if($woo_gallery.length && $.fn.lastudio_product_gallery){
                                $body.addClass('lightcase--pending');
                                $woo_gallery.lastudio_product_gallery();
                            }
                        },
                        afterClose: function(evt){
                            $body.removeClass('open-quickview-product lightcase--completed lightcase--pending');
                        }
                    });
                }
                if($.isFunction( $.fn.featherlight )) {
                    show_popup();
                }
                else{
                    LaStudio.global.loadDependencies([ LaStudio.global.loadJsFile('featherlight')], show_popup );
                }
            }
        });
    }
    LaStudioWooCommerce.prototype.Wishlist = function (){
        /**
         * Support YITH Wishlist
         */
        function set_attribute_for_wl_table(){
            var $table = $('table.wishlist_table');
            $table.addClass('shop_table_responsive');
            $table.find('thead th').each(function(){
                var _th = $(this),
                    _text = _th.text().trim();
                if(_text != ""){
                    $('td.' + _th.attr('class'), $table).attr('data-title', _text);
                }
            });
        }
        set_attribute_for_wl_table();
        $body.on('removed_from_wishlist', function(e){
            set_attribute_for_wl_table();
        });
        $document.on('added_to_cart', function(e, fragments, cart_hash, $button){
            setTimeout(set_attribute_for_wl_table, 800);
        });
        $document.on('click','.product a.add_wishlist.la-yith-wishlist',function(e){
            if(!$(this).hasClass('added')) {
                e.preventDefault();
                var $button     = $(this),
                    product_id = $button.data( 'product_id' ),
                    $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                    product_name = 'Product',
                    data = {
                        add_to_wishlist: product_id,
                        product_type: $button.data( 'product-type' ),
                        action: yith_wcwl_l10n.actions.add_to_wishlist_action
                    };
                if (!!$button.data('product_title')) {
                    product_name = $button.data('product_title');
                }
                if($button.closest('.product--summary').length){
                    $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                }
                try {
                    if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                        var wishlist_popup_container = $button.parents('.yith-wcwl-popup-footer').prev('.yith-wcwl-popup-content'),
                            wishlist_popup_select = wishlist_popup_container.find('.wishlist-select'),
                            wishlist_popup_name = wishlist_popup_container.find('.wishlist-name'),
                            wishlist_popup_visibility = wishlist_popup_container.find('.wishlist-visibility');

                        data.wishlist_id = wishlist_popup_select.val();
                        data.wishlist_name = wishlist_popup_name.val();
                        data.wishlist_visibility = wishlist_popup_visibility.val();
                    }

                    if (!LaStudio.global.isCookieEnable()) {
                        alert(yith_wcwl_l10n.labels.cookie_disabled);
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: yith_wcwl_l10n.ajax_url,
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $button.addClass('loading');
                        },
                        complete: function () {
                            $button.removeClass('loading').addClass('added');
                        },
                        success: function (response) {
                            var msg = $('#yith-wcwl-popup-message'),
                                response_result = response.result,
                                response_message = response.message;

                            if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                                var wishlist_select = $('select.wishlist-select');
                                if (typeof $.prettyPhoto !== 'undefined') {
                                    $.prettyPhoto.close();
                                }
                                wishlist_select.each(function (index) {
                                    var t = $(this),
                                        wishlist_options = t.find('option');
                                    wishlist_options = wishlist_options.slice(1, wishlist_options.length - 1);
                                    wishlist_options.remove();

                                    if (typeof response.user_wishlists !== 'undefined') {
                                        var i = 0;
                                        for (i in response.user_wishlists) {
                                            if (response.user_wishlists[i].is_default != "1") {
                                                $('<option>')
                                                    .val(response.user_wishlists[i].ID)
                                                    .html(response.user_wishlists[i].wishlist_name)
                                                    .insertBefore(t.find('option:last-child'))
                                            }
                                        }
                                    }
                                });

                            }
                            var html = '<div class="popup-added-msg">';
                            if (response_result == 'true') {
                                if ($product_image.length){
                                    html += $('<div>').append($product_image.clone()).html();
                                }
                                html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.wishlist.success + '</div>';
                            }else {
                                html += '<div class="popup-message">' + response_message + '</div>';
                            }
                            html += '<a class="button view-popup-wishlish" rel="nofollow" href="' + response.wishlist_url.replace('/view', '') + '">' + la_theme_config.i18n.wishlist.view + '</a>';
                            html += '<a class="button popup-button-continue" rel="nofollow" href="#">' + la_theme_config.i18n.global.continue_shopping + '</a>';
                            html += '</div>';

                            LaStudio.global.ShowMessageBox(html, 'open-wishlist-msg open-custom-msg');

                            $button.attr('href',response.wishlist_url);
                            $('.add_wishlist[data-product_id="' + $button.data('product_id') + '"]').addClass('added');
                            $body.trigger('added_to_wishlist');
                        }
                    });
                } catch (ex) {
                    console.log(ex);
                }
            }
        });


        /**
         * Support TI Wishlist
         */
        $document.on('click','.product a.add_wishlist.la-ti-wishlist',function(e){
            e.preventDefault();
            var $ti_action;
            if($(this).closest('.entry-summary').length){
                $ti_action = $(this).closest('.entry-summary').find('form.cart .tinvwl_add_to_wishlist_button');
            }
            else{
                $ti_action = $(this).closest('.product').find('.tinvwl_add_to_wishlist_button');
            }
            $ti_action.trigger('click');
        })

        /**
         * Core Wishlist
         */
        $document
            .on('click','.product a.add_wishlist.la-core-wishlist',function(e){
                if(!$(this).hasClass('added')) {
                    e.preventDefault();
                    var $button     = $(this),
                        product_id = $button.data( 'product_id' ),
                        $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                        product_name = 'Product',
                        data = {
                            action: 'la_helpers_wishlist',
                            security: la_theme_config.security.wishlist_nonce,
                            post_id: product_id,
                            type: 'add'
                        };
                    if (!!$button.data('product_title')) {
                        product_name = $button.data('product_title');
                    }
                    if($button.closest('.product--summary').length){
                        $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                    }

                    $.ajax({
                        type: 'POST',
                        url: la_theme_config.ajax_url,
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $button.addClass('loading');
                        },
                        complete: function () {
                            $button.removeClass('loading').addClass('added');
                        },
                        success: function (response) {
                            var html = '<div class="popup-added-msg">';

                            if (response.success) {
                                if ($product_image.length){
                                    html += $('<div>').append($product_image.clone()).html();
                                }
                                html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.wishlist.success + '</div>';
                            }
                            else {
                                html += '<div class="popup-message">' + response.data.message + '</div>';
                            }
                            html += '<a class="button view-popup-wishlish" rel="nofollow" href="'+response.data.wishlist_url+'">' + la_theme_config.i18n.wishlist.view + '</a>';
                            html += '<a class="button popup-button-continue" rel="nofollow" href="#">' + la_theme_config.i18n.global.continue_shopping + '</a>';
                            html += '</div>';

                            LaStudio.global.ShowMessageBox(html, 'open-wishlist-msg open-custom-msg');
                            $('.la-wishlist-count').html(response.data.count);

                            $('.add_wishlist[data-product_id="' + $button.data('product_id') + '"]').addClass('added').attr('href', response.data.wishlist_url);
                        }
                    });

                }
            })
            .on('click', '.la_wishlist_table a.la_remove_from_wishlist', function(e){
                e.preventDefault();
                var $table = $('#la_wishlist_table_wrapper');
                if( typeof $.fn.block != 'undefined' ) {
                    $table.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
                }
                $table.load( e.target.href + ' #la_wishlist_table_wrapper2', function(){
                    if( typeof $.fn.unblock != 'undefined' ) {
                        $table.stop(true).css('opacity', '1').unblock();
                    }
                } );
            });

        $document
            .on('adding_to_cart', function( e, $button, data ){
                if( $button && $button.closest('.la_wishlist_table').length ) {
                    data.la_remove_from_wishlist_after_add_to_cart = data.product_id;
                }
            })
            .on('added_to_cart', function( e, fragments, cart_hash, $button ){
                if($button && $button.closest('.la_wishlist_table').length ) {
                    var $table = $('#la_wishlist_table_wrapper');
                    $button.closest('tr').remove();
                    $table.load( window.location.href + ' #la_wishlist_table_wrapper2')
                }
            });

        $('form.variations_form').on('woocommerce_variation_has_changed', function(e){
            var $frm = $(this),
                variation_id = parseInt($frm.find('input[name="variation_id"]').val() || 0);
            if(variation_id == 0){
                variation_id = parseInt($frm.find('input[name="product_id"]').val());
            }
            $frm.closest('.entry-summary').find('.la-core-wishlist').attr('data-product_id', variation_id).removeClass('added');
        });
    }
    LaStudioWooCommerce.prototype.Compare = function (){
        /**
         * Support YITH Compare
         */
        $document
            .on('click', 'table.compare-list .remove a', function(e){
                e.preventDefault();
                $('.add_compare[data-product_id="' + $(this).data('product_id') + '"]', window.parent.document).removeClass('added');
            })
            .on('click','.la_com_action--compare', function(e){
                if(typeof yith_woocompare !== "undefined"){
                    e.preventDefault();
                    $document.trigger('LaStudio:Component:Popup:Close');
                    $body.trigger('yith_woocompare_open_popup', { response: LaStudio.global.addQueryArg( LaStudio.global.addQueryArg('', 'action', yith_woocompare.actionview) , 'iframe', 'true') });
                }
            })
            .on('click', '.product a.add_compare:not(.la-core-compare)', function(e){
                e.preventDefault();

                if($(this).hasClass('added')){
                    $body.trigger('yith_woocompare_open_popup', { response: LaStudio.global.addQueryArg( LaStudio.global.addQueryArg('', 'action', yith_woocompare.actionview) , 'iframe', 'true') });
                    return;
                }

                var $button     = $(this),
                    widget_list = $('.yith-woocompare-widget ul.products-list'),
                    $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                    data        = {
                        action: yith_woocompare.actionadd,
                        id: $button.data('product_id'),
                        context: 'frontend'
                    },
                    product_name = 'Product';
                if(!!$button.data('product_title')){
                    product_name = $button.data('product_title');
                }

                if($button.closest('.product--summary').length){
                    $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                }

                $.ajax({
                    type: 'post',
                    url: yith_woocompare.ajaxurl.toString().replace( '%%endpoint%%', yith_woocompare.actionadd ),
                    data: data,
                    dataType: 'json',
                    beforeSend: function(){
                        $button.addClass('loading');
                    },
                    complete: function(){
                        $button.removeClass('loading').addClass('added');
                    },
                    success: function(response){
                        if($.isFunction($.fn.block) ) {
                            widget_list.unblock()
                        }
                        var html = '<div class="popup-added-msg">';
                        if ($product_image.length){
                            html += $('<div>').append($product_image.clone()).html();
                        }
                        html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.compare.success + '</div>';
                        html += '<a class="button la_com_action--compare" rel="nofollow" href="'+response.table_url+'">'+la_theme_config.i18n.compare.view+'</a>';
                        html += '<a class="button popup-button-continue" href="#" rel="nofollow">'+ la_theme_config.i18n.global.continue_shopping + '</a>';
                        html += '</div>';

                        LaStudio.global.ShowMessageBox(html, 'open-compare-msg open-custom-msg');

                        $('.add_compare[data-product_id="' + $button.data('product_id') + '"]').addClass('added');

                        widget_list.unblock().html( response.widget_table );
                    }
                });
            });

        /**
         * Core Compare
         */
        $document.on('LaStudio.WooCommerceCompare.FixHeight', '.la-compare-table-items' ,function (e) {
            $('th', $(this)).each(function (idx) {
                $('.la-compare-table-heading th').eq(idx).css( 'height', $(this).outerHeight() );
            })
        });

        $('.la-compare-table-items').trigger('LaStudio.WooCommerceCompare.FixHeight');

        $document
            .on('click', '.product a.add_compare.la-core-compare', function(e){
                if(!$(this).hasClass('added')) {
                    e.preventDefault();
                    var $button     = $(this),
                        product_id = $button.data( 'product_id' ),
                        $product_image = $button.closest('.product').find('.product_item--thumbnail img:eq(0)'),
                        product_name = 'Product',
                        data = {
                            action: 'la_helpers_compare',
                            security: la_theme_config.security.compare_nonce,
                            post_id: product_id,
                            type: 'add'
                        };
                    if (!!$button.data('product_title')) {
                        product_name = $button.data('product_title');
                    }
                    if($button.closest('.product--summary').length){
                        $product_image = $button.closest('.product').find('.woocommerce-product-gallery__image img:eq(0)');
                    }

                    $.ajax({
                        type: 'POST',
                        url: la_theme_config.ajax_url,
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $button.addClass('loading');
                        },
                        complete: function () {
                            $button.removeClass('loading').addClass('added');
                        },
                        success: function (response) {
                            var html = '<div class="popup-added-msg">';

                            if (response.success) {
                                if ($product_image.length){
                                    html += $('<div>').append($product_image.clone()).html();
                                }
                                html += '<div class="popup-message"><strong class="text-color-heading">'+ product_name +' </strong>' + la_theme_config.i18n.compare.success + '</div>';
                            }
                            else {
                                html += '<div class="popup-message">' + response.data.message + '</div>';
                            }
                            html += '<a class="button view-popup-compare" rel="nofollow" href="'+response.data.compare_url+'">' + la_theme_config.i18n.compare.view + '</a>';
                            html += '<a class="button popup-button-continue" rel="nofollow" href="#">' + la_theme_config.i18n.global.continue_shopping + '</a>';
                            html += '</div>';

                            LaStudio.global.ShowMessageBox(html, 'open-compare-msg open-custom-msg');
                            $('.la-compare-count').html(response.data.count);

                            $('.add_compare[data-product_id="' + $button.data('product_id') + '"]').addClass('added').attr('href', response.data.compare_url);
                        }
                    });

                }
            })
            .on('click', '.la_remove_from_compare', function(e){
                e.preventDefault();
                var $table = $('#la_compare_table_wrapper');
                if( typeof $.fn.block != 'undefined' ) {
                    $table.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
                }

                console.log( e.target.href);

                $table.load( e.target.href + ' #la_compare_table_wrapper2', function(){
                    if( typeof $.fn.unblock != 'undefined' ) {
                        $table.stop(true).css('opacity', '1').unblock();
                        setTimeout(function () {
                            $('.la-compare-table-items').trigger('LaStudio.WooCommerceCompare.FixHeight');
                        }, 300);
                    }
                } );
            });
    }
    LaStudioWooCommerce.prototype.AjaxFilter = function (){
        if( $('#la_shop_products').length == 0 || $('#la_shop_products.deactive-filters').length){
            return;
        }

        $('li.current-cat, li.current-cat-parent', $('#sidebar_primary')).each(function(){
            $(this).addClass('open');
            $('>ul', $(this)).css('display','block');
        });

        function clone_view_count() {
            return;
            var $vcount = $('.wc-toolbar-top .wc-view-count');
            if($vcount.length){
                $('#la_shop_products .woocommerce-pagination').addClass('wc-toolbar').append($vcount.clone());
            }
        }

        clone_view_count();

        function init_price_filter() {
            if ( typeof woocommerce_price_slider_params === 'undefined' ) {
                return false;
            }

            $( 'input#min_price, input#max_price' ).hide();
            $( '.price_slider, .price_label' ).show();

            var min_price = $( '.price_slider_amount #min_price' ).data( 'min' ),
                max_price = $( '.price_slider_amount #max_price' ).data( 'max' ),
                current_min_price = $( '.price_slider_amount #min_price' ).val(),
                current_max_price = $( '.price_slider_amount #max_price' ).val();

            $( '.price_slider:not(.ui-slider)' ).slider({
                range: true,
                animate: true,
                min: min_price,
                max: max_price,
                values: [ current_min_price, current_max_price ],
                create: function() {

                    $( '.price_slider_amount #min_price' ).val( current_min_price );
                    $( '.price_slider_amount #max_price' ).val( current_max_price );

                    $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
                },
                slide: function( event, ui ) {

                    $( 'input#min_price' ).val( ui.values[0] );
                    $( 'input#max_price' ).val( ui.values[1] );

                    $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
                },
                change: function( event, ui ) {

                    $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
                }
            });
        }

        var elm_to_replace = [
            '.wc-toolbar-top',
            '.la-advanced-product-filters .sidebar-inner',
            '.wc_page_description'
        ];

        if( $('#la_shop_products').hasClass('elementor-widget') ) {
            elm_to_replace.push('ul.ul_products');
            elm_to_replace.push('.la-pagination');
        }
        else{
            elm_to_replace.push('#la_shop_products');
        }

        var target_to_init = '#la_shop_products .la-pagination:not(.la-ajax-pagination) a, .la-advanced-product-filters-result a',
            target_to_init2 = '.woo-widget-filter a, .wc-ordering a, .wc-view-count a, .woocommerce.product-sort-by a, .woocommerce.la-price-filter-list a, .woocommerce.widget_layered_nav a, .woocommerce.widget_product_tag_cloud li a, .woocommerce.widget_product_categories a',
            target_to_init3 = '.woocommerce.widget_product_tag_cloud:not(.la_product_tag_cloud) a';

        LaStudio.global.eventManager.subscribe('LaStudio:AjaxShopFilter', function(e, url, element){

            if( $('.wc-toolbar-container').length > 0) {
                var position = $('.wc-toolbar-container').offset().top - 200;
                $htmlbody.stop().animate({
                    scrollTop: position
                }, 800 );
            }

            if ('?' == url.slice(-1)) {
                url = url.slice(0, -1);
            }
            url = url.replace(/%2C/g, ',');

            url = LaStudio.global.removeURLParameter(url,'la_doing_ajax');

            try{
                history.pushState(null, null, url);
            }catch (ex) {

            }

            LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter:before_send', [url, element]);

            if (LaStudio.utils.ajax_xhr) {
                LaStudio.utils.ajax_xhr.abort();
            }

            url = LaStudio.global.addQueryArg(url, 'la_doing_ajax', 'true');

            LaStudio.utils.ajax_xhr = $.get(url, function ( response ) {

                for ( var i = 0; i < elm_to_replace.length; i++){
                    if( $(elm_to_replace[i]).length ){
                        if( elm_to_replace[i] == '.la-advanced-product-filters .sidebar-inner'){
                            if( $(response).find(elm_to_replace[i]).length ){
                                $(elm_to_replace[i]).replaceWith( $(response).find(elm_to_replace[i]) );
                                LaStudio.core.Blog($(elm_to_replace[i]));
                                $('li.current-cat, li.current-cat-parent', $(elm_to_replace[i])).each(function(){
                                    $(this).addClass('open');
                                    $('>ul', $(this)).css('display','block');
                                });
                            }
                        }
                        else{
                            $(elm_to_replace[i]).replaceWith( $(response).find(elm_to_replace[i]) );
                        }
                    }
                }

                if( $('#sidebar_primary').length && $(response).find('#sidebar_primary').length ) {
                    $('#sidebar_primary').replaceWith($(response).find('#sidebar_primary'));
                    LaStudio.core.Blog($('#sidebar_primary'));
                    $('li.current-cat, li.current-cat-parent', $('#sidebar_primary')).each(function(){
                        $(this).addClass('open');
                        $('>ul', $(this)).css('display','block');
                    });
                }

                if( $('#section_page_header').length && $(response).find('#section_page_header').length ) {
                    $('#section_page_header').replaceWith($(response).find('#section_page_header'));
                }

                try {
                    var _view_mode = Cookies.get('luxsa_wc_catalog_view_mode');
                    $('.wc-toolbar .wc-view-toggle button[data-view_mode="'+_view_mode+'"]').trigger('click');

                    var _per_row = Cookies.get('luxsa_wc_product_per_row');
                    $('.wc-toolbar .wc-view-item a[data-col="'+_per_row+'"]').trigger('click');

                }catch (e) {

                }

                $('body').trigger('lastudio-fix-ios-limit-image-resource');

                $('.la-ajax-shop-loading').removeClass('loading');

                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter:success', [response, url, element]);

            }, 'html');
        });
        LaStudio.global.eventManager.subscribe('LaStudio:AjaxShopFilter:success', function(e, response, url, element){

            if( $('.widget.woocommerce.widget_price_filter').length ) {
                init_price_filter();
            }
            if($body.hasClass('open-advanced-shop-filter')){
                $body.removeClass('open-advanced-shop-filter');
                $('.la-advanced-product-filters').stop().slideUp('fast');
            }
            clone_view_count();

            var pwb_params = LaStudio.global.getUrlParameter('pwb-brand-filter', location.href);
            if(pwb_params !== null && pwb_params !== ''){
                $('.pwb-filter-products input[type="checkbox"]').prop("checked", false);
                pwb_params.split(',').filter(function (el){
                    $('.pwb-filter-products input[type="checkbox"][value="'+el+'"]').prop("checked", true);
                })
            }
            $('body').trigger('lastudio-fix-ios-limit-image-resource').trigger( 'lastudio-lazy-images-load' ).trigger( 'jetpack-lazy-images-load' ).trigger( 'lastudio-object-fit' );
            LaStudio.core.initAll($document);
        });

        $document
            .on('click', '.btn-advanced-shop-filter', function (e) {
                e.preventDefault();
                $body.toggleClass('open-advanced-shop-filter');
                $('.la-advanced-product-filters').stop().animate({
                    height: 'toggle'
                });
            })
            .on('click', '.la-advanced-product-filters .close-advanced-product-filters', function(e){
                e.preventDefault();
                $('.btn-advanced-shop-filter').trigger('click');
            })
            .on('click', target_to_init, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [$(this).attr('href'), $(this)]);
            })
            .on('click', target_to_init2, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                if($(this).closest('.widget_layered_nav').length){
                    $(this).parent().addClass('active');
                }
                else{
                    $(this).parent().addClass('active').siblings().removeClass('active');
                }

                $('.lasf-custom-dropdown').removeClass('is-hover');

                var _url = $(this).attr('href'),
                    _preset_from_w = LaStudio.global.getUrlParameter('la_preset'),
                    _preset_from_e = LaStudio.global.getUrlParameter('la_preset', _url);

                if(!_preset_from_e && _preset_from_w){
                    _url = LaStudio.global.addQueryArg(_url, 'la_preset', _preset_from_w);
                }

                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $(this)]);
            })

            .on('click', target_to_init3, function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                $(this).addClass('active').siblings().removeClass('active');
                var _url = $(this).attr('href'),
                    _preset_from_w = LaStudio.global.getUrlParameter('la_preset'),
                    _preset_from_e = LaStudio.global.getUrlParameter('la_preset', _url);

                if(!_preset_from_e && _preset_from_w){
                    _url = LaStudio.global.addQueryArg(_url, 'la_preset', _preset_from_w);
                }
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $(this)]);
            })
            .on('click', '.woocommerce.widget_layered_nav_filters a', function(e){
                e.preventDefault();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [$(this).attr('href'), $(this)]);
            })
            .on('submit', '.widget_price_filter form, .woocommerce-widget-layered-nav form', function(e){
                e.preventDefault();
                var $form = $(this),
                    url = $form.attr('action') + '?' + $form.serialize();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [url, $form]);
            })
            .on('change', '.woocommerce-widget-layered-nav form select', function(e){
                e.preventDefault();
                var slug = $( this ).val(),
                    _id = $(this).attr('class').split('dropdown_layered_nav_')[1];
                $( ':input[name="filter_'+_id+'"]' ).val( slug );

                // Submit form on change if standard dropdown.
                if ( ! $( this ).attr( 'multiple' ) ) {
                    $( this ).closest( 'form' ).submit();
                }
            })
            .on('change', '.widget_pwb_dropdown_widget .pwb-dropdown-widget', function(e){
                e.preventDefault();
                var $form = $(this),
                    url = $(this).val();
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [url, $form]);
            })
            .on('click', '.widget_pwb_filter_by_brand_widget .pwb-filter-products button', function (e){
                e.preventDefault();
                var $form = $(this).closest('.pwb-filter-products'),
                    _url = $form.data('cat-url'),
                    _params = [];
                $form.find('input[type="checkbox"]:checked').each(function (){
                    _params.push($(this).val());
                });
                if(_params.length > 0){
                    _url = LaStudio.global.addQueryArg(_url, 'pwb-brand-filter', _params.join(','));
                }
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $form]);
            })
            .on('change', '.widget_pwb_filter_by_brand_widget .pwb-filter-products.pwb-hide-submit-btn input', function (e){
                e.preventDefault();
                var $form = $(this).closest('.pwb-filter-products'),
                    _url = $form.data('cat-url'),
                    _params = [];
                $form.find('input[type="checkbox"]:checked').each(function (){
                    _params.push($(this).val());
                });
                if(_params.length > 0){
                    _url = LaStudio.global.addQueryArg(_url, 'pwb-brand-filter', _params.join(','));
                }
                $('.la-ajax-shop-loading').addClass('loading');
                LaStudio.global.eventManager.publish('LaStudio:AjaxShopFilter', [_url, $form]);
            })
        $('.widget_pwb_dropdown_widget .pwb-dropdown-widget').off('change');
        $('.widget_pwb_filter_by_brand_widget .pwb-filter-products button').off('click');
        $('.widget_pwb_filter_by_brand_widget .pwb-filter-products.pwb-hide-submit-btn input').off('change');
    }

    window.LaStudioWooCommerce = new LaStudioWooCommerce()

})(jQuery);
(function ($) {
    "use strict";
    function _fix_wc_giftcart(){
        if (la_theme_config.single_ajax_add_cart == 'on' || la_theme_config.single_ajax_add_cart == 'yes') {
            var $form = $('.la-p-single-wrap.product-type-simple.wc_gc_giftcard_product .entry-summary form.cart');
            if($form.length){
                $form.find('.wrap-single-addcart').remove();
                $form.closest('.product-type-simple').removeClass('product-type-simple');
                $form.prepend('<input type="hidden" name="add-to-cart" value="' + $form.find('.single_add_to_cart_button[name="add-to-cart"]').val() + '">');
            }
        }
    }
})(jQuery);

