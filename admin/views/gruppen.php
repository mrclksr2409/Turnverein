<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Gruppen</h1>

    <div class="tv-layout">
        <!-- Form -->
        <div class="tv-form-box">
            <h2><?php echo $edit ? 'Gruppe bearbeiten' : 'Neue Gruppe'; ?></h2>
            <form method="post">
                <?php wp_nonce_field( 'tv_gruppen', 'tv_nonce' ); ?>
                <input type="hidden" name="tv_action" value="save">
                <?php if ( $edit ) : ?>
                    <input type="hidden" name="id" value="<?php echo esc_attr( $edit->id ); ?>">
                <?php endif; ?>

                <table class="form-table">
                    <tr>
                        <th><label for="name">Gruppenname *</label></th>
                        <td><input type="text" id="name" name="name" class="regular-text" required
                                   value="<?php echo esc_attr( $edit->name ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="trainer_id">Trainer</label></th>
                        <td>
                            <select id="trainer_id" name="trainer_id" class="regular-text">
                                <option value="">— kein Trainer —</option>
                                <?php foreach ( $trainer_list as $t ) : ?>
                                    <option value="<?php echo esc_attr( $t->id ); ?>"
                                        <?php selected( ( $edit->trainer_id ?? '' ), $t->id ); ?>>
                                        <?php echo esc_html( $t->nachname . ', ' . $t->vorname ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="sportstaette_id">Sportstätte</label></th>
                        <td>
                            <select id="sportstaette_id" name="sportstaette_id" class="regular-text">
                                <option value="">— keine Sportstätte —</option>
                                <?php foreach ( $ss_list as $s ) : ?>
                                    <option value="<?php echo esc_attr( $s->id ); ?>"
                                        <?php selected( ( $edit->sportstaette_id ?? '' ), $s->id ); ?>>
                                        <?php echo esc_html( $s->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="trainingszeiten">Trainingszeiten</label></th>
                        <td><input type="text" id="trainingszeiten" name="trainingszeiten" class="regular-text"
                                   placeholder="z.B. Mo/Mi 18:00–20:00 Uhr"
                                   value="<?php echo esc_attr( $edit->trainingszeiten ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th>Altersbereich</th>
                        <td>
                            <input type="number" name="min_alter" class="small-text" min="0" max="120"
                                   placeholder="von" value="<?php echo esc_attr( $edit->min_alter ?? '' ); ?>">
                            &nbsp;bis&nbsp;
                            <input type="number" name="max_alter" class="small-text" min="0" max="120"
                                   placeholder="bis" value="<?php echo esc_attr( $edit->max_alter ?? '' ); ?>">
                            &nbsp;Jahre
                        </td>
                    </tr>
                    <tr>
                        <th><label for="max_mitglieder">Max. Mitglieder</label></th>
                        <td><input type="number" id="max_mitglieder" name="max_mitglieder" class="small-text" min="0"
                                   value="<?php echo esc_attr( $edit->max_mitglieder ?? 0 ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="beschreibung">Beschreibung</label></th>
                        <td><textarea id="beschreibung" name="beschreibung" rows="4" class="large-text"><?php echo esc_textarea( $edit->beschreibung ?? '' ); ?></textarea></td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $edit ? 'Aktualisieren' : 'Speichern'; ?>
                    </button>
                    <?php if ( $edit ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-gruppen' ) ); ?>" class="button">Abbrechen</a>
                    <?php endif; ?>
                </p>
            </form>
        </div>

        <!-- List -->
        <div class="tv-list-box">
            <h2>Alle Gruppen (<?php echo count( $items ); ?>)</h2>
            <?php if ( $items ) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Gruppe</th>
                        <th>Trainer</th>
                        <th>Sportstätte</th>
                        <th>Zeiten</th>
                        <th>Alter</th>
                        <th>Max.</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $items as $item ) : ?>
                    <tr>
                        <td><strong><?php echo esc_html( $item->name ); ?></strong></td>
                        <td><?php echo esc_html( $item->trainer_name ?: '—' ); ?></td>
                        <td><?php echo esc_html( $item->sportstaette_name ?: '—' ); ?></td>
                        <td><?php echo esc_html( $item->trainingszeiten ?: '—' ); ?></td>
                        <td>
                            <?php
                            if ( $item->min_alter !== null && $item->max_alter !== null ) {
                                echo esc_html( $item->min_alter . '–' . $item->max_alter . ' J.' );
                            } elseif ( $item->min_alter !== null ) {
                                echo 'ab ' . esc_html( $item->min_alter ) . ' J.';
                            } elseif ( $item->max_alter !== null ) {
                                echo 'bis ' . esc_html( $item->max_alter ) . ' J.';
                            } else {
                                echo '—';
                            }
                            ?>
                        </td>
                        <td><?php echo $item->max_mitglieder ? esc_html( $item->max_mitglieder ) : '—'; ?></td>
                        <td class="tv-actions">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-gruppen&edit=' . $item->id ) ); ?>"
                               class="button button-small">Bearbeiten</a>
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
            <?php else : ?>
                <p>Noch keine Gruppen vorhanden.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
