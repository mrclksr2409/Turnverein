<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Gruppen {

    private $table;

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'tv_gruppen';
    }

    public function get_all() {
        global $wpdb;
        $trainer_table = $wpdb->prefix . 'tv_trainer';

        return $wpdb->get_results(
            "SELECT g.*,
                    CONCAT(t.vorname, ' ', t.nachname) AS trainer_name
             FROM {$this->table} g
             LEFT JOIN {$trainer_table} t ON g.trainer_id = t.id
             ORDER BY g.name ASC"
        );
    }

    public function get( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id ) );
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

    private function sanitize( $data ) {
        return array(
            'name'           => sanitize_text_field( $data['name'] ?? '' ),
            'trainer_id'     => ! empty( $data['trainer_id'] ) ? (int) $data['trainer_id'] : null,
            'min_alter'      => isset( $data['min_alter'] ) && $data['min_alter'] !== '' ? (int) $data['min_alter'] : null,
            'max_alter'      => isset( $data['max_alter'] ) && $data['max_alter'] !== '' ? (int) $data['max_alter'] : null,
            'max_mitglieder' => (int) ( $data['max_mitglieder'] ?? 0 ),
            'beschreibung'   => sanitize_textarea_field( $data['beschreibung'] ?? '' ),
        );
    }
}
