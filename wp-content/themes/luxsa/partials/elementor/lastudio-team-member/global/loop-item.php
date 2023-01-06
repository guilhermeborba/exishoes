<?php
/**
 * team-member loop start template
 */

$role           = luxsa_get_post_meta(get_the_ID(), 'role');
$thumbnail_size = $this->get_settings_for_display('thumb_size');
$excerpt_length = absint($this->get_settings_for_display('excerpt_length'));

$phone          = luxsa_get_post_meta(get_the_ID(), 'phone');
$email          = luxsa_get_post_meta(get_the_ID(), 'email');

$preset        = $this->get_settings_for_display('preset');
$layout        = $this->get_settings_for_display('layout_type');

$show_role      = $this->get_settings_for_display('show_role');
$show_social      = $this->get_settings_for_display('show_social');

$post_link = get_the_permalink();

?>
<div class="lastudio-team-member__item loop__item grid-item">
	<div class="lastudio-team-member__inner-box">
        <div class="lastudio-team-member__inner">
            <div class="lastudio-team-member__image_wrap">
                <a href="<?php echo esc_url($post_link); ?>" title="<?php the_title_attribute(); ?>" class="lastudio-images-layout__link">
                    <figure class="figure__object_fit lastudio-team-member__image">
                        <?php the_post_thumbnail($thumbnail_size, array('class' => 'lastudio-team-member__image-instance', 'alt' => esc_attr(get_the_title())));?>
                    </figure>
                </a>
                <?php
                if(in_array($preset, array('type-1', 'type-2', 'type-3')) && $show_social){
                    echo '<div class="lastudio-team-member__cover"><div class="lastudio-team-member__socials">' . luxsa_get_member_social_tpl(get_the_ID()) . '</div></div>';
                }
                ?>
            </div>
            <div class="lastudio-team-member__content">
                <?php

                $title_tag = $this->get_settings_for_display('title_html_tag');
                echo sprintf(
                    '<%1$s class="lastudio-team-member__name"><a href="%2$s">%3$s</a></%1$s>',
                    esc_attr($title_tag),
                    esc_url($post_link),
                    esc_html(get_the_title())
                );

                if(!empty($role) && $show_role){
                    echo sprintf('<div class="lastudio-team-member__position"><span>%s</span></div>', esc_html($role));
                }

                if($preset == 'type-8' && $show_social){
                    if(!empty($email)){
                        echo sprintf('<div class="lastudio-team-member__email"><i class="lastudioicon-mail"></i><span>%s</span></div>', esc_html($email));
                    }
                    if(!empty($phone)){
                        echo sprintf('<div class="lastudio-team-member__phone"><i class="lastudioicon-phone-call-2"></i><span>%s</span></div>', esc_html($phone));
                    }
                }

                if($excerpt_length > 0){
                    echo sprintf(
                        '<p class="lastudio-team-member__desc">%1$s</p>',
                        luxsa_excerpt(intval( $excerpt_length ))
                    );
                }

                if(!in_array($preset, array('type-1', 'type-2', 'type-3')) && $show_social){
                    echo '<div class="lastudio-team-member__socials">' . luxsa_get_member_social_tpl(get_the_ID()) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>