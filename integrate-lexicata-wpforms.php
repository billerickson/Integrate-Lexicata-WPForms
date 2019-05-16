<?php
/**
 * Plugin Name: Integrate Lexicata and WPForms
 * Plugin URI:  https://github.com/billerickson/Integrate-Lexicata-WPForms
 * Description: Connect WPForms to the Lexicata Law Firm CRM.
 * Version:     1.0.0
 * Author:      Bill Erickson
 * Author URI:  https://www.billerickson.net
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.  You may NOT assume that you can use any other
 * version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    Integrate_Lexicata_WPForms
 * @since      1.0.0
 * @copyright  Copyright (c) 2019, Bill Erickson
 * @license    GPL-2.0+
 */

 // Exit if accessed directly
 if ( ! defined( 'ABSPATH' ) ) exit;

 // Plugin version
 define( 'INTEGRATE_LEXICATA_WPFORMS_VERSION', '1.0.0' );

/**
 * Load the class
 *
 */
function integrate_lexicata_wpforms() {

    require_once( plugin_dir_path( __FILE__ ) . 'class-integrate-lexicata-wpforms.php' );

}
add_action( 'wpforms_loaded', 'integrate_lexicata_wpforms' );
