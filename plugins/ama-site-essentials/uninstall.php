<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('ama_site_essentials_custom_home_meta_description');
delete_option('ama_site_essentials_gtm_header_tag');
delete_option('ama_site_essentials_gtm_body_tag');
delete_option('ama_site_essentials_smtp_username');
delete_option('ama_site_essentials_smtp_password');
delete_option('ama_site_essentials_smtp_sender');
delete_option('ama_site_essentials_smtp_name');
delete_option('ama_site_essentials_smtp_server');
delete_option('ama_site_essentials_smtp_port');
delete_option('ama_site_essentials_smtp_secure');
delete_option('ama_site_essentials_test');