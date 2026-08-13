# StockPulse ID — Project Plan

> Near-real-time monitoring of Indonesian stock market (IDX) data.

## 1. Project Vision

**StockPulse ID** is a web application that monitors Indonesian stocks (IDX) with a
**near-real-time** approach. It lets users search stocks, view current prices,
changes, volume, top gainers/losers, candlestick charts, build watchlists,
create price alerts, and export data — powered by a clean, scalable,
maintainable architecture suitable for a professional GitHub portfolio.

## 2. Objectives

- Search and browse Indonesian stocks (symbol, name, sector).
- View current price, change, change percent, volume, OHLC.
- View top gainers and top losers.
- View candlestick charts across multiple timeframes (1D/5D/1M/3M/6M/1Y).
- Receive near-real-time price updates (polling baseline, WebSocket upgrade).
- Create and persist a personal watchlist.
- Create price alerts (threshold based).
- Export historical data to CSV.
- Provide automated tests, clear architecture, and deployment readiness.

## 3. Data Source & Limitation

- **Source:** Yahoo Finance (unofficial, free endpoints) via the Laravel backend.
- The frontend **never** talks to Yahoo Finance directly; all data flows through
  the Laravel REST API.
- Yahoo Finance can have delay (~15 minutes) and availability limits.
  StockPulse ID therefore advertises **near-real-time / realtime-style
  monitoring**, not exchange-grade realtime data. This is stated transparently
  in the README.

## 4. Technology Stack

### Backend
| Component       | Choice                     |
|-----------------|----------------------------|
| Framework       | Laravel 11+ (latest stable)|
| Language        | PHP 8.2+ (env: 8.3.26)     |
| Database        | PostgreSQL 17 (env: 17.6)  |
| HTTP client     | Guzzle (via Laravel HTTP)  |
| Scheduler       | Laravel Scheduler          |
| Jobs/Queue      | Laravel Queue (sync/redis per env) |
| Cache           | Laravel Cache (file/redis) |
| Realtime        | Laravel Reverb (WebSocket) |
| API             | REST, API Resources        |

### Frontend
| Component       | Choice                     |
|-----------------|----------------------------|
| Framework       | Vue 3 (Composition API)    |
| State           | Pinia                      |
| Routing         | Vue Router                 |
| HTTP            | Axios                      |
| Realtime        | Laravel Echo               |
| Charts          | Lightweight Charts or ApexCharts |
| Styling         | Tailwind CSS               |
| Build           | Vite                       |

### Infrastructure
| Component       | Choice                     |
|-----------------|----------------------------|
| Containerization| Docker + Docker Compose    |
| VCS             | Git + GitHub               |
| Deployment      | Free tier (Render/Railway) + optional Vercel/Netlify |

## 5. Environment (Verified)

| Tool           | Version  | Status      |
|----------------|----------|-------------|
| PHP            | 8.3.26   | OK (>=8.2)  |
| Composer       | 2.4.1    | OK          |
| Node.js        | 22.19.0  | OK          |
| npm            | 10.9.3   | OK          |
| PostgreSQL     | 17.6     | Running on :5432 |
| Docker         | 29.6.2   | OK          |
| Docker Compose | v5.3.1   | OK          |
| Git            | 2.46.0   | OK          |

> Laravel global installer is NOT present — use `composer create-project`.

## 6. System Architecture (High Level)

```text
Vue 3 SPA (frontend/)
        │  HTTP (REST)
        ▼
Laravel API (backend/)
        │  HTTP (Guzzle)
        ▼
Yahoo Finance (external, delayed ~15min)
```

Data flow (server side):

```text
Yahoo Finance
      │
      ▼
Laravel Data Service
      │
   ┌──┴───┐
   ▼      ▼
Cache  PostgreSQL
   │      │
   └──┬───┘
      ▼
   REST API
      │
      ▼
    Vue 3
```

## 7. Database Strategy

- **PostgreSQL is mandatory** since development through deployment
  (`DB_CONNECTION=pgsql`). No SQLite/MySQL as the primary database.
- Schema (detailed in `ARCHITECTURE.md`):
  - `stocks` — master data (symbol, name, sector, exchange, is_active)
  - `stock_quotes` — latest price snapshots per stock
  - `stock_candles` — historical OHLCV per interval
  - `watchlists` — user-to-stock (deferred; localStorage in MVP)
  - `price_alerts` — alert conditions per user (deferred; table created early)
- Foreign keys, indexes, unique constraints, and timestamps are mandatory.
- Optimized composite indexes:
  - `stock_quotes(stock_id, captured_at)`
  - `stock_candles(stock_id, interval, timestamp)`

## 8. Caching Strategy

Use cache keys for hot data, refresh via scheduler (never per user request):

```text
stock:{symbol}:quote
stock:{symbol}:history:{interval}
market:gainers
market:losers
```

Benefits: fewer external requests, faster API responses, lower rate-limit risk.

## 9. Realtime Strategy (Staged)

- **Phase 1 (MVP):** polling — Vue fetches REST API every N seconds
  (recursive `setTimeout`, lifecycle-managed).
- **Phase 2:** Laravel Reverb WebSocket broadcast via events; Laravel Echo on
  the client; reconnection handling; **polling kept as fallback** when the
  socket disconnects.

## 10. Sprint Roadmap

| Sprint | Name                                        | Goal |
|--------|---------------------------------------------|------|
| 0      | Project Discovery & Foundation              | Inspect env, docs, planning |
| 1      | PostgreSQL & Database Foundation            | Migrations, schema, seeds, tests |
| 2      | Yahoo Finance Data Engine                   | HTTP client, quote/history services |
| 3      | Data Synchronization                        | Scheduler, sync jobs, cache |
| 4      | REST API                                    | Endpoints, resources, validation |
| 5      | Vue Dashboard MVP                           | Market overview, table, gainers/losers |
| 6      | Polling Monitoring                          | Near-real-time via polling |
| 7      | Stock Detail & Candlestick                  | Detail page + charts |
| 8      | Watchlist & Search                          | Watchlist, search, sort/filter |
| 9      | Laravel Reverb & WebSocket                  | Realtime upgrade + fallback |
| 10     | Price Alert & Advanced Features             | Alerts, CSV export, recently viewed |
| 11     | Testing & Security                          | Reliability, validation, rate limiting |
| 12     | Performance Optimization                    | Indexes, N+1, cache, bundle, charts |
| 13     | Docker, Deployment & Portfolio              | Containerize, deploy, README, docs |

## 11. Repository Structure (Target)

```text
stockpulse-id/
├── PROJECT_PLAN.md
├── SPRINT_STATUS.md
├── ARCHITECTURE.md
├── CHANGELOG.md
├── backend/          # Laravel 11+ (from Sprint 1)
├── frontend/         # Vue 3 + Vite (from Sprint 5)
├── docker/           # Dockerfile + docker-compose.yml
└── README.md
```

## 12. Development Principles

SOLID, DRY, Separation of Concerns, Clean Code, Service Layer,
Form Requests, API Resources, centralized exception handling, logging,
caching, validation, rate limiting — without over-engineering.

## 13. Risk Analysis Summary

See `SPRINT_STATUS.md` / final Sprint 0 report for the detailed risk register.

| Risk | Impact | Mitigation |
|------|--------|------------|
| Yahoo Finance endpoint changes/blocks | High | Service layer abstraction, retry, caching, documented fallback |
| Home directory is an unrelated git repo | High | Dedicated `stockpulse-id/` repo (never commit into home repo) |
| No Laravel global installer | Low | `composer create-project laravel/laravel` |
| Windows: no native cron | Medium | Laravel scheduler + `schedule:work` / Docker cron |
| Local PG 17 + Docker PG port clash | Medium | Dev uses local PG :5432; Docker maps host port 5433 |
| Free-tier server sleep | Medium | Keep-alive ping + polling fallback |
| Reverb long-running process | Medium | Phase 2 only; polling remains fallback |

## 14. Definition of Done

A sprint is **COMPLETE** only when: all tasks done, acceptance criteria met,
tests pass, no critical errors, docs updated, architecture consistent,
code reviewed, and a Git commit recommendation is provided.

## 15. Git & Branching Policy

- Conventional Commits (`feat(scope): …`).
- No automatic push/commit unless requested.
- Commits are recommended per sprint; user controls Git manually.
