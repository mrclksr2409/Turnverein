<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">

<?php if ( $notice ) : ?>
    <div class="notice notice-<?php echo esc_attr( $notice[0] ); ?> is-dismissible">
        <p><?php echo esc_html( $notice[1] ); ?></p>
    </div>
<?php endif; ?>

<?php /* ================================================================
         MODUS: Liste
         ================================================================ */ ?>
<?php if ( $mode === 'list' ) : ?>

    <h1 class="wp-heading-inline">Gruppen</h1>
    <a href="<?php echo esc_url( add_query_arg( 'action', 'neu', $base_url ) ); ?>"
       class="page-title-action">Neue Gruppe</a>
    <hr class="wp-header-end">

    <?php if ( $items ) : ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Gruppe</th>
                <th>Trainer</th>
                <th>Alter</th>
                <th>Max. Mitglieder</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $items as $item ) : ?>
            <tr>
                <td>
                    <strong>
                        <a href="<?php echo esc_url( add_query_arg( 'id', $item->id, $base_url ) ); ?>">
                            <?php echo esc_html( $item->name ); ?>
                        </a>
                    </strong>
                </td>
                <td><?php echo esc_html( $item->trainer_names ?: '—' ); ?></td>
                <td><?php
                    if ( $item->min_alter !== null && $item->max_alter !== null ) echo esc_html( $item->min_alter . '–' . $item->max_alter . ' J.' );
                    elseif ( $item->min_alter !== null ) echo 'ab ' . esc_html( $item->min_alter ) . ' J.';
                    elseif ( $item->max_alter !== null ) echo 'bis ' . esc_html( $item->max_alter ) . ' J.';
                    else echo '—';
                ?></td>
                <td><?php echo $item->max_mitglieder ? esc_html( $item->max_mitglieder ) : '—'; ?></td>
                <td class="tv-actions">
                    <a href="<?php echo esc_url( add_query_arg( 'id', $item->id, $base_url ) ); ?>"
                       class="button button-small">Öffnen</a>
                    <form method="post" style="display:inline"
                          onsubmit="return confirm('Gruppe wirklich löschen?')">
                        <?php wp_nonce_field( 'tv_gruppen', 'tv_nonce' ); ?>
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
            <label>Trainer aller Gruppen:
                <input type="text" class="tv-shortcode-input regular-text" readonly value="[tv_gruppe_trainer]">
            </label>
        </p>
        <p>
            <label>Trainingszeiten einer Gruppe:
                <input type="text" class="tv-shortcode-input regular-text" readonly value='[tv_belegungsplan gruppe_id="ID"]'>
            </label>
        </p>
        <p class="description">
            Optionen: <code>id="3"</code> oder <code>gruppe="Name"</code> (nur eine Gruppe), <code>bild="nein"</code> (ohne Bilder),
            <code>email="ja"</code> (E-Mail-Adresse anzeigen), <code>titel="Eigene Überschrift"</code> oder <code>titel="nein"</code>,
            <code>ebene="h2"</code> (Ebene der eigenen Überschrift).
        </p>
    </div>
    <?php else : ?>
        <p>Noch keine Gruppen vorhanden. <a href="<?php echo esc_url( add_query_arg( 'action', 'neu', $base_url ) ); ?>">Erste Gruppe anlegen</a></p>
    <?php endif; ?>

<?php /* ================================================================
         MODUS: Neu anlegen
         ================================================================ */ ?>
<?php elseif ( $mode === 'new' ) : ?>

    <h1 class="wp-heading-inline">Neue Gruppe</h1>
    <a href="<?php echo esc_url( $base_url ); ?>" class="page-title-action">← Zurück zur Liste</a>
    <hr class="wp-header-end">

    <div class="tv-form-box" style="max-width:640px;margin-top:1rem">
        <form method="post">
            <?php wp_nonce_field( 'tv_gruppen', 'tv_nonce' ); ?>
            <input type="hidden" name="tv_action" value="save">
            <?php echo tv_gruppen_fields( null, $trainer_list, array() ); ?>
            <p class="submit">
                <button type="submit" class="button button-primary">Anlegen</button>
                <a href="<?php echo esc_url( $base_url ); ?>" class="button">Abbrechen</a>
            </p>
        </form>
    </div>

<?php /* ================================================================
         MODUS: Detailansicht
         ================================================================ */ ?>
<?php else : ?>

    <p class="tv-breadcrumb"><a href="<?php echo esc_url( $base_url ); ?>">← Alle Gruppen</a></p>
    <h1><?php echo esc_html( $gruppe->name ); ?></h1>

    <div class="tv-detail-layout">

        <div class="tv-shortcode-box">
            <label>Shortcode (Trainer dieser Gruppe):
                <input type="text" class="tv-shortcode-input regular-text" readonly
                       value="<?php echo esc_attr( '[tv_gruppe_trainer id="' . $gruppe->id . '"]' ); ?>">
            </label>
            <label>Shortcode (Trainingszeiten dieser Gruppe):
                <input type="text" class="tv-shortcode-input regular-text" readonly
                       value="<?php echo esc_attr( '[tv_belegungsplan gruppe_id="' . $gruppe->id . '"]' ); ?>">
            </label>
        </div>

        <details class="tv-details-box" <?php echo ( $notice && strpos( $notice[1], 'aktualisiert' ) !== false ) ? 'open' : ''; ?>>
            <summary>Stammdaten bearbeiten</summary>
            <div class="tv-details-content">
                <form method="post">
                    <?php wp_nonce_field( 'tv_gruppen', 'tv_nonce' ); ?>
                    <input type="hidden" name="tv_action" value="save">
                    <input type="hidden" name="id" value="<?php echo esc_attr( $gruppe->id ); ?>">
                    <?php echo tv_gruppen_fields( $gruppe, $trainer_list, $selected_tr_ids ); ?>
                    <p class="submit">
                        <button type="submit" class="button button-primary">Speichern</button>
                    </p>
                </form>
                <form method="post"
                      onsubmit="return confirm('Gruppe wirklich löschen?')">
                    <?php wp_nonce_field( 'tv_gruppen', 'tv_nonce' ); ?>
                    <input type="hidden" name="tv_action" value="delete">
                    <input type="hidden" name="id" value="<?php echo esc_attr( $gruppe->id ); ?>">
                    <button type="submit" class="button tv-btn-delete">Gruppe löschen</button>
                </form>
            </div>
        </details>

        <div class="tv-slot-list-box">
            <h2>Trainingszeiten (<?php echo count( $gruppe_slots ); ?>)</h2>
            <?php if ( $gruppe_slots ) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:110px">Tag</th>
                        <th style="width:70px">Von</th>
                        <th style="width:70px">Bis</th>
                        <th>Sportstätte</th>
                        <th>Notiz</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $gruppe_slots as $slot ) : ?>
                    <tr>
                        <td><?php echo esc_html( Turnverein_Trainingszeiten::$wochentage[ $slot->wochentag ] ?? $slot->wochentag ); ?></td>
                        <td><?php echo esc_html( substr( $slot->startzeit, 0, 5 ) ); ?></td>
                        <td><?php echo esc_html( substr( $slot->endzeit, 0, 5 ) ); ?></td>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&id=' . $slot->sportstaette_id ) ); ?>">
                                <?php echo esc_html( $slot->sportstaette_name ); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html( $slot->notiz ?: '' ); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else : ?>
                <p class="tv-empty-hint">
                    Noch keine Trainingszeiten. Diese werden in den
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten' ) ); ?>">Sportstätten</a>
                    gepflegt.
                </p>
            <?php endif; ?>
        </div>

    </div>

<?php endif; ?>
</div>

<?php
function tv_gruppen_fields( $item, $trainer_list, $selected_tr_ids ) {
    $selected_tr_ids = array_map( 'intval', (array) $selected_tr_ids );
    ob_start(); ?>
    <table class="form-table">
        <tr>
            <th><label for="gr_name">Gruppenname *</label></th>
            <td><input type="text" id="gr_name" name="name" class="regular-text" required
                       value="<?php echo esc_attr( $item->name ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th>Trainer</th>
            <td>
                <?php if ( $trainer_list ) : ?>
                    <fieldset>
                        <?php foreach ( $trainer_list as $t ) : ?>
                        <label style="display:block;margin-bottom:4px">
                            <input type="checkbox" name="trainer_ids[]"
                                   value="<?php echo esc_attr( $t->id ); ?>"
                                   <?php checked( in_array( (int) $t->id, $selected_tr_ids, true ) ); ?>>
                            <?php echo esc_html( $t->nachname . ', ' . $t->vorname ); ?>
                        </label>
                        <?php endforeach; ?>
                    </fieldset>
                <?php else : ?>
                    <p class="description">Noch keine Trainer angelegt.</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Altersbereich</th>
            <td>
                <input type="number" name="min_alter" class="small-text" min="0" max="120" placeholder="von"
                       value="<?php echo esc_attr( $item->min_alter ?? '' ); ?>">
                &nbsp;bis&nbsp;
                <input type="number" name="max_alter" class="small-text" min="0" max="120" placeholder="bis"
                       value="<?php echo esc_attr( $item->max_alter ?? '' ); ?>">
                &nbsp;Jahre
            </td>
        </tr>
        <tr>
            <th><label for="gr_max">Max. Mitglieder</label></th>
            <td><input type="number" id="gr_max" name="max_mitglieder" class="small-text" min="0"
                       value="<?php echo esc_attr( $item->max_mitglieder ?? 0 ); ?>"></td>
        </tr>
        <tr>
            <th><label for="gr_beschr">Beschreibung</label></th>
            <td><textarea id="gr_beschr" name="beschreibung" rows="3" class="large-text"><?php echo esc_textarea( $item->beschreibung ?? '' ); ?></textarea></td>
        </tr>
    </table>
    <?php
    return ob_get_clean();
}
