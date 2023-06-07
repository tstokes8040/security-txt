<?php
/**
 * @package Securitytxt
 * Description: Creates a security.txt file with contact info.
 * Plugin Name: Security-TXT
 * Plugin URI:
 * Version: 1.0.4
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
function sdottxt_create_update_file() {
	global $file_path;
	$securityTXTFile = fopen( $file_path, 'w' ) or wp_die( 'Unable to open or create file! Please check permissions.' );
	$optionSetting   = get_option( 'sdottxt_content' );
	// Check to see if we have an option in the DB. If so, write to the file.
	if ( $optionSetting ) {
		fwrite( $securityTXTFile, $optionSetting );
	}
	fclose( $securityTXTFile );
}

//Deactivating the plugin
function sdottxt_deactivatePlugin() {
	sdottxt_delete_file();
}

//Uninstalling the plugin
function sdottxt_uninstallPlugin() {
	sdottxt_delete_file();
	sdottxt_maybe_delete_options();
}

//Delete file
function sdottxt_delete_file() {
	global $file_path;
	unlink( $file_path );
}

//Maybe Delete Options
function sdottxt_maybe_delete_options() {
	// Check to see if they have checked the delete DB data. If so, then delete DB data.
	$optionSetting = esc_attr( get_option( 'sdottxt_delete_data' ) );
	if ( 'Yes' === $optionSetting ) {
		delete_option( 'sdottxt_delete_data' );
		delete_option( 'sdottxt_content' );
	}
}

//Setup Admin Menu Item
add_action( 'admin_menu', 'sdottxt_menu_item' );
function sdottxt_menu_item() {
	add_submenu_page( 'tools.php', 'Security TXT', 'Security TXT', 'manage_options', 'security-txt', 'securitytxt_form_func' );
	add_action( 'admin_init', 'register_securitytxt_settings' );
}

//Register SecurityTXT Settings
function register_securitytxt_settings() {
	register_setting( 'security_txt_settings_group', 'sdottxt_content' );
	register_setting( 'security_txt_settings_group', 'sdottxt_delete_data' );
}

//SecurityTXT Form Setup
function securitytxt_form_func() {
	?>
	<div class="wrap">
		<h1>Security.txt Settings</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'security_txt_settings_group' ); ?>
			<?php do_settings_sections( 'security_txt_settings_group' ); ?>
			<label for="sdottxt_content">Enter your information to display in the security.txt file</label>
			<br>
			<textarea rows="6" cols="50" type="text" name="sdottxt_content" ><?php echo esc_attr( get_option( 'sdottxt_content' ) ); ?></textarea>
			<br>
			<label for="delete_data">Delete Data on uninstall?</label>
			<br>
			<input type="radio" name="sdottxt_delete_data" class="" value="Yes" <?php checked( 'Yes', get_option( 'sdottxt_delete_data' ) ); ?>/>Yes
			<input type="radio" name="sdottxt_delete_data" class="" value="No" <?php checked( 'No', get_option( 'sdottxt_delete_data' ) ); ?>/>No
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

register_activation_hook( __FILE__, 'sdottxt_create_update_file' );
register_deactivation_hook( __FILE__, 'sdottxt_deactivatePlugin' );
register_uninstall_hook( __FILE__, 'sdottxt_uninstallPlugin' );
add_action('update_option_sdottxt_content', 'sdottxt_create_update_file');
