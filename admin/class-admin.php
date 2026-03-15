<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Admin {

    public function __construct() {
        add_action( 'admin_menu',            array( $this, 'register_menus' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    public function register_menus() {
        add_menu_page(
            __( 'Turnverein', 'turnverein' ),
            __( 'Turnverein', 'turnverein' ),
            'manage_options',
            'turnverein',
            array( $this, 'page_dashboard' ),
            'dashicons-groups',
            30
        );

        add_submenu_page(
            'turnverein',
            __( 'Sportstätten', 'turnverein' ),
            __( 'Sportstätten', 'turnverein' ),
            'manage_options',
            'turnverein-sportstaetten',
            array( $this, 'page_sportstaetten' )
        );

        add_submenu_page(
            'turnverein',
            __( 'Trainer', 'turnverein' ),
            __( 'Trainer', 'turnverein' ),
            'manage_options',
            'turnverein-trainer',
            array( $this, 'page_trainer' )
        );

        add_submenu_page(
            'turnverein',
            __( 'Gruppen', 'turnverein' ),
            __( 'Gruppen', 'turnverein' ),
            'manage_options',
            'turnverein-gruppen',
            array( $this, 'page_gruppen' )
        );
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'turnverein' ) === false ) {
            return;
        }
        wp_enqueue_style(
            'turnverein-admin',
            TURNVEREIN_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            TURNVEREIN_VERSION
        );
    }

    // -------------------------------------------------------------------------
    // Dashboard
    // -------------------------------------------------------------------------

    public function page_dashboard() {
        $sportstaetten = new Turnverein_Sportstaetten();
        $trainer       = new Turnverein_Trainer();
        $gruppen       = new Turnverein_Gruppen();

        $count_ss  = count( $sportstaetten->get_all() );
        $count_tr  = count( $trainer->get_all() );
        $count_gr  = count( $gruppen->get_all() );

        include TURNVEREIN_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    // -------------------------------------------------------------------------
    // Sportstätten
    // -------------------------------------------------------------------------

    public function page_sportstaetten() {
        $repo = new Turnverein_Sportstaetten();

        // Handle form submissions.
        if ( isset( $_POST['tv_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce'] ) ), 'tv_sportstaette' ) ) {
            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save' ) {
                $id = (int) ( $_POST['id'] ?? 0 );
                if ( $id ) {
                    $repo->update( $id, $_POST );
                    echo '<div class="notice notice-success"><p>Sportstätte aktualisiert.</p></div>';
                } else {
                    $repo->create( $_POST );
                    echo '<div class="notice notice-success"><p>Sportstätte gespeichert.</p></div>';
                }
            } elseif ( $action === 'delete' ) {
                $repo->delete( (int) $_POST['id'] );
                echo '<div class="notice notice-success"><p>Sportstätte gelöscht.</p></div>';
            }
        }

        // Edit mode.
        $edit = null;
        if ( isset( $_GET['edit'] ) ) {
            $edit = $repo->get( (int) $_GET['edit'] );
        }

        $items = $repo->get_all();
        include TURNVEREIN_PLUGIN_DIR . 'admin/views/sportstaetten.php';
    }

    // -------------------------------------------------------------------------
    // Trainer
    // -------------------------------------------------------------------------

    public function page_trainer() {
        $repo = new Turnverein_Trainer();

        if ( isset( $_POST['tv_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce'] ) ), 'tv_trainer' ) ) {
            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save' ) {
                $id = (int) ( $_POST['id'] ?? 0 );
                if ( $id ) {
                    $repo->update( $id, $_POST );
                    echo '<div class="notice notice-success"><p>Trainer aktualisiert.</p></div>';
                } else {
                    $repo->create( $_POST );
                    echo '<div class="notice notice-success"><p>Trainer gespeichert.</p></div>';
                }
            } elseif ( $action === 'delete' ) {
                $repo->delete( (int) $_POST['id'] );
                echo '<div class="notice notice-success"><p>Trainer gelöscht.</p></div>';
            }
        }

        $edit  = null;
        if ( isset( $_GET['edit'] ) ) {
            $edit = $repo->get( (int) $_GET['edit'] );
        }

        $items = $repo->get_all();
        include TURNVEREIN_PLUGIN_DIR . 'admin/views/trainer.php';
    }

    // -------------------------------------------------------------------------
    // Gruppen
    // -------------------------------------------------------------------------

    public function page_gruppen() {
        $repo          = new Turnverein_Gruppen();
        $trainer_repo  = new Turnverein_Trainer();
        $ss_repo       = new Turnverein_Sportstaetten();

        if ( isset( $_POST['tv_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce'] ) ), 'tv_gruppen' ) ) {
            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save' ) {
                $id = (int) ( $_POST['id'] ?? 0 );
                if ( $id ) {
                    $repo->update( $id, $_POST );
                    echo '<div class="notice notice-success"><p>Gruppe aktualisiert.</p></div>';
                } else {
                    $repo->create( $_POST );
                    echo '<div class="notice notice-success"><p>Gruppe gespeichert.</p></div>';
                }
            } elseif ( $action === 'delete' ) {
                $repo->delete( (int) $_POST['id'] );
                echo '<div class="notice notice-success"><p>Gruppe gelöscht.</p></div>';
            }
        }

        $edit  = null;
        if ( isset( $_GET['edit'] ) ) {
            $edit = $repo->get( (int) $_GET['edit'] );
        }

        $items        = $repo->get_all();
        $trainer_list = $trainer_repo->get_all();
        $ss_list      = $ss_repo->get_all();
        include TURNVEREIN_PLUGIN_DIR . 'admin/views/gruppen.php';
    }
}
