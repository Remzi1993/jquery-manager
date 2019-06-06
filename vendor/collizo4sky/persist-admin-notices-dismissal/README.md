# Persist Admin notice Dismissals
[![Latest Stable Version](https://poser.pugx.org/collizo4sky/persist-admin-notices-dismissal/v/stable)](https://packagist.org/packages/collizo4sky/persist-admin-notices-dismissal)
[![Total Downloads](https://poser.pugx.org/collizo4sky/persist-admin-notices-dismissal/downloads)](https://packagist.org/packages/collizo4sky/persist-admin-notices-dismissal)

Simple framework library that persists the dismissal of admin notices across pages in WordPress dashboard.

## Installation

Run `composer require collizo4sky/persist-admin-notices-dismissal`

Alternatively, clone or download this repo into the `vendor/` folder in your plugin, and include/require the `persist-admin-notices-dismissal.php` file like so

```php
require  __DIR__ . '/vendor/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
add_action( 'admin_init', array( 'PAnD', 'init' ) );
```

or let Composer's autoloader do the work.

## How to Use
Firstly, install and activate this library within a plugin.

Say you have the following markup as your admin notice,


```php
function sample_admin_notice__success() {
	?>
	<div class="updated notice notice-success is-dismissible">
    	<p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'sample_admin_notice__success' );
```

To make it hidden forever when dismissed, add the following data attribute `data-dismissible="disable-done-notice-forever"` to the div markup like so:


```php
function sample_admin_notice__success() {
	if ( ! PAnD::is_admin_notice_active( 'disable-done-notice-forever' ) ) {
		return;
	}

	?>
	<div data-dismissible="disable-done-notice-forever" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}
add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'sample_admin_notice__success' );
```

## Autoloaders
When using the framework with an autoloader you **must** also load the class outside of the `admin_notices` or `network_admin_notices` hooks. The reason is that these hooks come after the `admin_enqueue_script` hook that loads the javascript.

Just add the following in your main plugin file.

```php
add_action( 'admin_init', array( 'PAnD', 'init' ) );
```

#### Usage Instructions and Examples
If you have two notices displayed when certain actions are triggered; firstly, choose a string to uniquely identify them, e.g. `notice-one` and `notice-two`

To make the first notice never appear once dismissed, its `data-dismissible` attribute will be `data-dismissible="notice-one-forever"` where `notice-one` is its unique identifier and `forever` is the dismissal time period.

To make the second notice only hidden for 2 days, its `data-dismissible` attribute will be `data-dismissible="notice-two-2"` where `notice-two` is its unique identifier and the `2`, the number of days it will be hidden is the dismissal time period.

You **must** append the dismissal time period to the end of your unique identifier with a hyphen (`-`) and this value must be an integer. The only exception is the string `forever`.

To actually make the dismissed admin notice not to appear, use the `is_admin_notice_active()` function like so:


```php
function sample_admin_notice__success1() {
	if ( ! PAnD::is_admin_notice_active( 'notice-one-forever' ) ) {
		return;
	}

	?>
	<div data-dismissible="notice-one-forever" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done 1!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

function sample_admin_notice__success2() {
	if ( ! PAnD::is_admin_notice_active( 'notice-two-2' ) ) {
		return;
	}

	?>
	<div data-dismissible="notice-two-2" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Done 2!', 'sample-text-domain' ); ?></p>
	</div>
	<?php
}

add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'sample_admin_notice__success1' );
add_action( 'admin_notices', 'sample_admin_notice__success2' );
```

Please note that if you cleanup after your plugin deletion please try to make the removal of stored options as specific as possible. Otherwise you may end up deleting the stored options from other projects.

A filter hook is available to return the proper URL to the Javascript file. An example usage is as follows, especially if this is being used in a theme.

```php
add_filter( 'pand_theme_loader', '__return_true' );
```

The `pand_theme_loader` runs the following hook if `true`. You can directly change the URL to the Javascript file by using another hook in the following manner by changing the return value.

```php
add_filter(
	'pand_dismiss_notice_js_url',
	function( $js_url, $composer_path ) {
		return get_stylesheet_directory_uri() . $composer_path;
	},
	10,
	2
);
```

Cool beans. Isn't it?
