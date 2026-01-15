<?php
/**
 * Now Art Gallery Child Theme Functions
 *
 * @package NowArtGalleryChild
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue parent and child theme styles
 */
function nowartgallery_child_enqueue_styles() {
	// Enqueue parent theme styles
	wp_enqueue_style(
		'kiosko-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme()->parent()->get( 'Version' )
	);

	// Enqueue child theme styles
	wp_enqueue_style(
		'nowartgallery-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'kiosko-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);

	// Enqueue custom CSS if it exists
	if ( file_exists( get_stylesheet_directory() . '/assets/css/custom.css' ) ) {
		wp_enqueue_style(
			'nowartgallery-custom-style',
			get_stylesheet_directory_uri() . '/assets/css/custom.css',
			array( 'nowartgallery-child-style' ),
			filemtime( get_stylesheet_directory() . '/assets/css/custom.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nowartgallery_child_enqueue_styles' );

/**
 * Enqueue custom JavaScript
 */
function nowartgallery_child_enqueue_scripts() {
	if ( file_exists( get_stylesheet_directory() . '/assets/js/custom.js' ) ) {
		wp_enqueue_script(
			'nowartgallery-custom-script',
			get_stylesheet_directory_uri() . '/assets/js/custom.js',
			array( 'jquery' ),
			filemtime( get_stylesheet_directory() . '/assets/js/custom.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nowartgallery_child_enqueue_scripts' );

/**
 * Add custom WooCommerce support
 */
function nowartgallery_child_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'nowartgallery_child_woocommerce_setup' );

/**
 * Add custom product meta fields for artwork
 */
function nowartgallery_add_product_meta_fields() {
	global $woocommerce, $post;

	echo '<div class="product_meta_fields">';
	
	// Artwork dimensions
	woocommerce_wp_text_input(
		array(
			'id'          => '_artwork_dimensions',
			'label'       => __( 'Dimensions', 'nowartgallery-child' ),
			'placeholder' => 'e.g., 50cm x 70cm',
			'desc_tip'    => true,
			'description' => __( 'Enter the artwork dimensions', 'nowartgallery-child' ),
		)
	);

	// Artist name
	woocommerce_wp_text_input(
		array(
			'id'          => '_artist_name',
			'label'       => __( 'Artist Name', 'nowartgallery-child' ),
			'placeholder' => 'Artist name',
			'desc_tip'    => true,
			'description' => __( 'Enter the artist name', 'nowartgallery-child' ),
		)
	);

	// Limited edition checkbox
	woocommerce_wp_checkbox(
		array(
			'id'          => '_limited_edition',
			'label'       => __( 'Limited Edition', 'nowartgallery-child' ),
			'description' => __( 'Check if this is a limited edition artwork', 'nowartgallery-child' ),
		)
	);

	// Edition number (if limited edition)
	woocommerce_wp_text_input(
		array(
			'id'          => '_edition_number',
			'label'       => __( 'Edition Number', 'nowartgallery-child' ),
			'placeholder' => 'e.g., 1/100',
			'desc_tip'    => true,
			'description' => __( 'Enter the edition number (e.g., 1/100)', 'nowartgallery-child' ),
		)
	);

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'nowartgallery_add_product_meta_fields' );

/**
 * Save custom product meta fields
 */
function nowartgallery_save_product_meta_fields( $post_id ) {
	$artwork_dimensions = isset( $_POST['_artwork_dimensions'] ) ? sanitize_text_field( $_POST['_artwork_dimensions'] ) : '';
	$artist_name        = isset( $_POST['_artist_name'] ) ? sanitize_text_field( $_POST['_artist_name'] ) : '';
	$limited_edition    = isset( $_POST['_limited_edition'] ) ? 'yes' : 'no';
	$edition_number     = isset( $_POST['_edition_number'] ) ? sanitize_text_field( $_POST['_edition_number'] ) : '';

	update_post_meta( $post_id, '_artwork_dimensions', $artwork_dimensions );
	update_post_meta( $post_id, '_artist_name', $artist_name );
	update_post_meta( $post_id, '_limited_edition', $limited_edition );
	update_post_meta( $post_id, '_edition_number', $edition_number );
}
add_action( 'woocommerce_process_product_meta', 'nowartgallery_save_product_meta_fields' );

/**
 * Display custom product meta on single product page
 */
function nowartgallery_display_product_meta() {
	global $product;

	$artist_name     = get_post_meta( $product->get_id(), '_artist_name', true );
	$dimensions      = get_post_meta( $product->get_id(), '_artwork_dimensions', true );
	$limited_edition = get_post_meta( $product->get_id(), '_limited_edition', true );
	$edition_number  = get_post_meta( $product->get_id(), '_edition_number', true );

	if ( $artist_name || $dimensions || ( $limited_edition === 'yes' && $edition_number ) ) {
		echo '<div class="artwork-details">';
		
		if ( $artist_name ) {
			echo '<p class="artist-name"><strong>' . esc_html__( 'Artist:', 'nowartgallery-child' ) . '</strong> ' . esc_html( $artist_name ) . '</p>';
		}
		
		if ( $dimensions ) {
			echo '<p class="artwork-dimensions"><strong>' . esc_html__( 'Dimensions:', 'nowartgallery-child' ) . '</strong> ' . esc_html( $dimensions ) . '</p>';
		}
		
		if ( $limited_edition === 'yes' && $edition_number ) {
			echo '<p class="limited-edition"><strong>' . esc_html__( 'Limited Edition:', 'nowartgallery-child' ) . '</strong> ' . esc_html( $edition_number ) . '</p>';
		}
		
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'nowartgallery_display_product_meta', 25 );

/**
 * Add limited edition badge to product
 */
function nowartgallery_limited_edition_badge() {
	global $product;
	
	$limited_edition = get_post_meta( $product->get_id(), '_limited_edition', true );
	
	if ( $limited_edition === 'yes' ) {
		echo '<span class="limited-edition-badge">' . esc_html__( 'Limited Edition', 'nowartgallery-child' ) . '</span>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'nowartgallery_limited_edition_badge', 5 );
