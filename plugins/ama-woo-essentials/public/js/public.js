jQuery(document).ready(function($) {

    // Check if the sessionStorage flag is set on page load to display the sidepanel
    if (sessionStorage.getItem('showCartSidePanel') === 'true') {
        // Show the side panel
        showCartSidePanel();
    }

    // Attach an event listener to the 'added_to_cart' event
    $(document.body).on('wc_fragments_refreshed added_to_cart', function(event, fragments, cart_hash, $button) {
        // When an item is added to the cart, call the function to update the cart count
        updateCartCount();
    });


    // Just for TESTINGGGGGGGGGGGGGG
    jQuery(document.body).on('wc_fragments_refreshed', function(){
        console.log("wc_fragments_refreshed - TRIGGERED");
    });

    jQuery(document.body).on('updated_checkout', function(){
        console.log("updated_checkout - TRIGGERED");
    });

    jQuery(document.body).on('added_to_cart', function(){
        console.log("added_to_cart - TRIGGERED");
    });

    jQuery(document.body).on('removed_from_cart', function(){
        console.log("removed_from_cart - TRIGGERED");
    });

    jQuery(document.body).on('updated_cart_totals', function(){
        console.log("updated_cart_totals - TRIGGERED");
    });

    // Define the function to update the cart count
    function updateCartCount() {
        // Send an AJAX POST request
        $.ajax({
            // Specify the type of request
            type: 'POST',
            // Specify the URL to which the request will be sent
            url: ajax_object.ajax_url,
            // Specify the data to be sent along with the request
            data: {
                action: 'update_cart_count' // The action to be performed on the server
            },
            // Define what to do when the request is successful
            success: function(response) {
                // Check if the response contains the updated cart count and update the DOM
                if (response.success && response.data) {
                    $('.cart-count').html(response.data.cart_count); // Update the cart count
                    $('.cart-amount').html(response.data.cart_total); // Update the cart total
                } else {
                    console.error('Failed to update cart count: Invalid response', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to update cart count:', error);
            }
        });
    }

    // Show the side panel and overlay when the cart icon is clicked
    $('#cart-icon').on('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        showCartSidePanel();
    });

    // Show the side panel and overlay when an item is added to the cart
    $(document.body).on('wc_fragments_refreshed added_to_cart ', function(event, fragments, cart_hash, $button) {
        // Set a flag in sessionStorage to indicate that an item was added to the cart
        sessionStorage.setItem('showCartSidePanel', 'true');
        showCartSidePanel();
    });

    // Hide the side panel and overlay when the close button or overlay is clicked
    $('.close-panel, #cart-side-panel-overlay').on('click', function() {
        $('#cart-side-panel').removeClass('show');
        $('#cart-side-panel-overlay').removeClass('show');
    });

    // Function to show the cart side panel and load cart items
    function showCartSidePanel() {
        $('#cart-side-panel').addClass('show');
        $('#cart-side-panel-overlay').addClass('show');
        loadCartItems();
        // Clear the show side panel flag
        sessionStorage.removeItem('showCartSidePanel');
    }
    
    // Function to load cart items
    function loadCartItems() {
        // Show loading spinner
        $('.loader').show();
        $('.cart-items').hide();

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'get_cart_contents' // The action to be performed on the server
            },
            success: function(response) {
                if (response.success && response.data) {
                    var cartItems = response.data.cart_items;
                    var cartItemsHtml = '';

                    $.each(cartItems, function(index, item) {
                        cartItemsHtml += '<li class="cart-item">';
                        cartItemsHtml += '<span class="item-name">' + item.name + '</span>';
                        cartItemsHtml += '<span class="item-quantity">' + item.quantity + '</span>';
                        cartItemsHtml += '<span class="item-price">' + item.price + '</span>';
                        cartItemsHtml += '</li>';
                    });

                    $('.cart-items').html(cartItemsHtml);
                } else {
                    console.error('Failed to load cart items: Invalid response', response);
                }

                // Hide loading spinner and show cart items
                $('.loader').hide();
                $('.cart-items').show();
            },
            error: function(xhr, status, error) {
                console.error('Failed to load cart items:', error);

                // Hide loading spinner even on error
                $('.loader').hide();
                $('.cart-items').show();
            }
        });
    }

    $(document.body).on('added_to_cart', function() {
        // Trigger the WooCommerce event to open the mini cart panel
        if (typeof wc_cart_fragments_params !== 'undefined') {
            $(document.body).trigger('wc_fragments_refreshed');
        }
        
        // You can add additional code here to ensure the panel is displayed if needed
    });


});

