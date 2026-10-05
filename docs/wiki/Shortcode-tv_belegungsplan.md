# Shortcode `[tv_belegungsplan]`

Gibt den Belegungsplan auf der Website aus – wahlweise **vollständig**, für **eine Sportstätte** oder für **eine Gruppe**.

## Varianten

### Vollständiger Belegungsplan

```
[tv_belegungsplan]
```

- Ein Abschnitt je Sportstätte mit **Name** und **Adresse**.
- Reihenfolge wie in der Sportstätten-Liste im Admin (Drag & Drop).
- Sportstätten **ohne** Trainingszeiten werden ausgelassen.
- Innerhalb jeder Sportstätte: Wochentage Montag → Sonntag, Slots nach Uhrzeit.

### Je Sportstätte

```
[tv_belegungsplan sportstaette_id="1"]
```

Wie oben, aber nur für eine Sportstätte. Hat sie keine Trainingszeiten, erscheint „Keine Trainingszeiten eingetragen.“

### Je Gruppe

```
[tv_belegungsplan gruppe_id="3"]
[tv_belegungsplan gruppe="Kinderturnen"]
```

Zeigt alle Trainingszeiten einer Gruppe – über alle Sportstätten hinweg. Statt der Gruppen-Spalte gibt es hier eine Spalte **Sportstätte**. Überschrift ist der Gruppenname.

## Spalten

| Spalte | Vollständig / je Sportstätte | Je Gruppe |
|---|---|---|
| Uhrzeit | ✔ | ✔ |
| Gruppe (+ Notiz) | ✔ | – |
| Sportstätte (+ Notiz) | – | ✔ |
| Trainer | ✔ | ✔ |
| Telefon | ✔ (abschaltbar) | ✔ (abschaltbar) |

Ist am Slot ein bestimmter Trainer gewählt, erscheint nur dieser – sonst alle Trainer der Gruppe. Freie Slots erscheinen als „Freier Slot“ bzw. mit ihrer Notiz.

## Optionen

| Option | Werte | Standard | Beschreibung |
|---|---|---|---|
| `sportstaette_id` | Zahl | – | Nur diese Sportstätte |
| `gruppe_id` | Zahl | – | Nur diese Gruppe |
| `gruppe` | Text | – | Gruppe über den Namen (Alternative zu `gruppe_id`) |
| `tag` | `Montag` … `Sonntag` | – | Nur dieser Wochentag (Groß-/Kleinschreibung beachten; ein unbekannter Wert wird ignoriert) |
| `titel` | `ja` / `nein` / Text | `ja` | Überschriften, siehe [[Shortcodes]] |
| `ebene` | `h2` … `h6` | `h3` | Ebene der eigenen Überschrift |
| `telefon` | `ja` / `nein` | `ja` | Telefonspalte anzeigen |

Werden `gruppe_id`/`gruppe` **und** `sportstaette_id` angegeben, hat die Gruppe Vorrang.

## Beispiele

| Shortcode | Ergebnis |
|---|---|
| `[tv_belegungsplan titel="Unsere Trainingszeiten" ebene="h2"]` | Vollständiger Plan mit Hauptüberschrift, darunter die Sportstätten |
| `[tv_belegungsplan sportstaette_id="1" titel="Große Turnhalle"]` | Eine Sportstätte, Name durch eigenen Titel ersetzt |
| `[tv_belegungsplan gruppe_id="3" telefon="nein"]` | Trainingszeiten einer Gruppe ohne Telefonnummern |
| `[tv_belegungsplan tag="Samstag" titel="nein"]` | Nur Samstag, ohne Überschriften |

## Meldungen

| Meldung | Ursache |
|---|---|
| Keine Sportstätte gefunden. | `sportstaette_id` existiert nicht, oder es gibt noch keine Sportstätten |
| Gruppe nicht gefunden. | `gruppe_id` bzw. `gruppe` existiert nicht (Name falsch geschrieben?) |
| Keine Trainingszeiten eingetragen. | Für die Auswahl gibt es (an diesem Tag) keine Slots |
