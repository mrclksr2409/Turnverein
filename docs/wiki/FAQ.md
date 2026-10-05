# FAQ & Fehlerbehebung

### Der Shortcode erscheint als Text auf der Seite

- Ist das Plugin **aktiviert**?
- Ist der Shortcode korrekt geschrieben – mit eckigen Klammern und **geraden** Anführungszeichen (`"`)? Beim Kopieren aus Word oder E-Mails entstehen oft typografische Anführungszeichen („ “), die nicht funktionieren.
- Im Block-Editor möglichst den **Shortcode-Block** verwenden.

### „Gruppe nicht gefunden.“ / „Keine Sportstätte gefunden.“

Die ID oder der Name stimmt nicht. Den Shortcode am besten direkt von der Detailseite im Admin kopieren. Bei `gruppe="…"` muss der Name exakt übereinstimmen – nach einer Umbenennung lieber `gruppe_id` verwenden.

### Eine Sportstätte fehlt im vollständigen Belegungsplan

Im vollständigen Plan werden Sportstätten **ohne Trainingszeiten** ausgelassen. Mit `sportstaette_id="…"` wird sie trotzdem angezeigt (mit dem Hinweis „Keine Trainingszeiten eingetragen.“).

### Eine Gruppe fehlt bei `[tv_gruppe_trainer]`

Gruppen ohne zugeordnete Trainer werden nicht ausgegeben. Trainer bei der Gruppe per Häkchen zuordnen.

### Im Belegungsplan steht nur ein Trainer, obwohl die Gruppe mehrere hat

Am Slot ist ein bestimmter Trainer ausgewählt. In der Sportstätte den Slot bearbeiten und bei **Trainer** „— alle Trainer der Gruppe —“ wählen.

### Das Trainer-Feld fehlt im Slot-Formular

Es erscheint nur, wenn die gewählte Gruppe **mindestens zwei** Trainer hat. Bei nur einem Trainer gibt es nichts auszuwählen.

### Die Reihenfolge der Sportstätten wird nicht gespeichert

- Nach dem Verschieben sollte „Reihenfolge gespeichert.“ erscheinen.
- Erscheint eine Fehlermeldung, die Seite neu laden (die Sitzung kann abgelaufen sein) und erneut versuchen.
- Browser-Erweiterungen, die JavaScript blockieren, deaktivieren.

### Die Auswahl „Bild auswählen“ öffnet sich nicht

Die Mediathek benötigt JavaScript. Seite neu laden; ggf. andere Plugins testweise deaktivieren, die den Admin-Bereich verändern.

### Das Update wird nicht angezeigt

- Unter **Plugins** auf **Nach Updates suchen** klicken.
- Das Update erscheint nur, wenn auf `main` eine **höhere Versionsnummer** steht.
- Caching-Plugins oder Server-Caches können die Prüfung verzögern.

### Nach einem Update fehlen neue Funktionen oder es gibt Datenbankfehler

Einmal eine beliebige Seite im Admin-Bereich aufrufen – dabei werden neue Tabellenspalten automatisch angelegt. Hilft das nicht, das Plugin kurz deaktivieren und wieder aktivieren (Daten bleiben erhalten).

### Das Design passt nicht zu meinem Theme

Siehe [[Design anpassen]].

### Wer darf das Plugin bedienen?

Nur Benutzer mit der Berechtigung `manage_options` – standardmäßig **Administratoren**.
