jQuery(function($) {

	/**
	 * All of the code for your admin-facing JavaScript 
	 */

	// Hides/Shows the fields for physical businesses.

	// Get the initial selected value
	var initialValue = $("input[name='ama_woo_essentials_business_type']:checked").val();

    // Show/hide rows based on the initial value
    toggleRows(initialValue);

    // Attach an event listener to the radio buttons
    $("input[name='ama_woo_essentials_business_type']").change(function () {
        // Get the selected value
        var selectedValue = $(this).val();

        // Show/hide rows based on the selected value
        toggleRows(selectedValue);
    });

	function toggleRows(value) {
		// Get the container div
		var containerDiv = $("#business_information");
	
		// Check if the current state is different from the target state
		if ((containerDiv.is(':visible') && (value !== 'physical' && value !== 'online_and_physical')) ||
			(!containerDiv.is(':visible') && (value === 'physical' || value === 'online_and_physical'))) {
			// Hide or show the entire container without animation
			containerDiv.stop().slideToggle();
		}
		// If the current state is the same as the target state, do nothing
	}	
});
