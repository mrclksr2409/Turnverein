<?php
/**
 * Plugin Name: Turnverein Manager
 * Plugin URI:  https://example.com/turnverein
 * Description: Verwaltung von Sportstätten, Trainern und Gruppen für Turnvereine.
 * Version:     1.0.0
 * Author:      Turnverein
 * License:     GPL-2.0+
 * Text Domain: turnverein
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TURNVEREIN_VERSION', '1.0.0' );
define( 'TURNVEREIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TURNVEREIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-db.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-sportstaetten.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-trainer.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-gruppen.php';
require_once TURNVEREIN_PLUGIN_DIR . 'admin/class-admin.php';

register_activation_hook( __FILE__, array( 'Turnverein_DB', 'install' ) );
register_deactivation_hook( __FILE__, array( 'Turnverein_DB', 'uninstall' ) );

function turnverein_init() {
    new Turnverein_Admin();
}
add_action( 'plugins_loaded', 'turnverein_init' );
