<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Trainer {

    private $table;

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'tv_trainer';
    }

    public function get_all() {
        global $wpdb;
        return $wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY nachname ASC, vorname ASC" );
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
            'vorname'  => sanitize_text_field( $data['vorname'] ?? '' ),
            'nachname' => sanitize_text_field( $data['nachname'] ?? '' ),
            'email'    => sanitize_email( $data['email'] ?? '' ),
            'telefon'  => sanitize_text_field( $data['telefon'] ?? '' ),
            'sportart' => sanitize_text_field( $data['sportart'] ?? '' ),
            'lizenz'   => sanitize_text_field( $data['lizenz'] ?? '' ),
        );
    }
}
