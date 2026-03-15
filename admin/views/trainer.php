<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Trainer</h1>

    <div class="tv-layout">
        <!-- Form -->
        <div class="tv-form-box">
            <h2><?php echo $edit ? 'Trainer bearbeiten' : 'Neuer Trainer'; ?></h2>
            <form method="post">
                <?php wp_nonce_field( 'tv_trainer', 'tv_nonce' ); ?>
                <input type="hidden" name="tv_action" value="save">
                <?php if ( $edit ) : ?>
                    <input type="hidden" name="id" value="<?php echo esc_attr( $edit->id ); ?>">
                <?php endif; ?>

                <table class="form-table">
                    <tr>
                        <th><label for="vorname">Vorname *</label></th>
                        <td><input type="text" id="vorname" name="vorname" class="regular-text" required
                                   value="<?php echo esc_attr( $edit->vorname ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="nachname">Nachname *</label></th>
                        <td><input type="text" id="nachname" name="nachname" class="regular-text" required
                                   value="<?php echo esc_attr( $edit->nachname ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="email">E-Mail</label></th>
                        <td><input type="email" id="email" name="email" class="regular-text"
                                   value="<?php echo esc_attr( $edit->email ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="telefon">Telefon</label></th>
                        <td><input type="text" id="telefon" name="telefon" class="regular-text"
                                   value="<?php echo esc_attr( $edit->telefon ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="sportart">Sportart / Fachbereich</label></th>
                        <td><input type="text" id="sportart" name="sportart" class="regular-text"
                                   value="<?php echo esc_attr( $edit->sportart ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="lizenz">Lizenz / Qualifikation</label></th>
                        <td><input type="text" id="lizenz" name="lizenz" class="regular-text"
                                   value="<?php echo esc_attr( $edit->lizenz ?? '' ); ?>"></td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $edit ? 'Aktualisieren' : 'Speichern'; ?>
                    </button>
                    <?php if ( $edit ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-trainer' ) ); ?>" class="button">Abbrechen</a>
                    <?php endif; ?>
                </p>
            </form>
        </div>

        <!-- List -->
        <div class="tv-list-box">
            <h2>Alle Trainer (<?php echo count( $items ); ?>)</h2>
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
                        <td><strong><?php echo esc_html( $item->nachname . ', ' . $item->vorname ); ?></strong></td>
                        <td><?php echo $item->email ? '<a href="mailto:' . esc_attr( $item->email ) . '">' . esc_html( $item->email ) . '</a>' : '—'; ?></td>
                        <td><?php echo esc_html( $item->telefon ?: '—' ); ?></td>
                        <td><?php echo esc_html( $item->sportart ?: '—' ); ?></td>
                        <td><?php echo esc_html( $item->lizenz ?: '—' ); ?></td>
                        <td class="tv-actions">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-trainer&edit=' . $item->id ) ); ?>"
                               class="button button-small">Bearbeiten</a>
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
                <p>Noch keine Trainer vorhanden.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
