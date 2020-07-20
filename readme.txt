=== jQuery Manager for WordPress ===
Contributors: remzicavdar
Tags: jquery, jquery manager, jquery updater, jquery migrate, jquery update, remove jquery migrate, javascript, jquery ui, update, jquery update, jquery wordpress, jquery wordpress updater, manage jquery, jquery settings, jquery tool, jquery debugger, debug
Requires at least: 4.9
Tested up to: 5.5
Requires PHP: 5.6
Stable tag: trunk
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0

Manage jQuery and jQuery Migrate, activate a specific jQuery and/or jQuery Migrate version. The ultimate jQuery debugging tool for WordPress. This plugin is an open source project, made possible by your contribution (code). Development is done on GitHub.

== Description ==
Manage [jQuery](https://jquery.com) and [jQuery Migrate](https://jquery.com/download/#jquery-migrate-plugin) on a WordPress website, activate a specific jQuery and/or jQuery Migrate version. The ultimate jQuery debugging tool for WordPress

**TLDR:** Update jQuery / Remove jQuery Migrate / jQuery Updater and get notified of a jQuery update / manage jQuery

### Features ###
* **Remove jQuery Migrate** - if you use plugins which are updated regularly, then you can probably disable / remove jQuery Migrate.
* **jQuery Updater** - If this plugin is installed and activated, jQuery will be updated when you update this plugin
* **Select jQuery (Migrate) version** — Activate / select a specific version of jQuery and/or jQuery Migrate.
* **Debug mode** - Debug mode for developers
* **Active support** - Active support through [WordPress support forum](https://wordpress.org/support/plugin/jquery-manager/) and [GitHub issues page](https://github.com/Remzi1993/jquery-manager/issues)

Note that jQuery Manager requires PHP 5.6+ to run.

When the plugin is activated the latest stable version of jQuery and jQuery Migrate are added to your website. The default is sufficient for most people, however the settings provide a way for you to tweak your jQuery install.

jQuery Manager for WordPress is an open source project and I would like to invite anyone to contribute. The development and issue tracker is located on GitHub, see: [https://github.com/Remzi1993/jquery-manager](https://github.com/Remzi1993/jquery-manager)

### jQuery Updater - checks jQuery version ###
This plugin is especially designed to update and keep track of jQuery. When a new version of jQuery is released we update the plugin.

Updating the plugin will updates the jQuery version activated. Updates will be periodic, we also do (small) optimizations to our plugin from time to time.

Keep the plugin activated if you want to be alerted for any jQuery updates.

### Also a Developer / Debugging tool ###
Everything should work as intended, you should check your website if everything works fine. If things don't work, don't panic. You can deactivate the plugin and return to the state before or tweak it's settings until everything works as intended.

You should understand that not all themes and/or plugins support the latest jQuery version even with jQuery Migrate turned on, but for you to encounter this is highly unlikely if your theme or plugins are up to date.
This is also a developer/debugging tool.

I will not provide support on debugging your own JavaScript and/or jQuery code. (Only if you like to hire me, of course)

### Reporting problems ###
Report bugs, issues, questions and/or feature request on our [GitHub issues page](https://github.com/Remzi1993/jquery-manager/issues).

I will only provide support on issues caused by this plugin, not your own JavaScript and/or jQuery code. (Again, I will provide paid support or fix all your issues if you're willing to hire me to debug and/or optimize your website)

If you run into any bugs, turning this plugin off (by deactivation the plugin) will fully deactivate everything and return your website to it`s original state.

== Installation ==
Manual installation by uploading .zip file via WordPress admin
1. Navigate to > `Plugins` > `Add New`
2. On your top left > click on `Upload Plugin` and select your .zip file you downloaded from [wordpress.org](https://wordpress.org/plugins/jquery-manager/) and click `Install Now`
3. By activating the plugin, jQuery and jQuery Migrate are updated to the latest stable version. (Plugin settings are located under `Tools`)

Manual installation by uploading folder/directory via FTP, SFTP or SSH
1. Unzip (extract/unpack/uncompress) the .zip file you downloaded from [wordpress.org](https://wordpress.org/plugins/jquery-manager/)
2. Upload the folder `jquery-manager` to the `/wp-content/plugins/` directory on your server
3. Activate the plugin through the 'Plugins' menu in WordPress
4. By activating the plugin, jQuery and jQuery Migrate are updated to the latest stable version. (Plugin settings are located under `Tools`)

== Frequently Asked Questions ==
= Is this plugin compatible with PHP 5.6, 7, 7.1, 7.2, HHVM, et cetera, et cetera? =
Short answer: probably. Long answer: I honestly don't know for sure. As far as I know I didn't use specific PHP 7 features. I made a simple plugin. That's why I recommend a minimum of PHP 5.6. Also I do specific tests to ensure that I stay compatible with PHP 5.6

= This plugin breaks my site! How do I fix it? =
Deactivate the plugin and report this on the [plugin support forum](https://wordpress.org/support/plugin/jquery-manager/) or report it on the [GitHub project page](https://github.com/Remzi1993/jquery-manager/issues), so that I could take a look into the matter.

= Does this plugin modify my WP installation? =
**No** modification to the WordPress installation is made, therefore deactivation and/or uninstallation of this plugin returns your site to it`s original state.

= Which jQuery (Migrate) version do you support? =
This plugin supports the 3 jQuery branches/versions and 2 versions of jQuery Migrate. You're able to select/activate a specific version or to disable it/turn it off completely<br>

There are two versions of jQuery Migrate. jQuery Migrate 1.x will help you update your pre-1.9 jQuery code to jQuery 1.9 up to 3.0.

jQuery Migrate 3.x helps you update code to run on jQuery 3.0 or higher. Most websites need jQuery Migrate 3.x, but if you have a modern/well supported WP theme and/or plugin(s) than you could give it a try to disable it. Check your browser dev tools if you see any errors.

**jQuery versions**
jQuery 3.x (default is compressed/minified production)
jQuery 2.x
jQuery 1.x

**jQuery Migrate versions**
jQuery Migrate 3.x (default is compressed/minified production)
jQuery Migrate 1.x

= Is it possible to disable jQuery Migrate? =
Yes, this is possible and done in the plugin settings.

= Is it possible to enable a specific version of jQuery (Migrate) =
Yes, this is possible and done in the plugin settings.

= What is jQuery Migrate? =
The jQuery Migrate plugin was created to simplify the transition from older versions of jQuery. The plugin restores deprecated features and behaviors so that older code will still run properly on newer versions of jQuery.

Use the uncompressed development version to diagnose compatibility issues, it will generate warnings on the console that you can use to identify and fix problems. Use the compressed production version to simply fix compatibility issues without generating console warnings.

There are two versions of jQuery Migrate. jQuery Migrate 1.x will help you update your pre-1.9 jQuery code to jQuery 1.9 up to 3.0.

jQuery Migrate 3.x helps you update code to run on jQuery 3.0 or higher.

= How does the jQuery Updater work? =
We update the plugin if a new version of jQuery is released. If you update the plugin, all jQuery versions will be updated.

== Screenshots ==
1. jQuery settings: enable / disable jQuery, select version and etc
2. jQuery Migrate settings: enable / disable jQuery Migrate, select version and etc

== Changelog ==
See changelog on the [GitHub project page](https://github.com/Remzi1993/jquery-manager/releases)

== Upgrade Notice ==
See changelog on the [GitHub project page](https://github.com/Remzi1993/jquery-manager/releases)

== Translations ==
This plugin is not ready for translation. Plugin Internationalization (i18n) will eventually happen, see: [https://github.com/Remzi1993/jquery-manager/issues/14](https://github.com/Remzi1993/jquery-manager/issues/14)

== Additional Info ==
**Idea Behind / Philosophy:** It began as a plugin internally at our company, we would uses jQuery Updater plugin and this plugin was buggy and slow. That's why we decided to fork this plugin and create our own WP plugin.

 I think this plugin solves a major shortcoming in WordPress and gives you finite control over jQuery (giving you control which version you want to use).