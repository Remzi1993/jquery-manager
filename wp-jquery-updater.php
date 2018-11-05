<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       WP jQuery Updater
 * Plugin URI:        https://github.com/Remzi1993/wp-jquery-updater
 * Description:       With this plugin you're able to manage jQuery.
 * Version:           1.0.5
 * Author:            Remzi Cavdar
 * Author URI:        https://www.linkedin.com/in/remzicavdar/
 * License:           GPL 3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 */

// If this file is called directly, abort.
defined('ABSPATH') or exit();

// Config - CONSTANTS
// http://php.net/manual/en/dir.constants.php & https://www.quora.com/Should-class-constants-be-all-uppercase-in-PHP
define( 'WPJ_UPDATER_PLUGIN_DIR_PATH', str_replace( "\\", "/", plugin_dir_path(__FILE__) ) );
define( 'WPJ_UPDATER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WPJ_UPDATER_PLUGIN_SITE_URL', get_site_url() );
define( 'WPJ_UPDATER_PLUGIN_DOMAIN_NAME', $_SERVER['HTTP_HOST'] );
// jQuery version
define( 'WPJ_UPDATER_PLUGIN_JQUERY', plugins_url('/assets/js/jquery-3.3.1.min.js',__FILE__ ) );
// jQuery Migrate version
define( 'WPJ_UPDATER_PLUGIN_JQUERY_MIGRATE', plugins_url('/assets/js/jquery-migrate-3.0.1.js',__FILE__ ) );

// Plugin updater GitHub Repository
require WPJ_UPDATER_PLUGIN_DIR_PATH . 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Remzi1993/wp-jquery-updater',
	__FILE__,
	'wpj_updater_plugin'
);

$myUpdateChecker->getVcsApi()->enableReleaseAssets();

// Activation
function wpj_updater_plugin_activation(){
    do_action( 'wpj_updater_plugin_default_options' );
}
register_activation_hook( __FILE__, 'wpj_updater_plugin_activation' );

// Set default values here (wp database options)
function wpj_updater_plugin_default_values(){
	// jQuery and jQuery Migrate default url settings upon activation
	add_option( 'wpj_updater_jquery_url', WPJ_UPDATER_PLUGIN_JQUERY );
	add_option( 'wpj_updater_jquery_migrate_url', WPJ_UPDATER_PLUGIN_JQUERY_MIGRATE );
}
add_action( 'wpj_updater_plugin_default_options', 'wpj_updater_plugin_default_values' );

/*
 * Create WP jQuery Updater menu item in WordPress admin backend in the Tools section
 * Remzi Cavdar
 */
function wpj_updater_plugin_menu() {
    add_management_page( 'WP jQuery Updater', 'WP jQuery Updater', 'administrator', 'wpj-updater-plugin-settings', 'wpj_updater_plugin_settings' );
    add_action( 'admin_init', 'wpj_updater_plugin_register_settings' );
}
add_action( 'admin_menu', 'wpj_updater_plugin_menu' );

function wpj_updater_plugin_register_settings() {
	// jQuery and jQuery Migrate settings
    register_setting( 'wpj_updater_plugin_settings', 'wpj_updater_jquery_url', 'wpj_updater_plugin_validation' );
    register_setting( 'wpj_updater_plugin_settings', 'wpj_updater_jquery_migrate_url', 'wpj_updater_plugin_validation' );
}

// Sanitize it
function wpj_updater_plugin_validation($input) {
	return sanitize_text_field($input);
}

class wpj_updater_plugin {
	public $jquery;
	public $jquery_migrate;

	public function __construct() {
        // jQuery
		$this->jquery = get_option( 'wpj_updater_jquery_url', WPJ_UPDATER_PLUGIN_JQUERY );
        // jQuery Migrate
		$this->jquery_migrate = get_option( 'wpj_updater_jquery_migrate_url', WPJ_UPDATER_PLUGIN_JQUERY_MIGRATE );
	}

	public function debug() {
        echo '<h1>Debug information:</h1>';
        echo '<strong>Domain name:</strong> ' . WPJ_UPDATER_PLUGIN_DOMAIN_NAME . '<br>';
        echo '<strong>URL:</strong> ' . WPJ_UPDATER_PLUGIN_SITE_URL . '<br>';
        echo '<strong>Plugin directory path:</strong> ' . WPJ_UPDATER_PLUGIN_DIR_PATH . '<br>';
        echo '<strong>Plugin URL directory path:</strong> ' . WPJ_UPDATER_PLUGIN_DIR_URL . '<br>';
        echo '<h2>Plugin settings:</h2>';
        echo '<strong>jQuery URL:</strong> ' . $this->jquery . '<br>';
        echo '<strong>jQuery Migrate URL:</strong> ' . $this->jquery_migrate . '<br>';
	}
}

// Front-end not excuted in the wp admin and the wp customizer (for compatibility reasons)
// See: https://core.trac.wordpress.org/ticket/45130 and https://core.trac.wordpress.org/ticket/37110
function wpj_updater_plugin_front_end_scripts() {
    $wp_admin = is_admin();
    $wp_customizer = is_customize_preview();

    if ( $wp_admin || $wp_customizer ) {
        // echo 'We are in the WP Admin or in the WP Customizer';
    }
    else {
        $plugin = new wpj_updater_plugin;

        // Deregister WP core jQuery
        wp_deregister_script('jquery');
        // Enqueue jQuery in the head
        wp_enqueue_script( 'jquery', $plugin->jquery, array(), null, false );

        // Deregister WP core jQuery Migrate
        wp_deregister_script('jquery-migrate');
        // Enqueue jQuery Migrate in the footer
        wp_enqueue_script( 'jquery-migrate', $plugin->jquery_migrate, array('jquery'), null, true );
    }
}

// Back end specific
// Load only on tools.php?page=wpj-updater-plugin-settings (plugin settings)
function wpj_updater_plugin_admin_scripts($hook) {

	if( $hook != 'tools_page_wpj-updater-plugin-settings' ) {
		return;
	}

	// CSS
	wp_enqueue_style( 'wpj-updater-admin', plugins_url('/assets/css/admin.css', __FILE__), array(), null );

}

// Register styles and scripts
add_action( 'wp_enqueue_scripts', 'wpj_updater_plugin_front_end_scripts' );
add_action( 'admin_enqueue_scripts', 'wpj_updater_plugin_admin_scripts' );

// Defer jQuery Migrate
function wpj_updater_plugin_add_defer_attribute( $tag, $handle ) {
        if ( is_admin() || is_customize_preview() ) {
                return $tag;
        }

        // Add script handles to the array below
        $scripts_to_defer = array(
            'jquery-migrate',
        );

        foreach ( $scripts_to_defer as $defer_script ) {
            if ( $defer_script === $handle ) {
                return str_replace( "src", "defer src", $tag );
            }
        }

        return $tag;
}
add_filter( 'script_loader_tag', 'wpj_updater_plugin_add_defer_attribute', 10, 2 );

// Admin page
require WPJ_UPDATER_PLUGIN_DIR_PATH . 'admin.php';

// Deactivation
register_deactivation_hook( __FILE__, 'wpj_updater_plugin_deactivation' );

function wpj_updater_plugin_deactivation() {
	delete_option( 'wpj_updater_jquery_url' );
	delete_option( 'wpj_updater_jquery_migrate_url' );
}
