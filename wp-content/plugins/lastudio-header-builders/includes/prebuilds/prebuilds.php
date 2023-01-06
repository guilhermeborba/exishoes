<div class="lahb-predefined-modal-inner-content">
    <?php

    $lahb_preheaders = LAHB_Helper::get_prebuild_headers();

    $current_url = admin_url( 'admin.php?page=lastudio_header_builder_setting' );

    if(LAHB_Helper::is_frontend_builder()){
        $current_url = admin_url( 'admin.php?page=lastudio_header_builder' );
    }

    if(!empty($lahb_preheaders)){
        foreach ($lahb_preheaders as $k => $v){
            if(!empty($v['image']) && wp_attachment_is_image($v['image'])){
                $tmp_name = '<img src="'.wp_get_attachment_image_url($v['image'],'full').'" alt="'.esc_attr($v['name']).'"/>';
            }
            elseif( !empty($v['image_url']) ){
                $tmp_name = '<img src="'.$v['image_url'].'" alt="'.esc_attr($v['name']).'"/>';
            }
            else{
                $tmp_name = '<span>' . $v['name'] . '</span>';
            }
            echo sprintf(
                '<a class="lahb-prebuild-item" data-saved-name="%s" href="%s">%s<i class="dashicons dashicons-trash"></i></a>',
                esc_attr($k),
                add_query_arg(array('prebuild_header' => esc_attr($k)), $current_url ),
                $tmp_name
            );
        }
    }
    ?>
</div>