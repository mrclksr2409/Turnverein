<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Sportstaetten {

    private $table;

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'tv_sportstaetten';
    }

    public function get_all() {
        global $wpdb;
        return $wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY name ASC" );
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
            'name'         => sanitize_text_field( $data['name'] ?? '' ),
            'strasse'      => sanitize_text_field( $data['strasse'] ?? '' ),
            'plz'          => sanitize_text_field( $data['plz'] ?? '' ),
            'ort'          => sanitize_text_field( $data['ort'] ?? '' ),
            'kapazitaet'   => (int) ( $data['kapazitaet'] ?? 0 ),
            'beschreibung' => sanitize_textarea_field( $data['beschreibung'] ?? '' ),
        );
    }
}
