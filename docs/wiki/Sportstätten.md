# Sportstätten

Menü: **Turnverein → Sportstätten**

Sportstätten sind die Orte, an denen trainiert wird (Hallen, Plätze, Räume). Zu jeder Sportstätte werden die **Trainingszeiten (Slots)** gepflegt, aus denen der [[Belegungsplan]] entsteht.

## Übersicht

Die Liste zeigt alle Sportstätten mit Name, Adresse und Kapazität.

| Aktion | So geht's |
|---|---|
| Neue Sportstätte | Button **Neue Sportstätte** oben |
| Öffnen / Bearbeiten | Auf den Namen oder **Öffnen** klicken |
| Löschen | **Löschen** – entfernt die Sportstätte **und alle ihre Trainingszeiten** |
| Reihenfolge ändern | Zeile am Griff ☰ (links) anfassen und verschieben |

### Reihenfolge per Drag & Drop

Die Reihenfolge wird beim Loslassen **sofort gespeichert** (Meldung „Reihenfolge gespeichert.“). Sie gilt überall:

- in der Liste,
- im [[Belegungsplan]] im Admin,
- in den Shortcodes [[Shortcode tv_sportstaetten]] und [[Shortcode tv_belegungsplan]].

Neu angelegte Sportstätten werden ans Ende gehängt.

## Felder

| Feld | Pflicht | Hinweis |
|---|---|---|
| Name | ✔ | z. B. „Große Turnhalle“ |
| Straße | | |
| PLZ / Ort | | |
| Kapazität | | Anzahl Personen; `0` = keine Angabe |
| Beschreibung | | Freitext, Zeilenumbrüche bleiben in der Ausgabe erhalten |

## Detailansicht

Nach dem Anlegen bzw. beim Öffnen einer Sportstätte siehst du:

1. **Shortcodes** zum Kopieren – für die Tabelle dieser Sportstätte und ihren Belegungsplan. Ein Klick ins Feld markiert den Text.
2. **Stammdaten bearbeiten** (aufklappbar) – inklusive Button zum Löschen.
3. **Neuen Slot hinzufügen** – Formular für Trainingszeiten.
4. **Belegung** – Liste aller Slots dieser Sportstätte mit *Bearbeiten* und *Löschen*.

## Trainingszeiten (Slots)

| Feld | Pflicht | Hinweis |
|---|---|---|
| Wochentag | ✔ | Montag bis Sonntag |
| Von / Bis | ✔ | Uhrzeit, z. B. 17:00 – 18:30 |
| Gruppe | | Leer lassen für einen **freien Slot** |
| Trainer | | Erscheint nur, wenn die gewählte Gruppe **mehr als einen** Trainer hat |
| Notiz | | z. B. „Halle 2“ oder „nur in den Ferien“ |

### Trainer je Slot

Hat eine Gruppe mehrere Trainer, kann pro Slot ein bestimmter Trainer gewählt werden. Im Belegungsplan erscheint dann **nur dieser Trainer**. Bei „— alle Trainer der Gruppe —“ werden alle Trainer der Gruppe angezeigt.

Wird der gewählte Trainer später aus der Gruppe entfernt, zeigt der Plan automatisch wieder alle Trainer der Gruppe. Wird der Trainer gelöscht, wird die Zuordnung am Slot entfernt.

### Freie Slots

Slots ohne Gruppe erscheinen im Belegungsplan als **„Freier Slot“** – oder mit der Notiz, falls eine eingetragen ist. So lassen sich z. B. Hallenzeiten anderer Vereine oder Reservierungen sichtbar machen.

## Siehe auch

- [[Belegungsplan]]
- [[Shortcode tv_sportstaetten]]
- [[Shortcode tv_belegungsplan]]
