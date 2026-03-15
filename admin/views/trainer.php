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

    <h1 class="wp-heading-inline">Trainer</h1>
    <a href="<?php echo esc_url( add_query_arg( 'action', 'neu', $base_url ) ); ?>"
       class="page-title-action">Neuer Trainer</a>
    <hr class="wp-header-end">

    <?php if ( $items ) : ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>E-Mail</th>
                <th>Telefon</th>
                <th>Sportart</th>
                <th>Lizenz</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $items as $item ) : ?>
            <tr>
                <td>
                    <strong>
                        <a href="<?php echo esc_url( add_query_arg( 'id', $item->id, $base_url ) ); ?>">
                            <?php echo esc_html( $item->nachname . ', ' . $item->vorname ); ?>
                        </a>
                    </strong>
                </td>
                <td><?php echo $item->email ? '<a href="mailto:' . esc_attr( $item->email ) . '">' . esc_html( $item->email ) . '</a>' : '—'; ?></td>
                <td><?php echo esc_html( $item->telefon ?: '—' ); ?></td>
                <td><?php echo esc_html( $item->sportart ?: '—' ); ?></td>
                <td><?php echo esc_html( $item->lizenz ?: '—' ); ?></td>
                <td class="tv-actions">
                    <a href="<?php echo esc_url( add_query_arg( 'id', $item->id, $base_url ) ); ?>"
                       class="button button-small">Öffnen</a>
                    <form method="post" style="display:inline"
                          onsubmit="return confirm('Trainer wirklich löschen?')">
                        <?php wp_nonce_field( 'tv_trainer', 'tv_nonce' ); ?>
                        <input type="hidden" name="tv_action" value="delete">
                        <input type="hidden" name="id" value="<?php echo esc_attr( $item->id ); ?>">
                        <button type="submit" class="button button-small tv-btn-delete">Löschen</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
        <p>Noch keine Trainer vorhanden. <a href="<?php echo esc_url( add_query_arg( 'action', 'neu', $base_url ) ); ?>">Ersten Trainer anlegen</a></p>
    <?php endif; ?>

<?php /* ================================================================
         MODUS: Neu anlegen
         ================================================================ */ ?>
<?php elseif ( $mode === 'new' ) : ?>

    <h1 class="wp-heading-inline">Neuer Trainer</h1>
    <a href="<?php echo esc_url( $base_url ); ?>" class="page-title-action">← Zurück zur Liste</a>
    <hr class="wp-header-end">

    <div class="tv-form-box" style="max-width:640px;margin-top:1rem">
        <form method="post">
            <?php wp_nonce_field( 'tv_trainer', 'tv_nonce' ); ?>
            <input type="hidden" name="tv_action" value="save">
            <?php echo tv_trainer_fields( null ); ?>
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

    <p class="tv-breadcrumb"><a href="<?php echo esc_url( $base_url ); ?>">← Alle Trainer</a></p>
    <h1><?php echo esc_html( $trainer->vorname . ' ' . $trainer->nachname ); ?></h1>

    <div class="tv-detail-layout">

        <details class="tv-details-box" <?php echo ( $notice && $notice[0] === 'success' && strpos( $notice[1], 'aktualisiert' ) !== false ) ? 'open' : ''; ?>>
            <summary>Stammdaten bearbeiten</summary>
            <div class="tv-details-content">
                <form method="post">
                    <?php wp_nonce_field( 'tv_trainer', 'tv_nonce' ); ?>
                    <input type="hidden" name="tv_action" value="save">
                    <input type="hidden" name="id" value="<?php echo esc_attr( $trainer->id ); ?>">
                    <?php echo tv_trainer_fields( $trainer ); ?>
                    <p class="submit">
                        <button type="submit" class="button button-primary">Speichern</button>
                    </p>
                </form>
                <form method="post"
                      onsubmit="return confirm('Trainer wirklich löschen?')">
                    <?php wp_nonce_field( 'tv_trainer', 'tv_nonce' ); ?>
                    <input type="hidden" name="tv_action" value="delete">
                    <input type="hidden" name="id" value="<?php echo esc_attr( $trainer->id ); ?>">
                    <button type="submit" class="button tv-btn-delete">Trainer löschen</button>
                </form>
            </div>
        </details>

        <?php /* Gruppen dieses Trainers */ ?>
        <?php
        global $wpdb;
        $gruppen_von_trainer = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}tv_gruppen WHERE trainer_id = %d ORDER BY name ASC",
            $trainer->id
        ) );
        ?>
        <div class="tv-slot-list-box">
            <h2>Zugeordnete Gruppen (<?php echo count( $gruppen_von_trainer ); ?>)</h2>
            <?php if ( $gruppen_von_trainer ) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Gruppe</th>
                        <th>Alter</th>
                        <th>Max. Mitglieder</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $gruppen_von_trainer as $g ) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-gruppen&id=' . $g->id ) ); ?>">
                                <?php echo esc_html( $g->name ); ?>
                            </a>
                        </td>
                        <td><?php
                            if ( $g->min_alter !== null && $g->max_alter !== null ) echo esc_html( $g->min_alter . '–' . $g->max_alter . ' J.' );
                            elseif ( $g->min_alter !== null ) echo 'ab ' . esc_html( $g->min_alter ) . ' J.';
                            elseif ( $g->max_alter !== null ) echo 'bis ' . esc_html( $g->max_alter ) . ' J.';
                            else echo '—';
                        ?></td>
                        <td><?php echo $g->max_mitglieder ? esc_html( $g->max_mitglieder ) : '—'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else : ?>
                <p class="tv-empty-hint">Diesem Trainer sind noch keine Gruppen zugeordnet.</p>
            <?php endif; ?>
        </div>

    </div>

<?php endif; ?>
</div>

<?php
function tv_trainer_fields( $item ) {
    ob_start(); ?>
    <table class="form-table">
        <tr>
            <th><label for="tr_vorname">Vorname *</label></th>
            <td><input type="text" id="tr_vorname" name="vorname" class="regular-text" required
                       value="<?php echo esc_attr( $item->vorname ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="tr_nachname">Nachname *</label></th>
            <td><input type="text" id="tr_nachname" name="nachname" class="regular-text" required
                       value="<?php echo esc_attr( $item->nachname ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="tr_email">E-Mail</label></th>
            <td><input type="email" id="tr_email" name="email" class="regular-text"
                       value="<?php echo esc_attr( $item->email ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="tr_telefon">Telefon</label></th>
            <td><input type="text" id="tr_telefon" name="telefon" class="regular-text"
                       value="<?php echo esc_attr( $item->telefon ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="tr_sportart">Sportart / Fachbereich</label></th>
            <td><input type="text" id="tr_sportart" name="sportart" class="regular-text"
                       value="<?php echo esc_attr( $item->sportart ?? '' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="tr_lizenz">Lizenz / Qualifikation</label></th>
            <td><input type="text" id="tr_lizenz" name="lizenz" class="regular-text"
                       value="<?php echo esc_attr( $item->lizenz ?? '' ); ?>"></td>
        </tr>
    </table>
    <?php
    return ob_get_clean();
}
