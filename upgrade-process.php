<?php
// Check if the user has changed the settings, has old versions of jQuery and/or jQuery Migrate settings and han excute the upgrade process
function wp_jquery_manager_plugin_check_version() {
	// Get entire arrays - jQuery settings and jQuery Migrate settings
	$jquery_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_SETTINGS;
	$jquery_migrate_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_SETTINGS;

	if ( isset( $jquery_options['jquery_version'] ) ) {

		switch ( !$jquery_options['jquery_version'] ) {
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X . '.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X . '.min.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X . '.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X . '.min.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
		} // End switch case

	} // End if statement

	if ( isset( $jquery_migrate_options['jquery_migrate_version'] ) ) {

		switch ( !$jquery_migrate_options['jquery_migrate_version'] ) {
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X . '.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
			case WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X . '.min.js':
				wp_jquery_manager_plugin_activation_upgrade_process();
				break;
		} // End switch case

	} // End if statement

} // End function
add_action('plugins_loaded', 'wp_jquery_manager_plugin_check_version');

// Activation / upgrade process
function wp_jquery_manager_plugin_activation_upgrade_process() {
	// Get entire arrays - jQuery settings and jQuery Migrate settings
	$jquery_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_SETTINGS;
	$jquery_migrate_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_SETTINGS;

	if ( isset( $jquery_options['jquery_version'] ) ) {
		// Upgrade previous jQuery settings
		switch ( $jquery_options['jquery_version'] ) { // Check if user has old jQuery settings
			case 'jquery-3.3.0.js':
				// Alter the options array appropriately
				$jquery_options['jquery_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.js';
				break;
			case 'jquery-3.3.0.min.js':
				// Alter the options array appropriately
				$jquery_options['jquery_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js';
				break;
		} // End switch case
		// Update entire array
		update_option('wp_jquery_manager_plugin_jquery_settings', $jquery_options);
	}

	if ( isset( $jquery_migrate_options['jquery_migrate_version'] ) ) {
		// Upgrade previous jQuery Migrate settings
		switch ( $jquery_migrate_options['jquery_migrate_version'] ) { // Check if user has old jQuery Migrate settings
			case 'jquery-migrate-3.0.0.js':
				// Alter the options array appropriately
				$jquery_migrate_options['jquery_migrate_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.js';
				break;
			case 'jquery-migrate-3.0.0.min.js':
				// Alter the options array appropriately
				$jquery_migrate_options['jquery_migrate_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js';
				break;
		} // End switch case
		// Update entire array
		update_option('wp_jquery_manager_plugin_jquery_migrate_settings', $jquery_migrate_options);
	}
}
register_activation_hook(__FILE__, 'wp_jquery_manager_plugin_activation_upgrade_process');
