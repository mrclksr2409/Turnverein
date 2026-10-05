# Gruppen

Menü: **Turnverein → Gruppen**

Gruppen sind die Trainingsgruppen des Vereins, z. B. „Kinderturnen 4–6 Jahre“ oder „Rückenfit“.

## Übersicht

Die Liste zeigt alle Gruppen alphabetisch mit Trainern, Altersbereich und maximaler Mitgliederzahl. Darunter stehen die passenden Shortcodes.

## Felder

| Feld | Pflicht | Hinweis |
|---|---|---|
| Gruppenname | ✔ | Wird auf der Website als Überschrift verwendet |
| Trainer | | Beliebig viele per Häkchen |
| Altersbereich | | „von“ und/oder „bis“ in Jahren; Anzeige z. B. „4–6 J.“, „ab 18 J.“ |
| Max. Mitglieder | | `0` = keine Angabe |
| Beschreibung | | Freitext, nur intern |

## Detailansicht

1. **Shortcodes** zum Kopieren:
   - `[tv_gruppe_trainer id="…"]` – Trainer dieser Gruppe
   - `[tv_belegungsplan gruppe_id="…"]` – Trainingszeiten dieser Gruppe
2. **Stammdaten bearbeiten** (aufklappbar) – inklusive Button zum Löschen.
3. **Trainingszeiten** – alle Slots dieser Gruppe über alle Sportstätten hinweg. Gepflegt werden sie bei den [[Sportstätten]].

## Gruppe löschen

Beim Löschen werden die Trainer-Zuordnungen entfernt. Die Trainingszeiten der Gruppe bleiben in den Sportstätten erhalten und erscheinen dort als **freie Slots** – sie können anschließend einer anderen Gruppe zugeordnet oder gelöscht werden.

## Siehe auch

- [[Trainer]]
- [[Sportstätten]]
- [[Shortcode tv_gruppe_trainer]]
- [[Shortcode tv_belegungsplan]]
