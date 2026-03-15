<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap tv-wrap">
    <h1>Turnverein Manager</h1>

    <div class="tv-dashboard-cards">
        <div class="tv-card">
            <span class="dashicons dashicons-location"></span>
            <h2><?php echo esc_html( $count_ss ); ?></h2>
            <p>Sportstätten</p>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-sportstaetten' ) ); ?>" class="button">Verwalten</a>
        </div>
        <div class="tv-card">
            <span class="dashicons dashicons-id"></span>
            <h2><?php echo esc_html( $count_tr ); ?></h2>
            <p>Trainer</p>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-trainer' ) ); ?>" class="button">Verwalten</a>
        </div>
        <div class="tv-card">
            <span class="dashicons dashicons-groups"></span>
            <h2><?php echo esc_html( $count_gr ); ?></h2>
            <p>Gruppen</p>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=turnverein-gruppen' ) ); ?>" class="button">Verwalten</a>
        </div>
    </div>
</div>
