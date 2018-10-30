=== WP jQuery Updater ===
Contributors: Remzi Cavdar
Tags: jquery, update, jquery ui, javascript, jq, jquery migrate, jquery updater
Requires at least: 4.9
Tested up to: 4.9
Stable tag: 1.0.2

This plugin updates jQuery and jQuery Migrate to the latest stable version on your website.

== Description ==
This plugin updates [jQuery](http://jquery.com/) to the latest official stable version, which is most likely not available within the latest stable release of WordPress.
[jQuery Migrate](http://jquery.com/download/#jquery-migrate-plugin) is also included with logging active, so that developers are able to identify deprecated code.

**No** modification to the WordPress installation is made, therefore deactivation and/or uninstallation of this plugin returns your site to it`s original state.

**Warning**

If you are not familiar with beta testing, bugfixing, javascript or running bleeding edge software it`s **not** recommended.
I will not provide help on JavaScript and jQuery!

**Reporting problems**

Please post bug reports and request for help on [GitHub Issues page](https://github.com/Remzi1993/wp-jquery-updater/issues). I will only provide support on issues caused by this plugin, not your own JavaScript and/or jQuery code.

If you run into any bugs, turning this plugin off will fully deactivate everything.

== Installation ==
1. Upload `wp-jquery-updater` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. jQuery and jQuery Migrate are updated to the latest stable version

== Frequently Asked Questions ==
1. Q: Is this plugin compatible with PHP 5.2 / 5.3 / 5.4 / 5.5 / 5.6 or 7 and HHVM?
A: Short answer: Yes Long Answer: I honestly don't know. I didn't use PHP7 features or something, but I don't know for sure.

2. Q: This plugin breaks my site! How do I fix it?
A: Deactivate the plugin and report this to me, so that I could look into this matter.

== Changelog ==
= 1.0.2 =
* Cleanup
= 1.0.1 =
* Disabled debugging mode, changed readme.txt and clarified some comments
= 1.0.0 =
* First version, uses jQuery 3.3.1 and jQuery Migrate 3.0.1
