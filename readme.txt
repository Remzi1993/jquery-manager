=== jQuery Manager ===
Contributors: Remzi Cavdar
Tags: jquery, update, jquery ui, javascript, jq, jquery migrate, jquery updater
Requires at least: 4.9
Tested up to: 4.9

Manage jQuery and jQuery Migrate on a WordPress website, select a specific jQuery and/or jQuery Migrate version. The ultimate jQuery debugging tool for WordPress

== Description ==
Manage [jQuery](https://jquery.com) and [jQuery Migrate](http://jquery.com/download/#jquery-migrate-plugin) on a WordPress website, select a specific jQuery and/or jQuery Migrate version. Use this as a debugging tool for your jQuery code.

By default jQuery Migrate will be loaded at the end of the body tag and with defer, also an uncompressed development build is being used with logging active, so that developers are able to identify deprecated code.

**No** modification to the WordPress installation is made, therefore deactivation and/or uninstallation of this plugin returns your site to it`s original state.

**Warning**

If you are not familiar with beta testing, bug fixing, JavaScript, jQuery and/or running bleeding edge software than I **don't** recommend using this plugin.
I will not provide support on debugging your JavaScript and/or jQuery code. (Only if you like to hire me, of course)

**Reporting problems**

Please post bug reports, feature and/or support requests on the [GitHub issues page](https://github.com/Remzi1993/wp-jquery-manager/issues). I will only provide support on issues caused by this plugin, not your own JavaScript and/or jQuery code. (Again, I will provide support or fix all your issues if you're willing to hire me to debug and/or optimize your website)

If you run into any bugs, turning this plugin off (by deactivation the plugin) will fully deactivate everything and return your website to it`s original state.

== Installation ==
Installation via WordPress admin
1. Navigate to > `Plugins` > `Add New`
2. On your top left > click on `Upload Plugin` and select your .zip file you downloaded from [Github releases](https://github.com/Remzi1993/wp-jquery-manager/releases) and click `Install Now`
3. By activating the plugin, jQuery and jQuery Migrate are updated to the latest stable version.

Installation via FTP, SFTP or SSH
1. Unzip (unpack/uncompress) the .zip file you downloaded from [Github releases](https://github.com/Remzi1993/wp-jquery-manager/releases)
2. Upload the folder `wp-jquery-updater` to the `/wp-content/plugins/` directory on your server
3. By activating the plugin, jQuery and jQuery Migrate are updated to the latest stable version.

== Frequently Asked Questions ==
Is this plugin compatible with PHP 5.2 / 5.3 / 5.4 / 5.5 / 5.6, 7, 7.1, 7.2, HHVM and/or whatever?
1. Short answer: Properly
2. Long answer: I honestly don't know for sure. I didn't used PHP7's new features or something, but I don't know for sure. I made a simple plugin.

This plugin breaks my site! How do I fix it?
2. Deactivate the plugin and report this to me, so that I could look into this matter.

== Changelog ==
See changelog on [GitHub](https://github.com/Remzi1993/wp-jquery-manager/releases)
