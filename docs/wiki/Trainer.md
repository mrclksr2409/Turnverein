# Trainer

Menü: **Turnverein → Trainer**

## Übersicht

Die Liste zeigt alle Trainer alphabetisch nach Nachname mit Bild, E-Mail, Telefon, Sportart und Lizenz.

| Aktion | So geht's |
|---|---|
| Neuer Trainer | Button **Neuer Trainer** oben |
| Öffnen / Bearbeiten | Auf den Namen oder **Öffnen** klicken |
| Löschen | **Löschen** – der Trainer wird aus allen Gruppen und Slots entfernt |

## Felder

| Feld | Pflicht | Hinweis |
|---|---|---|
| Vorname | ✔ | |
| Nachname | ✔ | |
| E-Mail | | Wird auf der Website nur mit `email="ja"` angezeigt |
| Telefon | | Wird auf der Website als anklickbarer Link (`tel:`) ausgegeben |
| Sportart / Fachbereich | | Nur intern |
| Lizenz / Qualifikation | | Nur intern, z. B. „Übungsleiter C“ |
| Bild | | Aus der WordPress-Mediathek |

## Trainerbild

1. Im Feld **Bild** auf **Bild auswählen** klicken.
2. Ein vorhandenes Bild aus der Mediathek wählen oder ein neues hochladen.
3. **Bild verwenden** klicken – die Vorschau erscheint.
4. **Speichern** bzw. **Anlegen** klicken.

Mit **Bild entfernen** wird die Zuordnung gelöst (das Bild bleibt in der Mediathek).

**Empfehlungen für Bilder:**

- Quadratisches Format (z. B. 400 × 400 px), Gesicht mittig – auf der Website wird das Bild **rund** zugeschnitten.
- Für den Alternativtext wird automatisch der Name des Trainers verwendet, sofern in der Mediathek keiner hinterlegt ist.
- Datenschutz: Vor der Veröffentlichung eines Fotos die Einwilligung der Person einholen.

Das Bild erscheint:

- in der Trainerliste und in der Detailansicht im Admin,
- im Shortcode [[Shortcode tv_gruppe_trainer]] (abschaltbar mit `bild="nein"`).

## Detailansicht

Zeigt die Stammdaten (aufklappbar) und die Tabelle **Zugeordnete Gruppen** mit Altersbereich und maximaler Mitgliederzahl. Die Zuordnung selbst erfolgt bei den [[Gruppen]].

## Siehe auch

- [[Gruppen]]
- [[Shortcode tv_gruppe_trainer]]
