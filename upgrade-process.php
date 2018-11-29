<?php
// Activation process
function wp_jquery_manager_plugin_activation() {
	if ( $GLOBALS['wp_jquery_manager_plugin_jquery_settings'] && empty( $GLOBALS['wp_jquery_manager_plugin_jquery_migrate_settings'] ) ) {
		return;
	}
	else {
		echo 'bulsit';
		exit;
		wp_jquery_manager_plugin_upgrade_process();
	}
}
register_activation_hook(__FILE__, 'wp_jquery_manager_plugin_activation');

// Check if the user has changed the settings, has old versions of jQuery and/or jQuery Migrate settings and han excute the upgrade process
function wp_jquery_manager_plugin_upgrade_process() {
	if ( !$GLOBALS['wp_jquery_manager_plugin_jquery_settings'] && !$GLOBALS['wp_jquery_manager_plugin_jquery_migrate_settings'] ) {
		echo 'true';
		exit;

		return;
	}

	$ArrayFour = array();
	$jquery_options = $GLOBALS['wp_jquery_manager_plugin_jquery_settings'];

	if ( empty($jquery_options) ) {
		echo 'true fgd';
		exit;
	}

	// echo $GLOBALS['wp_jquery_manager_plugin_jquery_settings'];
	// exit;

	// Get entire arrays - jQuery settings and jQuery Migrate settings
	$jquery_options = $GLOBALS['wp_jquery_manager_plugin_jquery_settings'];
	$jquery_migrate_options = $GLOBALS['wp_jquery_manager_plugin_jquery_migrate_settings'];

	if ( empty( $jquery_options['jquery_version'] ) && empty( $jquery_migrate_options['jquery_migrate_version'] ) ) {
		return;
	}

	// jQuery
	if ( !empty( $jquery_options['jquery_version'] ) ) {
		// Upgrade previous jQuery settings
		switch ( $jquery_options['jquery_version'] ) { // Check if user has old jQuery settings
			case 'jquery-3.3.0.js':
				// Alter the options array appropriately
				$jquery_options['jquery_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.js';
				update_option('wp_jquery_manager_plugin_jquery_settings', $jquery_options); // Update entire array
				break;
			case 'jquery-3.3.0.min.js':
				// Alter the options array appropriately
				$jquery_options['jquery_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js';
				update_option('wp_jquery_manager_plugin_jquery_settings', $jquery_options); // Update entire array
				break;
		} // End switch case
	}

	// jQuery Migrate
	if ( !empty( $jquery_migrate_options['jquery_migrate_version'] ) ) {
		// Upgrade previous jQuery Migrate settings
		switch ( $jquery_migrate_options['jquery_migrate_version'] ) { // Check if user has old jQuery Migrate settings
			case 'jquery-migrate-3.0.0.js':
				// Alter the options array appropriately
				$jquery_migrate_options['jquery_migrate_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.js';
				update_option('wp_jquery_manager_plugin_jquery_migrate_settings', $jquery_migrate_options); // Update entire array
				break;
			case 'jquery-migrate-3.0.0.min.js':
				// Alter the options array appropriately
				$jquery_migrate_options['jquery_migrate_version'] = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js';
				update_option('wp_jquery_manager_plugin_jquery_migrate_settings', $jquery_migrate_options); // Update entire array
				break;
		} // End switch case
	}

} // End function
add_action('plugins_loaded', 'wp_jquery_manager_plugin_upgrade_process');
