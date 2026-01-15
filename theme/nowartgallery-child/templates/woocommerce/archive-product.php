<?php
/**
 * Product Archive Template Override
 *
 * This template can be used to override the default WooCommerce archive template.
 * Copy this file to your child theme's woocommerce folder if you need customizations.
 *
 * @package NowArtGalleryChild
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Use parent theme's archive template
// This file exists to allow future customizations
// For now, we'll let Kiosko handle the archive display
// and add customizations via hooks in functions.php

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

if ( woocommerce_product_loop() ) {
	woocommerce_product_loop_start();
	
	if ( wc_get_loop_prop( 'is_shortcode' ) ) {
		woocommerce_product_loop_start();
	}

	while ( have_posts() ) {
		the_post();
		wc_get_template_part( 'content', 'product' );
	}

	woocommerce_product_loop_end();

	do_action( 'woocommerce_after_shop_loop' );
} else {
	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
