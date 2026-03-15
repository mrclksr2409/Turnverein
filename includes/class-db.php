<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Turnverein_DB {

    public static function install() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql_sportstaetten = "CREATE TABLE {$wpdb->prefix}tv_sportstaetten (
            id          INT(11) NOT NULL AUTO_INCREMENT,
            name        VARCHAR(255) NOT NULL,
            strasse     VARCHAR(255) DEFAULT '',
            plz         VARCHAR(10)  DEFAULT '',
            ort         VARCHAR(100) DEFAULT '',
            kapazitaet  INT(11)      DEFAULT 0,
            beschreibung TEXT        DEFAULT '',
            erstellt_am DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $sql_trainer = "CREATE TABLE {$wpdb->prefix}tv_trainer (
            id          INT(11) NOT NULL AUTO_INCREMENT,
            vorname     VARCHAR(100) NOT NULL,
            nachname    VARCHAR(100) NOT NULL,
            email       VARCHAR(255) DEFAULT '',
            telefon     VARCHAR(50)  DEFAULT '',
            sportart    VARCHAR(255) DEFAULT '',
            lizenz      VARCHAR(100) DEFAULT '',
            erstellt_am DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $sql_gruppen = "CREATE TABLE {$wpdb->prefix}tv_gruppen (
            id             INT(11) NOT NULL AUTO_INCREMENT,
            name           VARCHAR(255) NOT NULL,
            trainer_id     INT(11)      DEFAULT NULL,
            min_alter      INT(3)       DEFAULT NULL,
            max_alter      INT(3)       DEFAULT NULL,
            max_mitglieder INT(11)      DEFAULT 0,
            beschreibung   TEXT         DEFAULT '',
            erstellt_am    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY trainer_id (trainer_id)
        ) $charset_collate;";

        // Wochentag: 1=Mo, 2=Di, 3=Mi, 4=Do, 5=Fr, 6=Sa, 7=So
        $sql_trainingszeiten = "CREATE TABLE {$wpdb->prefix}tv_trainingszeiten (
            id              INT(11)  NOT NULL AUTO_INCREMENT,
            sportstaette_id INT(11)  NOT NULL,
            gruppe_id       INT(11)  DEFAULT NULL,
            wochentag       TINYINT  NOT NULL,
            startzeit       TIME     NOT NULL,
            endzeit         TIME     NOT NULL,
            notiz           VARCHAR(255) DEFAULT '',
            PRIMARY KEY (id),
            KEY sportstaette_id (sportstaette_id),
            KEY gruppe_id (gruppe_id)
        ) $charset_collate;";

        $sql_gruppen_trainer = "CREATE TABLE {$wpdb->prefix}tv_gruppen_trainer (
            gruppe_id   INT(11) NOT NULL,
            trainer_id  INT(11) NOT NULL,
            PRIMARY KEY (gruppe_id, trainer_id),
            KEY trainer_id (trainer_id)
        ) $charset_collate;";

        dbDelta( $sql_sportstaetten );
        dbDelta( $sql_trainer );
        dbDelta( $sql_gruppen );
        dbDelta( $sql_trainingszeiten );
        dbDelta( $sql_gruppen_trainer );

        add_option( 'turnverein_db_version', TURNVEREIN_VERSION );
    }

    public static function uninstall() {
        // Tables are kept on deactivation; use uninstall.php for full removal.
    }
}
