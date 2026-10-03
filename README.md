# Turnverein Manager

WordPress plugin for managing sports facilities, trainers, groups and training times of a gymnastics club.

## Requirements

- WordPress 6.0+
- PHP 7.4+

## Installation

1. Download the latest release ZIP from the [Releases page](https://github.com/mrclksr2409/Turnverein/releases).
2. In WordPress go to **Plugins → Add New → Upload Plugin** and upload the ZIP.
3. Activate **Turnverein Manager**.

## Updates

This plugin uses the [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) library (bundled in `lib/plugin-update-checker/`) to deliver updates directly from GitHub.

- Updates are taken from the **`main`** branch: PUC uses the latest stable GitHub Release first, then the latest tag, and falls back to the current state of `main` (version from the plugin header).
- Development happens on the **`beta`** branch. Changes reach installed sites only after they are merged into `main` with a higher version number.

WordPress checks for updates automatically (every 12 hours by default). You can trigger a manual check under **Plugins → Check for updates**.

## Changelog

### 1.3.0 — 2026-10-03
- Added: automatic updates via Plugin Update Checker v5.7 (branch `main`).
- Fixed: plugin header version now matches `TURNVEREIN_VERSION`.
