<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Sportstätten</h1>

    <div class="tv-layout">
        <!-- Sportstätte Form -->
        <div class="tv-form-box">
            <h2><?php echo $edit ? 'Sportstätte bearbeiten' : 'Neue Sportstätte'; ?></h2>
            <form method="post">
                <?php wp_nonce_field( 'tv_sportstaette', 'tv_nonce' ); ?>
                <input type="hidden" name="tv_action" value="save">
                <?php if ( $edit ) : ?>
                    <input type="hidden" name="id" value="<?php echo esc_attr( $edit->id ); ?>">
                <?php endif; ?>

                <table class="form-table">
                    <tr>
                        <th><label for="name">Name *</label></th>
                        <td><input type="text" id="name" name="name" class="regular-text" required
                                   value="<?php echo esc_attr( $edit->name ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="strasse">Straße</label></th>
                        <td><input type="text" id="strasse" name="strasse" class="regular-text"
                                   value="<?php echo esc_attr( $edit->strasse ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="plz">PLZ</label></th>
                        <td><input type="text" id="plz" name="plz" class="small-text"
                                   value="<?php echo esc_attr( $edit->plz ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="ort">Ort</label></th>
                        <td><input type="text" id="ort" name="ort" class="regular-text"
                                   value="<?php echo esc_attr( $edit->ort ?? '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="kapazitaet">Kapazität</label></th>
                        <td><input type="number" id="kapazitaet" name="kapazitaet" class="small-text" min="0"
                                   value="<?php echo esc_attr( $edit->kapazitaet ?? 0 ); ?>"></td>
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
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten' ) ); ?>" class="button">Abbrechen</a>
                    <?php endif; ?>
                </p>
            </form>

            <?php if ( $edit ) : ?>
            <!-- Trainingszeit-Slot Form (nur wenn eine Sportstätte gewählt ist) -->
            <hr>
            <h2><?php echo $edit_slot ? 'Slot bearbeiten' : 'Neuen Slot hinzufügen'; ?></h2>
            <form method="post">
                <?php wp_nonce_field( 'tv_trainingszeit', 'tv_nonce_tz' ); ?>
                <input type="hidden" name="tv_action" value="save_slot">
                <input type="hidden" name="sportstaette_id" value="<?php echo esc_attr( $edit->id ); ?>">
                <?php if ( $edit_slot ) : ?>
                    <input type="hidden" name="slot_id" value="<?php echo esc_attr( $edit_slot->id ); ?>">
                <?php endif; ?>

                <table class="form-table">
                    <tr>
                        <th><label for="wochentag">Wochentag *</label></th>
                        <td>
                            <select id="wochentag" name="wochentag" required>
                                <?php foreach ( Turnverein_Trainingszeiten::$wochentage as $nr => $tag ) : ?>
                                    <option value="<?php echo esc_attr( $nr ); ?>"
                                        <?php selected( ( $edit_slot->wochentag ?? '' ), $nr ); ?>>
                                        <?php echo esc_html( $tag ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="startzeit">Von *</label></th>
                        <td><input type="time" id="startzeit" name="startzeit" required
                                   value="<?php echo esc_attr( isset( $edit_slot->startzeit ) ? substr( $edit_slot->startzeit, 0, 5 ) : '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="endzeit">Bis *</label></th>
                        <td><input type="time" id="endzeit" name="endzeit" required
                                   value="<?php echo esc_attr( isset( $edit_slot->endzeit ) ? substr( $edit_slot->endzeit, 0, 5 ) : '' ); ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="gruppe_id">Gruppe</label></th>
                        <td>
                            <select id="gruppe_id" name="gruppe_id">
                                <option value="">— freier Slot —</option>
                                <?php foreach ( $gruppen_list as $g ) : ?>
                                    <option value="<?php echo esc_attr( $g->id ); ?>"
                                        <?php selected( ( $edit_slot->gruppe_id ?? '' ), $g->id ); ?>>
                                        <?php echo esc_html( $g->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="notiz">Notiz</label></th>
                        <td><input type="text" id="notiz" name="notiz" class="regular-text"
                                   value="<?php echo esc_attr( $edit_slot->notiz ?? '' ); ?>"></td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $edit_slot ? 'Slot aktualisieren' : 'Slot speichern'; ?>
                    </button>
                    <?php if ( $edit_slot ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&edit=' . $edit->id ) ); ?>" class="button">Abbrechen</a>
                    <?php endif; ?>
                </p>
            </form>
            <?php endif; ?>
        </div>

        <!-- Right column: Sportstätten-Liste + Slots -->
        <div>
            <div class="tv-list-box">
                <h2>Alle Sportstätten (<?php echo count( $items ); ?>)</h2>
                <?php if ( $items ) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Adresse</th>
                            <th>Kapazität</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $items as $item ) : ?>
                        <tr>
                            <td><strong><?php echo esc_html( $item->name ); ?></strong></td>
                            <td><?php echo esc_html( trim( $item->strasse . ' ' . $item->plz . ' ' . $item->ort ) ); ?></td>
                            <td><?php echo $item->kapazitaet ? esc_html( $item->kapazitaet ) . ' Pers.' : '—'; ?></td>
                            <td class="tv-actions">
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&edit=' . $item->id ) ); ?>"
                                   class="button button-small">Bearbeiten</a>
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
                <?php else : ?>
                    <p>Noch keine Sportstätten vorhanden.</p>
                <?php endif; ?>
            </div>

            <?php if ( $edit && $active_ss_id ) : ?>
            <!-- Slot-Liste für die gewählte Sportstätte -->
            <div class="tv-list-box" style="margin-top:1.5rem">
                <h2>Belegung: <?php echo esc_html( $edit->name ); ?> (<?php echo count( $slots ); ?> Slots)</h2>
                <?php if ( $slots ) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Von</th>
                            <th>Bis</th>
                            <th>Gruppe</th>
                            <th>Notiz</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $slots as $slot ) : ?>
                        <tr>
                            <td><?php echo esc_html( Turnverein_Trainingszeiten::$wochentage[ $slot->wochentag ] ?? $slot->wochentag ); ?></td>
                            <td><?php echo esc_html( substr( $slot->startzeit, 0, 5 ) ); ?></td>
                            <td><?php echo esc_html( substr( $slot->endzeit, 0, 5 ) ); ?></td>
                            <td><?php echo esc_html( $slot->gruppe_name ?: '— frei —' ); ?></td>
                            <td><?php echo esc_html( $slot->notiz ?: '' ); ?></td>
                            <td class="tv-actions">
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&edit=' . $edit->id . '&edit_slot=' . $slot->id ) ); ?>"
                                   class="button button-small">Bearbeiten</a>
                                <a href="<?php echo esc_url( wp_nonce_url(
                                    admin_url( 'admin.php?page=turnverein-sportstaetten&edit=' . $edit->id . '&delete_slot=' . $slot->id ),
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
                    <p>Noch keine Trainingszeiten für diese Sportstätte.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
