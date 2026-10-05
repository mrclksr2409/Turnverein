# Shortcode `[tv_gruppe_trainer]`

Gibt die Trainer der Gruppen aus – mit **Bild**, **Name** und **Telefonnummer** (als anklickbarer Link), optional mit E-Mail-Adresse.

## Varianten

```
[tv_gruppe_trainer]                     → alle Gruppen
[tv_gruppe_trainer id="3"]              → eine Gruppe (über die ID)
[tv_gruppe_trainer gruppe="Rückenfit"]  → eine Gruppe (über den Namen)
```

Gruppen werden alphabetisch ausgegeben, Trainer nach Nachname. Gruppen **ohne** Trainer erscheinen nicht.

## Optionen

| Option | Werte | Standard | Beschreibung |
|---|---|---|---|
| `id` | Zahl | – | Nur diese Gruppe |
| `gruppe` | Text | – | Gruppe über den Namen |
| `bild` | `ja` / `nein` | `ja` | Trainerbild anzeigen |
| `email` | `ja` / `nein` | `nein` | E-Mail-Adresse anzeigen |
| `titel` | `ja` / `nein` / Text | `ja` | Überschriften, siehe [[Shortcodes]] |
| `ebene` | `h2` … `h6` | `h3` | Ebene der eigenen Überschrift |

## Hinweise

- **Bilder** werden rund (56 × 56 px) dargestellt. Trainer ohne Bild erhalten eine leere Bildspalte, damit die Namen bündig bleiben. Bilder werden „lazy“ geladen.
- **E-Mail-Adressen** werden im HTML verschleiert (WordPress-Funktion `antispambot`), um das Auslesen durch Spam-Bots zu erschweren.
- **Telefonnummern** werden als `tel:`-Link ausgegeben – auf dem Smartphone startet ein Tippen den Anruf.

## Beispiele

| Shortcode | Ergebnis |
|---|---|
| `[tv_gruppe_trainer id="3" titel="Ansprechpartner"]` | Trainer einer Gruppe unter der Überschrift „Ansprechpartner“ |
| `[tv_gruppe_trainer titel="Unser Trainerteam" ebene="h2"]` | Hauptüberschrift, darunter alle Gruppen mit ihren Trainern |
| `[tv_gruppe_trainer id="3" titel="nein" bild="nein"]` | Kompakte Liste nur mit Namen und Telefon |
| `[tv_gruppe_trainer email="ja"]` | Alle Gruppen inkl. E-Mail-Adressen |

## Meldungen

| Meldung | Ursache |
|---|---|
| Keine Trainer gefunden. | Die Gruppe existiert nicht oder hat keine Trainer |

> 🔒 **Datenschutz:** Telefonnummern, E-Mail-Adressen und Fotos sind personenbezogene Daten. Vor der Veröffentlichung sollte die Einwilligung der Trainer vorliegen.
