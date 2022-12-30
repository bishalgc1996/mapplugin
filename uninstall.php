<?php

/**
 * Fired when the plugin is uninstalled.

 * @since      1.0.0
 *
 * @package    Gd Map Plugin
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Class Uninstall
 *

 */
class Uninstall {
	/**
	 * Destroy Config
	 * Drop Database
	 * Delete options
	 * Removing Settings
	 */
	public static function uninstall() {


	}
}