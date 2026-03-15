<?php
/**
 * Shortcodes für den Turnverein Manager.
 *
 * [tv_gruppe_trainer]          – Alle Gruppen mit Trainern und Telefonnummern
 * [tv_gruppe_trainer id="3"]   – Nur eine bestimmte Gruppe (nach ID)
 * [tv_gruppe_trainer gruppe="Fußball"] – Nur eine bestimmte Gruppe (nach Name)
 *
 * [tv_belegungsplan]                    – Alle Sportstätten
 * [tv_belegungsplan sportstaette_id="1"] – Nur eine bestimmte Sportstätte
 * [tv_belegungsplan tag="Montag"]        – Nur einen bestimmten Wochentag
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Shortcodes {

    public function __construct() {
        add_shortcode( 'tv_gruppe_trainer',  array( $this, 'shortcode_gruppe_trainer' ) );
        add_shortcode( 'tv_belegungsplan',   array( $this, 'shortcode_belegungsplan' ) );
    }

    // -------------------------------------------------------------------------
    // [tv_gruppe_trainer id="…" gruppe="…"]
    // -------------------------------------------------------------------------

    public function shortcode_gruppe_trainer( $atts ) {
        global $wpdb;

        $atts = shortcode_atts( array(
            'id'     => '',
            'gruppe' => '',
        ), $atts, 'tv_gruppe_trainer' );

        $gruppen_table = $wpdb->prefix . 'tv_gruppen';
        $pivot_table   = $wpdb->prefix . 'tv_gruppen_trainer';
        $trainer_table = $wpdb->prefix . 'tv_trainer';

        // Build WHERE clause.
        if ( ! empty( $atts['id'] ) ) {
            $where = $wpdb->prepare( 'WHERE g.id = %d', (int) $atts['id'] );
        } elseif ( ! empty( $atts['gruppe'] ) ) {
            $where = $wpdb->prepare( 'WHERE g.name = %s', sanitize_text_field( $atts['gruppe'] ) );
        } else {
            $where = '';
        }

        $rows = $wpdb->get_results(
            "SELECT g.name AS gruppe_name,
                    tr.vorname, tr.nachname, tr.telefon, tr.email
             FROM {$gruppen_table} g
             JOIN {$pivot_table}   gt ON g.id        = gt.gruppe_id
             JOIN {$trainer_table} tr ON gt.trainer_id = tr.id
             {$where}
             ORDER BY g.name ASC, tr.nachname ASC, tr.vorname ASC"
        );

        if ( empty( $rows ) ) {
            return '<p class="tv-no-data">Keine Trainer gefunden.</p>';
        }

        // Group rows by gruppe_name.
        $by_gruppe = array();
        foreach ( $rows as $row ) {
            $by_gruppe[ $row->gruppe_name ][] = $row;
        }

        ob_start();
        $this->enqueue_styles();
        ?>
        <div class="tv-gruppe-trainer">
        <?php foreach ( $by_gruppe as $gruppenname => $trainer_list ) : ?>
            <div class="tv-gt-gruppe">
                <h3 class="tv-gt-gruppe-name"><?php echo esc_html( $gruppenname ); ?></h3>
                <table class="tv-gt-table">
                    <tbody>
                    <?php foreach ( $trainer_list as $tr ) : ?>
                    <tr>
                        <td class="tv-gt-name"><?php echo esc_html( $tr->vorname . ' ' . $tr->nachname ); ?></td>
                        <td class="tv-gt-telefon">
                            <?php if ( $tr->telefon ) : ?>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $tr->telefon ) ); ?>">
                                    <?php echo esc_html( $tr->telefon ); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // [tv_belegungsplan sportstaette_id="…" tag="…"]
    // -------------------------------------------------------------------------

    public function shortcode_belegungsplan( $atts ) {
        global $wpdb;

        $atts = shortcode_atts( array(
            'sportstaette_id' => '',
            'tag'             => '',  // z.B. "Montag"
        ), $atts, 'tv_belegungsplan' );

        $tz    = new Turnverein_Trainingszeiten();
        $slots = $tz->get_all_with_details();

        if ( empty( $slots ) ) {
            return '<p class="tv-no-data">Keine Trainingszeiten eingetragen.</p>';
        }

        // Optional: filter by sportstaette_id.
        if ( ! empty( $atts['sportstaette_id'] ) ) {
            $filter_ss = (int) $atts['sportstaette_id'];
            $slots = array_filter( $slots, fn( $s ) => (int) $s->sportstaette_id === $filter_ss );
        }

        // Optional: filter by weekday name.
        $filter_tag = '';
        if ( ! empty( $atts['tag'] ) ) {
            $filter_tag = sanitize_text_field( $atts['tag'] );
            $tag_nr = array_search( $filter_tag, Turnverein_Trainingszeiten::$wochentage, true );
            if ( $tag_nr !== false ) {
                $slots = array_filter( $slots, fn( $s ) => (int) $s->wochentag === $tag_nr );
            }
        }

        if ( empty( $slots ) ) {
            return '<p class="tv-no-data">Keine Trainingszeiten für diese Auswahl gefunden.</p>';
        }

        // Group: wochentag → slots (sorted by startzeit).
        $by_tag = array();
        foreach ( $slots as $slot ) {
            $by_tag[ (int) $slot->wochentag ][] = $slot;
        }
        ksort( $by_tag );

        ob_start();
        $this->enqueue_styles();
        ?>
        <div class="tv-belegungsplan-sc">
            <table class="tv-bp-table">
                <tbody>
                <?php foreach ( $by_tag as $tag_nr => $tag_slots ) :
                    $tagname = Turnverein_Trainingszeiten::$wochentage[ $tag_nr ] ?? '';
                    usort( $tag_slots, fn( $a, $b ) => strcmp( $a->startzeit, $b->startzeit ) );
                ?>
                    <tr class="tv-bp-tag-header">
                        <td colspan="4"><strong><?php echo esc_html( $tagname ); ?></strong></td>
                    </tr>
                    <?php foreach ( $tag_slots as $slot ) : ?>
                    <tr class="tv-bp-slot-row">
                        <td class="tv-bp-zeit">
                            <?php echo esc_html(
                                substr( $slot->startzeit, 0, 5 ) . ' – ' .
                                substr( $slot->endzeit,   0, 5 ) . ' Uhr'
                            ); ?>
                        </td>
                        <td class="tv-bp-gruppe">
                            <?php echo esc_html( $slot->gruppe_name ?: ( $slot->notiz ?: 'Freier Slot' ) ); ?>
                            <?php if ( $slot->gruppe_id && $slot->notiz ) : ?>
                                <br><small><?php echo esc_html( $slot->notiz ); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="tv-bp-trainer"><?php echo esc_html( $slot->trainer_names ?: '' ); ?></td>
                        <td class="tv-bp-telefon">
                            <?php if ( $slot->trainer_telefone ) : ?>
                                <?php echo esc_html( $slot->trainer_telefone ); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // Frontend-CSS (einmalig einbinden)
    // -------------------------------------------------------------------------

    private static $styles_added = false;

    private function enqueue_styles() {
        if ( self::$styles_added ) {
            return;
        }
        self::$styles_added = true;
        add_action( 'wp_head', array( $this, 'print_styles' ), 20 );
    }

    public function print_styles() {
        ?>
        <style>
        /* --- tv_gruppe_trainer --- */
        .tv-gruppe-trainer { margin: 1.5em 0; }
        .tv-gt-gruppe { margin-bottom: 1.5em; }
        .tv-gt-gruppe-name { margin: 0 0 .5em; font-size: 1.1em; }
        .tv-gt-table { border-collapse: collapse; width: 100%; max-width: 480px; }
        .tv-gt-table td { padding: 5px 10px 5px 0; border-bottom: 1px solid #e5e7eb; }
        .tv-gt-table tr:last-child td { border-bottom: none; }
        .tv-gt-name { font-weight: 600; }
        .tv-gt-telefon { color: #555; }
        .tv-gt-telefon a { color: inherit; text-decoration: none; }
        .tv-gt-telefon a:hover { text-decoration: underline; }

        /* --- tv_belegungsplan --- */
        .tv-belegungsplan-sc { margin: 1.5em 0; overflow-x: auto; }
        .tv-bp-table { border-collapse: collapse; width: 100%; }
        .tv-bp-table td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .tv-bp-tag-header td {
            background: #f3f4f6;
            border-bottom: 2px solid #d1d5db;
            padding: 10px 12px 8px;
            font-size: .95em;
            letter-spacing: .02em;
        }
        .tv-bp-slot-row:last-of-type td { border-bottom: none; }
        .tv-bp-zeit    { white-space: nowrap; color: #555; width: 160px; }
        .tv-bp-gruppe  { min-width: 180px; }
        .tv-bp-trainer { width: 180px; }
        .tv-bp-telefon { width: 140px; }
        .tv-no-data { color: #888; }
        </style>
        <?php
    }
}
