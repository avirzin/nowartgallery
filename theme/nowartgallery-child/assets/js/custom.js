/**
 * Now Art Gallery Child - Custom JavaScript
 *
 * Custom JavaScript for enhanced interactions
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		
		// Enhanced image gallery interactions
		if ($('.woocommerce-product-gallery').length) {
			// Add smooth scroll to gallery thumbnails
			$('.woocommerce-product-gallery__image').on('click', function() {
				$('html, body').animate({
					scrollTop: $('.woocommerce-product-gallery').offset().top - 20
				}, 300);
			});
		}

		// Copy to clipboard functionality for any copy buttons
		$('.copy-to-clipboard').on('click', function(e) {
			e.preventDefault();
			var text = $(this).data('copy') || $(this).text();
			
			// Create temporary textarea
			var $temp = $('<textarea>');
			$('body').append($temp);
			$temp.val(text).select();
			
			try {
				document.execCommand('copy');
				// Show feedback
				var $feedback = $('<span class="copy-feedback">Copied!</span>');
				$(this).append($feedback);
				setTimeout(function() {
					$feedback.fadeOut(function() {
						$(this).remove();
					});
				}, 2000);
			} catch (err) {
				console.error('Failed to copy text:', err);
			}
			
			$temp.remove();
		});

		// Lazy load images (if not already handled by theme)
		if ('loading' in HTMLImageElement.prototype) {
			var images = document.querySelectorAll('img[data-src]');
			images.forEach(function(img) {
				img.src = img.dataset.src;
			});
		}

	});

})(jQuery);
