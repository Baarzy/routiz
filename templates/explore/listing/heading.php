<?php

defined('ABSPATH') || exit;

global $rz_listing;

$verified = get_user_meta( $rz_listing->post->post_author, 'rz_verified', true );

?>

<div class="rz--heading">
    <div class="rz--main">
        <div class="rz-title">
            <h4>
                <?php if( $verified ): ?>
                    <i class="rz--verified fas fa-check-circle"></i>
                <?php endif; ?>
                <?php echo do_shortcode( $rz_listing->get( $rz_listing->type->get('rz_display_listing_title') ) ); ?>
            </h4>
        </div>
        <?php if( $tagline_field = $rz_listing->type->get('rz_display_listing_tagline') ): ?>
            <?php if( $tagline = $rz_listing->get( $tagline_field ) ): ?>
                <div class="rz-listing-tagline">
                    <?php echo do_shortcode( wp_trim_words( $tagline, 12 ) ); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php if( $rz_listing->type->get('rz_display_listing_review') and $rz_listing->reviews->average ): ?>
        <div class="rz--review">
            <div class="rz-listing-review">
                <i class="fas fa-star"></i>
                <span><?php echo number_format( $rz_listing->reviews->average, 2 ); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>
