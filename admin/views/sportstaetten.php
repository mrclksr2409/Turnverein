<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Sportstätten</h1>

    <div class="tv-layout">
        <!-- Form -->
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
        </div>

        <!-- List -->
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
                        <td><?php echo $item->kapazitaet ? esc_html( $item->kapazitaet ) . ' Personen' : '—'; ?></td>
                        <td class="tv-actions">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&edit=' . $item->id ) ); ?>"
                               class="button button-small">Bearbeiten</a>
                            <form method="post" style="display:inline"
                                  onsubmit="return confirm('Sportstätte wirklich löschen?')">
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
    </div>
</div>
