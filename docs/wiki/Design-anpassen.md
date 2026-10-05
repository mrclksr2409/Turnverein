# Design anpassen

Die Shortcodes bringen ein schlichtes Grund-Design mit (graue Linien, hellgraue Tageszeilen). Schriftart, Schriftgröße und Linkfarben werden vom Theme übernommen.

Eigene Anpassungen am besten unter **Design → Customizer → Zusätzliches CSS** (klassische Themes) bzw. **Design → Editor → Stile → Zusätzliches CSS** (Block-Themes) eintragen. So bleiben sie bei Plugin-Updates erhalten.

> ℹ️ Das CSS des Plugins wird nur auf Seiten geladen, die einen der Shortcodes enthalten.

## CSS-Klassen

### Allgemein

| Klasse | Element |
|---|---|
| `.tv-sc-titel` | Eigene Überschrift (`titel="…"`) – über der gesamten Ausgabe oder anstelle des Namens |
| `.tv-no-data` | Hinweistexte wie „Keine Trainer gefunden.“ |

### `[tv_belegungsplan]`

| Klasse | Element |
|---|---|
| `.tv-belegungsplan-sc` | Äußerer Container |
| `.tv-bp-mode-gruppe` | Zusätzlich am Container in der Gruppen-Variante |
| `.tv-bp-section` | Abschnitt einer Sportstätte |
| `.tv-bp-title` | Überschrift (Sportstätte bzw. Gruppe) |
| `.tv-bp-adresse` | Adresszeile unter der Überschrift |
| `.tv-bp-table` | Tabelle |
| `.tv-bp-tag-header` | Zeile mit dem Wochentag |
| `.tv-bp-slot-row` | Zeile eines Slots |
| `.tv-bp-zeit` | Zelle Uhrzeit |
| `.tv-bp-gruppe` | Zelle Gruppe |
| `.tv-bp-sportstaette` | Zelle Sportstätte (Gruppen-Variante) |
| `.tv-bp-trainer` | Zelle Trainer |
| `.tv-bp-telefon` | Zelle Telefon |

### `[tv_gruppe_trainer]`

| Klasse | Element |
|---|---|
| `.tv-gruppe-trainer` | Äußerer Container |
| `.tv-gt-gruppe` | Block einer Gruppe |
| `.tv-gt-gruppe-name` | Gruppenüberschrift |
| `.tv-gt-table` | Tabelle |
| `.tv-gt-bild` / `.tv-gt-img` | Bildzelle / Bild |
| `.tv-gt-name` | Name |
| `.tv-gt-telefon` | Telefon |
| `.tv-gt-email` | E-Mail |

### `[tv_sportstaetten]`

| Klasse | Element |
|---|---|
| `.tv-sportstaetten-sc` | Äußerer Container |
| `.tv-ss-table` | Tabelle |
| `.tv-ss-name`, `.tv-ss-adresse`, `.tv-ss-kapazitaet`, `.tv-ss-beschreibung` | Spalten (Kopf- und Datenzellen) |

## Beispiele

**Vereinsfarbe für die Wochentag-Zeilen:**

```css
.tv-bp-tag-header td {
    background: #2571b8;
    color: #fff;
    border-bottom-color: #2571b8;
}
```

**Eckige statt runde Trainerbilder, etwas größer:**

```css
.tv-gt-img {
    width: 80px;
    height: 80px;
    border-radius: 6px;
}
.tv-gt-bild { width: 90px; }
```

**Trainertabelle über die volle Breite:**

```css
.tv-gt-table { max-width: none; }
```

**Zebra-Streifen im Belegungsplan:**

```css
.tv-bp-slot-row:nth-of-type(even) td { background: #fafafa; }
```

**Spalte ausblenden, z. B. die Kapazität nur auf dem Handy:**

```css
@media (max-width: 600px) {
    .tv-ss-kapazitaet { display: none; }
}
```
