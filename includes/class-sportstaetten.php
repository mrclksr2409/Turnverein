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
        return $wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY sortierung ASC, name ASC" );
    }

    public function get( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id ) );
    }

    public function create( $data ) {
        global $wpdb;
        $row               = $this->sanitize( $data );
        $row['sortierung'] = (int) $wpdb->get_var( "SELECT COALESCE( MAX( sortierung ), 0 ) + 1 FROM {$this->table}" );
        $wpdb->insert( $this->table, $row );
        return $wpdb->insert_id;
    }

    /**
     * Stores a new order. The array position of each ID becomes its sort value.
     *
     * @param int[] $ids Sportstätten IDs in the desired order.
     */
    public function save_order( $ids ) {
        global $wpdb;
        $position = 1;
        foreach ( (array) $ids as $id ) {
            $id = absint( $id );
            if ( ! $id ) {
                continue;
            }
            $wpdb->update( $this->table, array( 'sortierung' => $position ), array( 'id' => $id ), array( '%d' ), array( '%d' ) );
            $position++;
        }
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
