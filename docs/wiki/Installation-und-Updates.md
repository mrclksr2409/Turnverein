# Installation und Updates

## Voraussetzungen

| | Mindestversion |
|---|---|
| WordPress | 6.0 |
| PHP | 7.4 |

Für die Verwaltung wird ein Benutzer mit der Rolle **Administrator** benötigt (Berechtigung `manage_options`).

## Installation

1. Auf GitHub unter [mrclksr2409/Turnverein](https://github.com/mrclksr2409/Turnverein) über **Code → Download ZIP** den aktuellen Stand von `main` herunterladen.
2. Die ZIP-Datei entpacken und den Ordner in **`turnverein`** umbenennen (GitHub nennt ihn sonst `Turnverein-main`).
3. Den Ordner wieder als ZIP packen.
4. In WordPress unter **Plugins → Installieren → Plugin hochladen** die ZIP-Datei hochladen.
5. **Turnverein Manager** aktivieren.

Nach der Aktivierung erscheint im Admin-Menü der Punkt **Turnverein** mit den Unterseiten *Sportstätten*, *Trainer*, *Gruppen* und *Belegungsplan*. Die benötigten Datenbanktabellen werden automatisch angelegt.

## Automatische Updates

Das Plugin bringt die Bibliothek [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) mit und holt sich Updates direkt von GitHub:

- Maßgeblich ist der **Branch `main`** und die dort im Plugin-Header eingetragene Versionsnummer.
- GitHub-Releases und Tags werden **ignoriert**.
- WordPress prüft standardmäßig **alle 12 Stunden** auf Updates.
- Sofort prüfen: **Plugins → Nach Updates suchen** (Link unter dem Plugin-Eintrag).

Ein Update wird nur angeboten, wenn die Versionsnummer auf `main` **höher** ist als die installierte.

### Datenbank-Aktualisierung

Bei einem Update mit Datenbankänderungen (z. B. neue Spalten) passt das Plugin die Tabellen beim **nächsten Aufruf des Admin-Bereichs** automatisch an. Es ist nichts weiter zu tun. Bestehende Daten bleiben erhalten.

## Entwicklungs-Workflow

| Branch | Zweck |
|---|---|
| `beta` | Entwicklung und Test neuer Funktionen |
| `main` | Stabiler Stand – nur dieser wird an die Websites ausgeliefert |

Neue Funktionen landen zuerst auf `beta`. Erst wenn sie mit erhöhter Versionsnummer nach `main` übernommen werden, erhalten die Websites das Update – und dieses Wiki wird aktualisiert.

## Deaktivieren und Deinstallieren

- **Deaktivieren:** Alle Daten bleiben erhalten.
- **Löschen:** Die Datenbanktabellen (`wp_tv_*`) werden derzeit **nicht** automatisch entfernt. Wer alle Daten restlos löschen möchte, muss die Tabellen manuell entfernen (siehe [[Für Entwickler]]).
