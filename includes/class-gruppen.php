<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Gruppen {

    private $table;
    private $pivot;

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'tv_gruppen';
        $this->pivot = $wpdb->prefix . 'tv_gruppen_trainer';
    }

    public function get_all() {
        global $wpdb;
        $trainer_table = $wpdb->prefix . 'tv_trainer';

        return $wpdb->get_results(
            "SELECT g.*,
                    GROUP_CONCAT(
                        CONCAT(t.vorname, ' ', t.nachname)
                        ORDER BY t.nachname, t.vorname
                        SEPARATOR ', '
                    ) AS trainer_names
             FROM {$this->table} g
             LEFT JOIN {$this->pivot} gt ON g.id = gt.gruppe_id
             LEFT JOIN {$trainer_table} t ON gt.trainer_id = t.id
             GROUP BY g.id
             ORDER BY g.name ASC"
        );
    }

    public function get( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id ) );
    }

    public function get_trainer_ids( $gruppe_id ) {
        global $wpdb;
        return $wpdb->get_col( $wpdb->prepare(
            "SELECT trainer_id FROM {$this->pivot} WHERE gruppe_id = %d ORDER BY trainer_id ASC",
            (int) $gruppe_id
        ) );
    }

    public function create( $data ) {
        global $wpdb;
        $wpdb->insert( $this->table, $this->sanitize( $data ) );
        $id = $wpdb->insert_id;
        $this->sync_trainers( $id, $data['trainer_ids'] ?? array() );
        return $id;
    }

    public function update( $id, $data ) {
        global $wpdb;
        $wpdb->update( $this->table, $this->sanitize( $data ), array( 'id' => (int) $id ) );
        $this->sync_trainers( $id, $data['trainer_ids'] ?? array() );
    }

    public function delete( $id ) {
        global $wpdb;
        $wpdb->delete( $this->pivot, array( 'gruppe_id' => (int) $id ) );
        return $wpdb->delete( $this->table, array( 'id' => (int) $id ) );
    }

    private function sync_trainers( $gruppe_id, $trainer_ids ) {
        global $wpdb;
        $gruppe_id = (int) $gruppe_id;

        $wpdb->delete( $this->pivot, array( 'gruppe_id' => $gruppe_id ) );

        if ( ! is_array( $trainer_ids ) ) {
            return;
        }
        foreach ( $trainer_ids as $tid ) {
            $tid = (int) $tid;
            if ( $tid > 0 ) {
                $wpdb->replace( $this->pivot, array(
                    'gruppe_id'  => $gruppe_id,
                    'trainer_id' => $tid,
                ) );
            }
        }
    }

    private function sanitize( $data ) {
        return array(
            'name'           => sanitize_text_field( $data['name'] ?? '' ),
            'min_alter'      => isset( $data['min_alter'] ) && $data['min_alter'] !== '' ? (int) $data['min_alter'] : null,
            'max_alter'      => isset( $data['max_alter'] ) && $data['max_alter'] !== '' ? (int) $data['max_alter'] : null,
            'max_mitglieder' => (int) ( $data['max_mitglieder'] ?? 0 ),
            'beschreibung'   => sanitize_textarea_field( $data['beschreibung'] ?? '' ),
        );
    }
}
