<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Belegungsplan</h1>

    <div class="tv-shortcode-box">
        <label>Vollständiger Belegungsplan:
            <input type="text" class="tv-shortcode-input regular-text" readonly value="[tv_belegungsplan]">
        </label>
        <p class="description">
            Optionen: <code>sportstaette_id="1"</code> (eine Sportstätte), <code>gruppe_id="3"</code> oder <code>gruppe="Name"</code>
            (eine Gruppe), <code>tag="Montag"</code>, <code>trainer="nein"</code>, <code>telefon="nein"</code>,
            <code>titel="Eigene Überschrift"</code> oder <code>titel="nein"</code>, <code>ebene="h2"</code> (Ebene der obersten Überschrift).
        </p>
    </div>

    <?php if ( empty( $all_ss ) ) : ?>
        <p>Noch keine Sportstätten vorhanden.
           <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten' ) ); ?>">Sportstätte anlegen</a>
        </p>
    <?php else : ?>

    <?php foreach ( $all_ss as $ss ) :
        $ss_slots = $plan[ $ss->id ] ?? array();
    ?>
    <div class="tv-belegung-section">

        <div class="tv-belegung-header">
            <h2><?php echo esc_html( $ss->name ); ?></h2>
            <?php if ( $ss->strasse || $ss->ort ) : ?>
                <span class="tv-belegung-adresse">
                    <?php echo esc_html( trim( $ss->strasse . ', ' . $ss->plz . ' ' . $ss->ort, ', ' ) ); ?>
                </span>
            <?php endif; ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&id=' . $ss->id ) ); ?>"
               class="button button-small">Slots verwalten</a>
            <input type="text" class="tv-shortcode-input" readonly size="34"
                   value="<?php echo esc_attr( '[tv_belegungsplan sportstaette_id="' . $ss->id . '"]' ); ?>">
        </div>

        <?php if ( empty( $ss_slots ) ) : ?>
            <p class="tv-belegung-empty">Keine Trainingszeiten eingetragen.</p>
        <?php else : ?>

        <table class="tv-belegung-table widefat">
            <tbody>
            <?php foreach ( Turnverein_Trainingszeiten::$wochentage as $nr => $tagname ) :
                if ( empty( $ss_slots[ $nr ] ) ) continue;
                $slots = $ss_slots[ $nr ];
                usort( $slots, fn( $a, $b ) => strcmp( $a->startzeit, $b->startzeit ) );
            ?>
                <tr class="tv-tag-header">
                    <td colspan="4"><strong><?php echo esc_html( $tagname ); ?></strong></td>
                </tr>
                <?php foreach ( $slots as $slot ) : ?>
                <tr class="tv-slot-row">
                    <td class="tv-col-zeit">
                        <?php echo esc_html( substr( $slot->startzeit, 0, 5 ) . ' – ' . substr( $slot->endzeit, 0, 5 ) . ' Uhr' ); ?>
                    </td>
                    <td class="tv-col-gruppe">
                        <?php if ( $slot->gruppe_id ) : ?>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-gruppen&id=' . $slot->gruppe_id ) ); ?>">
                                <?php echo esc_html( $slot->gruppe_name ); ?>
                            </a>
                        <?php else : ?>
                            <span class="tv-slot-frei"><?php echo esc_html( $slot->notiz ?: 'Freier Slot' ); ?></span>
                        <?php endif; ?>
                        <?php if ( $slot->gruppe_id && $slot->notiz ) : ?>
                            <br><small class="description"><?php echo esc_html( $slot->notiz ); ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="tv-col-trainer"><?php echo esc_html( $slot->trainer_names ?: '' ); ?></td>
                    <td class="tv-col-telefon"><?php echo esc_html( $slot->trainer_telefone ?: '' ); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <?php endif; ?>
</div>

<style>
.tv-belegung-table {
    border-collapse: collapse;
    margin-bottom: 2rem;
}
.tv-belegung-table td {
    padding: 8px 12px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}
.tv-tag-header td {
    background: #f9fafb;
    padding: 10px 12px 8px;
    border-bottom: 2px solid #d1d5db;
    font-size: 0.95em;
    letter-spacing: .02em;
}
.tv-slot-row:last-child td { border-bottom: none; }
.tv-col-zeit    { width: 160px; white-space: nowrap; color: #555; }
.tv-col-gruppe  { min-width: 200px; }
.tv-col-trainer { width: 180px; color: #444; }
.tv-col-telefon { width: 140px; color: #444; }
.tv-slot-frei   { color: #9ca3af; }
</style>
