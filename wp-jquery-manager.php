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
 * Plugin Name:       jQuery Manager for WordPress
 * Plugin URI:        https://github.com/Remzi1993/wp-jquery-manager
 * Description:       Manage jQuery and jQuery Migrate on a WordPress website, select a specific jQuery and/or jQuery Migrate version. The ultimate jQuery debugging tool for WordPress
 * Version:           1.2.1
 * Author:            Remzi Cavdar
 * Author URI:        https://www.linkedin.com/in/remzicavdar/
 * License:           GPL 3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 */

// If this file is called directly, abort.
defined('ABSPATH') or exit();

// Config - CONSTANTS
// http://php.net/manual/en/dir.constants.php & https://www.quora.com/Should-class-constants-be-all-uppercase-in-PHP
define( 'WP_JQUERY_MANAGER_PLUGIN_DIR_PATH', str_replace( "\\", "/", plugin_dir_path(__FILE__) ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_SITE_URL', get_site_url() );
define( 'WP_JQUERY_MANAGER_PLUGIN_DOMAIN_NAME', $_SERVER['HTTP_HOST'] );

// Plugin updater GitHub Repository
require WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'inc/plugin-update-checker/plugin-update-checker.php';
$wp_jquery_manager_plugin_updater = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Remzi1993/wp-jquery-manager',
	__FILE__,
	'wp_jquery_manager_plugin'
);

$wp_jquery_manager_plugin_updater->getVcsApi()->enableReleaseAssets();

// Include weDevs Settings API wrapper class
require WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'inc/class.settings-api.php';

if ( !class_exists( 'wp_jquery_manager_plugin' ) ) {

	class wp_jquery_manager_plugin {
		private $settings_api;
		public $text_domain;

	    public function __construct() {
	        $this->settings_api = new WeDevs_Settings_API;
			$this->text_domain = 'wp_jquery_manager_plugin';

	        add_action( 'admin_init', array( $this, 'admin_init' ) );
	        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	    }

	    public function admin_init() {
	        // set the settings
	        $this->settings_api->set_sections( $this->get_settings_sections() );
	        $this->settings_api->set_fields( $this->get_settings_fields() );

	        // initialize settings
	        $this->settings_api->admin_init();
	    }

	    public function admin_menu() {
			$page_title = 'jQuery Manager';
			$menu_title = 'jQuery Manager';
			$capability = 'administrator';
			$menu_slug = 'wp-jquery-manager-plugin-settings';
			$function = array( $this, 'plugin_settings_page' );

			add_management_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	    }

	    public function get_settings_sections() {
	        $sections = array(
	            array(
	                'id'    => 'wp_jquery_manager_plugin_jquery_settings',
	                'title' => __( 'jQuery settings', $this->text_domain )
	            ),
	            array(
	                'id'    => 'wp_jquery_manager_plugin_jquery_migrate_settings',
	                'title' => __( 'jQuery Migrate settings', $this->text_domain )
	            )
	        );
	        return $sections;
	    }

	    /**
	     * Returns all the settings fields
	     *
	     * @return array settings fields
	     */
	    public function get_settings_fields() {
	        $settings_fields = array(
				// jQuery settings
	            'wp_jquery_manager_plugin_jquery_settings' => array(
					array(
	                    'name'		=> 'jquery',
	                    'label'		=> __( 'jQuery', $this->text_domain ),
	                    'desc'		=> __( 'On / Off', $this->text_domain ),
						'default'	=> 'on',
	                    'type'		=> 'checkbox'
	                ),
					array(
	                    'name'    => 'jquery_version',
	                    'label'   => __( 'jQuery version', $this->text_domain ),
	                    'desc'    => __( 'Select a particular jQuery version', $this->text_domain ),
	                    'type'    => 'select',
	                    'default' => 'jquery-3.3.1.min.js',
	                    'options' => array(
	                        'jquery-3.3.1.min.js'	=> 'jquery-3.3.1.min.js',
							'jquery-3.3.1.js'		=> 'jquery-3.3.1.js',
							'jquery-2.2.4.min.js'	=> 'jquery-2.2.4.min.js',
							'jquery-2.2.4.js'		=> 'jquery-2.2.4.js',
							'jquery-1.12.4.min.js'	=> 'jquery-1.12.4.min.js',
	                        'jquery-1.12.4.js'		=> 'jquery-1.12.4.js'
	                    )
	                ),
					array(
	                    'name'    => 'jquery_delay',
	                    'label'   => __( 'jQuery delay execution', $this->text_domain ),
	                    'desc'    => __( 'Experimental! Some plugins and/or themes do not support this', $this->text_domain ),
	                    'type'    => 'radio',
						'default' => 'off',
	                    'options' => array(
							'off'	=> 'Off',
							'async'	=> 'Async',
	                        'defer'	=> 'Defer'
	                    )
	                )
	            ), // End jQuery settings
				// jQuery Migrate settings
	            'wp_jquery_manager_plugin_jquery_migrate_settings' => array(
					array(
						'name'		=> 'jquery_migrate',
						'label'		=> __( 'jQuery Migrate', $this->text_domain ),
						'desc'		=> __( 'On / Off', $this->text_domain ),
						'default'	=> 'on',
						'type'		=> 'checkbox'
					),
					array(
						'name'    => 'jquery_migrate_version',
						'label'   => __( 'jQuery Migrate version', $this->text_domain ),
						'desc'    => __( 'Select a particular jQuery Migrate version', $this->text_domain ),
						'type'    => 'select',
						'default' => 'jquery-migrate-3.0.1.js',
						'options' => array(
							'jquery-migrate-3.0.1.js'		=> 'jquery-migrate-3.0.1.js',
							'jquery-migrate-3.0.1.min.js'	=> 'jquery-migrate-3.0.1.min.js',
							'jquery-migrate-1.4.1.js'		=> 'jquery-migrate-1.4.1.js',
							'jquery-migrate-1.4.1.min.js'	=> 'jquery-migrate-1.4.1.min.js'
						)
					),
					array(
	                    'name'    => 'jquery_migrate_head_body',
	                    'label'   => __( 'jQuery Migrate loading', $this->text_domain ),
	                    'desc'    => __( 'Choose how to load jQuery Migrate - &lt;head&gt; or before &lt;/body&gt; (default is in the head)', $this->text_domain ),
	                    'type'    => 'radio',
						'default' => 'head',
	                    'options' => array(
							'head'	=> 'Head',
							'body'	=> 'Body'
	                    )
	                ),
					array(
						'name'		=> 'jquery_migrate_delay',
						'label'		=> __( 'jQuery Migrate delay execution', $this->text_domain ),
						'desc'		=> __( 'Experimental! Some plugins and/or themes do not support this', $this->text_domain ),
						'type'		=> 'radio',
						'default'	=> 'off',
						'options'	=> array(
							'off'	=> 'Off',
							'async'	=> 'Async',
							'defer'	=> 'Defer'
						)
					)
	            ) // End jQuery Migrate settings
	        ); // End $settings_fields

	        return $settings_fields;
	    }

	    public function plugin_settings_page() {
			// Debugging
			// echo '<h1>Debug information</h1>';
			// echo '<strong>Plugin directory:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . '<br>';
			// echo '<strong>Plugin URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DIR_URL . '<br>';
			// echo '<strong>Domain name:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DOMAIN_NAME . '<br>';
			// echo '<strong>URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_SITE_URL . '<br>';

	        echo '<div class="wrap">';

	        $this->settings_api->show_navigation();
	        $this->settings_api->show_forms();

	        echo '</div>';
	    }

	    /**
	     * Get all the pages
	     *
	     * @return array page names with key value pairs
	     */
	    public function get_pages() {
	        $pages = get_pages();
	        $pages_options = array();
	        if ( $pages ) {
	            foreach ($pages as $page) {
	                $pages_options[$page->ID] = $page->post_title;
	            }
	        }

	        return $pages_options;
	    }

	} // End wp_jquery_manager_plugin class

	new wp_jquery_manager_plugin();
}

// Front-end not excuted in the wp admin and the wp customizer (for compatibility reasons)
// See: https://core.trac.wordpress.org/ticket/45130 and https://core.trac.wordpress.org/ticket/37110
function wp_jquery_manager_plugin_front_end_scripts() {
	$wp_admin = is_admin();
	$wp_customizer = is_customize_preview();

	$jquery_options = (array) get_option( 'wp_jquery_manager_plugin_jquery_settings' );
	$jquery_migrate_options = (array) get_option( 'wp_jquery_manager_plugin_jquery_migrate_settings' );

	// jQuery
	if ( $wp_admin || $wp_customizer ) {
		// echo 'We are in the WP Admin or in the WP Customizer';
		return;
	}
	elseif ( !isset( $jquery_options['jquery'] ) ) { // Default setting
		// Deregister WP core jQuery
		wp_deregister_script('jquery');

		// Enqueue jQuery in the head
		wp_enqueue_script( 'jquery', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . 'assets/js/jquery-3.3.1.min.js', array(), null, false );
	}
	elseif ( $jquery_options['jquery'] != 'off' ) {
		// Deregister WP core jQuery
		wp_deregister_script('jquery');

		$jquery_version = 'assets/js/' . $jquery_options['jquery_version'];

		// Enqueue jQuery in the head
		wp_enqueue_script( 'jquery', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_version, array(), null, false );
	}
	elseif ( $jquery_options['jquery'] == 'off' ) {
		// Deregister WP core jQuery
		wp_deregister_script('jquery');
	}

	// jQuery Migrate
	if ( $wp_admin || $wp_customizer ) {
		// echo 'We are in the WP Admin or in the WP Customizer';
	}
	elseif ( !isset( $jquery_migrate_options['jquery_migrate'] ) ) { // Default setting
		// Deregister WP core jQuery Migrate
		wp_deregister_script('jquery-migrate');

		// Enqueue jQuery Migrate in the body
		wp_enqueue_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . 'assets/js/jquery-migrate-3.0.1.js', array('jquery'), null, false );
	}
	elseif ( $jquery_migrate_options['jquery_migrate'] != 'off' ) {
		// Deregister WP core jQuery Migrate
		wp_deregister_script('jquery-migrate');

		$jquery_migrate_version = 'assets/js/' . $jquery_migrate_options['jquery_migrate_version'];

		if ( $jquery_migrate_options['jquery_migrate_head_body'] == 'body' ) {
			// Enqueue jQuery before </body>
			wp_enqueue_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate_version, array('jquery'), null, true );
		}
		else {
			// Enqueue jQuery in the head
			wp_enqueue_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate_version, array('jquery'), null, false );
		}
	} // End jQuery Migrate
	elseif ( $jquery_migrate_options['jquery_migrate'] == 'off' ) {
		// Deregister WP core jQuery
		wp_deregister_script('jquery-migrate');
	}

}

// Back end specific
// Load only on tools.php?page=wpj-updater-plugin-settings (plugin settings)
function wp_jquery_manager_plugin_admin_scripts($hook) {

	if( $hook != 'tools_page_wp-jquery-manager-plugin-settings' ) {
		return;
	}

	// CSS
	wp_enqueue_style( 'wp-jquery-manager-plugin-admin', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . 'assets/css/admin.css', array(), null );

}

// Register styles and scripts
add_action( 'wp_enqueue_scripts', 'wp_jquery_manager_plugin_front_end_scripts' );
add_action( 'admin_enqueue_scripts', 'wp_jquery_manager_plugin_admin_scripts' );


// Defer jQuery Migrate
function wp_jquery_manager_plugin_add_attribute( $tag, $handle ) {
	if ( is_admin() || is_customize_preview() ) {
		return $tag;
	}

	$jquery_options = (array) get_option( 'wp_jquery_manager_plugin_jquery_settings' );
	$jquery_migrate_options = (array) get_option( 'wp_jquery_manager_plugin_jquery_migrate_settings' );

	if ( isset( $jquery_options['jquery_delay'] ) ) {
		switch ( $jquery_options['jquery_delay'] ) {
			case 'async':
					if ( 'jquery' === $handle ) {
						return str_replace( "src", "async src", $tag );
					}
				break;
			case 'defer':
					if ( 'jquery' === $handle ) {
						return str_replace( "src", "defer src", $tag );
					}
				break;
		}
	}

	if ( isset( $jquery_migrate_options['jquery_migrate_delay'] ) ) {
		switch ( $jquery_migrate_options['jquery_migrate_delay'] ) {
			case 'async':
					if ( 'jquery-migrate' === $handle ) {
						return str_replace( "src", "async src", $tag );
					}
				break;
			case 'defer':
					if ( 'jquery-migrate' === $handle ) {
						return str_replace( "src", "defer src", $tag );
					}
				break;
		}
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'wp_jquery_manager_plugin_add_attribute', 10, 2 );


// Deactivation
register_deactivation_hook( __FILE__, 'wp_jquery_manager_plugin_deactivation' );

function wp_jquery_manager_plugin_deactivation() {
	delete_option( 'wp_jquery_manager_plugin_jquery_settings' );
	delete_option( 'wp_jquery_manager_plugin_jquery_migrate_settings' );
}
