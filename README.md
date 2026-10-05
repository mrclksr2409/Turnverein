# Turnverein Manager

WordPress plugin for managing sports facilities, trainers, groups and training times of a gymnastics club.

## Features

- Manage sports facilities (Sportstätten) with drag & drop ordering.
- Manage trainers including a profile image from the media library.
- Manage groups with assigned trainers.
- Weekly training schedule (Belegungsplan) per facility.
- Shortcodes for the frontend (see below).

## Shortcodes

| Shortcode | Description | Attributes |
|---|---|---|
| `[tv_sportstaetten]` | Table of all facilities in the admin order | `id="1"` – single facility; `spalten="name,adresse,kapazitaet,beschreibung"` – columns and their order (default: `name,adresse,kapazitaet`) |
| `[tv_gruppe_trainer]` | Trainers per group with image and phone number | `id="3"` or `gruppe="Name"` – single group; `bild="nein"` – hide images; `email="ja"` – show e-mail; `titel="nein"` – hide group heading |
| `[tv_belegungsplan]` | Training schedule grouped by weekday | `sportstaette_id="1"`, `tag="Montag"` |

The matching shortcode for a single facility or group is shown on its detail page in the admin.

## Requirements

- WordPress 6.0+
- PHP 7.4+

## Installation

1. Download the latest release ZIP from the [Releases page](https://github.com/mrclksr2409/Turnverein/releases).
2. In WordPress go to **Plugins → Add New → Upload Plugin** and upload the ZIP.
3. Activate **Turnverein Manager**.

## Updates

This plugin uses the [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) library (bundled in `lib/plugin-update-checker/`) to deliver updates directly from GitHub.

- Updates are taken straight from the **`main`** branch (version from the plugin header). GitHub releases and tags are ignored.
- Development happens on the **`beta`** branch. Changes reach installed sites only after they are merged into `main` with a higher version number.

WordPress checks for updates automatically (every 12 hours by default). You can trigger a manual check under **Plugins → Check for updates**.

## Changelog

### 1.5.0 — 2026-10-05
- Added: trainer image (media library), shown in the admin and in `[tv_gruppe_trainer]`.
- Added: drag & drop ordering of Sportstätten (new column `sortierung`).
- Added: shortcode `[tv_sportstaetten]` for a facility table.
- Added: `[tv_gruppe_trainer]` options `bild`, `email`, `titel`; shortcode hints in the admin.
- Fixed: frontend shortcode styles were never printed (enqueued after `wp_head`).

### 1.4.0 — 2026-10-05
- Added: when a group has several trainers, a specific trainer can be selected per training slot. The Belegungsplan (admin and `[tv_belegungsplan]` shortcode) then shows only that trainer.

### 1.3.0 — 2026-10-03
- Added: automatic updates via Plugin Update Checker v5.7 (branch `main`).
- Fixed: plugin header version now matches `TURNVEREIN_VERSION`.
