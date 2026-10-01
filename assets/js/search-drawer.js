jQuery(document).ready(function($) {
    console.log('Search drawer script loaded');
    
    // Check if woocommerce_search_params is defined
    if (typeof woocommerce_search_params === 'undefined') {
        console.error('woocommerce_search_params is not defined');
        return;
    }
    
    console.log('Search params:', woocommerce_search_params);
    
    let searchTimeout;
    
    // Live search functionality
    $('.search-drawer input[name="s"]').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        console.log('Search term:', searchTerm);
        
        if (searchTerm.length >= 2) {
            searchTimeout = setTimeout(function() {
                performLiveSearch(searchTerm);
            }, 500);
        } else if (searchTerm.length === 0) {
            $('.drawer_search_reasult').empty();
        }
    });
    
    // Clear results when drawer closes
    $('#search-drawer').on('transitionend', function() {
        if (!$(this).hasClass('active')) {
            $('.drawer_search_reasult').empty();
            $('.search-drawer input[name="s"]').val('');
        }
    });
    
    // Perform live search
   function performLiveSearch(searchTerm) {
    console.log('Performing search for:', searchTerm);
    
    // Show loading state
    $('.drawer_search_reasult').html('<div class="search-loading">Loading...</div>');
    
    $.ajax({
        url: woocommerce_search_params.ajax_url,
        type: 'POST',
        data: {
            action: 'live_product_search',
            search_term: searchTerm,
            nonce: woocommerce_search_params.nonce
        },
        success: function(response) {
            if (response.success) {
                // 1. Handle Suggestions Logic
                let suggestionsHtml = '';
                if (response.data.suggestions && response.data.suggestions.length > 0) {
                    response.data.suggestions.forEach(function(word) {
                        suggestionsHtml += `<button type="button" class="list-group-item list-group-item-action py-2 suggestion-item">${word}</button>`;
                    });
                    $('#search-suggestions-container').html(suggestionsHtml).show();
                } else {
                    $('#search-suggestions-container').hide();
                }

                // 2. Handle Product Results Logic
                updateSearchResults(response.data);
               
                  refreshAOS();
                
            } else {
                $('.drawer_search_reasult').html('<div class="search-error">Error loading results</div>');
            }
        },
        error: function(xhr, status, error) {
            $('.drawer_search_reasult').html('<div class="search-error">Error loading results. Please try again.</div>');
        }
    });
}
 
/** * MOVE THESE OUTSIDE so they only run once when the page loads
 **/

// Handle clicking a suggestion word
$(document).on('click', '.suggestion-item', function(e) {
    e.preventDefault();
    let chosenWord = $(this).text();
    $('#search-drawer-input').val(chosenWord); 
    $('#search-suggestions-container').hide(); 
    
    // Trigger search again with the full word
    performLiveSearch(chosenWord); 
    // OR just submit the form:
    // $('.search-drawer-form').submit(); 
});

// Hide suggestions if user clicks outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('.search-input-wrapper').length) {
        $('#search-suggestions-container').hide();
    }
});
    // Update search results in drawer
    function updateSearchResults(data) {

        console.log(data);
        let html = '<div class="search-results-wrapper">';
        
        if (data.products && data.products.length > 0) {
            html += '<div class="search-results-list">';
            
            $.each(data.products, function(index, product) {
                html += `
                    <div class="search-result-item" data-aos="fade-up" data-aos-delay="${index * 100}">
                        <a href="${product.url}" class="search-result-link">
                            <div class="search-result-image-wrapper">
                                ${product.image ? `<img src="${product.image}" alt="${escapeHtml(product.title)}" class="search-result-image">` : '<div class="search-result-placeholder"></div>'}
                            </div>
                            <div class="search-result-info">
                                <h6 class="search-result-title">${escapeHtml(product.title)}</h6>
                                <span class="search-result-price">${product.price}</span>
                            </div>
                        </a>
                    </div>
                `;
            });
            
            html += '</div>';
            
            // Add "More Results" button if there are more products
            if (data.total > data.limit) {
                html += `
                    <div class="search-more-results">
                        <button class="btn btn-outline-dark w-100 more-results-btn" data-search-term="${escapeHtml(data.search_term)}">
                            ${woocommerce_search_params.more_results_text} (${data.total} ${woocommerce_search_params.products_text})
                        </button>
                    </div>
                `;
            }
        } else {
            html += `
                <div class="search-no-results">
                    <span class="no-results-icon">🔍</span>
                    <p>${woocommerce_search_params.no_results_text}</p>
                </div>
            `;
        }
        
        html += '</div>';
        $('.drawer_search_reasult').html(html);
        
        // Handle more results button click
        $('.more-results-btn').on('click', function(e) {
            e.preventDefault();
            const searchTerm = $(this).data('search-term');
            // Use the current page URL with search parameters
        
            const searchUrl = woocommerce_search_params.archive_url + '?s=' + encodeURIComponent(searchTerm) + '&post_type=product';
            
            console.log('Redirecting to:', searchUrl);
            window.location.href = searchUrl;
        // window.location.href = searchUrl;
         });
           refreshAOS();
         
    }
    
    // Helper function to escape HTML
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // Handle more results button click

});