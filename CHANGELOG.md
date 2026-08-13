# StockPulse ID — Changelog

All notable changes per sprint are documented here following
[Keep a Changelog](https://keepachangelog.com/) conventions.

## [Sprint 0] — 2026-08-13 — Project Discovery & Foundation

### Added
- `PROJECT_PLAN.md` — vision, objectives, stack, architecture, roadmap, risk summary.
- `SPRINT_STATUS.md` — sprint roadmap with status tracking (Sprint 0–13).
- `ARCHITECTURE.md` — system, backend, frontend, database, cache, realtime, API, deployment design.
- `CHANGELOG.md` — this file.

### Environment verified
- PHP 8.3.26, Composer 2.4.1, Node 22.19.0, npm 10.9.3.
- PostgreSQL 17.6 running locally on port 5432.
- Docker 29.6.2 + Compose v5.3.1.
- Git 2.46.0, user `Ilham Dharma Atmaja <meniandol@gmail.com>`.

### Decisions
- Primary database: **PostgreSQL** (no SQLite/MySQL).
- Repository: dedicated `stockpulse-id/` directory (home directory is an unrelated git repo).
- Data source: Yahoo Finance via Laravel backend only (proxied, no browser direct access).
- Realtime: Phase 1 polling → Phase 2 Laravel Reverb with polling fallback.
- Watchlist MVP: localStorage; DB table created now for future auth.

### Notes
- No Laravel global installer present → Sprint 1 will use `composer create-project`.
- Windows: no native cron → Laravel Scheduler via `php artisan schedule:work` or Docker cron.

### Git
- Initialized dedicated repository on branch `main`.
- Remote: `origin` → `https://github.com/ianmen10/-StockPulse-ID.git`.
- No commit/push performed (awaiting user instruction).
