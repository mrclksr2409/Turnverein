<?php
/**
 * Plugin Name: Turnverein Manager
 * Plugin URI:  https://github.com/mrclksr2409/Turnverein
 * Description: Verwaltung von Sportstätten, Trainern und Gruppen für Turnvereine.
 * Version:     1.4.0
 * Author:      Turnverein
 * License:     GPL-2.0+
 * Text Domain: turnverein
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'TURNVEREIN_VERSION', '1.4.0' );
define( 'TURNVEREIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TURNVEREIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-db.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-sportstaetten.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-trainer.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-gruppen.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-trainingszeiten.php';
require_once TURNVEREIN_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once TURNVEREIN_PLUGIN_DIR . 'admin/class-admin.php';

/**
 * Plugin Update Checker — pulls updates from GitHub (stable branch: main).
 */
$turnverein_puc_loader = TURNVEREIN_PLUGIN_DIR . 'lib/plugin-update-checker/plugin-update-checker.php';
if ( file_exists( $turnverein_puc_loader ) ) {
    require_once $turnverein_puc_loader;

    if ( class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
        $turnverein_update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
            'https://github.com/mrclksr2409/Turnverein/',
            __FILE__,
            'turnverein'
        );

        // Development happens on "beta"; installed sites only receive what is on "main".
        $turnverein_update_checker->setBranch( 'main' );

        // Ignore GitHub releases and tags so only the main branch HEAD is used.
        add_filter(
            $turnverein_update_checker->getUniqueName( 'vcs_update_detection_strategies' ),
            static function ( $strategies ) {
                unset( $strategies['latest_release'], $strategies['latest_tag'] );
                return $strategies;
            }
        );
    }
}
unset( $turnverein_puc_loader );

register_activation_hook( __FILE__, array( 'Turnverein_DB', 'install' ) );
register_deactivation_hook( __FILE__, array( 'Turnverein_DB', 'uninstall' ) );

function turnverein_init() {
    new Turnverein_Admin();
    new Turnverein_Shortcodes();
}
add_action( 'plugins_loaded', 'turnverein_init' );

/**
 * Führt dbDelta erneut aus, wenn sich die DB-Version geändert hat.
 * Damit werden neue Tabellen und Spalten bei bestehenden Installationen angelegt.
 */
function turnverein_maybe_upgrade() {
    if ( get_option( 'turnverein_db_version' ) !== TURNVEREIN_VERSION ) {
        Turnverein_DB::install();
        update_option( 'turnverein_db_version', TURNVEREIN_VERSION );
    }
}
add_action( 'admin_init', 'turnverein_maybe_upgrade' );
