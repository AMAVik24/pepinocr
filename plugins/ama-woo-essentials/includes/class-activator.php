<?php

namespace AmaWooEssentials\Includes;

class Activator {

	/**
	 * Saves the default settings for the mini cart css parameters as soon as the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        $cartSettings = array(
            'ama_woo_essentials_default_text_color' => '#000000',
            'ama_woo_essentials_default_hover_text_color' => '#000000',
            'ama_woo_essentials_default_enable_hover_background_color' => '0',
            'ama_woo_essentials_default_hover_background_color' => '#ffffff',
            'ama_woo_essentials_default_hover_text_decoration' => 'none',
            'ama_woo_essentials_default_hover_opacity' => '1',
            'ama_woo_essentials_default_hover_text_decoration_style' => 'solid',
            'ama_woo_essentials_default_padding_top' => '0',
            'ama_woo_essentials_default_hover_border_style' => 'none',
            'ama_woo_essentials_default_hover_border_color' => '#000000',
            'ama_woo_essentials_default_hover_border_width' => '1',
            'ama_woo_essentials_selected_text_color' => '',
            'ama_woo_essentials_selected_hover_text_color' => '',
            'ama_woo_essentials_selected_enable_hover_background_color' => '',
            'ama_woo_essentials_selected_hover_background_color' => '',
            'ama_woo_essentials_selected_hover_text_decoration' => '',
            'ama_woo_essentials_selected_hover_opacity' => '',
            'ama_woo_essentials_selected_hover_text_decoration_style' => '',
            'ama_woo_essentials_selected_padding_top' => '',
            'ama_woo_essentials_selected_hover_border_style' => '',
            'ama_woo_essentials_selected_hover_border_color' => '',
            'ama_woo_essentials_selected_hover_border_width' => '',
        );
    
        foreach ($cartSettings as $key => $value) {
            add_option($key, $value);
        }

	}

}