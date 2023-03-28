<?php
/**
 * @package Securitytxt
 * Description: Creates a security.txt file with contact info.
 * Plugin Name: Security TXT
 * Plugin URI:
 * Version: 1.0.1
 * Author:  Tyler Stokes
 * Author URI: https://tswebservices.com/
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: securitytxt
 * PHP Version: 7.0
 */

/*
Security TXT is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Security TXT is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Security TXT. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once(ABSPATH . 'wp-admin/includes/file.php');
global $file_path;
$file_path = get_home_path() . "security.txt";

//Create or Update our security.txt file
if ( ! function_exists( 'st_create_update_file' ) ) {
	function st_create_update_file() {
        global $file_path;
		$securityTXTFile = fopen( $file_path, 'w' ) or wp_die( 'Unable to open or create file! Please check permissions.' );
		$optionSetting   = get_option( 'st_content' );
		// Check to see if we have an option in the DB. If so, write to the file.
		if ( $optionSetting ) {
			fwrite( $securityTXTFile, $optionSetting );
		}
		fclose( $securityTXTFile );
	}
}

//Deactivating the plugin
if ( ! function_exists( 'st_deactivatePlugin' ) ) {
    function st_deactivatePlugin() {
		st_delete_file();
	}
}

//Uninstalling the plugin
if ( ! function_exists( 'st_uninstallPlugin' ) ) {
	function st_uninstallPlugin() {
		st_delete_file();
		st_maybe_delete_options();
	}
}

//Delete file
if ( ! function_exists('st_delete_file') )  {
	function st_delete_file() {
		global $file_path;
		unlink( $file_path );
	}
}

//Maybe Delete Options
if ( ! function_exists('st_maybe_delete_options') ) {
	function st_maybe_delete_options() {
		// Check to see if they have checked the delete DB data. If so, then delete DB data.
		$optionSetting = esc_attr( get_option( 'st_delete_data' ) );
		if ( 'Yes' === $optionSetting ) {
			delete_option( 'st_delete_data' );
			delete_option( 'st_content' );
		}
	}
}

//Setup Admin Menu Item
add_action( 'admin_menu', 'st_menu_item' );
if ( ! function_exists('st_menu_item') ) {
	function st_menu_item() {
		add_submenu_page( 'tools.php', 'Security TXT', 'Security TXT', 'manage_options', 'security-txt', 'securitytxt_form_func' );
		add_action( 'admin_init', 'register_securitytxt_settings' );
	}
}

//Register SecurityTXT Settings
if ( ! function_exists('register_securitytxt_settings') ) {
	function register_securitytxt_settings() {
		register_setting( 'security_txt_settings_group', 'st_content' );
		register_setting( 'security_txt_settings_group', 'st_delete_data' );
	}
}

//SecurityTXT Form Setup
if ( ! function_exists('securitytxt_form_func') ) {
	function securitytxt_form_func() {
		?>
		<div class="wrap">
			<h1>Security.txt Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'security_txt_settings_group' ); ?>
				<?php do_settings_sections( 'security_txt_settings_group' ); ?>
				<label for="st_content">Enter your information to display in the security.txt file</label>
				<br>
				<textarea rows="6" cols="50" type="text" name="st_content" ><?php echo esc_attr( get_option( 'st_content' ) ); ?></textarea>
				<br>
				<label for="delete_data">Delete Data on uninstall?</label>
				<br>
				<input type="radio" name="st_delete_data" class="" value="Yes" <?php checked( 'Yes', get_option( 'st_delete_data' ) ); ?>/>Yes
				<input type="radio" name="st_delete_data" class="" value="No" <?php checked( 'No', get_option( 'st_delete_data' ) ); ?>/>No
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

register_activation_hook( __FILE__, 'st_create_update_file' );
register_deactivation_hook( __FILE__, 'st_deactivatePlugin' );
register_uninstall_hook( __FILE__, 'st_uninstallPlugin' );
add_action('update_option_st_content', 'st_create_update_file');