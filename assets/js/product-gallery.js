/**
 * Product Gallery JavaScript
 *
 * @package Loomaama
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // Initialize PhotoSwipe lightbox
        if (typeof PhotoSwipeLightbox !== 'undefined' && $('.product-gallery').length) {
            const lightbox = new PhotoSwipeLightbox({
                gallery: '.product-gallery',
                children: 'a',
                pswpModule: () => import('https://unpkg.com/photoswipe@5.4.4/dist/photoswipe.esm.js')
            });
            lightbox.init();
        }

        // Handle gallery indicators on mobile
        initGalleryIndicators();

        // Handle variation swatches
        initVariationSwatches();

    });

    /**
     * Initialize gallery indicators for mobile
     */
    function initGalleryIndicators() {
        var gallery = document.querySelector('.product-gallery');
        var dots = document.querySelectorAll('.gallery-indicators .dot');

        if (gallery && dots.length) {
            gallery.addEventListener('scroll', function() {
                var scrollPosition = gallery.scrollLeft;
                var itemWidth = gallery.clientWidth;
                var index = Math.round(scrollPosition / itemWidth);

                dots.forEach(function(dot, i) {
                    dot.classList.toggle('active', i === index);
                });
            });

            dots.forEach(function(dot, index) {
                dot.addEventListener('click', function() {
                    var itemWidth = gallery.clientWidth;
                    gallery.scrollTo({
                        left: index * itemWidth,
                        behavior: 'smooth'
                    });
                });
            });
        }
    }

    /**
     * Initialize variation swatches for variable products
     */
    function initVariationSwatches() {
        if (!$('.variations_form').length) return;

        // Color swatches
        $('.swatch').on('click', function() {
            var $this = $(this);
            var color = $this.data('color');
            var $select = $this.closest('.color-section').find('select').first();

            $('.swatch').removeClass('active');
            $this.addClass('active');

            if ($select.length) {
                $select.val(color).trigger('change');
            }

            $('.selected-color').text($this.attr('title'));
        });

        // Size options
        $('.size-option:not(.disabled)').on('click', function() {
            var $this = $(this);
            var size = $this.data('size');
            var $select = $this.closest('.size-section').find('select').first();

            $('.size-option').removeClass('active');
            $this.addClass('active');

            if ($select.length) {
                $select.val(size).trigger('change');
            }
        });

        // Handle variation change events
        $('.variations_form').on('woocommerce_variation_has_changed', function() {
            updateSelectedSwatches($(this));
        });

        $('.variations_form').on('found_variation', function(event, variation) {
            updateStockStatus($(this), variation);
        });
    }

    /**
     * Update selected swatches based on variation
     */
    function updateSelectedSwatches($form) {
        var selectedAttributes = {};

        $form.find('.variations select').each(function() {
            var attrName = $(this).data('attribute_name') || $(this).attr('name');
            var attrValue = $(this).val();
            selectedAttributes[attrName] = attrValue;
        });

        // Update color swatch
        if (selectedAttributes.attribute_pa_color) {
            var colorValue = selectedAttributes.attribute_pa_color;
            $('.swatch[data-color="' + colorValue + '"]').addClass('active').siblings().removeClass('active');
        }

        // Update size options
        if (selectedAttributes.attribute_pa_size) {
            var sizeValue = selectedAttributes.attribute_pa_size;
            $('.size-option[data-size="' + sizeValue + '"]').addClass('active').siblings().removeClass('active');
        }
    }

    /**
     * Update stock status based on variation
     */
    function updateStockStatus($form, variation) {
        if (variation.is_in_stock) {
            $('.stock').removeClass('out-of-stock').addClass('in-stock').text(loomaama_i18n.in_stock || 'In stock');
        } else {
            $('.stock').removeClass('in-stock').addClass('out-of-stock').text(loomaama_i18n.out_of_stock || 'Out of stock');
        }
    }

})(jQuery);