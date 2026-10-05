<?php
/**
 * Shortcodes für den Turnverein Manager.
 *
 * [tv_gruppe_trainer]          – Alle Gruppen mit Trainern und Telefonnummern
 * [tv_gruppe_trainer id="3"]   – Nur eine bestimmte Gruppe (nach ID)
 * [tv_gruppe_trainer gruppe="Fußball"] – Nur eine bestimmte Gruppe (nach Name)
 *   Optionen: bild="ja|nein" (Standard: ja), email="ja|nein" (Standard: nein),
 *             titel="ja|nein|Eigener Text" (Gruppenüberschrift, Standard: ja)
 *
 * [tv_sportstaetten]          – Tabelle aller Sportstätten (in der Admin-Reihenfolge)
 * [tv_sportstaetten id="1"]   – Nur eine bestimmte Sportstätte
 *   Optionen: spalten="name,adresse,kapazitaet,beschreibung" (Auswahl/Reihenfolge),
 *             titel="Eigener Text" (Überschrift über der Tabelle)
 *
 * [tv_belegungsplan]                    – Vollständiger Plan, je Sportstätte ein Abschnitt
 * [tv_belegungsplan sportstaette_id="1"] – Plan einer Sportstätte
 * [tv_belegungsplan gruppe_id="3"]       – Trainingszeiten einer Gruppe (alternativ gruppe="Name")
 *   Optionen: tag="Montag", titel="ja|nein|Eigener Text", telefon="ja|nein"
 *
 * Eigener Titel: ersetzt bei einer einzelnen Gruppe/Sportstätte deren Namen,
 * sonst erscheint er als Überschrift über der gesamten Ausgabe.
 * ebene="h2" … "h6" legt die Überschriften-Ebene des eigenen Titels fest (Standard: h3).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_Shortcodes {

    public function __construct() {
        add_shortcode( 'tv_gruppe_trainer',  array( $this, 'shortcode_gruppe_trainer' ) );
        add_shortcode( 'tv_belegungsplan',   array( $this, 'shortcode_belegungsplan' ) );
        add_shortcode( 'tv_sportstaetten',   array( $this, 'shortcode_sportstaetten' ) );
    }

    // -------------------------------------------------------------------------
    // [tv_gruppe_trainer id="…" gruppe="…"]
    // -------------------------------------------------------------------------

    public function shortcode_gruppe_trainer( $atts ) {
        global $wpdb;

        $atts = shortcode_atts( array(
            'id'     => '',
            'gruppe' => '',
            'bild'   => 'ja',
            'email'  => 'nein',
            'titel'  => 'ja',
            'ebene'  => 'h3',
        ), $atts, 'tv_gruppe_trainer' );

        $show_bild  = $this->is_truthy( $atts['bild'] );
        $show_email = $this->is_truthy( $atts['email'] );
        $titel      = $this->parse_titel( $atts['titel'] );
        $tag        = $this->heading_tag( $atts['ebene'] );

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
                    tr.vorname, tr.nachname, tr.telefon, tr.email, tr.bild_id
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
        <?php
        // A custom title replaces the group name for a single group, otherwise it is shown above all groups.
        $single = 1 === count( $by_gruppe );
        if ( '' !== $titel['text'] && ! $single ) {
            $this->render_heading( $titel['text'], $tag, 'tv-sc-titel' );
        }
        ?>
        <?php foreach ( $by_gruppe as $gruppenname => $trainer_list ) : ?>
            <div class="tv-gt-gruppe">
                <?php
                if ( '' !== $titel['text'] && $single ) {
                    $this->render_heading( $titel['text'], $tag, 'tv-gt-gruppe-name' );
                } elseif ( $titel['show'] ) {
                    $this->render_heading( $gruppenname, 'h3', 'tv-gt-gruppe-name' );
                }
                ?>
                <table class="tv-gt-table">
                    <tbody>
                    <?php foreach ( $trainer_list as $tr ) : ?>
                    <tr>
                        <?php if ( $show_bild ) : ?>
                        <td class="tv-gt-bild">
                            <?php echo Turnverein_Trainer::get_bild_html( $tr, 'thumbnail', array( 'class' => 'tv-gt-img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
                        </td>
                        <?php endif; ?>
                        <td class="tv-gt-name"><?php echo esc_html( $tr->vorname . ' ' . $tr->nachname ); ?></td>
                        <td class="tv-gt-telefon">
                            <?php if ( $tr->telefon ) : ?>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $tr->telefon ) ); ?>">
                                    <?php echo esc_html( $tr->telefon ); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <?php if ( $show_email ) : ?>
                        <td class="tv-gt-email">
                            <?php if ( $tr->email ) : ?>
                                <a href="<?php echo esc_url( 'mailto:' . antispambot( $tr->email ) ); ?>"><?php echo esc_html( antispambot( $tr->email ) ); ?></a>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
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
    // [tv_belegungsplan sportstaette_id="…" gruppe_id="…" gruppe="…" tag="…"]
    // -------------------------------------------------------------------------

    public function shortcode_belegungsplan( $atts ) {
        $atts = shortcode_atts( array(
            'sportstaette_id' => '',
            'gruppe_id'       => '',
            'gruppe'          => '',    // group name, alternative to gruppe_id
            'tag'             => '',    // e.g. "Montag"
            'titel'           => 'ja',  // "ja", "nein" or a custom heading text
            'ebene'           => 'h3',  // heading level of a custom title
            'telefon'         => 'ja',  // trainer phone column
        ), $atts, 'tv_belegungsplan' );

        $titel        = $this->parse_titel( $atts['titel'] );
        $tag          = $this->heading_tag( $atts['ebene'] );
        $show_telefon = $this->is_truthy( $atts['telefon'] );

        $tz    = new Turnverein_Trainingszeiten();
        $slots = $tz->get_all_with_details();

        // Optional: filter by weekday name.
        if ( '' !== $atts['tag'] ) {
            $tag_nr = array_search( sanitize_text_field( $atts['tag'] ), Turnverein_Trainingszeiten::$wochentage, true );
            if ( false !== $tag_nr ) {
                $slots = array_filter( $slots, fn( $s ) => (int) $s->wochentag === $tag_nr );
            }
        }

        // ── Plan of a single group ────────────────────────────────────────────
        if ( '' !== $atts['gruppe_id'] || '' !== $atts['gruppe'] ) {
            $gruppe = $this->find_gruppe( $atts['gruppe_id'], $atts['gruppe'] );
            if ( ! $gruppe ) {
                return '<p class="tv-no-data">Gruppe nicht gefunden.</p>';
            }

            $slots = array_filter( $slots, fn( $s ) => (int) $s->gruppe_id === (int) $gruppe->id );

            ob_start();
            $this->enqueue_styles();
            ?>
            <div class="tv-belegungsplan-sc tv-bp-mode-gruppe">
                <?php
                if ( '' !== $titel['text'] ) {
                    $this->render_heading( $titel['text'], $tag, 'tv-bp-title' );
                } elseif ( $titel['show'] ) {
                    $this->render_heading( $gruppe->name, 'h3', 'tv-bp-title' );
                }
                ?>
                <?php $this->render_plan_table( $slots, 'gruppe', $show_telefon ); ?>
            </div>
            <?php
            return ob_get_clean();
        }

        // ── Plan per facility (all, or a single one) ──────────────────────────
        $sportstaetten = ( new Turnverein_Sportstaetten() )->get_all();
        if ( '' !== $atts['sportstaette_id'] ) {
            $filter_ss     = absint( $atts['sportstaette_id'] );
            $sportstaetten = array_filter( $sportstaetten, fn( $ss ) => (int) $ss->id === $filter_ss );
        }

        if ( empty( $sportstaetten ) ) {
            return '<p class="tv-no-data">Keine Sportstätte gefunden.</p>';
        }

        $by_ss = array();
        foreach ( $slots as $slot ) {
            $by_ss[ (int) $slot->sportstaette_id ][] = $slot;
        }

        $single = 1 === count( $sportstaetten );

        ob_start();
        $this->enqueue_styles();
        ?>
        <div class="tv-belegungsplan-sc">
        <?php
        // A custom title replaces the facility name for a single facility, otherwise it is shown above the plan.
        if ( '' !== $titel['text'] && ! $single ) {
            $this->render_heading( $titel['text'], $tag, 'tv-sc-titel' );
        }
        ?>
        <?php foreach ( $sportstaetten as $ss ) :
            $ss_slots = $by_ss[ (int) $ss->id ] ?? array();
            // In the full plan, facilities without training times are skipped.
            if ( ! $single && empty( $ss_slots ) ) {
                continue;
            }
            $adresse = trim( $ss->strasse . ', ' . trim( $ss->plz . ' ' . $ss->ort ), ', ' );
        ?>
            <div class="tv-bp-section">
                <?php if ( $titel['show'] ) : ?>
                    <?php
                    if ( '' !== $titel['text'] && $single ) {
                        $this->render_heading( $titel['text'], $tag, 'tv-bp-title' );
                    } else {
                        $this->render_heading( $ss->name, 'h3', 'tv-bp-title' );
                    }
                    ?>
                    <?php if ( $adresse ) : ?>
                        <p class="tv-bp-adresse"><?php echo esc_html( $adresse ); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php $this->render_plan_table( $ss_slots, 'sportstaette', $show_telefon ); ?>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Outputs a weekday-grouped schedule table.
     *
     * @param object[] $slots        Slots from Turnverein_Trainingszeiten::get_all_with_details().
     * @param string   $context      'sportstaette' shows the group column, 'gruppe' the facility column.
     * @param bool     $show_telefon Whether to show the phone column.
     */
    private function render_plan_table( $slots, $context, $show_telefon ) {
        if ( empty( $slots ) ) {
            echo '<p class="tv-no-data">Keine Trainingszeiten eingetragen.</p>';
            return;
        }

        $by_tag = array();
        foreach ( $slots as $slot ) {
            $by_tag[ (int) $slot->wochentag ][] = $slot;
        }
        ksort( $by_tag );

        $colspan = $show_telefon ? 4 : 3;
        ?>
        <table class="tv-bp-table">
            <tbody>
            <?php foreach ( $by_tag as $tag_nr => $tag_slots ) :
                $tagname = Turnverein_Trainingszeiten::$wochentage[ $tag_nr ] ?? '';
                usort( $tag_slots, fn( $a, $b ) => strcmp( $a->startzeit, $b->startzeit ) );
            ?>
                <tr class="tv-bp-tag-header">
                    <td colspan="<?php echo esc_attr( $colspan ); ?>"><strong><?php echo esc_html( $tagname ); ?></strong></td>
                </tr>
                <?php foreach ( $tag_slots as $slot ) : ?>
                <tr class="tv-bp-slot-row">
                    <td class="tv-bp-zeit">
                        <?php echo esc_html(
                            substr( $slot->startzeit, 0, 5 ) . ' – ' .
                            substr( $slot->endzeit,   0, 5 ) . ' Uhr'
                        ); ?>
                    </td>
                    <?php if ( 'gruppe' === $context ) : ?>
                    <td class="tv-bp-sportstaette">
                        <?php echo esc_html( $slot->sportstaette_name ); ?>
                        <?php if ( $slot->notiz ) : ?>
                            <br><small><?php echo esc_html( $slot->notiz ); ?></small>
                        <?php endif; ?>
                    </td>
                    <?php else : ?>
                    <td class="tv-bp-gruppe">
                        <?php echo esc_html( $slot->gruppe_name ?: ( $slot->notiz ?: 'Freier Slot' ) ); ?>
                        <?php if ( $slot->gruppe_id && $slot->notiz ) : ?>
                            <br><small><?php echo esc_html( $slot->notiz ); ?></small>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                    <td class="tv-bp-trainer"><?php echo esc_html( $slot->trainer_names ?: '' ); ?></td>
                    <?php if ( $show_telefon ) : ?>
                    <td class="tv-bp-telefon"><?php echo esc_html( $slot->trainer_telefone ?: '' ); ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Finds a group by ID or (fallback) by name.
     *
     * @param string $id   Group ID.
     * @param string $name Group name.
     * @return object|null
     */
    private function find_gruppe( $id, $name ) {
        global $wpdb;
        $repo = new Turnverein_Gruppen();

        if ( '' !== $id ) {
            return $repo->get( absint( $id ) );
        }

        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}tv_gruppen WHERE name = %s",
            sanitize_text_field( $name )
        ) );
    }

    // -------------------------------------------------------------------------
    // [tv_sportstaetten id="…" spalten="…"]
    // -------------------------------------------------------------------------

    public function shortcode_sportstaetten( $atts ) {
        $atts = shortcode_atts( array(
            'id'      => '',
            'spalten' => 'name,adresse,kapazitaet',
            'titel'   => '',
            'ebene'   => 'h3',
        ), $atts, 'tv_sportstaetten' );

        $titel = $this->parse_titel( $atts['titel'] );

        $available = array(
            'name'         => 'Sportstätte',
            'adresse'      => 'Adresse',
            'kapazitaet'   => 'Kapazität',
            'beschreibung' => 'Beschreibung',
        );

        $spalten = array_values( array_intersect(
            array_map( 'trim', explode( ',', strtolower( $atts['spalten'] ) ) ),
            array_keys( $available )
        ) );
        if ( empty( $spalten ) ) {
            $spalten = array( 'name', 'adresse', 'kapazitaet' );
        }

        $repo = new Turnverein_Sportstaetten();
        if ( ! empty( $atts['id'] ) ) {
            $item  = $repo->get( absint( $atts['id'] ) );
            $items = $item ? array( $item ) : array();
        } else {
            $items = $repo->get_all();
        }

        if ( empty( $items ) ) {
            return '<p class="tv-no-data">Keine Sportstätten gefunden.</p>';
        }

        ob_start();
        $this->enqueue_styles();
        ?>
        <div class="tv-sportstaetten-sc">
            <?php
            if ( '' !== $titel['text'] ) {
                $this->render_heading( $titel['text'], $this->heading_tag( $atts['ebene'] ), 'tv-sc-titel' );
            }
            ?>
            <table class="tv-ss-table">
                <thead>
                    <tr>
                    <?php foreach ( $spalten as $spalte ) : ?>
                        <th class="tv-ss-<?php echo esc_attr( $spalte ); ?>"><?php echo esc_html( $available[ $spalte ] ); ?></th>
                    <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $items as $ss ) : ?>
                    <tr>
                    <?php foreach ( $spalten as $spalte ) : ?>
                        <td class="tv-ss-<?php echo esc_attr( $spalte ); ?>" data-label="<?php echo esc_attr( $available[ $spalte ] ); ?>">
                            <?php
                            switch ( $spalte ) {
                                case 'name':
                                    echo '<strong>' . esc_html( $ss->name ) . '</strong>';
                                    break;
                                case 'adresse':
                                    $ort = trim( $ss->plz . ' ' . $ss->ort );
                                    echo esc_html( $ss->strasse );
                                    if ( $ss->strasse && $ort ) {
                                        echo '<br>';
                                    }
                                    echo esc_html( $ort );
                                    break;
                                case 'kapazitaet':
                                    echo $ss->kapazitaet ? esc_html( $ss->kapazitaet . ' Pers.' ) : '';
                                    break;
                                case 'beschreibung':
                                    echo nl2br( esc_html( $ss->beschreibung ) );
                                    break;
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Interprets the "titel" attribute.
     *
     * "ja"/empty shows the automatic headings, "nein" hides them,
     * any other value is used as a custom heading text.
     *
     * @param string $value Attribute value.
     * @return array{show: bool, text: string}
     */
    private function parse_titel( $value ) {
        $value = trim( (string) $value );
        $lower = strtolower( $value );

        if ( '' === $value || in_array( $lower, array( 'ja', 'yes', '1', 'true', 'on' ), true ) ) {
            return array( 'show' => true, 'text' => '' );
        }
        if ( in_array( $lower, array( 'nein', 'no', '0', 'false', 'off' ), true ) ) {
            return array( 'show' => false, 'text' => '' );
        }
        return array( 'show' => true, 'text' => sanitize_text_field( $value ) );
    }

    /**
     * Returns a safe heading tag (h2–h6), falling back to h3.
     *
     * @param string $value Attribute value.
     * @return string
     */
    private function heading_tag( $value ) {
        $value = strtolower( trim( (string) $value ) );
        return in_array( $value, array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $value : 'h3';
    }

    /**
     * Outputs a heading.
     *
     * @param string $text  Heading text.
     * @param string $tag   Heading tag (validated via heading_tag()).
     * @param string $class CSS class.
     */
    private function render_heading( $text, $tag, $class ) {
        printf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            tag_escape( $tag ),
            esc_attr( $class ),
            esc_html( $text )
        );
    }

    /**
     * Interprets shortcode yes/no values ("ja", "yes", "1", "true").
     *
     * @param string $value Attribute value.
     * @return bool
     */
    private function is_truthy( $value ) {
        return in_array( strtolower( trim( (string) $value ) ), array( 'ja', 'yes', '1', 'true', 'on' ), true );
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

        // Shortcodes render after wp_head, so the style is enqueued late and printed in the footer.
        wp_register_style( 'turnverein-frontend', false, array(), TURNVEREIN_VERSION );
        wp_add_inline_style( 'turnverein-frontend', $this->get_styles() );
        wp_enqueue_style( 'turnverein-frontend' );
    }

    private function get_styles() {
        ob_start();
        ?>
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
        .tv-gt-table td { vertical-align: middle; }
        .tv-gt-bild { width: 64px; }
        .tv-gt-img { display: block; width: 56px; height: 56px; object-fit: cover; border-radius: 50%; }
        .tv-gt-email a { color: inherit; }

        /* --- tv_sportstaetten --- */
        .tv-sportstaetten-sc { margin: 1.5em 0; overflow-x: auto; }
        .tv-ss-table { border-collapse: collapse; width: 100%; }
        .tv-ss-table th,
        .tv-ss-table td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        .tv-ss-table thead th { background: #f3f4f6; border-bottom: 2px solid #d1d5db; }
        .tv-ss-table tbody tr:last-child td { border-bottom: none; }
        .tv-ss-kapazitaet { white-space: nowrap; }
        @media (max-width: 600px) {
            .tv-ss-table thead { display: none; }
            .tv-ss-table tr { display: block; border-bottom: 1px solid #e5e7eb; padding: 6px 0; }
            .tv-ss-table td { display: block; border: none; padding: 4px 0; }
            .tv-ss-table td:not(.tv-ss-name)::before { content: attr(data-label) ": "; font-weight: 600; }
        }

        /* --- tv_belegungsplan --- */
        .tv-belegungsplan-sc { margin: 1.5em 0; overflow-x: auto; }
        .tv-bp-section { margin-bottom: 2em; }
        .tv-bp-title { margin: 0 0 .25em; font-size: 1.1em; }
        .tv-bp-adresse { margin: 0 0 .5em; color: #666; font-size: .9em; }
        .tv-bp-sportstaette { min-width: 180px; }
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
        .tv-sc-titel { margin: 0 0 .75em; }
        .tv-no-data { color: #888; }
        <?php
        return ob_get_clean();
    }
}
