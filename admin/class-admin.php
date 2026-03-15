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
        $repo   = new Turnverein_Sportstaetten();
        $tz     = new Turnverein_Trainingszeiten();
        $notice = array();

        // Aktuelle Sportstätte aus POST oder GET ermitteln.
        $ss_id = 0;
        if ( ! empty( $_POST['id'] ) ) {
            $ss_id = (int) $_POST['id'];
        } elseif ( ! empty( $_POST['sportstaette_id'] ) ) {
            $ss_id = (int) $_POST['sportstaette_id'];
        } elseif ( ! empty( $_GET['id'] ) ) {
            $ss_id = (int) $_GET['id'];
        }

        // ── POST: Sportstätte anlegen / aktualisieren / löschen ──────────────
        if ( isset( $_POST['tv_nonce'] ) &&
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce'] ) ), 'tv_sportstaette' ) ) {

            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save' ) {
                $id = (int) ( $_POST['id'] ?? 0 );
                if ( $id ) {
                    $repo->update( $id, $_POST );
                    $ss_id  = $id;
                    $notice = array( 'success', 'Sportstätte aktualisiert.' );
                } else {
                    $ss_id  = $repo->create( $_POST );
                    $notice = array( 'success', 'Sportstätte angelegt. Jetzt Trainingszeiten eintragen.' );
                }
            } elseif ( $action === 'delete' ) {
                $tz->delete_by_sportstaette( (int) $_POST['id'] );
                $repo->delete( (int) $_POST['id'] );
                $ss_id  = 0;
                $notice = array( 'success', 'Sportstätte gelöscht.' );
            }
        }

        // ── POST: Slot anlegen / aktualisieren ────────────────────────────────
        if ( isset( $_POST['tv_nonce_tz'] ) &&
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce_tz'] ) ), 'tv_trainingszeit' ) ) {

            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save_slot' ) {
                $slot_id = (int) ( $_POST['slot_id'] ?? 0 );
                if ( $slot_id ) {
                    $tz->update( $slot_id, $_POST );
                    $notice = array( 'success', 'Trainingszeit aktualisiert.' );
                } else {
                    $tz->create( $_POST );
                    $notice = array( 'success', 'Trainingszeit gespeichert.' );
                }
            }
        }

        // ── GET: Slot löschen ─────────────────────────────────────────────────
        if ( isset( $_GET['delete_slot'], $_GET['_wpnonce'] ) &&
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'tv_delete_slot' ) ) {
            $tz->delete( (int) $_GET['delete_slot'] );
            $notice = array( 'success', 'Trainingszeit gelöscht.' );
        }

        // ── Daten laden ───────────────────────────────────────────────────────
        $ss           = $ss_id ? $repo->get( $ss_id ) : null;
        $items        = $repo->get_all();
        $gruppen_list = ( new Turnverein_Gruppen() )->get_all();
        $slots        = $ss ? $tz->get_by_sportstaette( $ss->id ) : array();
        $edit_slot    = isset( $_GET['edit_slot'] ) ? $tz->get( (int) $_GET['edit_slot'] ) : null;

        // Modus: 'list' | 'new' | 'detail'
        $mode = 'list';
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'neu' ) {
            $mode = 'new';
        } elseif ( $ss ) {
            $mode = 'detail';
        }

        $base_url = admin_url( 'admin.php?page=turnverein-sportstaetten' );

        include TURNVEREIN_PLUGIN_DIR . 'admin/views/sportstaetten.php';
    }

    // -------------------------------------------------------------------------
    // Trainer
    // -------------------------------------------------------------------------

    public function page_trainer() {
        $repo     = new Turnverein_Trainer();
        $notice   = array();

        $id = 0;
        if ( ! empty( $_POST['id'] ) ) {
            $id = (int) $_POST['id'];
        } elseif ( ! empty( $_GET['id'] ) ) {
            $id = (int) $_GET['id'];
        }

        if ( isset( $_POST['tv_nonce'] ) &&
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce'] ) ), 'tv_trainer' ) ) {

            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save' ) {
                if ( $id ) {
                    $repo->update( $id, $_POST );
                    $notice = array( 'success', 'Trainer aktualisiert.' );
                } else {
                    $id     = $repo->create( $_POST );
                    $notice = array( 'success', 'Trainer angelegt.' );
                }
            } elseif ( $action === 'delete' ) {
                $repo->delete( $id );
                $id     = 0;
                $notice = array( 'success', 'Trainer gelöscht.' );
            }
        }

        $trainer  = $id ? $repo->get( $id ) : null;
        $items    = $repo->get_all();
        $base_url = admin_url( 'admin.php?page=turnverein-trainer' );

        $mode = 'list';
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'neu' ) {
            $mode = 'new';
        } elseif ( $trainer ) {
            $mode = 'detail';
        }

        include TURNVEREIN_PLUGIN_DIR . 'admin/views/trainer.php';
    }

    // -------------------------------------------------------------------------
    // Gruppen
    // -------------------------------------------------------------------------

    public function page_gruppen() {
        $repo         = new Turnverein_Gruppen();
        $trainer_repo = new Turnverein_Trainer();
        $tz           = new Turnverein_Trainingszeiten();
        $notice       = array();

        $id = 0;
        if ( ! empty( $_POST['id'] ) ) {
            $id = (int) $_POST['id'];
        } elseif ( ! empty( $_GET['id'] ) ) {
            $id = (int) $_GET['id'];
        }

        if ( isset( $_POST['tv_nonce'] ) &&
             wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tv_nonce'] ) ), 'tv_gruppen' ) ) {

            $action = sanitize_text_field( $_POST['tv_action'] ?? '' );

            if ( $action === 'save' ) {
                if ( $id ) {
                    $repo->update( $id, $_POST );
                    $notice = array( 'success', 'Gruppe aktualisiert.' );
                } else {
                    $id     = $repo->create( $_POST );
                    $notice = array( 'success', 'Gruppe angelegt.' );
                }
            } elseif ( $action === 'delete' ) {
                $repo->delete( $id );
                $id     = 0;
                $notice = array( 'success', 'Gruppe gelöscht.' );
            }
        }

        $gruppe       = $id ? $repo->get( $id ) : null;
        $items        = $repo->get_all();
        $trainer_list = $trainer_repo->get_all();
        $gruppe_slots = $gruppe ? $tz->get_by_gruppe( $gruppe->id ) : array();
        $base_url     = admin_url( 'admin.php?page=turnverein-gruppen' );

        $mode = 'list';
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'neu' ) {
            $mode = 'new';
        } elseif ( $gruppe ) {
            $mode = 'detail';
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
