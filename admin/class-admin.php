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

        add_submenu_page(
            'turnverein',
            __( 'Belegungsplan', 'turnverein' ),
            __( 'Belegungsplan', 'turnverein' ),
            'manage_options',
            'turnverein-belegungsplan',
            array( $this, 'page_belegungsplan' )
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

        $count_ss = count( $sportstaetten->get_all() );
        $count_tr = count( $trainer->get_all() );
        $count_gr = count( $gruppen->get_all() );

        include TURNVEREIN_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    // -------------------------------------------------------------------------
    // Sportstätten
    // -------------------------------------------------------------------------

    public function page_sportstaetten() {
        $repo  = new Turnverein_Sportstaetten();
        $tz    = new Turnverein_Trainingszeiten();

        // Delete a time slot via GET (simple link).
        if ( isset( $_GET['delete_slot'] ) && isset( $_GET['_wpnonce'] ) &&
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'tv_delete_slot' ) ) {
            $tz->delete( (int) $_GET['delete_slot'] );
            echo '<div class="notice notice-success"><p>Slot gelöscht.</p></div>';
        }

        // Handle Sportstätte form.
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
                $tz->delete_by_sportstaette( (int) $_POST['id'] );
                $repo->delete( (int) $_POST['id'] );
                echo '<div class="notice notice-success"><p>Sportstätte gelöscht.</p></div>';
            }
        }

        // Handle Trainingszeit form.
        if ( isset( $_POST['tv_nonce_tz'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce_tz'] ) ), 'tv_trainingszeit' ) ) {
            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save_slot' ) {
                $slot_id = (int) ( $_POST['slot_id'] ?? 0 );
                if ( $slot_id ) {
                    $tz->update( $slot_id, $_POST );
                    echo '<div class="notice notice-success"><p>Trainingszeit aktualisiert.</p></div>';
                } else {
                    $tz->create( $_POST );
                    echo '<div class="notice notice-success"><p>Trainingszeit gespeichert.</p></div>';
                }
            }
        }

        $edit      = null;
        $edit_slot = null;
        if ( isset( $_GET['edit'] ) ) {
            $edit = $repo->get( (int) $_GET['edit'] );
        }
        if ( isset( $_GET['edit_slot'] ) ) {
            $edit_slot = $tz->get( (int) $_GET['edit_slot'] );
        }

        $items        = $repo->get_all();
        $gruppen_list = ( new Turnverein_Gruppen() )->get_all();

        // Slots for the currently viewed Sportstätte.
        $slots          = array();
        $active_ss_id   = null;
        if ( isset( $_GET['slots'] ) ) {
            $active_ss_id = (int) $_GET['slots'];
            $slots        = $tz->get_by_sportstaette( $active_ss_id );
        } elseif ( $edit ) {
            $active_ss_id = $edit->id;
            $slots        = $tz->get_by_sportstaette( $edit->id );
        }

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

        $edit = null;
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
        $repo         = new Turnverein_Gruppen();
        $trainer_repo = new Turnverein_Trainer();
        $tz           = new Turnverein_Trainingszeiten();

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

        $edit = null;
        if ( isset( $_GET['edit'] ) ) {
            $edit = $repo->get( (int) $_GET['edit'] );
        }

        $items        = $repo->get_all();
        $trainer_list = $trainer_repo->get_all();

        // Show training times for the selected group.
        $gruppe_slots = array();
        if ( $edit ) {
            $gruppe_slots = $tz->get_by_gruppe( $edit->id );
        }

        include TURNVEREIN_PLUGIN_DIR . 'admin/views/gruppen.php';
    }

    // -------------------------------------------------------------------------
    // Belegungsplan
    // -------------------------------------------------------------------------

    public function page_belegungsplan() {
        $ss_repo = new Turnverein_Sportstaetten();
        $tz      = new Turnverein_Trainingszeiten();

        $all_ss   = $ss_repo->get_all();
        $all_slots = $tz->get_all_with_details();

        // Group slots by sportstaette_id and then by wochentag.
        $plan = array();
        foreach ( $all_slots as $slot ) {
            $plan[ $slot->sportstaette_id ][ $slot->wochentag ][] = $slot;
        }

        include TURNVEREIN_PLUGIN_DIR . 'admin/views/belegungsplan.php';
    }
}
