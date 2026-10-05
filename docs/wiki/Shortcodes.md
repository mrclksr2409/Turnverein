# Shortcodes

Mit Shortcodes werden die Daten des Plugins auf beliebigen Seiten und Beiträgen ausgegeben. Im Block-Editor dazu einen **Shortcode-Block** einfügen (oder einfach in einen Absatz schreiben) und den Shortcode eintragen.

## Übersicht

| Shortcode | Ausgabe | Details |
|---|---|---|
| `[tv_belegungsplan]` | Belegungsplan – gesamt, je Sportstätte oder je Gruppe | [[Shortcode tv_belegungsplan]] |
| `[tv_gruppe_trainer]` | Trainer je Gruppe mit Bild und Telefon | [[Shortcode tv_gruppe_trainer]] |
| `[tv_sportstaetten]` | Tabelle der Sportstätten | [[Shortcode tv_sportstaetten]] |

## Schnellreferenz

| Ziel | Shortcode |
|---|---|
| Vollständiger Belegungsplan | `[tv_belegungsplan]` |
| Belegungsplan einer Sportstätte | `[tv_belegungsplan sportstaette_id="1"]` |
| Trainingszeiten einer Gruppe | `[tv_belegungsplan gruppe_id="3"]` |
| Nur ein Wochentag | `[tv_belegungsplan tag="Montag"]` |
| Trainer aller Gruppen | `[tv_gruppe_trainer]` |
| Trainer einer Gruppe | `[tv_gruppe_trainer id="3"]` |
| Alle Sportstätten | `[tv_sportstaetten]` |
| Eine Sportstätte mit Beschreibung | `[tv_sportstaetten id="1" spalten="name,adresse,beschreibung"]` |

## Gemeinsame Optionen

### `titel` – Überschrift

| Wert | Wirkung |
|---|---|
| `ja` *(Standard)* | Automatische Überschriften (Name der Gruppe bzw. Sportstätte) |
| `nein` | Keine Überschriften |
| beliebiger Text | Eigene Überschrift |

Eine **eigene Überschrift** …

- **ersetzt** den Namen, wenn nur **eine** Gruppe bzw. Sportstätte ausgegeben wird,
- erscheint **zusätzlich ganz oben**, wenn mehrere ausgegeben werden – die Namen bleiben dann als Zwischenüberschriften.

Bei `[tv_sportstaetten]` gibt es keine automatische Überschrift; dort erscheint ein eigener Titel über der Tabelle.

### `ebene` – Überschriften-Ebene

Legt die Ebene der **obersten Überschrift** fest: `h2`, `h3`, `h4`, `h5` oder `h6`. Standard ist `h3`. Ungültige Werte werden ignoriert.

- Ohne eigenen Titel gilt die Ebene für die automatischen Überschriften (Gruppen- bzw. Sportstättennamen).
- Mit eigenem Titel gilt sie für den Titel; darunter stehende Gruppen-/Sportstättennamen liegen automatisch eine Ebene tiefer (z. B. `h2` → `h3`).

> 💡 Für eine saubere Gliederung (und Barrierefreiheit) sollte die Ebene zur Seite passen: Steht der Shortcode direkt unter der Seitenüberschrift (`h1`), ist `ebene="h2"` meist richtig.

### Anführungszeichen

Werte mit Leerzeichen gehören in Anführungszeichen: `titel="Unsere Trainer"`. Wandelt der Editor sie in typografische Anführungszeichen um (`„…“` oder `“…”`), erkennt das Plugin sie trotzdem.

### Ja/Nein-Werte

Optionen wie `bild`, `email` oder `telefon` akzeptieren `ja` / `nein`. Alternativ funktionieren auch `yes`, `1`, `true`, `on` bzw. `no`, `0`, `false`, `off`.

## IDs herausfinden

Die einfachste Möglichkeit: Die Detailseite der Gruppe bzw. Sportstätte im Admin öffnen – dort steht der fertige Shortcode mit der richtigen ID zum Kopieren. Ein Klick ins Feld markiert den Text.

Alternativ steht die ID in der Adresszeile des Browsers, z. B. `…page=turnverein-gruppen&id=3`.

> ⚠️ Bei Gruppen kann statt der ID auch der Name verwendet werden (`gruppe="Kinderturnen"`). Wird die Gruppe umbenannt, funktioniert der Shortcode dann aber nicht mehr – die ID ist robuster.

## Optionen kombinieren

Alle Optionen eines Shortcodes lassen sich beliebig kombinieren:

```
[tv_belegungsplan gruppe_id="3" tag="Montag" telefon="nein" titel="Montagstraining" ebene="h2"]
```

## Darstellung

Die Shortcodes bringen ein schlichtes, neutrales Design mit, das sich an das Theme anpasst. Eigene Anpassungen: siehe [[Design anpassen]].
