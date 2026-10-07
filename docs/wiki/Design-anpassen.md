# Design anpassen

Das Plugin bringt **kein eigenes CSS** mit. Die Shortcodes geben schlichtes HTML mit festen CSS-Klassen aus; das Aussehen kommt komplett vom Theme bzw. von eigenem CSS.

Eigenes CSS am besten im Theme (z. B. `style.css` eines Child-Themes) oder unter **Design → Customizer → Zusätzliches CSS** (klassische Themes) bzw. **Design → Editor → Stile → Zusätzliches CSS** (Block-Themes) eintragen. So bleibt es bei Plugin-Updates erhalten.

> ℹ️ Trainerbilder werden in der WordPress-Größe „Vorschaubild“ ausgegeben (meist 150 × 150 px). Wer sie kleiner oder rund möchte, regelt das über `.tv-gt-img`.

## CSS-Klassen

### Allgemein

| Klasse | Element |
|---|---|
| `.tv-sc-titel` | Eigene Überschrift (`titel="…"`) – über der gesamten Ausgabe oder anstelle des Namens (dann zusätzlich zu `.tv-gt-gruppe-name` bzw. `.tv-bp-title`) |
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

Die Datenzellen tragen zusätzlich `data-label` mit dem Spaltennamen – praktisch für eine mobile Darstellung (siehe Vorlage unten).

## Vorlage: früheres Grund-Design

Bis Version 1.5.4 brachte das Plugin dieses CSS selbst mit. Wer das alte Aussehen behalten möchte, kopiert es als Ausgangspunkt ins Theme:

```css
/* --- tv_gruppe_trainer --- */
.tv-gruppe-trainer { margin: 1.5em 0; }
.tv-gt-gruppe { margin-bottom: 1.5em; }
.tv-gt-gruppe-name { margin: 0 0 .5em; }
.tv-gt-gruppe-name:not(.tv-sc-titel) { font-size: 1.1em; }
.tv-gt-table { border-collapse: collapse; width: 100%; max-width: 480px; }
.tv-gt-table td { padding: 5px 10px 5px 0; border-bottom: 1px solid #e5e7eb; }
.tv-gt-table tr:last-child td { border-bottom: none; }
.tv-gt-name { font-weight: 600; }
.tv-gt-telefon { color: #555; }
.tv-gt-telefon a { color: inherit; text-decoration: none; }
.tv-gt-telefon a:hover { text-decoration: underline; }
.tv-gt-table td { vertical-align: middle; }
.tv-gt-bild { width: 64px; }
.tv-gt-img { display: block; width: 56px; height: 56px; object-fit: cover; border-radius: 50%; }
.tv-gt-email a { color: inherit; }

/* --- tv_sportstaetten --- */
.tv-sportstaetten-sc { margin: 1.5em 0; overflow-x: auto; }
.tv-ss-table { border-collapse: collapse; width: 100%; }
.tv-ss-table th,
.tv-ss-table td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
.tv-ss-table thead th { background: #f3f4f6; border-bottom: 2px solid #d1d5db; }
.tv-ss-table tbody tr:last-child td { border-bottom: none; }
.tv-ss-kapazitaet { white-space: nowrap; }
@media (max-width: 600px) {
    .tv-ss-table thead { display: none; }
    .tv-ss-table tr { display: block; border-bottom: 1px solid #e5e7eb; padding: 6px 0; }
    .tv-ss-table td { display: block; border: none; padding: 4px 0; }
    .tv-ss-table td:not(.tv-ss-name)::before { content: attr(data-label) ": "; font-weight: 600; }
}

/* --- tv_belegungsplan --- */
.tv-belegungsplan-sc { margin: 1.5em 0; overflow-x: auto; }
.tv-bp-section { margin-bottom: 2em; }
.tv-bp-title { margin: 0 0 .25em; }
.tv-bp-title:not(.tv-sc-titel) { font-size: 1.1em; }
.tv-bp-adresse { margin: 0 0 .5em; color: #666; font-size: .9em; }
.tv-bp-sportstaette { min-width: 180px; }
.tv-bp-table { border-collapse: collapse; width: 100%; }
.tv-bp-table td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
.tv-bp-tag-header td {
    background: #f3f4f6;
    border-bottom: 2px solid #d1d5db;
    padding: 10px 12px 8px;
    font-size: .95em;
    letter-spacing: .02em;
}
.tv-bp-slot-row:last-of-type td { border-bottom: none; }
.tv-bp-zeit    { white-space: nowrap; color: #555; width: 160px; }
.tv-bp-gruppe  { min-width: 180px; }
.tv-bp-trainer { width: 180px; }
.tv-bp-telefon { width: 140px; }
.tv-sc-titel:not(.tv-gt-gruppe-name):not(.tv-bp-title) { margin: 0 0 .75em; }
.tv-no-data { color: #888; }
```

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
