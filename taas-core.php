<?php
/**
 * Provides shared foundation used by TAAS Onboarding and TAAS Matching
 *
 * @package TAAS_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'TAAS_CORE_VERSION', '0.1.0' );
define( 'TAAS_CORE_FILE', __FILE__ );
define( 'TAAS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'TAAS_CORE_URL', plugin_dir_url( __FILE__ ) );

/*
 * Composer autoloader
 */
if ( file_exists( TAAS_CORE_PATH . 'vendor/autoload.php' ) ) {
	require_once TAAS_CORE_PATH . 'vendor/autoload.php';
}

/*
 * Explicit requires
 */
require_once TAAS_CORE_PATH . 'includes/class-taas-logger.php';
require_once TAAS_CORE_PATH . 'includes/class-taas-db.php';
require_once TAAS_CORE_PATH . 'includes/class-taas-auth.php';

/**
 * Activate plugin
 *
 * @return void
 */
function taas_core_activate(): void {
	TAAS_Auth::register_roles();
	TAAS_DB::set_schema_version( 'core', TAAS_CORE_VERSION );
	TAAS_Logger::info( 'TAAS Core activated (version ' . TAAS_CORE_VERSION . ').' );
}
register_activation_hook( __FILE__, 'taas_core_activate' );

/**
 * Deactivate plugin
 *
 * @return void
 */
function taas_core_deactivate(): void {
	TAAS_Logger::info( 'TAAS Core deactivated.' );
}
register_deactivation_hook( __FILE__, 'taas_core_deactivate' );

/**
 * Boot the plugin
 *
 * @return void
 */
function taas_core_init(): void {
	load_plugin_textdomain( 'taas-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	/**
	 * Fire once TAAS Core is loaded and its helpers are available
	 *
	 * @since 0.1.0
	 */
	do_action( 'taas_core_loaded' );
}
add_action( 'plugins_loaded', 'taas_core_init' );

/**
 * Warn admin if a dependent plugin is active without TAAS Core
 *
 * @return void
 */
function taas_core_admin_notices(): void {
	if ( ! TAAS_Auth::is_local_dev() ) {
		return;
	}

	echo '<div class="notice notice-info"><p><strong>TAAS Core:</strong> ';
	echo esc_html__( 'Local development mode is ON. Authentication is mocked.', 'taas-core' );
	echo ' ' . esc_html( sprintf( 'Current mock role: %s', TAAS_Auth::local_dev_role() ) );
	echo '</p></div>';
}
add_action( 'admin_notices', 'taas_core_admin_notices' );
