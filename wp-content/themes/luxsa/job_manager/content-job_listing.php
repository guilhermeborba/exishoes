<?php
/**
 * Job listing in the loop.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @since       1.0.0
 * @version     1.27.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


global $post, $lastudio_listing_loop;

$image_size = isset($lastudio_listing_loop['image_size']) ? $lastudio_listing_loop['image_size'] : 'thumbnail';

$price_text = get_post_meta(get_the_ID(), '_company_price_text', true);

$preset = isset($lastudio_listing_loop['preset']) ? $lastudio_listing_loop['preset'] : '';

?>
<li <?php job_listing_class('grid-item'); ?> data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">
    <?php if(has_post_thumbnail()): ?>
    <div class="listing__image">
        <a href="<?php the_job_permalink(); ?>">
            <figure class="figure__object_fit"><?php
                the_post_thumbnail($image_size);
            ?></figure>
        </a>
    </div>
    <?php endif; ?>
    <?php if($preset == 'type-3') { echo '<div class="listing__content-outer">'; } ?>
    <div class="listing__content">
        <div class="listing__content-inner">
            <div class="listing__content-types">
                <?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
                    <?php $types = wpjm_get_the_job_types(); ?>
                    <?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
                        <span class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>"><?php echo esc_html( $type->name ); ?></span>
                    <?php endforeach; endif; ?>
                <?php } ?>
            </div>
            <h3 class="listing__content-title"><a href="<?php the_job_permalink(); ?>"><?php wpjm_the_job_title(); ?></a></h3>
            <div class="listing__content-meta"><div class="listing__content-metaitem meta--location"><i class="lastudioicon-pin-3-2"></i><span><?php the_job_location( false ); ?></span></div></div>
            <div class="listing__content-price"><?php echo esc_html($price_text); ?></div>
            <div class="listing__content-meta"><?php
                $metadata = array(
                    array(
                        'icon' => 'lastudioicon-zoom-88',
                        'class' => 'size',
                        'value' => get_post_meta(get_the_ID(), '_company_size_text', true)
                    ),
                    array(
                        'icon' => 'lastudioicon-car-parking',
                        'class' => 'gara',
                        'value' => get_post_meta(get_the_ID(), '_company_gara', true)
                    ),
                    array(
                        'icon' => 'lastudioicon-bath-tub-1',
                        'class' => 'bathroom',
                        'value' => get_post_meta(get_the_ID(), '_company_bathroom', true)
                    ),
                    array(
                        'icon' => 'lastudioicon-bedroom-1',
                        'class' => 'bedroom',
                        'value' => get_post_meta(get_the_ID(), '_company_bedroom', true)
                    ),
                );
                foreach ($metadata as $meta){
                    if(!empty($meta['value'])){
                        printf('<div class="listing__content-metaitem meta--%s"><i class="%s"></i><span class="listing__content-metavalue">%s</span></div>',
                            esc_attr($meta['class']),
                            esc_attr($meta['icon']),
                            ( $meta['value'] < 10 ? '0' : '' ) . esc_html($meta['value'])
                        );
                    }
                }
            ?></div>
            <ul class="meta hidden">
                <?php do_action( 'job_listing_meta_start' ); ?>
                <li class="date"><?php the_job_publish_date(); ?></li>
                <?php do_action( 'job_listing_meta_end' ); ?>
            </ul>
        </div>
    </div>
    <?php if($preset == 'type-3') { echo '</div>'; } ?>
</li>