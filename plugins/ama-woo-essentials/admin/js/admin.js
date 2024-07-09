jQuery(document).ready(function($) {

    var selected_settings = miniCartOptions.selected_settings;

    // Function to update preview based on form inputs
    function updatePreview() {

        var textColor = $('#text_color').val() || selected_settings.text_color;
        var hoverTextColor = $('#hover_text_color').val() || selected_settings.hover_text_color;
		var enableHoverBackgroundColor = $('#enable_hover_background_color').is(':checked') ? '1' : '0';
        var hoverBackgroundColor = $('#hover_background_color').val() || selected_settings.hover_background_color;
        var hoverTextDecoration = $('#hover_text_decoration').val() || selected_settings.hover_text_decoration;
        var hoverOpacity = $('#hover_opacity').val() || selected_settings.hover_opacity;
        var hoverTextDecorationStyle = $('#hover_text_decoration_style').val() || selected_settings.hover_text_decoration_style;
        var paddingTop = $('#padding_top').val() || selected_settings.padding_top;
        var hover_border_style = $('#hover_border_style').val() || selected_settings.hover_border_style;
        var hover_border_color = $('#hover_border_color').val() || selected_settings.hover_border_color;
        var hover_border_width = $('#hover_border_width').val() || selected_settings.hover_border_width;

        $('#mini-cart-preview').css({
            'color': textColor,
            'background-color': 'transparent',
            'padding-top': paddingTop + 'px',
        });

        $('#mini-cart-preview').hover(
            function() {
                $(this).css({
                    'color': hoverTextColor,
                    'background-color': enableHoverBackgroundColor === '1' ? hoverBackgroundColor : 'transparent',
                    'text-decoration': hoverTextDecoration,
                    'opacity': hoverOpacity,
                    'text-decoration-style': hoverTextDecorationStyle,
					'border-style': hover_border_style,
					'border-color': hover_border_color,
					'border-width': hover_border_width + 'px'

                });
            },
            function() {
                $(this).css({
                    'color': textColor,
                    'background-color': 'transparent',
                    'text-decoration': 'none',
                    'opacity': '1',
					'border-style': 'none'
                });
            }
        );

        $('#mini-cart-preview').css({
            'text-decoration-style': hoverTextDecorationStyle
        });
    }

    // Function to initialize form fields with stored option values
    function initializeFormFields() {
        
        $('#text_color').val(selected_settings.text_color);
        $('#hover_text_color').val(selected_settings.hover_text_color);
		$('#enable_hover_background_color').prop('checked', selected_settings.enable_hover_background_color === '1');
        $('#hover_background_color').val(selected_settings.hover_background_color);
        $('#hover_text_decoration').val(selected_settings.hover_text_decoration);
        $('#hover_opacity').val(selected_settings.hover_opacity);
        $('#hover_text_decoration_style').val(selected_settings.hover_text_decoration_style);
        $('#padding_top').val(selected_settings.padding_top);
        $('#hover_border_style').val(selected_settings.hover_border_style);
        $('#hover_border_color').val(selected_settings.hover_border_color);
        $('#hover_border_width').val(selected_settings.hover_border_width);
		
		// Use a sliding effect for the initial state
		if (selected_settings.enable_mini_cart_on_menu === '1') {
			$('#mini_cart_container').show();
		} else {
			$('#mini_cart_container').hide();
		}

		if (selected_settings.enable_hover_background_color === '1') {
			$('#hover_background_color_container').show();
		} else {
			$('#hover_background_color_container').hide();
		}

		if (selected_settings.hover_text_decoration === 'none') {
            $('#hover_text_decoration_style_container').hide();
        } else {
            $('#hover_text_decoration_style_container').show();
        }

		if (selected_settings.hover_text_decoration === 'none') {
            $('#hover_border_container').hide();
        } else {
            $('#hover_border_container').show();
        }
    }

    // Initial call to initialize form fields and update preview on page load
    initializeFormFields();
    updatePreview();

    // Bind input change events to update preview dynamically
    $('#text_color, #hover_text_color, #hover_background_color, #hover_text_decoration, #hover_opacity, #hover_text_decoration_style, #padding_top, #hover_border_style, #hover_border_color, #hover_border_width' ).on('input', updatePreview);

	//Bind the checkbox change event to update the preview and toggle the field visibility:
	$('#enable_mini_cart_on_menu').on('change', function() {
		toggleMiniCartFields();
	});	

	//Bind the checkbox change event to update the preview and toggle the field visibility:
	$('#enable_hover_background_color').on('change', function() {
		toggleHoverBackgroundColorField();
		updatePreview();
	});	

	// Bind the select change event to toggle hover text decoration style visibility
	$('#hover_text_decoration').on('change', function() {
		toggleHoverTextDecorationStyleField();
		updatePreview();
	});

	// Bind the select change event to toggle hover border details visibility
	$('#hover_border_style').on('change', function() {
		toggleHoverBorderDetailsFields();
		updatePreview();
	});

    // Restore Defaults Button
	$('#restore_defaults').on('click', function(e) {
		e.preventDefault();
	
		// Reset form fields to default values
        $.each(miniCartOptions.default_settings, function(key, value) {
            if (key === 'enable_hover_background_color') {
                $('#enable_hover_background_color').prop('checked', value === '1');
                toggleHoverBackgroundColorField();
            } else {
                $('#' + key).val(value);
            }
        });
		
		// Update options to defaults
		miniCartOptions.selected_settings = $.extend(true, {}, miniCartOptions.default_settings);
	
		// Update preview and toggle field visibility
		updatePreview();
		toggleHoverBackgroundColorField();
		toggleHoverTextDecorationStyleField();
		toggleHoverBorderDetailsFields();
	});

    function toggleMiniCartFields() {
		if ($('#enable_mini_cart_on_menu').is(':checked')) {
			$('#mini_cart_container').slideDown();
		} else {
			$('#mini_cart_container').slideUp();
		}
	}	

	function toggleHoverBackgroundColorField() {
		if ($('#enable_hover_background_color').is(':checked')) {
			$('#hover_background_color_container').slideDown();
		} else {
			$('#hover_background_color_container').slideUp();
		}
	}	

	function toggleHoverTextDecorationStyleField() {
        if ($('#hover_text_decoration').val() === 'none') {
            $('#hover_text_decoration_style_container').slideUp();
        } else {
            $('#hover_text_decoration_style_container').slideDown();
        }
    }

	function toggleHoverBorderDetailsFields() {
        if ($('#hover_border_style').val() === 'none') {
            $('#hover_border_container').slideUp();
        } else {
            $('#hover_border_container').slideDown();
        }
    }
});
