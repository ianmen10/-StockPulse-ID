# StockPulse ID — Sprint Status

Status values: `NOT STARTED` · `IN PROGRESS` · `REVIEW` · `COMPLETE` · `BLOCKED`

## Sprint Overview

| Sprint | Name                                   | Status      |
|--------|----------------------------------------|-------------|
| 0      | Project Discovery & Foundation         | COMPLETE    |
| 1      | PostgreSQL & Database Foundation       | NOT STARTED |
| 2      | Yahoo Finance Data Engine              | NOT STARTED |
| 3      | Data Synchronization                   | NOT STARTED |
| 4      | REST API                               | NOT STARTED |
| 5      | Vue Dashboard MVP                      | NOT STARTED |
| 6      | Polling Monitoring                     | NOT STARTED |
| 7      | Stock Detail & Candlestick             | NOT STARTED |
| 8      | Watchlist & Search                     | NOT STARTED |
| 9      | Laravel Reverb & WebSocket             | NOT STARTED |
| 10     | Price Alert & Advanced Features        | NOT STARTED |
| 11     | Testing & Security                     | NOT STARTED |
| 12     | Performance Optimization               | NOT STARTED |
| 13     | Docker, Deployment & Portfolio         | NOT STARTED |

## Sprint 0 — Project Discovery & Foundation

**Status:** COMPLETE

**Objective:** Understand the environment and lay the foundation.

**Tasks:**
- [x] Inspect repository (home directory, unrelated git repo detected)
- [x] Verify PHP / Composer / Node / npm / Docker / PostgreSQL / Git
- [x] Validate PostgreSQL 17 as primary database (running on :5432)
- [x] Validate Laravel + Vue architecture feasibility
- [x] Define repository structure (`stockpulse-id/`)
- [x] Define database strategy
- [x] Define API strategy
- [x] Create `PROJECT_PLAN.md`
- [x] Create `SPRINT_STATUS.md`
- [x] Create `ARCHITECTURE.md`
- [x] Create `CHANGELOG.md`
- [x] Present risk analysis
- [x] Present development order recommendation
- [x] Initialize dedicated Git repo (branch `main`, origin `https://github.com/ianmen10/-StockPulse-ID.git`)

**Acceptance Criteria:**
- [x] Environment validated
- [x] Stack constraints verified (no SQLite/MySQL primary)
- [x] Planning docs created
- [x] Roadmap Sprint 0–13 defined
- [x] Risk register presented
- [x] No code written

## Sprint 1 — PostgreSQL & Database Foundation

**Status:** NOT STARTED

**Tasks:**
- [ ] PostgreSQL configuration (`DB_CONNECTION=pgsql`)
- [ ] Migrations: `stocks`, `stock_quotes`, `stock_candles`, `watchlists`, `price_alerts`
- [ ] Relationships, indexes, constraints, timestamps
- [ ] Seed initial IDX stocks (BBCA.JK, BBRI.JK, BMRI.JK, TLKM.JK, ASII.JK, ANTM.JK, GOTO.JK, …)
- [ ] Migration / relationship / integrity tests

## Sprint 2 — Yahoo Finance Data Engine

**Tasks:**
- [ ] HTTP client setup (Guzzle, headers, timeout)
- [ ] Quote service + history service
- [ ] Response normalization, retry, error handling, logging, caching
- [ ] Unit tests

## Sprint 3 — Data Synchronization

**Tasks:**
- [ ] Laravel Scheduler jobs
- [ ] Quote + historical sync
- [ ] Cache invalidation, retry, logging

## Sprint 4 — REST API

**Tasks:**
- [ ] `GET /api/stocks`
- [ ] `GET /api/stocks/{symbol}`
- [ ] `GET /api/stocks/{symbol}/quote`
- [ ] `GET /api/stocks/{symbol}/history`
- [ ] `GET /api/stocks/{symbol}/candles`
- [ ] `GET /api/stocks/gainers`
- [ ] `GET /api/stocks/losers`
- [ ] Pagination, filtering, sorting, validation, API Resources, standardized response

## Sprint 5 — Vue Dashboard MVP

**Tasks:**
- [ ] Market overview, stock table, gainers/losers, search
- [ ] Loading / error / empty states
- [ ] Vue 3 + Pinia + Axios + responsive design

## Sprint 6 — Polling Monitoring

**Tasks:**
- [ ] Recursive polling with lifecycle + cleanup
- [ ] Error handling, retry, connection status

## Sprint 7 — Stock Detail & Candlestick

**Tasks:**
- [ ] Detail page (price, OHLC, change, volume)
- [ ] Candlestick chart, timeframes 1D/5D/1M/3M/6M/1Y

## Sprint 8 — Watchlist & Search

**Tasks:**
- [ ] Search, add/remove watchlist, sort/filter, persistence (localStorage MVP)
- [ ] Polling updates

## Sprint 9 — Laravel Reverb & WebSocket

**Tasks:**
- [ ] Reverb, broadcasting, events, channels, Echo
- [ ] Reconnect handling + polling fallback

## Sprint 10 — Price Alert & Advanced Features

**Tasks:**
- [ ] Price alerts, notifications, thresholds
- [ ] CSV export, recently viewed, market summary

## Sprint 11 — Testing & Security

**Tasks:**
- [ ] Unit/feature/API/database/scheduler/cache/external-failure tests
- [ ] Validation, rate limiting, authz, secure env, logging

## Sprint 12 — Performance Optimization

**Tasks:**
- [ ] Indexes, N+1, cache efficiency, API payload size
- [ ] WebSocket & frontend rendering & chart performance

## Sprint 13 — Docker, Deployment & Portfolio

**Tasks:**
- [ ] Dockerfile, docker-compose.yml, PG container, health checks
- [ ] Deployment + README + screenshots + architecture diagram + API docs
- [ ] Known limitations + future roadmap
