# Shortcode `[tv_sportstaetten]`

Gibt eine Tabelle der Sportstätten aus – in der Reihenfolge, die im Admin per Drag & Drop festgelegt wurde.

## Varianten

```
[tv_sportstaetten]         → alle Sportstätten
[tv_sportstaetten id="1"]  → eine Sportstätte
```

## Optionen

| Option | Werte | Standard | Beschreibung |
|---|---|---|---|
| `id` | Zahl | – | Nur diese Sportstätte |
| `spalten` | Liste, kommagetrennt | `name,adresse,kapazitaet` | Welche Spalten in welcher Reihenfolge |
| `titel` | Text | – | Überschrift über der Tabelle |
| `ebene` | `h2` … `h6` | `h3` | Ebene der Überschrift |

### Verfügbare Spalten

| Schlüssel | Spaltenkopf | Inhalt |
|---|---|---|
| `name` | Sportstätte | Name (fett) |
| `adresse` | Adresse | Straße, darunter PLZ und Ort |
| `kapazitaet` | Kapazität | z. B. „120 Pers.“ (leer bei 0) |
| `beschreibung` | Beschreibung | Freitext mit Zeilenumbrüchen |

Unbekannte Schlüssel werden ignoriert. Bleibt kein gültiger Schlüssel übrig, gilt der Standard.

## Mobile Darstellung

Auf schmalen Bildschirmen (bis 600 px) wird die Tabelle untereinander dargestellt: Jede Sportstätte ist ein Block, vor jedem Wert steht die Spaltenbezeichnung.

## Beispiele

| Shortcode | Ergebnis |
|---|---|
| `[tv_sportstaetten titel="Unsere Sportstätten" ebene="h2"]` | Alle Sportstätten mit Überschrift |
| `[tv_sportstaetten spalten="name,adresse"]` | Nur Name und Adresse |
| `[tv_sportstaetten id="1" spalten="name,beschreibung"]` | Eine Sportstätte mit Beschreibung |
| `[tv_sportstaetten spalten="adresse,name"]` | Adresse zuerst |

## Meldungen

| Meldung | Ursache |
|---|---|
| Keine Sportstätten gefunden. | Die `id` existiert nicht oder es gibt noch keine Sportstätten |
