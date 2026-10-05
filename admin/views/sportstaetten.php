<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">

<?php if ( $notice ) : ?>
    <div class="notice notice-<?php echo esc_attr( $notice[0] ); ?> is-dismissible">
        <p><?php echo esc_html( $notice[1] ); ?></p>
    </div>
<?php endif; ?>

<?php /* ================================================================
         MODUS: Liste aller Sportstätten
         ================================================================ */ ?>
<?php if ( $mode === 'list' ) : ?>

    <h1 class="wp-heading-inline">Sportstätten</h1>
    <a href="<?php echo esc_url( add_query_arg( 'action', 'neu', $base_url ) ); ?>"
       class="page-title-action">Neue Sportstätte</a>
    <hr class="wp-header-end">

    <?php if ( $items ) : ?>
    <p class="description">
        Reihenfolge per Drag &amp; Drop am Griff <span class="dashicons dashicons-menu" aria-hidden="true"></span> ändern – sie gilt auch für Belegungsplan und Shortcodes.
        <span id="tv-sort-status" class="tv-sort-status" role="status" aria-live="polite"></span>
    </p>
    <table class="wp-list-table widefat fixed striped tv-sortable-table">
        <thead>
            <tr>
                <th class="tv-col-sort"><span class="screen-reader-text">Sortieren</span></th>
                <th>Name</th>
                <th>Adresse</th>
                <th>Kapazität</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody id="tv-sportstaetten-sortable">
            <?php foreach ( $items as $item ) : ?>
            <tr data-id="<?php echo esc_attr( $item->id ); ?>">
                <td class="tv-col-sort">
                    <span class="tv-sort-handle dashicons dashicons-menu" title="Ziehen zum Sortieren"></span>
                </td>
                <td>
                    <strong>
                        <a href="<?php echo esc_url( add_query_arg( 'id', $item->id, $base_url ) ); ?>">
                            <?php echo esc_html( $item->name ); ?>
                        </a>
                    </strong>
                </td>
                <td><?php echo esc_html( trim( $item->strasse . ' ' . $item->plz . ' ' . $item->ort ) ?: '—' ); ?></td>
                <td><?php echo $item->kapazitaet ? esc_html( $item->kapazitaet ) . ' Pers.' : '—'; ?></td>
                <td class="tv-actions">
                    <a href="<?php echo esc_url( add_query_arg( 'id', $item->id, $base_url ) ); ?>"
                       class="button button-small">Öffnen</a>
                    <form method="post" style="display:inline"
                          onsubmit="return confirm('Sportstätte und alle Slots löschen?')">
                        <?php wp_nonce_field( 'tv_sportstaette', 'tv_nonce' ); ?>
                        <input type="hidden" name="tv_action" value="delete">
                        <input type="hidden" name="id" value="<?php echo esc_attr( $item->id ); ?>">
                        <button type="submit" class="button button-small tv-btn-delete">Löschen</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="tv-shortcode-box">
        <h2>Shortcodes</h2>
        <p>
            <label>Tabelle aller Sportstätten:
                <input type="text" class="tv-shortcode-input regular-text" readonly value="[tv_sportstaetten]">
            </label>
        </p>
        <p class="description">
            Optionen: <code>id="1"</code> (nur eine Sportstätte), <code>spalten="name,adresse,kapazitaet,beschreibung"</code>
            (Auswahl und Reihenfolge der Spalten).
        </p>
    </div>
    <?php else : ?>
        <p>Noch keine Sportstätten vorhanden. <a href="<?php echo esc_url( add_query_arg( 'action', 'neu', $base_url ) ); ?>">Erste Sportstätte anlegen</a></p>
    <?php endif; ?>

<?php /* ================================================================
         MODUS: Neue Sportstätte anlegen
         ================================================================ */ ?>
<?php elseif ( $mode === 'new' ) : ?>

    <h1 class="wp-heading-inline">Neue Sportstätte</h1>
    <a href="<?php echo esc_url( $base_url ); ?>" class="page-title-action">← Zurück zur Liste</a>
    <hr class="wp-header-end">

    <div class="tv-form-box" style="max-width:640px;margin-top:1rem">
        <form method="post">
            <?php wp_nonce_field( 'tv_sportstaette', 'tv_nonce' ); ?>
            <input type="hidden" name="tv_action" value="save">
            <?php echo tv_sportstaette_fields( null ); ?>
            <p class="submit">
                <button type="submit" class="button button-primary">Anlegen & Trainingszeiten einrichten</button>
                <a href="<?php echo esc_url( $base_url ); ?>" class="button">Abbrechen</a>
            </p>
        </form>
    </div>

<?php /* ================================================================
         MODUS: Detailansicht einer Sportstätte
         ================================================================ */ ?>
<?php else : ?>

    <?php /* Breadcrumb */ ?>
    <p class="tv-breadcrumb">
        <a href="<?php echo esc_url( $base_url ); ?>">← Alle Sportstätten</a>
    </p>

    <h1><?php echo esc_html( $ss->name ); ?></h1>

    <div class="tv-detail-layout">

        <div class="tv-shortcode-box">
            <label>Shortcode (Tabelle dieser Sportstätte):
                <input type="text" class="tv-shortcode-input regular-text" readonly
                       value="<?php echo esc_attr( '[tv_sportstaetten id="' . $ss->id . '"]' ); ?>">
            </label>
            <label>Belegungsplan:
                <input type="text" class="tv-shortcode-input regular-text" readonly
                       value="<?php echo esc_attr( '[tv_belegungsplan sportstaette_id="' . $ss->id . '"]' ); ?>">
            </label>
        </div>

        <?php /* ── Stammdaten (aufklappbar) ── */ ?>
        <details class="tv-details-box" <?php echo $notice && isset( $_GET['updated'] ) ? 'open' : ''; ?>>
            <summary>Stammdaten bearbeiten</summary>
            <div class="tv-details-content">
                <form method="post">
                    <?php wp_nonce_field( 'tv_sportstaette', 'tv_nonce' ); ?>
                    <input type="hidden" name="tv_action" value="save">
                    <input type="hidden" name="id" value="<?php echo esc_attr( $ss->id ); ?>">
                    <?php echo tv_sportstaette_fields( $ss ); ?>
                    <p class="submit">
                        <button type="submit" class="button button-primary">Speichern</button>
                    </p>
                </form>
                <form method="post"
                      onsubmit="return confirm('Sportstätte und alle Trainingszeiten löschen?')">
                    <?php wp_nonce_field( 'tv_sportstaette', 'tv_nonce' ); ?>
                    <input type="hidden" name="tv_action" value="delete">
                    <input type="hidden" name="id" value="<?php echo esc_attr( $ss->id ); ?>">
                    <button type="submit" class="button tv-btn-delete">Sportstätte löschen</button>
                </form>
            </div>
        </details>

        <?php /* ── Slot-Formular ── */ ?>
        <div class="tv-slot-form-box">
            <h2><?php echo $edit_slot ? 'Slot bearbeiten' : 'Neuen Slot hinzufügen'; ?></h2>
            <form method="post">
                <?php wp_nonce_field( 'tv_trainingszeit', 'tv_nonce_tz' ); ?>
                <input type="hidden" name="tv_action" value="save_slot">
                <input type="hidden" name="sportstaette_id" value="<?php echo esc_attr( $ss->id ); ?>">
                <?php if ( $edit_slot ) : ?>
                    <input type="hidden" name="slot_id" value="<?php echo esc_attr( $edit_slot->id ); ?>">
                <?php endif; ?>

                <div class="tv-slot-form-grid">
                    <label>
                        Wochentag *
                        <select name="wochentag" required>
                            <?php foreach ( Turnverein_Trainingszeiten::$wochentage as $nr => $tag ) : ?>
                                <option value="<?php echo esc_attr( $nr ); ?>"
                                    <?php selected( ( $edit_slot->wochentag ?? '' ), $nr ); ?>>
                                    <?php echo esc_html( $tag ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>
                        Von *
                        <input type="time" name="startzeit" required
                               value="<?php echo esc_attr( isset( $edit_slot->startzeit ) ? substr( $edit_slot->startzeit, 0, 5 ) : '' ); ?>">
                    </label>
                    <label>
                        Bis *
                        <input type="time" name="endzeit" required
                               value="<?php echo esc_attr( isset( $edit_slot->endzeit ) ? substr( $edit_slot->endzeit, 0, 5 ) : '' ); ?>">
                    </label>
                    <label>
                        Gruppe
                        <select name="gruppe_id" id="tv_slot_gruppe">
                            <option value="">— freier Slot —</option>
                            <?php foreach ( $gruppen_list as $g ) : ?>
                                <option value="<?php echo esc_attr( $g->id ); ?>"
                                    <?php selected( ( $edit_slot->gruppe_id ?? '' ), $g->id ); ?>>
                                    <?php echo esc_html( $g->name ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <?php
                    $slot_gruppe_id  = (int) ( $edit_slot->gruppe_id ?? 0 );
                    $slot_trainer_id = (int) ( $edit_slot->trainer_id ?? 0 );
                    $slot_trainers   = $trainer_map[ $slot_gruppe_id ] ?? array();
                    ?>
                    <label id="tv_slot_trainer_wrap" <?php echo count( $slot_trainers ) > 1 ? '' : 'hidden'; ?>>
                        Trainer
                        <select name="trainer_id" id="tv_slot_trainer">
                            <option value="">— alle Trainer der Gruppe —</option>
                            <?php foreach ( $slot_trainers as $tid => $tname ) : ?>
                                <option value="<?php echo esc_attr( $tid ); ?>" <?php selected( $slot_trainer_id, $tid ); ?>>
                                    <?php echo esc_html( $tname ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="tv-slot-notiz">
                        Notiz
                        <input type="text" name="notiz" placeholder="z.B. Halle 2"
                               value="<?php echo esc_attr( $edit_slot->notiz ?? '' ); ?>">
                    </label>
                </div>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $edit_slot ? 'Slot aktualisieren' : 'Slot speichern'; ?>
                    </button>
                    <?php if ( $edit_slot ) : ?>
                        <a href="<?php echo esc_url( add_query_arg( 'id', $ss->id, $base_url ) ); ?>" class="button">Abbrechen</a>
                    <?php endif; ?>
                </p>
            </form>
            <script>
            ( function () {
                var map     = <?php echo wp_json_encode( (object) array_map( fn( $t ) => array_map( null, array_keys( $t ), array_values( $t ) ), $trainer_map ) ); ?>;
                var gruppe  = document.getElementById( 'tv_slot_gruppe' );
                var wrap    = document.getElementById( 'tv_slot_trainer_wrap' );
                var trainer = document.getElementById( 'tv_slot_trainer' );

                gruppe.addEventListener( 'change', function () {
                    var list = map[ gruppe.value ] || [];
                    trainer.length = 1; // nur "alle Trainer der Gruppe" behalten
                    list.forEach( function ( t ) {
                        trainer.add( new Option( t[1], t[0] ) );
                    } );
                    trainer.value = '';
                    wrap.hidden   = list.length < 2;
                } );
            } )();
            </script>
        </div>

        <?php /* ── Slot-Liste ── */ ?>
        <div class="tv-slot-list-box">
            <h2>Belegung (<?php echo count( $slots ); ?> Slots)</h2>
            <?php if ( $slots ) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:110px">Tag</th>
                        <th style="width:70px">Von</th>
                        <th style="width:70px">Bis</th>
                        <th>Gruppe</th>
                        <th>Trainer</th>
                        <th>Notiz</th>
                        <th style="width:140px">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $slots as $slot ) : ?>
                    <tr <?php echo ( $edit_slot && $edit_slot->id === $slot->id ) ? 'class="tv-row-active"' : ''; ?>>
                        <td><?php echo esc_html( Turnverein_Trainingszeiten::$wochentage[ $slot->wochentag ] ?? $slot->wochentag ); ?></td>
                        <td><?php echo esc_html( substr( $slot->startzeit, 0, 5 ) ); ?></td>
                        <td><?php echo esc_html( substr( $slot->endzeit, 0, 5 ) ); ?></td>
                        <td><?php echo esc_html( $slot->gruppe_name ?: '— frei —' ); ?></td>
                        <td><?php echo esc_html( $slot->gruppe_id ? ( $slot->trainer_name ?: 'alle' ) : '' ); ?></td>
                        <td><?php echo esc_html( $slot->notiz ?: '' ); ?></td>
                        <td class="tv-actions">
                            <a href="<?php echo esc_url( add_query_arg( array( 'id' => $ss->id, 'edit_slot' => $slot->id ), $base_url ) ); ?>"
                               class="button button-small">Bearbeiten</a>
                            <a href="<?php echo esc_url( wp_nonce_url(
                                add_query_arg( array( 'id' => $ss->id, 'delete_slot' => $slot->id ), $base_url ),
                                'tv_delete_slot'
                            ) ); ?>"
                               class="button button-small tv-btn-delete"
                               onclick="return confirm('Slot löschen?')">Löschen</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else : ?>
                <p class="tv-empty-hint">Noch keine Trainingszeiten – füge oben den ersten Slot hinzu.</p>
            <?php endif; ?>
        </div>

    </div><!-- .tv-detail-layout -->

<?php endif; ?>
</div><!-- .wrap -->

<?php
/**
 * Gibt die Formularfelder einer Sportstätte aus.
 * Kein eigenes <form> – wird in Formulare eingebettet.
 */
function tv_sportstaette_fields( $item ) {
    ob_start(); ?>
    <table class="form-table">
        <tr>
            <th><label for="ss_name">Name *</label></th>
            <td><input type="text" id="ss_name" name="name" class="regular-text" required
                       value="<?php echo esc_attr( $item->name ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="ss_strasse">Straße</label></th>
            <td><input type="text" id="ss_strasse" name="strasse" class="regular-text"
                       value="<?php echo esc_attr( $item->strasse ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th>PLZ / Ort</th>
            <td>
                <input type="text" name="plz" class="small-text" placeholder="PLZ"
                       value="<?php echo esc_attr( $item->plz ?? '' ); ?>">
                <input type="text" name="ort" class="regular-text" placeholder="Ort"
                       value="<?php echo esc_attr( $item->ort ?? '' ); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="ss_kap">Kapazität</label></th>
            <td><input type="number" id="ss_kap" name="kapazitaet" class="small-text" min="0"
                       value="<?php echo esc_attr( $item->kapazitaet ?? 0 ); ?>"></td>
        </tr>
        <tr>
            <th><label for="ss_beschr">Beschreibung</label></th>
            <td><textarea id="ss_beschr" name="beschreibung" rows="3" class="large-text"><?php echo esc_textarea( $item->beschreibung ?? '' ); ?></textarea></td>
        </tr>
    </table>
    <?php
    return ob_get_clean();
}
