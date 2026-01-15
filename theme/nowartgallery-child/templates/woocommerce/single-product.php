<?php
/**
 * Single Product Template Override
 *
 * This template can be used to override the default WooCommerce single product template.
 * Copy this file to your child theme's woocommerce folder if you need customizations.
 *
 * @package NowArtGalleryChild
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Use parent theme's single product template
// This file exists to allow future customizations
// For now, we'll let Kiosko handle the single product display
// and add customizations via hooks in functions.php

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

while ( have_posts() ) :
	the_post();
	wc_get_template_part( 'content', 'single-product' );
endwhile;

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
