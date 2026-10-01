/**
 * Professional Hover Image Handler - Overlay Method with Touch Support
 * Works perfectly with Lightspeed Cache and mobile devices
 * 
 * @package Loomaama
 * @version 5.1.0
 */

(function($) {
    'use strict';

    class HoverImageHandler {
        constructor() {
            this.touchTimeout = null;
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
            
            // Handle touch end globally to clean up
            $(document).on('touchend', () => {
                if (this.touchTimeout) {
                    clearTimeout(this.touchTimeout);
                }
            });
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
            
            const productContainer = document.querySelector('.product-masonry, .main-product-masonry, .related-slider .swiper-wrapper, .products');
            if (productContainer) {
                observer.observe(productContainer, { childList: true, subtree: true });
            }
        }

        isTouchDevice() {
            return (('ontouchstart' in window) ||
                    (navigator.maxTouchPoints > 0) ||
                    (navigator.msMaxTouchPoints > 0));
        }

        initHoverOverlays() {
            const overlays = document.querySelectorAll('.hover-overlay-img');
            
            if (!overlays.length) return;
            
            console.log(`Initializing ${overlays.length} hover overlays (Touch: ${this.isTouchDevice()})`);
            
            overlays.forEach(overlay => {
                // Skip if already initialized
                if (overlay.dataset.hoverInitialized === 'true') return;
                
                const $overlay = $(overlay);
                const $parent = $overlay.closest('.lux-img');
                
                // Ensure parent has position relative and overflow hidden
                if ($parent.css('position') !== 'relative') {
                    $parent.css('position', 'relative');
                }
                if ($parent.css('overflow') !== 'hidden') {
                    $parent.css('overflow', 'hidden');
                }
                
                // Preload the hover image
                const preloadImg = new Image();
                preloadImg.src = $overlay.attr('src');
                
                // Store state
                let isHovered = false;
                let touchActive = false;
                
                // Clear existing handlers
                $parent.off('mouseenter.hover mouseleave.hover touchstart.hover touchmove.hover touchend.hover touchcancel.hover');
                
                // ==============================================
                // DESKTOP HOVER EVENTS
                // ==============================================
                $parent.on('mouseenter.hover', function() {
                    if (touchActive) return; // Don't interfere with touch
                    isHovered = true;
                    $parent.addClass('hover-active');
                    self.showOverlay($parent, $overlay);
                });
                
                $parent.on('mouseleave.hover', function() {
                    if (touchActive) return;
                    isHovered = false;
                    $parent.removeClass('hover-active');
                    self.hideOverlay($parent, $overlay);
                });
                
                // ==============================================
                // TOUCH EVENTS FOR MOBILE
                // ==============================================
                const self = this;
                
                $parent.on('touchstart.hover', function(e) {
                    e.stopPropagation();
                    
                    // If already active, this tap might be for navigation
                    if (touchActive) {
                        // Allow the click to go through for navigation
                        return true;
                    }
                    
                    touchActive = true;
                    
                    // Show overlay on touch
                    self.showOverlay($parent, $overlay);
                    
                    // Prevent default to avoid immediate click
                    e.preventDefault();
                    
                    // Set timeout to hide overlay after ~2 seconds if no further interaction
                    if (self.touchTimeout) {
                        clearTimeout(self.touchTimeout);
                    }
                    
                    self.touchTimeout = setTimeout(() => {
                        if (touchActive) {
                            self.hideOverlay($parent, $overlay);
                            touchActive = false;
                        }
                    }, 2000);
                });
                
                $parent.on('touchmove.hover', function(e) {
                    // If user is swiping/scrolling, hide the overlay
                    if (touchActive) {
                        self.hideOverlay($parent, $overlay);
                        touchActive = false;
                        if (self.touchTimeout) {
                            clearTimeout(self.touchTimeout);
                        }
                    }
                });
                
                $parent.on('touchend.hover', function(e) {
                    e.stopPropagation();
                    
                    if (self.touchTimeout) {
                        clearTimeout(self.touchTimeout);
                    }
                    
                    // If overlay is showing and user taps again, navigate to product
                    if (touchActive) {
                        // User tapped again - navigate to product page
                        const $link = $parent.find('a');
                        if ($link.length) {
                            window.location.href = $link.attr('href');
                        }
                    }
                    
                    // Hide overlay after a short delay
                    setTimeout(() => {
                        self.hideOverlay($parent, $overlay);
                        touchActive = false;
                    }, 300);
                });
                
                $parent.on('touchcancel.hover', function() {
                    if (self.touchTimeout) {
                        clearTimeout(self.touchTimeout);
                    }
                    self.hideOverlay($parent, $overlay);
                    touchActive = false;
                });
                
                // Mark as initialized
                overlay.dataset.hoverInitialized = 'true';
            });
        }

        showOverlay($parent, $overlay) {
            // Clear any existing transition timeouts
            if ($parent.data('hover-timeout')) {
                clearTimeout($parent.data('hover-timeout'));
            }
            
            // Show overlay
            $overlay.css({
                'opacity': '1',
                'visibility': 'visible',
                'transform': 'scale(1.06)'
            });
            
            // Scale the main image
            const $mainImg = $parent.find('img:first-child');
            if ($mainImg.length) {
                $mainImg.css('transform', 'scale(1.06)');
            }
            
            // Add a class for potential additional styling
            $parent.addClass('hover-active');
        }

        hideOverlay($parent, $overlay) {
            // Set timeout to ensure smooth transition
            const timeoutId = setTimeout(() => {
                $overlay.css({
                    'opacity': '0',
                    'visibility': 'visible',
                    'transform': 'scale(1)'
                });
                
                // Scale back the main image
                const $mainImg = $parent.find('img:first-child');
                if ($mainImg.length) {
                    $mainImg.css('transform', 'scale(1)');
                }
                
                $parent.removeClass('hover-active');
            }, 50);
            
            $parent.data('hover-timeout', timeoutId);
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