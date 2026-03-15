<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Belegungsplan</h1>

    <?php if ( empty( $all_ss ) ) : ?>
        <p>Noch keine Sportstätten vorhanden. <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten' ) ); ?>">Sportstätte anlegen</a></p>
    <?php else : ?>

    <?php foreach ( $all_ss as $ss ) : ?>
    <div class="tv-belegung-section">
        <div class="tv-belegung-header">
            <h2><?php echo esc_html( $ss->name ); ?></h2>
            <?php if ( $ss->strasse || $ss->ort ) : ?>
                <span class="tv-belegung-adresse">
                    <?php echo esc_html( trim( $ss->strasse . ', ' . $ss->plz . ' ' . $ss->ort, ', ' ) ); ?>
                </span>
            <?php endif; ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten&edit=' . $ss->id ) ); ?>"
               class="button button-small">Slots verwalten</a>
        </div>

        <?php
        $ss_slots = $plan[ $ss->id ] ?? array();
        if ( empty( $ss_slots ) ) :
        ?>
            <p class="tv-belegung-empty">Keine Trainingszeiten eingetragen.</p>
        <?php else : ?>

        <div class="tv-timetable-wrapper">
            <table class="tv-timetable">
                <thead>
                    <tr>
                        <th class="tv-time-col">Zeit</th>
                        <?php foreach ( Turnverein_Trainingszeiten::$wochentage as $nr => $tag ) : ?>
                            <th><?php echo esc_html( $tag ); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Collect all unique time ranges across all days for this venue.
                    $time_ranges = array();
                    foreach ( $ss_slots as $day_slots ) {
                        foreach ( $day_slots as $slot ) {
                            $key = substr( $slot->startzeit, 0, 5 ) . '–' . substr( $slot->endzeit, 0, 5 );
                            $time_ranges[ $key ] = array( $slot->startzeit, $slot->endzeit );
                        }
                    }
                    ksort( $time_ranges );
                    ?>
                    <?php foreach ( $time_ranges as $label => $times ) : ?>
                    <tr>
                        <td class="tv-time-col"><strong><?php echo esc_html( $label ); ?></strong></td>
                        <?php foreach ( Turnverein_Trainingszeiten::$wochentage as $nr => $tag ) : ?>
                        <td>
                            <?php
                            $day_slots = $ss_slots[ $nr ] ?? array();
                            foreach ( $day_slots as $slot ) {
                                $slot_start = substr( $slot->startzeit, 0, 5 );
                                $slot_end   = substr( $slot->endzeit, 0, 5 );
                                $range_start = substr( $times[0], 0, 5 );
                                $range_end   = substr( $times[1], 0, 5 );
                                if ( $slot_start === $range_start && $slot_end === $range_end ) :
                                ?>
                                <div class="tv-slot <?php echo $slot->gruppe_id ? 'tv-slot--belegt' : 'tv-slot--frei'; ?>">
                                    <strong><?php echo esc_html( $slot->gruppe_name ?: 'Freier Slot' ); ?></strong>
                                    <?php if ( $slot->notiz ) : ?>
                                        <br><small><?php echo esc_html( $slot->notiz ); ?></small>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            <?php } ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <?php endif; ?>
</div>
