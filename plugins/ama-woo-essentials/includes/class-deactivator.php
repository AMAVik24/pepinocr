<?php

namespace AmaWooEssentials\Includes;

class Deactivator {

	/**
	 * Deletes the default settings for the mini cart when deactivating the plugin.
     * 
	 * @since    1.0.0
	 */
	public static function deactivate() {
        $options = array(
            'ama_woo_essentials_default_enable_hover_background_color',
            'ama_woo_essentials_default_text_color',
            'ama_woo_essentials_default_hover_text_color',
            'ama_woo_essentials_default_enable_hover_background_color',
            'ama_woo_essentials_default_hover_background_color',
            'ama_woo_essentials_default_hover_text_decoration',
            'ama_woo_essentials_default_hover_opacity',
            'ama_woo_essentials_default_hover_text_decoration_style',
            'ama_woo_essentials_default_padding_top',
            'ama_woo_essentials_default_hover_border_style',
            'ama_woo_essentials_default_hover_border_color',
            'ama_woo_essentials_default_hover_border_width'
        );
    
        // Loop through the options and delete each one
        foreach ($options as $option) {
            delete_option($option);
        }
	}

}