/**
 * Professional Hover Image Handler - Overlay Method
 * Works perfectly with Lightspeed Cache
 * 
 * @package Loomaama
 * @version 5.0.0
 */

(function($) {
    'use strict';

    class HoverImageHandler {
        constructor() {
            this.init();
        }

        init() {
            this.initHoverOverlays();
            this.initMutationObserver();
            
            // AJAX content loading events
            $(document).on('hover_images_refresh', () => this.initHoverOverlays());
            $(document.body).on('added_to_cart updated_wc_div wc_fragments_refreshed', () => this.initHoverOverlays());
            $(document).on('filters_applied', () => this.initHoverOverlays());
            
            // Force refresh after page load
            setTimeout(() => this.initHoverOverlays(), 100);
            setTimeout(() => this.initHoverOverlays(), 500);
            setTimeout(() => this.initHoverOverlays(), 1500);
        }

        initMutationObserver() {
            const observer = new MutationObserver((mutations) => {
                let shouldRefresh = false;
                
                mutations.forEach((mutation) => {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === 1) {
                                if ($(node).find('.hover-overlay-img').length || 
                                    $(node).is('.hover-overlay-img')) {
                                    shouldRefresh = true;
                                }
                            }
                        });
                    }
                });
                
                if (shouldRefresh) {
                    setTimeout(() => this.initHoverOverlays(), 50);
                }
            });
            
            const productContainer = document.querySelector('.product-masonry, .main-product-masonry, .related-slider .swiper-wrapper');
            if (productContainer) {
                observer.observe(productContainer, { childList: true, subtree: true });
            }
        }

        initHoverOverlays() {
            const overlays = document.querySelectorAll('.hover-overlay-img');
            
            if (!overlays.length) return;
            
            console.log(`Initializing ${overlays.length} hover overlays`);
            
            overlays.forEach(overlay => {
                // Skip if already initialized
                if (overlay.dataset.hoverInitialized === 'true') return;
                
                const $overlay = $(overlay);
                const $parent = $overlay.closest('.lux-img');
                const $link = $parent.find('a');
                
                // Ensure parent has position relative
                if ($parent.css('position') !== 'relative') {
                    $parent.css('position', 'relative');
                }
                
                // Preload the hover image
                const preloadImg = new Image();
                preloadImg.src = $overlay.attr('src');
                
                // Store reference
                $overlay.data('parent', $parent);
                
                // Clear existing handlers
                $parent.off('mouseenter.hover mouseleave.hover');
                
                // Hover handlers on parent container
                $parent.on('mouseenter.hover', function() {
                    $overlay.css({
                        'opacity': '1',
                        'visibility': 'visible'
                    });
                    // Scale the overlay image along with the main image
                    $parent.find('img:first-child').css('transform', 'scale(1.06)');
                    $overlay.css('transform', 'scale(1.06)');
                });
                
                $parent.on('mouseleave.hover', function() {
                    $overlay.css({
                        'opacity': '0',
                        'visibility': 'visible'
                    });
                    $parent.find('img:first-child').css('transform', 'scale(1)');
                    $overlay.css('transform', 'scale(1)');
                });
                
                // Mark as initialized
                overlay.dataset.hoverInitialized = 'true';
            });
        }

        refresh() {
            document.querySelectorAll('.hover-overlay-img').forEach(overlay => {
                overlay.dataset.hoverInitialized = 'false';
            });
            this.initHoverOverlays();
        }
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        window.loomaamaHoverHandler = new HoverImageHandler();
    });

})(jQuery);