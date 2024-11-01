<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.wbcomdesigns.com
 * @since             1.0.0
 * @package           Wp_System_Log
 *
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs - WordPress System Log
 * Plugin URI:        http://www.wbcomdesigns.com
 * Description:       WordPress System Log is a simple WordPress plugin for quickly displaying important statistics and configuration settings in regard to your server and WordPress environment. It will also logs all active plugin details which help to developer to analyse issues and bug faster.
 * Version:           1.3.0
 * Author:            Wbcom Designs
 * Author URI:        http://www.wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-system-log
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-system-log-activator.php
 */
function activate_wp_system_log() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-system-log-activator.php';
	Wp_System_Log_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-system-log-deactivator.php
 */
function deactivate_wp_system_log() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-system-log-deactivator.php';
	Wp_System_Log_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_system_log' );
register_deactivation_hook( __FILE__, 'deactivate_wp_system_log' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-system-log.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_system_log() {
	if ( ! defined( 'WPSL_PLUGIN_PATH' ) ) {
		define( 'WPSL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
	}
	if ( ! defined( 'WPSL_PLUGIN_URL' ) ) {
		define( 'WPSL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	$plugin = new Wp_System_Log();
	$plugin->run();

}
run_wp_system_log();

// Settings link for this plugin.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpsl_admin_page_link' );
function wpsl_admin_page_link( $links ) {
	$wpsl_links = array(
		'<a href="' . admin_url( 'admin.php?page=wpsl-setting-log' ) . '">' . __( 'Settings', 'wp-system-log' ) . '</a>',
		'<a href="https://wbcomdesigns.com/contact/" target="_blank">' . __( 'Support', 'wp-system-log' ) . '</a>',
	);
	return array_merge( $links, $wpsl_links );
}

add_action( 'activated_plugin', 'wpsl_system_log_activation_redirect_settings' );
/**
 * woo_document_preview_activation_activation_redirect_settings
 *
 * @param  string $plugin plugin.
 * @return void
 */
function wpsl_system_log_activation_redirect_settings( $plugin ) {
	if ( plugin_basename( __FILE__ ) === $plugin ) {
		wp_redirect( admin_url( 'admin.php?page=wpsl-setting-log' ) );
		exit;
	}
}
