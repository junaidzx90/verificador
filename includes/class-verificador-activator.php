<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Verificador
 * @subpackage Verificador/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Verificador
 * @subpackage Verificador/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class Verificador_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
		$verificador = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}verificador` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`username` VARCHAR(555) NOT NULL,
			`useremail` VARCHAR(555) NOT NULL,
			`coupon` VARCHAR(555) NOT NULL,
			`manager_name` VARCHAR(555) NOT NULL,
			`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
			dbDelta($verificador);
	}

}
