# Für Entwickler

## Projektstruktur

```
turnverein/
├── turnverein.php                 Plugin-Header, Konstanten, Bootstrap, Update Checker
├── includes/
│   ├── class-db.php               Tabellen anlegen/aktualisieren (dbDelta)
│   ├── class-sportstaetten.php    Repository Sportstätten (inkl. Sortierung)
│   ├── class-trainer.php          Repository Trainer (inkl. Bild-Helfer)
│   ├── class-gruppen.php          Repository Gruppen + Trainer-Zuordnung
│   ├── class-trainingszeiten.php  Repository Slots + Belegungsplan-Abfrage
│   └── class-shortcodes.php       Alle Frontend-Shortcodes (ohne CSS)
├── admin/
│   ├── class-admin.php            Menüs, Formularverarbeitung, AJAX, Assets
│   └── views/                     Admin-Templates
├── assets/
│   ├── css/admin.css
│   └── js/admin.js                Mediathek, Drag & Drop, Shortcode-Felder
├── lib/plugin-update-checker/     Bibliothek für Updates aus GitHub
└── docs/wiki/                     Quelle dieses Wikis
```

## Datenbank

Alle Tabellen tragen das WordPress-Präfix (Standard `wp_`).

### `wp_tv_sportstaetten`

| Spalte | Typ | Hinweis |
|---|---|---|
| `id` | INT, PK | |
| `name` | VARCHAR(255) | Pflicht |
| `strasse`, `plz`, `ort` | VARCHAR | |
| `kapazitaet` | INT | |
| `beschreibung` | TEXT | |
| `sortierung` | INT | Reihenfolge (Drag & Drop) |
| `erstellt_am` | DATETIME | |

### `wp_tv_trainer`

| Spalte | Typ | Hinweis |
|---|---|---|
| `id` | INT, PK | |
| `vorname`, `nachname` | VARCHAR(100) | Pflicht |
| `email`, `telefon`, `sportart`, `lizenz` | VARCHAR | |
| `bild_id` | BIGINT UNSIGNED | Attachment-ID, `0` = kein Bild |
| `erstellt_am` | DATETIME | |

### `wp_tv_gruppen`

| Spalte | Typ | Hinweis |
|---|---|---|
| `id` | INT, PK | |
| `name` | VARCHAR(255) | Pflicht |
| `trainer_id` | INT | Veraltet, ersetzt durch `wp_tv_gruppen_trainer` |
| `min_alter`, `max_alter` | INT | NULL = keine Angabe |
| `max_mitglieder` | INT | |
| `beschreibung` | TEXT | |
| `erstellt_am` | DATETIME | |

### `wp_tv_gruppen_trainer`

Zuordnung Gruppe ↔ Trainer (n:m), PK `(gruppe_id, trainer_id)`.

### `wp_tv_trainingszeiten`

| Spalte | Typ | Hinweis |
|---|---|---|
| `id` | INT, PK | |
| `sportstaette_id` | INT | Pflicht |
| `gruppe_id` | INT | NULL = freier Slot |
| `trainer_id` | INT | NULL = alle Trainer der Gruppe |
| `wochentag` | TINYINT | 1 = Montag … 7 = Sonntag |
| `startzeit`, `endzeit` | TIME | |
| `notiz` | VARCHAR(255) | |

### Schema-Updates

`turnverein_maybe_upgrade()` vergleicht beim `admin_init` die Option `turnverein_db_version` mit `TURNVEREIN_VERSION` und führt bei Abweichung `Turnverein_DB::install()` (dbDelta) aus. **Jede Schemaänderung erfordert daher eine Erhöhung der Versionsnummer.**

### Manuelles Entfernen aller Daten

```sql
DROP TABLE wp_tv_trainingszeiten, wp_tv_gruppen_trainer, wp_tv_gruppen, wp_tv_trainer, wp_tv_sportstaetten;
DELETE FROM wp_options WHERE option_name = 'turnverein_db_version';
```

## Filter

Jeder Shortcode durchläuft den WordPress-Standardfilter `shortcode_atts_{shortcode}`. Damit lassen sich z. B. Standardwerte global ändern:

```php
// Trainer-Shortcode standardmäßig mit E-Mail und ohne Bilder.
add_filter( 'shortcode_atts_tv_gruppe_trainer', function ( $out, $pairs, $atts ) {
    if ( ! isset( $atts['email'] ) ) {
        $out['email'] = 'ja';
    }
    if ( ! isset( $atts['bild'] ) ) {
        $out['bild'] = 'nein';
    }
    return $out;
}, 10, 3 );
```

Verfügbar: `shortcode_atts_tv_belegungsplan`, `shortcode_atts_tv_gruppe_trainer`, `shortcode_atts_tv_sportstaetten`.

## AJAX

| Action | Zweck | Absicherung |
|---|---|---|
| `tv_sportstaetten_sort` | Speichert die Reihenfolge der Sportstätten (`ids[]`) | Nonce `tv_sportstaetten_sort`, Capability `manage_options` |

## Frontend-CSS

Das Plugin lädt im Frontend kein CSS. Die Gestaltung erfolgt im Theme über die CSS-Klassen, siehe [[Design anpassen]].

## Release-Workflow

1. Entwicklung auf `beta`.
2. Versionsnummer im Plugin-Header **und** in `TURNVEREIN_VERSION` erhöhen (SemVer).
3. Changelog in der `README.md` ergänzen.
4. `beta` nach `main` übernehmen → Websites erhalten das Update über den Plugin Update Checker.

## Wiki

Dieses Wiki liegt als Markdown in [`docs/wiki/`](https://github.com/mrclksr2409/Turnverein/tree/main/docs/wiki). Die GitHub Action [`.github/workflows/wiki.yml`](https://github.com/mrclksr2409/Turnverein/blob/main/.github/workflows/wiki.yml) synchronisiert den Ordner bei jedem Push auf `main` ins GitHub-Wiki.

Dabei werden automatisch ersetzt bzw. erzeugt:

| Platzhalter / Seite | Quelle |
|---|---|
| `{{!VERSION}}` | `Version:` im Plugin-Header von `turnverein.php` |
| `{{!DATE}}` | Datum des Commits |
| `{{!COMMIT}}` | Kurz-Hash des Commits |
| Seite [[Changelog]] | Abschnitt `## Changelog` der `README.md` |

**Neue Seite anlegen:** Datei `docs/wiki/Seitenname.md` erstellen (Bindestriche werden im Wiki zu Leerzeichen) und in `_Sidebar.md` verlinken. Interne Links: `[[Seitenname]]` oder `[[Linktext|Seitenname]]`.

Soll ein Platzhalter wörtlich im Wiki erscheinen, wird direkt nach den öffnenden Klammern ein Ausrufezeichen eingefügt (so wie in der Tabelle oben im Quelltext dieser Seite).
