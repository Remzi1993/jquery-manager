<?php
// If this file is called directly, abort.
defined('ABSPATH') or exit();

/*
 * Generate the admin page
 */
function wpj_updater_plugin_settings() {
    //For debugging
    $plugin_settings = new wpj_updater_plugin;
    $plugin_settings->debug();
    ?>
    <div class="wrap">
        <h1><?php echo __('WP jQuery Updater', 'wpj_updater_plugin_settings');?></h1>
        <p>WP jQuery Updater settings, you're able to enter your own version of jQuery or enter a CDN.</p>

        <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'):
            echo '<div id="setting-error-settings_updated" class="updated settings-error">
            <p><strong>' . __("Settings saved", "wpj_updater_plugin_settings") . '</strong></p>
         </div>';
        endif;
        ?>
        <form method="post" action="options.php">
            <?php settings_fields('wpj_updater_plugin_settings'); ?>
            <?php do_settings_sections('wpj_updater_plugin_settings'); ?>
            <table id="wpj-updater-plugin-settings" class="form-table form-settings">
                <tr valign="top">
                    <th scope="row"><?php echo __('jQuery URL', 'wpj_updater_jquery_url');?></th>
                    <td>
                        <input type="text" name="wpj_updater_jquery_url" value="<?php echo get_option('wpj_updater_jquery_url'); ?>" placeholder="<?php echo WPJ_UPDATER_PLUGIN_JQUERY; ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php echo __('jQuery Migrate URL', 'wpj_updater_jquery_migrate_url');?></th>
                    <td>
                        <input type="text" name="wpj_updater_jquery_migrate_url" value="<?php echo get_option('wpj_updater_jquery_migrate_url'); ?>" placeholder="<?php echo WPJ_UPDATER_PLUGIN_JQUERY_MIGRATE; ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>

        </form>
    </div>
<?php }
