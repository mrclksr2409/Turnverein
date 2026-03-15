<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Trainingszeiten {

    private $table;

    public static $wochentage = array(
        1 => 'Montag',
        2 => 'Dienstag',
        3 => 'Mittwoch',
        4 => 'Donnerstag',
        5 => 'Freitag',
        6 => 'Samstag',
        7 => 'Sonntag',
    );

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'tv_trainingszeiten';
    }

    /** Alle Slots einer Sportstätte, sortiert nach Wochentag + Startzeit. */
    public function get_by_sportstaette( $sportstaette_id ) {
        global $wpdb;
        $gruppen_table = $wpdb->prefix . 'tv_gruppen';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT t.*, g.name AS gruppe_name
             FROM {$this->table} t
             LEFT JOIN {$gruppen_table} g ON t.gruppe_id = g.id
             WHERE t.sportstaette_id = %d
             ORDER BY t.wochentag ASC, t.startzeit ASC",
            $sportstaette_id
        ) );
    }

    /** Alle Slots einer Gruppe (alle Sportstätten). */
    public function get_by_gruppe( $gruppe_id ) {
        global $wpdb;
        $ss_table = $wpdb->prefix . 'tv_sportstaetten';

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT t.*, s.name AS sportstaette_name
             FROM {$this->table} t
             LEFT JOIN {$ss_table} s ON t.sportstaette_id = s.id
             WHERE t.gruppe_id = %d
             ORDER BY t.wochentag ASC, t.startzeit ASC",
            $gruppe_id
        ) );
    }

    /** Alle Slots aller Sportstätten (für den Belegungsplan). */
    public function get_all_with_details() {
        global $wpdb;
        $ss_table      = $wpdb->prefix . 'tv_sportstaetten';
        $gruppen_table = $wpdb->prefix . 'tv_gruppen';
        $pivot_table   = $wpdb->prefix . 'tv_gruppen_trainer';
        $trainer_table = $wpdb->prefix . 'tv_trainer';

        return $wpdb->get_results(
            "SELECT t.*,
                    s.name AS sportstaette_name,
                    g.name AS gruppe_name,
                    GROUP_CONCAT(
                        CONCAT(tr.vorname, ' ', tr.nachname)
                        ORDER BY tr.nachname, tr.vorname
                        SEPARATOR ', '
                    ) AS trainer_names,
                    GROUP_CONCAT(
                        tr.telefon
                        ORDER BY tr.nachname, tr.vorname
                        SEPARATOR ', '
                    ) AS trainer_telefone
             FROM {$this->table} t
             LEFT JOIN {$ss_table}      s  ON t.sportstaette_id = s.id
             LEFT JOIN {$gruppen_table} g  ON t.gruppe_id       = g.id
             LEFT JOIN {$pivot_table}   gt ON g.id              = gt.gruppe_id
             LEFT JOIN {$trainer_table} tr ON gt.trainer_id     = tr.id
             GROUP BY t.id
             ORDER BY t.sportstaette_id ASC, t.wochentag ASC, t.startzeit ASC"
        );
    }

    public function get( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id = %d", $id
        ) );
    }

    public function create( $data ) {
        global $wpdb;
        $wpdb->insert( $this->table, $this->sanitize( $data ) );
        return $wpdb->insert_id;
    }

    public function update( $id, $data ) {
        global $wpdb;
        return $wpdb->update( $this->table, $this->sanitize( $data ), array( 'id' => (int) $id ) );
    }

    public function delete( $id ) {
        global $wpdb;
        return $wpdb->delete( $this->table, array( 'id' => (int) $id ) );
    }

    /** Löscht alle Slots einer Sportstätte (z.B. beim Löschen der Sportstätte). */
    public function delete_by_sportstaette( $sportstaette_id ) {
        global $wpdb;
        return $wpdb->delete( $this->table, array( 'sportstaette_id' => (int) $sportstaette_id ) );
    }

    private function sanitize( $data ) {
        return array(
            'sportstaette_id' => (int) $data['sportstaette_id'],
            'gruppe_id'       => ! empty( $data['gruppe_id'] ) ? (int) $data['gruppe_id'] : null,
            'wochentag'       => (int) $data['wochentag'],
            'startzeit'       => $this->sanitize_time( $data['startzeit'] ?? '' ),
            'endzeit'         => $this->sanitize_time( $data['endzeit'] ?? '' ),
            'notiz'           => sanitize_text_field( $data['notiz'] ?? '' ),
        );
    }

    private function sanitize_time( $time ) {
        // Accept HH:MM or HH:MM:SS, return HH:MM:SS.
        if ( preg_match( '/^\d{1,2}:\d{2}(:\d{2})?$/', trim( $time ) ) ) {
            return strlen( $time ) === 5 ? $time . ':00' : trim( $time );
        }
        return '00:00:00';
    }
}
