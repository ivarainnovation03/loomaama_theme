/**
 * Main Theme JavaScript
 *
 * @package Loomaama
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Initialize AOS FIRST before anything else
        initAOS();

        // Initialize all sliders
        initSliders();

        // Initialize header scroll effect
        initHeaderScroll();

        // Initialize product quick view (if applicable)
        initQuickView();

        // Re-initialize AOS after Swiper loads (critical for carousels)
        if (typeof Swiper !== 'undefined') {
            // Watch for Swiper initialization
            setTimeout(function() {
                refreshAOS();
            }, 500);
        }

        // Listen for AJAX complete events
        $(document).ajaxComplete(function() {
            refreshAOS();
        });

        // Listen for images loaded
        $(window).on('load', function() {
            refreshAOS();
        });

       
    });

    /**
     * Initialize AOS (Animate on Scroll)
     */
    function initAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                once: false,           // Allow re-animation (important for dynamic content)
                offset: 50,            // Trigger slightly earlier
                easing: 'ease-out-cubic',
                delay: 0,
                disable: false,
                startEvent: 'DOMContentLoaded',
                initClassName: 'aos-init',
                animatedClassName: 'aos-animate',
                useClassNames: false,
                disableMutationObserver: false,
                throttleDelay: 99,
                debounceDelay: 50
            });
            
            console.log('AOS initialized successfully');
        } else {
            console.warn('AOS library not loaded');
        }
    }


/**
 * Force AOS refresh on dynamic content with reset
 */
window.forceAOSRefresh = function(container) {
    if (typeof AOS === 'undefined') return;
    
    // If container specified, target only that container
    var targetElements = container ? 
        $(container).find('[data-aos]') : 
        $('[data-aos]');
    
    // Reset AOS classes on target elements
    targetElements.each(function() {
        $(this).removeClass('aos-init aos-animate');
    });
    
    // Refresh AOS
    AOS.refresh();
    
    // Manually trigger animation for visible elements
    setTimeout(function() {
        targetElements.each(function() {
            var rect = this.getBoundingClientRect();
            var windowHeight = window.innerHeight;
            
            if (rect.top < windowHeight - 50) {
                $(this).addClass('aos-animate');
            }
        });
    }, 50);
};

// Override the existing refreshAOS to be more aggressive
window.refreshAOS = function() {
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
        console.log('AOS refreshed from main.js');
        
        // Also manually trigger visible elements
        setTimeout(function() {
            $('[data-aos]:not(.aos-animate)').each(function() {
                var rect = this.getBoundingClientRect();
                if (rect.top < window.innerHeight - 50) {
                    $(this).addClass('aos-animate');
                }
            });
        }, 100);
    }
};

    /**
     * Initialize all Swiper sliders
     */
    function initSliders() {
        // Featured slider
        if ($('.featured-slider').length) {
            var featuredSlider = new Swiper('.featured-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                speed: 800,
                navigation: {
                    nextEl: '.btn-featured-next',
                    prevEl: '.btn-featured-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1400: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                on: {
                    init: function() {
                        refreshAOS();
                    },
                    slideChange: function() {
                        refreshAOS();
                    },
                    resize: function() {
                        refreshAOS();
                    }
                }
            });
        }
        
        // Seasonal slider
        if ($('.seasonal-slider').length) {
            var seasonalSlider = new Swiper('.seasonal-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                speed: 800,
                navigation: {
                    nextEl: '.btn-arrival-next',
                    prevEl: '.btn-arrival-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    1400: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
                on: {
                    init: function() {
                        refreshAOS();
                    },
                    slideChange: function() {
                        refreshAOS();
                    }
                }
            });
        }
        
        // Arrival slider
        if ($('.arrival-slider').length) {
            var arrivalSlider = new Swiper('.arrival-slider', {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: true,
                speed: 800,
                navigation: {
                    nextEl: '.btn-arrival-next',
                    prevEl: '.btn-arrival-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                    1400: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                on: {
                    init: function() {
                        refreshAOS();
                    }
                }
            });
        }
         // Artisan slider
        if ($('.artisan-slider').length) {
            var artisanSlider = new Swiper('.artisan-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                speed: 800,
                grabCursor: true,
                navigation: {
                    nextEl: '.btn-artisan-next',
                    prevEl: '.btn-artisan-prev',
                },
                pagination: {
                    el: '.artisan-slider .swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    1400: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
                on: {
                    init: function() {
                        refreshAOS();
                    }
                }
            });
        }
        // Related slider
        if ($('.related-slider').length) {
            var relatedSlider = new Swiper('.related-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                speed: 800,
                navigation: {
                    nextEl: '.btn-related-next',
                    prevEl: '.btn-related-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                    1400: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                on: {
                    init: function() {
                        refreshAOS();
                    }
                }
            });
        }
    }

    /**
     * Initialize header scroll effect
     */
    function initHeaderScroll() {
        var header = $('.main-header');
        var scrollThreshold = 50;

        function updateHeaderBackground() {
            if ($(window).scrollTop() > scrollThreshold) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }
        }

        updateHeaderBackground();
        $(window).on('scroll', updateHeaderBackground);
    }

    /**
     * Initialize product quick view (placeholder)
     */
    function initQuickView() {
        // Quick view functionality can be added here
    }

    /**
     * Load more products function
     */
    window.loadMoreProducts = function(button, category) {
        var $button = $(button);
        var page = parseInt($button.data('page')) + 1;
        var max = $button.data('max');

        $.ajax({
            url: loomaama_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'loomaama_load_more',
                page: page,
                category: category,
                nonce: loomaama_ajax.nonce
            },
            beforeSend: function() {
                $button.text(loomaama_i18n.loading || 'Loading...');
            },
            success: function(response) {
                if (response.success) {
                    $('.product-masonry').append(response.data.html);
                    $button.data('page', page);
                    
                    // Refresh AOS after adding new content
                    refreshAOS();

                    if (page >= max) {
                        $button.remove();
                    } else {
                        $button.text(loomaama_i18n.load_more || 'Load More →');
                    }
                }
            }
        });
    };

    // Force AOS refresh on window resize
    $(window).on('resize', function() {
        setTimeout(function() {
            refreshAOS();
        }, 200);
    });

    // Force AOS refresh on scroll (debounced)
    var scrollTimeout;
    $(window).on('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            refreshAOS();
        }, 100);
    });

    // For parallax jarallax
    if (typeof jarallax !== 'undefined') {
        jarallax(document.querySelectorAll(".jarallax"));
        jarallax(document.querySelectorAll(".jarallax-keep-img"), {
            keepImg: true,
        });
    }

    jQuery(window).trigger('resize').trigger('scroll');

    document.addEventListener("DOMContentLoaded", function () {

    // Create button
    const scrollBtn = document.createElement("button");
    scrollBtn.innerHTML = "↑";
    scrollBtn.id = "scrollTopBtn";
    document.body.appendChild(scrollBtn);

    // Show/hide on scroll
    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            scrollBtn.style.opacity = "1";
            scrollBtn.style.visibility = "visible";
        } else {
            scrollBtn.style.opacity = "0";
            scrollBtn.style.visibility = "hidden";
        }
    });

    // Scroll to top
    scrollBtn.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

    //counter animation
    if ($('.counter').length > 0) {
        $('.counter').jsCounter();
    }

});

})(jQuery);