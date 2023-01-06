(function($) {
    'use strict';

    if( typeof lastudio_swatches_vars === "undefined" ){
        return;
    }

    var $document = $(document),
        $body = $('body');


    function input_variation_gallery_changed( $input ) {
        $input
            .closest( '.woocommerce_variation' )
            .addClass( 'variation-needs-update' );

        $( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );
        $( '#variable_product_options' ).trigger( 'woocommerce_variations_input_changed' );
    }

    // Update Selected Images
    function update_selected_images( $table_col ) {
        // Get all selected images
        var $selectedImgs = [],
            $gallery_field = $table_col.find('.la_variation_image_gallery');

        $table_col.find('.la_variation_thumbs .image').each(function(){
            $selectedImgs.push($(this).attr('data-attachment_id'));
        });
        // Update hidden input with chosen images
        $gallery_field.val($selectedImgs.join(','));
        input_variation_gallery_changed( $gallery_field );
    }


    function trigger_get_gallery_data() {
        // Moving gallery after featured image row
        $('.woocommerce_variable_attributes .data > .lastudio-advance-gallery-for-variation').each(function () {

            var $me = $(this);

            $me.appendTo( $me.closest('.data').find('.form-row.upload_image') );
            // Sort Images
            $( '.la_variation_thumbs', $me ).sortable({
                deactivate: function(en, ui) {
                    var $table_col = $(ui.item).closest('.la_variation_thumb');
                    update_selected_images($table_col);
                },
                placeholder: 'ui-state-highlight'
            });
        })
    }

    // Setup Variation Image Manager
    function setup_variation_image_manager(){

        trigger_get_gallery_data();

        var product_gallery_frame;
        $document.on('click', '.la_swatches--manage_variation_thumbs', function(e){
            e.preventDefault();
            var $el = $(this),
                $variation_thumbs = $el.siblings('.la_variation_thumbs'),
                $image_gallery_ids = $el.siblings('.la_variation_image_gallery'),
                attachment_ids = $image_gallery_ids.val();

            // Create the media frame.
            product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                // Set the title of the modal.
                title: 'Manage Variation Images',
                button: {
                    text: 'Add to variation'
                },
                multiple: true
            });

            // When an image is selected, run a callback.
            product_gallery_frame.on( 'select', function() {
                var selection = product_gallery_frame.state().get('selection');
                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();
                    if ( attachment.id ) {
                        attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
                        $variation_thumbs.append('<li class="image" data-attachment_id="' + attachment.id + '"><a href="#" class="delete" title="Delete image"><span style="background-image: url('+attachment.url+')"></span></a></li>');
                    }
                } );

                $image_gallery_ids.val( attachment_ids );
                input_variation_gallery_changed( $image_gallery_ids );
            });

            // Finally, open the modal.
            product_gallery_frame.open();

            return false;
        });

        // Delete Image
        $document.on('click', '.la_variation_thumbs .delete', function(e){
            e.preventDefault();
            var $table_col = $(this).closest('.la_variation_thumb');
            // Remove clicked image
            $(this).closest('li').remove();
            update_selected_images($table_col);
        });

        // after variations load
        $( '#woocommerce-product-data' ).on( 'woocommerce_variations_loaded', function(){
            trigger_get_gallery_data();
        });

        // Once a new variation is added
        $('#variable_product_options').on('woocommerce_variations_added', function(){
            trigger_get_gallery_data();
        });
    }

    $(function(){

        setup_variation_image_manager();

        $('.la_swatch_field_form_mask .field_form').lasf_reload_script();

        $document
            .on('click', '.la_swatch_field_meta', function(e){
                e.preventDefault();
                $(this).toggleClass('open-form');
            })

            .on('change', '.lastudio_swatches .fields .sub_field select', function(e){
                var $this = $(this);
                $this.closest('.sub_field').find('.attribute_swatch_type').html($this.find('option:selected').text());
                if($this.val() == 'color'){
                    $this.closest('.sub_field').find('.attr-prev-type-color').show();
                    $this.closest('.sub_field').find('.attr-prev-type-image').hide();
                }else{
                    $this.closest('.sub_field').find('.attr-prev-type-color').hide();
                    $this.closest('.sub_field').find('.attr-prev-type-image').show();
                }
            })
            .on('change', '.lastudio_swatches .fields .sub_field input.wp-color-picker', function(){
                var $this = $(this);
                $this.closest('.sub_field').find('.attr-prev-type-color').css('background-color', $this.val());
            })
            .on('change', '.lastudio_swatches .fields .sub_field .lasf-field-media input', function(){
                var $this = $(this);
                $this.closest('.sub_field').find('.attr-prev-type-image').html($this.closest('.lasf-fieldset').find('.lasf--preview').html());
            })
            .on('change', '.lastudio_swatches .fields .lasf-parent-type-class', function(){
                var $this = $(this);
                $this.closest('.field').find('> .la_swatch_field_meta .attribute_swatch_type').html($this.find('option:selected').text());
            })
            .on('reload', '#variable_product_options', function(e){

                if($('#panel_la_swatches_inner').length == 0){
                    return;
                }
                $( '#woocommerce-product-data' ).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
                var this_page = window.location.toString().replace( 'post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&' );
                $( '#lastudio_swatches' ).load( this_page + ' #panel_la_swatches_inner', function() {
                    $( '#lastudio_swatches').trigger('reload');
                    $('.la_swatch_field_form_mask .field_form').lasf_reload_script();
                });
            })
            .on('woocommerce_variations_saved', '#woocommerce-product-data' ,function(e){
                if($('#panel_la_swatches_inner').length == 0){
                    return;
                }
                $( '#woocommerce-product-data' ).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
                var this_page = window.location.toString().replace( 'post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&' );
                $( '#lastudio_swatches' ).load( this_page + ' #panel_la_swatches_inner', function() {
                    $( '#lastudio_swatches').trigger('reload');
                    $('.la_swatch_field_form_mask .field_form').lasf_reload_script();
                });
            })

    })

})(jQuery);