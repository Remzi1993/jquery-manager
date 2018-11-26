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
 * Plugin Name:		jQuery Manager for WordPress
 * Plugin URI:		https://github.com/Remzi1993/wp-jquery-manager
 * Description:		Manage jQuery and jQuery Migrate on a WordPress website, select a specific jQuery and/or jQuery Migrate version. The ultimate jQuery debugging tool for WordPress. This plugin is a open source project, made possible by your contribution (code). Development is done on GitHub.
 * Version:			1.5.1
 * Author:			Remzi Cavdar
 * Author URI:		https://twitter.com/remzicavdar
 * License:			GPLv3
 * License URI:		https://www.gnu.org/licenses/gpl-3.0
 * Text Domain:		wp-jquery-manager
 * Domain Path:		/languages
 */

// If this file is called directly, abort.
defined('ABSPATH') or exit();

// Config - CONSTANTS
// http://php.net/manual/en/dir.constants.php & https://www.quora.com/Should-class-constants-be-all-uppercase-in-PHP
define( 'WP_JQUERY_MANAGER_PLUGIN_DIR_PATH', str_replace( "\\", "/", plugin_dir_path(__FILE__) ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_SLUG', 'wp-jquery-manager-plugin-settings' );
define( 'WP_JQUERY_MANAGER_PLUGIN_ADMIN_URL', admin_url( 'tools.php?page=' . WP_JQUERY_MANAGER_PLUGIN_SLUG ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_TEXT_DOMAIN', 'wp-jquery-manager' );
define( 'WP_JQUERY_MANAGER_PLUGIN_SITE_URL', get_site_url() );
define( 'WP_JQUERY_MANAGER_PLUGIN_DOMAIN_NAME', $_SERVER['HTTP_HOST'] );

// jQuery versions, don't forget to update your files! .js and .min.js are automatically added accordingly at the end of the name/file.
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X', 'jquery-3.3.1' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X', 'jquery-2.2.4' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X', 'jquery-1.12.4' );

// jQuery Migrate versions, don't forget to update your files! .js and .min.js are automatically added accordingly at the end of the name/file.
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X', 'jquery-migrate-3.0.1' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X', 'jquery-migrate-1.4.1' );

// Settings
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_SETTINGS', (array) get_option( 'wp_jquery_manager_plugin_jquery_settings' ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_SETTINGS', (array) get_option( 'wp_jquery_manager_plugin_jquery_migrate_settings' ) );

// Plugin updater GitHub Repository
require WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'inc/plugin-update-checker/plugin-update-checker.php';
$wp_jquery_manager_plugin_updater = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Remzi1993/wp-jquery-manager',
	__FILE__,
	'wp_jquery_manager_plugin'
);
// Updater options
$wp_jquery_manager_plugin_updater->getVcsApi()->enableReleaseAssets();
$wp_jquery_manager_plugin_updater->setBranch('master');

// Activation / upgrade process
require WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'upgrade-process.php';

// Include weDevs Settings API wrapper class
require WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'inc/settings-api.php';

// Add settings link to our plugin section on the plugin list page
function wp_jquery_manager_plugin_add_action_links ( $links ) {
	$mylinks = array(
		'<a href="' . WP_JQUERY_MANAGER_PLUGIN_ADMIN_URL . '">Settings</a>',
	);

	return array_merge( $links, $mylinks );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wp_jquery_manager_plugin_add_action_links' );


// Our plugin class
if ( !class_exists( 'wp_jquery_manager_plugin' ) ) {

	class wp_jquery_manager_plugin {
		private $settings_api;
		public $text_domain;
		public $title;
		public $capability;
		public $slug;

	    public function __construct() {
			// Using the weDevs WordPress Settings API wrapper class
	        $this->settings_api = new WeDevs_Settings_API;

			// Plugin text domain
			$this->text_domain = WP_JQUERY_MANAGER_PLUGIN_TEXT_DOMAIN;

			// Plugin menu title and settings
			$this->title = 'jQuery Manager';
			$this->capability = 'administrator';
			$this->slug = WP_JQUERY_MANAGER_PLUGIN_SLUG;

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
			$page_title = $this->title;
			$menu_title = $this->title;
			$capability = $this->capability;
			$menu_slug = $this->slug;
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
			$jquery_3x = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X;
			$jquery_2x = WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X;
			$jquery_1x = WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X;

			$jquery_migrate_3x = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X;
			$jquery_migrate_1x = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X;

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
	                    'default' => $jquery_3x . '.min.js',
	                    'options' => array(
							'jquery-3.3.0.min.js'	=> 'jquery-3.3.0.min.js', // Debug / test old version
	                        $jquery_3x . '.min.js'	=> $jquery_3x . '.min.js',
							$jquery_3x . '.js'		=> $jquery_3x . '.js',
							$jquery_2x . '.min.js'	=> $jquery_2x . '.min.js',
							$jquery_2x . '.js'		=> $jquery_2x . '.js',
							$jquery_1x . '.min.js'	=> $jquery_1x . '.min.js',
	                        $jquery_1x . '.js'		=> $jquery_1x . '.js'
	                    )
	                ),
					array(
	                    'name'    => 'jquery_execution',
	                    'label'   => __( 'jQuery execution', $this->text_domain ),
	                    'desc'    => __( 'Experimental! Some plugins and/or themes may not support this', $this->text_domain ),
	                    'type'    => 'radio',
						'default' => 'default',
	                    'options' => array(
							'default'	=> 'Default / Normal',
							'async'		=> 'Async',
	                        'defer'		=> 'Defer'
	                    )
	                ),
					array(
	                    'name'		=> 'debug_mode',
	                    'label'		=> __( 'Debug mode', $this->text_domain ),
	                    'desc'		=> __( 'On / Off', $this->text_domain ),
						'default'	=> 'off',
	                    'type'		=> 'checkbox'
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
						'default' => $jquery_migrate_3x . '.js',
						'options' => array(
							'jquery-migrate-3.0.0.min.js'	=> 'jquery-migrate-3.0.0.min.js', // Debug / test old version
							$jquery_migrate_3x . '.js'		=> $jquery_migrate_3x . '.js',
							$jquery_migrate_3x . '.min.js'	=> $jquery_migrate_3x . '.min.js',
							$jquery_migrate_1x . '.js'		=> $jquery_migrate_1x . '.js',
							$jquery_migrate_1x . '.min.js'	=> $jquery_migrate_1x . '.min.js'
						)
					),
					array(
	                    'name'    => 'jquery_migrate_head_body',
	                    'label'   => __( 'jQuery Migrate code', $this->text_domain ),
	                    'desc'    => __( 'Choose where to put jQuery Migrate in the <strong>&lt;head&gt;</strong> or at the end of the <strong>&lt;body&gt;</strong> tag, just before it closes (default is in the head)', $this->text_domain ),
	                    'type'    => 'radio',
						'default' => 'head',
	                    'options' => array(
							'head'	=> '&lt;head&gt;',
							'body'	=> '&lt;body&gt;'
	                    )
	                ),
					array(
						'name'		=> 'jquery_migrate_execution',
						'label'		=> __( 'jQuery Migrate execution', $this->text_domain ),
						'desc'		=> __( 'Experimental! Some plugins and/or themes do not support this', $this->text_domain ),
						'type'		=> 'radio',
						'default'	=> 'default',
						'options'	=> array(
							'default'	=> 'Default / Normal',
							'async'		=> 'Async',
							'defer'		=> 'Defer'
						)
					)
	            ) // End jQuery Migrate settings
	        ); // End $settings_fields

	        return $settings_fields;
	    }

	    public function plugin_settings_page() {
			$jquery_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_SETTINGS;

			// For debugging
			if ( isset( $jquery_options['debug_mode'] ) ) {
				if ( $jquery_options['debug_mode'] == 'on' ) {
					echo '<h1>Debug information</h1>';
					echo '<strong>Plugin directory:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . '<br>';
					echo '<strong>Plugin URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DIR_URL . '<br>';
					echo '<strong>Plugin admin URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_ADMIN_URL . '<br>';
					echo '<strong>Domain name:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DOMAIN_NAME . '<br>';
					echo '<strong>URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_SITE_URL . '<br>';
				}
			}

			// Plugin settings
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

	$jquery_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_SETTINGS;
	$jquery_migrate_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_SETTINGS;

	$margin_j = "margin: 40px 0 0 20px;";
	$margin_jm = "margin: 140px 0 0 20px;";
	$style_j = "position: fixed; top: 0; left: 0; z-index: 9999; color: black; background: gray; " . $margin_j .  " padding: 20px;";
	$style_jm = "position: fixed; top: 0; left: 0; z-index: 9999; color: black; background: gray; " . $margin_jm .  " padding: 20px;";

	// jQuery
	if ( $wp_admin || $wp_customizer ) {
		// echo 'We are in the WP Admin or in the WP Customizer';
		return;
	}
	elseif ( !isset( $jquery_options['jquery'] ) ) { // Default setting
		// Deregister WP core jQuery, see https://github.com/Remzi1993/wp-jquery-manager/issues/2
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );

		// Default jQuery version
		$jquery_version = 'assets/js/' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js';

		// Register jQuery in the head
		wp_register_script( 'jquery-core', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_version, array(), null, false );

		/**
		 * Register jquery using jquery-core as a dependency, so other scripts could use the jquery handle
		 * see https://wordpress.stackexchange.com/questions/283828/wp-register-script-multiple-identifiers
		 * We first register the script and afther that we enqueue it, see why:
		 * https://wordpress.stackexchange.com/questions/82490/when-should-i-use-wp-register-script-with-wp-enqueue-script-vs-just-wp-enque
		 * https://stackoverflow.com/questions/39653993/what-is-diffrence-between-wp-enqueue-script-and-wp-register-script
		 */
		wp_register_script( 'jquery', false, array( 'jquery-core' ), null, false );
		wp_enqueue_script( 'jquery' );

		// When debugging is enabled
		if ( isset( $jquery_options['debug_mode'] ) ) {
			if ( $jquery_options['debug_mode'] == 'on' ) {
				echo '<h1 style="'. $style_j .'">jQuery version: ' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '</h1>';
			}
		}
	}
	elseif ( $jquery_options['jquery'] == 'on' ) {
		// Deregister WP core jQuery
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );

		// Get jQuery version
		$jquery_version = 'assets/js/' . $jquery_options['jquery_version'];

		// Register jQuery in the head
		wp_register_script( 'jquery-core', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_version, array(), null, false );
		wp_register_script( 'jquery', false, array( 'jquery-core' ), null, false ); // Alias
		wp_enqueue_script( 'jquery' );

		// When debugging is enabled
		if ( isset( $jquery_options['debug_mode'] ) ) {
			if ( $jquery_options['debug_mode'] == 'on' ) {
				echo '<h1 style="'. $style_j .'">jQuery version: ' . $jquery_options['jquery_version'] . '</h1>';
			}
		}
	}
	elseif ( $jquery_options['jquery'] == 'off' ) {
		// Deregister WP core jQuery
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );

	} // End jQuery

	// jQuery Migrate
	if ( $wp_admin || $wp_customizer ) {
		// echo 'We are in the WP Admin or in the WP Customizer';
		return;
	}
	elseif ( !isset( $jquery_migrate_options['jquery_migrate'] ) ) { // Default setting
		// Deregister WP core jQuery Migrate
		wp_deregister_script( 'jquery-migrate' );

		// Default jQuery Migrate version
		$jquery_migrate_version = 'assets/js/' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.js';

		// Register and than enqueue jQuery Migrate in the head
		wp_register_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate_version, array(), null, false );
		wp_enqueue_script( 'jquery-migrate' );

		// When debugging is enabled
		if ( isset( $jquery_options['debug_mode'] ) ) {
			if ( $jquery_options['debug_mode'] == 'on' ) {
				echo '<h1 style="'. $style_jm .'">jQuery Migrate version: ' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '</h1>';
			}
		}
	}
	elseif ( $jquery_migrate_options['jquery_migrate'] == 'on' ) {
		// Deregister WP core jQuery Migrate
		wp_deregister_script( 'jquery-migrate' );

		// Get jQuery Migrate version
		$jquery_migrate_version = 'assets/js/' . $jquery_migrate_options['jquery_migrate_version'];

		// Setting head or body
		if ( $jquery_migrate_options['jquery_migrate_head_body'] == 'head' ) {
			// Enqueue jQuery Migrate in the head
			wp_register_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate_version, array(), null, false );
			wp_enqueue_script( 'jquery-migrate' );
		}
		else {
			// Enqueue jQuery Migrate before </body>
			wp_register_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate_version, array(), null, true );
			wp_enqueue_script( 'jquery-migrate' );
		}

		// When debugging is enabled
		if ( isset( $jquery_options['debug_mode'] ) ) {
			if ( $jquery_options['debug_mode'] == 'on' ) {
				echo '<h1 style="'. $style_jm .'">jQuery Migrate version: ' . $jquery_migrate_options['jquery_migrate_version'] . '</h1>';
			}
		}
	}
	elseif ( $jquery_migrate_options['jquery_migrate'] == 'off' ) {
		// Deregister WP core jQuery Migrate
		wp_deregister_script( 'jquery-migrate' );

	} // End jQuery Migrate

} // End function wp_jquery_manager_plugin_front_end_scripts


// Back end specific
// Load only on tools.php?page=wp-jquery-manager-plugin-settings (plugin settings)
function wp_jquery_manager_plugin_admin_scripts($hook) {
	if( $hook != 'tools_page_' . WP_JQUERY_MANAGER_PLUGIN_SLUG ) {
		return;
	}

	// CSS
	wp_enqueue_style( 'wp-jquery-manager-plugin-admin', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . 'assets/css/admin.css', array(), null );
}

// Register styles and scripts
add_action( 'wp_enqueue_scripts', 'wp_jquery_manager_plugin_front_end_scripts' );
add_action( 'admin_enqueue_scripts', 'wp_jquery_manager_plugin_admin_scripts' );


// Defer jQuery and/or jQuery Migrate
function wp_jquery_manager_plugin_add_attribute( $tag, $handle ) {
	if ( is_admin() || is_customize_preview() ) {
		return $tag;
	}
	elseif ( !isset( $jquery_options['jquery_execution'] ) && !isset( $jquery_migrate_options['jquery_migrate_execution'] ) ) { // No settings, default. Exit and stop wasting time :)
		return $tag;
	}

	$jquery_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_SETTINGS;
	$jquery_migrate_options = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_SETTINGS;

	if ( isset( $jquery_options['jquery_execution'] ) ) {
		switch ( $jquery_options['jquery_execution'] ) {
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

	if ( isset( $jquery_migrate_options['jquery_migrate_execution'] ) ) {
		switch ( $jquery_migrate_options['jquery_migrate_execution'] ) {
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
	delete_option( 'external_updates-wp_jquery_manager_plugin' );
}
