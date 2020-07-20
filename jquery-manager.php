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
 * Plugin Name:     jQuery Manager for WordPress
 * Plugin URI:      https://github.com/Remzi1993/jquery-manager
 * Description:     Manage jQuery and jQuery Migrate, activate a specific jQuery and/or jQuery Migrate version. The ultimate jQuery debugging tool for WordPress. This plugin is an open source project, made possible by your contribution (code). Development is done on GitHub.
 * Version:         1.10.6
 * Author:          Remzi Cavdar
 * Author URI:      https://twitter.com/remzicavdar
 * License:			GPLv3
 * License URI:		https://www.gnu.org/licenses/gpl-3.0
 * Text Domain:		jquery-manager
 * Domain Path:		/languages
 */

// If this file is called directly, abort.
defined('ABSPATH') or exit();

if ( defined('WP_CLI') && WP_CLI ) {
     $_SERVER['HTTP_HOST'] = 'localhost';
}

// Config - CONSTANTS
// http://php.net/manual/en/dir.constants.php & https://www.quora.com/Should-class-constants-be-all-uppercase-in-PHP
define( 'WP_JQUERY_MANAGER_PLUGIN_DIR_PATH', str_replace( "\\", "/", plugin_dir_path(__FILE__) ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_SLUG', 'wp-jquery-manager-plugin-settings' );
define( 'WP_JQUERY_MANAGER_PLUGIN_ADMIN_URL', admin_url( 'tools.php?page=' . WP_JQUERY_MANAGER_PLUGIN_SLUG ) );
define( 'WP_JQUERY_MANAGER_PLUGIN_TEXT_DOMAIN', 'jquery-manager' );
define( 'WP_JQUERY_MANAGER_PLUGIN_SITE_URL', get_site_url() );
define( 'WP_JQUERY_MANAGER_PLUGIN_DOMAIN_NAME', $_SERVER['HTTP_HOST'] );

// jQuery versions, don't forget to update the files! .js and .min.js are automatically added accordingly at the end of the name/file.
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X', 'jquery-3.5.1' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X_SLIM', 'jquery-3.5.1.slim' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X', 'jquery-2.2.4' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X', 'jquery-1.12.4' );

// jQuery Migrate versions, don't forget to update your files! .js and .min.js are automatically added accordingly at the end of the name/file.
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X', 'jquery-migrate-3.3.0' );
define( 'WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X', 'jquery-migrate-1.4.1' );

// Settings
$wp_jquery_manager_plugin_jquery_settings = (array) get_option( 'wp_jquery_manager_plugin_jquery_settings' );
$wp_jquery_manager_plugin_jquery_migrate_settings = (array) get_option( 'wp_jquery_manager_plugin_jquery_migrate_settings' );

// Include weDevs Settings API wrapper class
require WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'inc/settings-api.php';

// All filters
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wp_jquery_manager_plugin_add_action_links' );
add_filter( 'autoptimize_filter_js_dontmove', array( 'wp_jquery_manager_plugin', 'autoptimize_support' ) );
add_filter( 'script_loader_tag', 'wp_jquery_manager_plugin_add_attribute', 10, 2 );

// All actions
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'wp_jquery_manager_plugin_admin_notice' );

// Add settings link to our plugin section on the plugin list page
function wp_jquery_manager_plugin_add_action_links ( $links ) {
	$plugin_links = array(
		'<a href="' . WP_JQUERY_MANAGER_PLUGIN_ADMIN_URL . '">Settings</a>',
	);

	return array_merge( $links, $plugin_links );
}

// Activation process
register_activation_hook( __FILE__, 'wp_jquery_manager_plugin_activation' );

function wp_jquery_manager_plugin_activation() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
    	exit;
    }

    global $wp_version;
	$php = '5.6';
	$wp  = '4.9';

	if ( version_compare( PHP_VERSION, $php, '<' ) || version_compare( $wp_version, $wp, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
		wp_die(
			'<p>' .
			sprintf(
				__( 'This plugin can not be activated because either your WordPress instalation has an outdated/unsuported PHP version or you are using an outdated/old WordPress version.<br><br>This plugin requires a minimum of <strong>PHP 5.6 or greater</strong> and <strong>WordPress 4.9 or greater</strong>.<br><br> Your install:<br><strong>PHP: ' . PHP_VERSION .  '</strong><br><strong>WordPress: ' . $wp_version . '</strong><br><br>You need to update either one of them or both, before you are able to activate and use this plugin.<br>- <a href="https://wordpress.org/support/update-php/" target="_blank" rel="noopener noreferrer">Learn more about updating PHP</a><br>- <a href="https://wordpress.org/support/article/updating-wordpress/" target="_blank" rel="noopener noreferrer">Learn more about updating WordPress</a>', 'wp_jquery_manager_plugin' ),
				$php
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'wp_jquery_manager_plugin' ) . '</a>'
		);
	}
}

// Initial admin notice for new users of this plugin
require  WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'vendor/collizo4sky/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
function wp_jquery_manager_plugin_admin_notice() {
    if ( ! PAnD::is_admin_notice_active( 'disable-done-notice-forever' ) ) {
		return;
	}

	?>
    	<div data-dismissible="disable-done-notice-forever" class="updated notice notice-success is-dismissible">
    		<p><?php _e( '<strong style="font-size: 21px;">Thank you for using jQuery Manager 👍</strong> <span style="margin-right: 10px;font-size: 18px;">This plugin is brand new, it could use some attention. Please leave a review 😉</span><a href="https://wordpress.org/support/plugin/jquery-manager/reviews/#new-post" class="button secondary" target="_blank" rel="noopener noreferrer">Add your review</a>', WP_JQUERY_MANAGER_PLUGIN_TEXT_DOMAIN ); ?></p>
    	</div>
	<?php
}

/**
 * Load plugin textdomain.
 * This feature is not stable, so it is commented out.
 */
// function wp_jquery_manager_plugin_load_textdomain() {
//     load_plugin_textdomain( WP_JQUERY_MANAGER_PLUGIN_TEXT_DOMAIN, false, WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . 'languages' );
// }
// add_action( 'init', 'wp_jquery_manager_plugin_load_textdomain' );


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

        public function autoptimize_support( $dontmove_array ) {
            $dontmove_array[] = '/jquery-manager/assets/js';
            return $dontmove_array;
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
            $jquery_3x_slim = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X_SLIM;
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
	                    'default' => 'jquery_3x_min',
	                    'options' => array(
	                        'jquery_3x_min'      => $jquery_3x . '.min.js (default)',
							'jquery_3x'          => $jquery_3x . '.js',
                            'jquery_3x_slim_min' => $jquery_3x_slim . '.min.js',
							'jquery_3x_slim'     => $jquery_3x_slim . '.js',
							'jquery_2x_min'      => $jquery_2x . '.min.js',
							'jquery_2x'          => $jquery_2x . '.js',
							'jquery_1x_min'      => $jquery_1x . '.min.js',
	                        'jquery_1x'          => $jquery_1x . '.js'
	                    )
	                ),
					array(
	                    'name'    => 'jquery_execution',
	                    'label'   => __( 'jQuery execution', $this->text_domain ),
	                    'desc'    => __( 'Experimental! Some plugins and/or themes may not support this. <strong>Broken for now, does nothing.</strong> See: https://github.com/Remzi1993/jquery-manager/issues/8', $this->text_domain ),
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
						'default' => 'jquery_migrate_3x_min',
						'options' => array(
							'jquery_migrate_3x_min'	=> $jquery_migrate_3x . '.min.js (default)',
                            'jquery_migrate_3x'		=> $jquery_migrate_3x . '.js',
							'jquery_migrate_1x_min'	=> $jquery_migrate_1x . '.min.js',
                            'jquery_migrate_1x'		=> $jquery_migrate_1x . '.js'
						)
					),
					array(
	                    'name'    => 'jquery_migrate_head_body',
	                    'label'   => __( 'jQuery Migrate code', $this->text_domain ),
	                    'desc'    => __( 'Choose where to put jQuery Migrate in the <strong>&lt;head&gt;</strong> or at the end of the <strong>&lt;body&gt;</strong> tag, just before it closes', $this->text_domain ),
	                    'type'    => 'radio',
						'default' => 'head',
	                    'options' => array(
							'head'	=> '&lt;head&gt; (default)',
							'body'	=> '&lt;body&gt;'
	                    )
	                ),
					array(
						'name'		=> 'jquery_migrate_execution',
						'label'		=> __( 'jQuery Migrate execution', $this->text_domain ),
						'desc'		=> __( 'Experimental! Some plugins and/or themes do not support this. <strong>Broken for now, does nothing.</strong> See: https://github.com/Remzi1993/jquery-manager/issues/8', $this->text_domain ),
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
			$jquery_options = $GLOBALS['wp_jquery_manager_plugin_jquery_settings'];
            $jquery_migrate_options = $GLOBALS['wp_jquery_manager_plugin_jquery_migrate_settings'];

            // Get jQuery version
            if ( isset( $jquery_options['jquery_version'] ) ) {
                switch ( $jquery_options['jquery_version'] ) {
                    case 'jquery_3x_min':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js';
                        break;
                    case 'jquery_3x':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.js';
                        break;
                    case 'jquery_3x_slim_min':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X_SLIM . '.min.js';
                        break;
                    case 'jquery_3x_slim':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X_SLIM . '.js';
                        break;
                    case 'jquery_2x_min':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X . '.min.js';
                        break;
                    case 'jquery_2x':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X . '.js';
                        break;
                    case 'jquery_1x_min':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X . '.min.js';
                        break;
                    case 'jquery_1x':
                        $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X . '.js';
                        break;
                } // End switch case
            }

            // Get jQuery Migrate version
            if ( isset( $jquery_migrate_options['jquery_migrate_version'] ) ) {
                switch ( $jquery_migrate_options['jquery_migrate_version'] ) {
                    case 'jquery_migrate_3x':
                        $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.js';
                        break;
                    case 'jquery_migrate_3x_min':
                        $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js';
                        break;
                    case 'jquery_migrate_1x':
                        $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X . '.js';
                        break;
                    case 'jquery_migrate_1x_min':
                        $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X . '.min.js';
                        break;
                } // End switch case
            }

			// For debugging
			if ( isset( $jquery_options['debug_mode'] ) ) {
				if ( $jquery_options['debug_mode'] == 'on' ) {
					echo '<h1>Debug information</h1>';

                    echo '<p>';
                    echo '<span><strong>Plugin directory:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DIR_PATH . '</span><br>';
					echo '<span><strong>Plugin URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DIR_URL . '</span><br>';
					echo '<span><strong>Plugin admin URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_ADMIN_URL . '</span>';
                    echo '</p>';

                    echo '<p>';
					echo '<span><strong>Domain name:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_DOMAIN_NAME . '</span><br>';
					echo '<span><strong>URL:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_SITE_URL . '</span>';
                    echo '</p>';

                    echo '<p>';
                    if ( $jquery_options['jquery'] == 'off' ) {
                        echo '<span><strong>jQuery:</strong> disabled</span><br>';
                    }
                    else {
                        echo '<span><strong>jQuery:</strong> ' . $jquery_version . '</span><br>';
                    }

                    if ( !isset( $jquery_migrate_options['jquery_migrate'] ) ) {
                        echo '<strong>jQuery Migrate:</strong> ' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js';
                    }
                    elseif ( $jquery_migrate_options['jquery_migrate'] == 'off' ) {
                        echo '<span><strong>jQuery Migrate:</strong> disabled</span><br>';
                    }
                    else {
                        echo '<strong>jQuery Migrate:</strong> ' . $jquery_migrate_version;
                    }
                    echo '</p>';
				}
			}

			// Plugin settings
	        echo '<div class="wrap">';
			settings_errors();
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

    $jquery_options = $GLOBALS['wp_jquery_manager_plugin_jquery_settings'];
    $jquery_migrate_options = $GLOBALS['wp_jquery_manager_plugin_jquery_migrate_settings'];

    // Get jQuery version
    if ( isset( $jquery_options['jquery_version'] ) ) {
        switch ( $jquery_options['jquery_version'] ) {
            case 'jquery_3x_min':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js';
                break;
            case 'jquery_3x':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.js';
                break;
            case 'jquery_3x_slim_min':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X_SLIM . '.min.js';
                break;
            case 'jquery_3x_slim':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X_SLIM . '.js';
                break;
            case 'jquery_2x_min':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X . '.min.js';
                break;
            case 'jquery_2x':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_2X . '.js';
                break;
            case 'jquery_1x_min':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X . '.min.js';
                break;
            case 'jquery_1x':
                $jquery_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_1X . '.js';
                break;
        } // End switch case
    }

    // Get jQuery Migrate version
    if ( isset( $jquery_migrate_options['jquery_migrate_version'] ) ) {
        switch ( $jquery_migrate_options['jquery_migrate_version'] ) {
            case 'jquery_migrate_3x':
                $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.js';
                break;
            case 'jquery_migrate_3x_min':
                $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js';
                break;
            case 'jquery_migrate_1x':
                $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X . '.js';
                break;
            case 'jquery_migrate_1x_min':
                $jquery_migrate_version = WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_1X . '.min.js';
                break;
        } // End switch case
    }

    // Default setting
	if ( $wp_admin || $wp_customizer ) {
		// echo 'We are in the WP Admin or in the WP Customizer';
		return;
	}
    elseif ( !isset( $jquery_options['jquery'] ) ) { // Default setting

        // Removing WP core jQuery, see https://github.com/Remzi1993/jquery-manager/issues/2 and https://github.com/WordPress/WordPress/blob/91da29d9afaa664eb84e1261ebb916b18a362aa9/wp-includes/script-loader.php#L226
        wp_dequeue_script( 'jquery' );
        wp_deregister_script( 'jquery' );

        // Removing WP core jQuery
        wp_dequeue_script( 'jquery-core' );
        wp_deregister_script( 'jquery-core' );

        // Get jQuery version
        $jquery = 'assets/js/' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_3X . '.min.js';

        // Register jQuery in the head
        wp_register_script( 'jquery-core', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery, array(), null, false );

        /**
         * Register jquery using jquery-core as a dependency, so other scripts could use the jquery handle
         * see https://wordpress.stackexchange.com/questions/283828/wp-register-script-multiple-identifiers
         * We first register the script and afther that we enqueue it, see why:
         * https://wordpress.stackexchange.com/questions/82490/when-should-i-use-wp-register-script-with-wp-enqueue-script-vs-just-wp-enque
         * https://stackoverflow.com/questions/39653993/what-is-diffrence-between-wp-enqueue-script-and-wp-register-script
         */
        wp_register_script( 'jquery', false, array( 'jquery-core' ), null, false ); // The jquery handle is an alias to load jquery-core
        wp_enqueue_script( 'jquery' );

    }
	elseif ( $jquery_options['jquery'] == 'on' ) {

        // Removing WP core jQuery
        wp_dequeue_script( 'jquery' );
		wp_deregister_script( 'jquery' );
        wp_dequeue_script( 'jquery-core' );
		wp_deregister_script( 'jquery-core' );

        // Get jQuery version
        $jquery = 'assets/js/' . $jquery_version;

		// Register jQuery in the head
		wp_register_script( 'jquery-core', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery, array(), null, false );
		wp_register_script( 'jquery', false, array( 'jquery-core' ), null, false ); // Alias
		wp_enqueue_script( 'jquery' );

	}
	elseif ( $jquery_options['jquery'] == 'off' ) {

        // Removing WP core jQuery
        wp_dequeue_script( 'jquery' );
		wp_deregister_script( 'jquery' );
        wp_dequeue_script( 'jquery-core' );
		wp_deregister_script( 'jquery-core' );

	} // End jQuery

    // jQuery Migrate
    if ( $wp_admin || $wp_customizer ) {
        // echo 'We are in the WP Admin or in the WP Customizer';
        return;
    }
    elseif ( !isset( $jquery_migrate_options['jquery_migrate'] ) ) { // Default setting

        // Removing WP core jQuery Migrate
        wp_dequeue_script( 'jquery-migrate' );
		wp_deregister_script( 'jquery-migrate' );

        // Get jQuery Migrate version
        $jquery_migrate = 'assets/js/' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js';

        // Register and than enqueue jQuery Migrate in the head
        wp_register_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate, array(), null, false );
        wp_enqueue_script( 'jquery-migrate' );

    }
	elseif ( $jquery_migrate_options['jquery_migrate'] == 'on' ) {

        // Removing WP core jQuery Migrate
        wp_dequeue_script( 'jquery-migrate' );
		wp_deregister_script( 'jquery-migrate' );

		// Get jQuery Migrate version
        $jquery_migrate = 'assets/js/' . $jquery_migrate_version;

		// Setting head or body
		if ( $jquery_migrate_options['jquery_migrate_head_body'] == 'head' ) {
			// Enqueue jQuery Migrate in the head
			wp_register_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate, array(), null, false );
			wp_enqueue_script( 'jquery-migrate' );
		}
		else {
			// Enqueue jQuery Migrate before </body>
			wp_register_script( 'jquery-migrate', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . $jquery_migrate, array(), null, true );
			wp_enqueue_script( 'jquery-migrate' );
		}

	}
	elseif ( $jquery_migrate_options['jquery_migrate'] == 'off' ) {

        // Removing WP core jQuery Migrate
        wp_dequeue_script( 'jquery-migrate' );
		wp_deregister_script( 'jquery-migrate' );

	} // End jQuery Migrate


    // When debugging is enabled
    if ( $wp_admin || $wp_customizer ) {
        return;
    }
    elseif ( isset( $jquery_options['debug_mode'] ) ) {
        if ( $jquery_options['debug_mode'] == 'on' ) {

            $margin_j = "margin: 40px 0 0 20px;";
            $margin_jm = "margin: 140px 0 0 20px;";
            $style_j = "position: fixed; top: 0; left: 0; z-index: 9999; color: black; background: gray; " . $margin_j .  " padding: 20px; font-size: 30px;";
            $style_jm = "position: fixed; top: 0; left: 0; z-index: 9999; color: black; background: gray; " . $margin_jm .  " padding: 20px; font-size: 30px;";

            if ( $jquery_options['jquery'] == 'on' ) {
                echo '<div style="'. $style_j .'">jQuery version: ' . $jquery_version . '</div>';
            }
            else {
                echo '<div style="'. $style_j .'">jQuery is disabled</div>';
            }

            if ( !isset( $jquery_migrate_options['jquery_migrate'] ) ) {
                echo '<div style="'. $style_jm .'">jQuery Migrate version: ' . WP_JQUERY_MANAGER_PLUGIN_JQUERY_MIGRATE_3X . '.min.js</div>';
            }
            elseif ( $jquery_migrate_options['jquery_migrate'] == 'on' ) {
                echo '<div style="'. $style_jm .'">jQuery Migrate version: ' . $jquery_migrate_version . '</div>';
            }
            else {
                echo '<div style="'. $style_jm .'">jQuery Migrate is disabled</div>';
            }

            if ( $jquery_options['jquery'] == 'off' && $jquery_migrate_options['jquery_migrate'] == 'off' ) {
                echo '<div style="'. $style_j .'">jQuery and jQuery Migrate are both disabled</div>';
            }

        }
    }

} // End function wp_jquery_manager_plugin_front_end_scripts


// Back end specific CSS
// Load only on tools.php?page=wp-jquery-manager-plugin-settings (plugin settings)
function wp_jquery_manager_plugin_admin_scripts($hook) {
	if( $hook != 'tools_page_' . WP_JQUERY_MANAGER_PLUGIN_SLUG ) {
		return;
	}

	wp_enqueue_style( 'wp-jquery-manager-plugin-admin', WP_JQUERY_MANAGER_PLUGIN_DIR_URL . 'assets/css/admin.css', array(), null );
}

// Register styles and scripts
add_action( 'wp_enqueue_scripts', 'wp_jquery_manager_plugin_front_end_scripts', 1 );
add_action( 'login_enqueue_scripts', 'wp_jquery_manager_plugin_front_end_scripts', 1 );
add_action( 'admin_enqueue_scripts', 'wp_jquery_manager_plugin_admin_scripts' );


// Defer and/or async jQuery and/or jQuery Migrate
function wp_jquery_manager_plugin_add_attribute( $tag, $handle ) {
	if ( is_admin() || is_customize_preview() ) {
		return $tag;
	}
	elseif ( !isset( $jquery_options['jquery_execution'] ) && !isset( $jquery_migrate_options['jquery_migrate_execution'] ) ) { // No settings, default. Exit and stop wasting time :)
		return $tag;
	}

    $jquery_options = $GLOBALS['wp_jquery_manager_plugin_jquery_settings'];
    $jquery_migrate_options = $GLOBALS['wp_jquery_manager_plugin_jquery_migrate_settings'];

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

// Deactivation
register_deactivation_hook( __FILE__, 'wp_jquery_manager_plugin_deactivation' );

function wp_jquery_manager_plugin_deactivation() {
	delete_option( 'wp_jquery_manager_plugin_jquery_settings' );
	delete_option( 'wp_jquery_manager_plugin_jquery_migrate_settings' );
}
