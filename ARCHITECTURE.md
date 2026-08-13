# StockPulse ID — Architecture

## 1. System Architecture

```text
                 Yahoo Finance (external, ~15min delayed)
                       │
                       ▼
              Laravel Data Service (backend/)
                       │
             ┌─────────┴─────────┐
             ▼                   ▼
          Cache              PostgreSQL
             │                   │
             │             ┌─────┴──────┐
             │             │            │
             │          Historical   Persistent
             │             Data         Data
             │
             ▼
         REST API  (/api/*)
             │
             ▼
           Vue 3 SPA (frontend/)
```

**Rules:**
- Frontend never calls Yahoo Finance directly.
- Scheduler → Fetch → Cache/Database → API → Client.
- No external fetch per user request.

## 2. Backend Architecture (Laravel)

```text
routes/api.php                → REST endpoints (thin controllers)
app/Http/Controllers          → request handling
app/Http/Requests             → Form Request validation
app/Http/Resources            → API Resources (standardized response)
app/Services/StockService     → orchestration
app/Services/YahooFinanceService → external data source (single point of change)
app/Jobs / app/Console/Commands → scheduled sync work
app/Events + Broadcasting      → realtime updates (Phase 2)
app/Models                     → Eloquent models
app/Exceptions                 → centralized exception handling
app/Http/Middleware            → rate limiting, cors, security
```

**Layers & flow:**
- Controller → FormRequest (validate) → Service (business logic) → Repository/Model → Response (Resource).
- All Yahoo Finance access goes through `YahooFinanceService` so that API drift is contained in one place.

### Yahoo Finance endpoints used (unofficial)
- Chart/quote: `https://query1.finance.yahoo.com/v8/finance/chart/{symbol}?range=1d&interval=1m`
- Historical: same chart endpoint with `range`/`interval` params (1d, 5d, 1mo, 3mo, 6mo, 1y).
- Symbols carry `.JK` suffix for IDX (e.g., `BBRI.JK`).
- Requires a browser-like `User-Agent` header; responses are JSON.

## 3. Frontend Architecture (Vue 3)

```text
src/
├── main.js
├── App.vue
├── router/           # Vue Router
├── stores/           # Pinia: marketStore, stockStore, watchlistStore
├── services/         # Axios API client (single baseURL, interceptors)
├── echo/             # Laravel Echo instance (Phase 2)
├── composables/      # usePolling, useWebSocket, useChart, usePriceFormat
├── components/       # StockTable, GainerLoserCard, SearchBar, AlertToast, …
├── views/            # Dashboard, StockDetail, Watchlist, Settings
└── assets/           # Tailwind entry, global styles
```

**Data access:** all requests go through `services/api.js` (Axios) → Laravel API.
No direct external calls from the browser.

## 4. Database Architecture (PostgreSQL)

### `stocks`
| Column      | Type        | Notes                     |
|-------------|-------------|---------------------------|
| id          | bigint PK   |                           |
| symbol      | varchar(20) | unique, e.g. `BBRI.JK`    |
| name        | varchar     |                           |
| sector      | varchar     | nullable                  |
| exchange    | varchar(20) | default `IDX`             |
| is_active   | boolean     | default true              |
| created_at / updated_at | timestamp | |

Indexes: `uq_stocks_symbol`.

### `stock_quotes`
| Column         | Type      | Notes                     |
|----------------|-----------|---------------------------|
| id             | bigint PK |                           |
| stock_id       | FK stocks |                           |
| price, open, high, low, previous_close | numeric | |
| change, change_percent | numeric | |
| volume         | bigint    |                           |
| captured_at    | timestamp |                           |
| timestamps     |           |                           |

Indexes: `stock_id`, `captured_at`, composite `(stock_id, captured_at)`.

### `stock_candles`
| Column      | Type      | Notes                     |
|-------------|-----------|---------------------------|
| id          | bigint PK |                           |
| stock_id    | FK stocks |                           |
| interval    | varchar   | `1d`/`1mo`/…              |
| open/high/low/close | numeric |                  |
| volume      | bigint    |                           |
| timestamp   | timestamp | candle open time          |
| timestamps  |           |                           |

Constraint: `UNIQUE(stock_id, interval, timestamp)` prevents duplicate candles.

### `watchlists`
| Column  | Type      | Notes                |
|---------|-----------|----------------------|
| id      | bigint PK |                      |
| user_id | FK users  | nullable in MVP      |
| stock_id| FK stocks |                      |
| timestamps |        |                      |

Constraint: `UNIQUE(user_id, stock_id)`.

> MVP decision: without authentication yet, watchlist is persisted in
> **localStorage** on the frontend. The table is created now for future auth.

### `price_alerts`
| Column         | Type      | Notes                |
|----------------|-----------|----------------------|
| id             | bigint PK |                      |
| user_id        | FK users  |                      |
| stock_id       | FK stocks |                      |
| condition      | varchar   | `above`/`below`      |
| target_price   | numeric   |                      |
| is_triggered   | boolean   | default false        |
| triggered_at   | timestamp | nullable            |
| timestamps     |           |                      |

## 5. Caching Strategy

```text
Cache keys:
  stock:{symbol}:quote             → current quote (TTL ~30–60s)
  stock:{symbol}:history:{interval}→ historical series (TTL ~5–15min)
  market:gainers                    → top gainers (TTL ~60s)
  market:losers                     → top losers (TTL ~60s)
```

- Scheduler refreshes cache; API serves cache first.
- External failure → serve stale cache if available.
- Adapter: `file` locally, `redis` optional in production.

## 6. Realtime Strategy

### Phase 1 — MVP: Polling
```text
Vue (usePolling, recursive setTimeout, e.g. 15–30s)
        │
        ▼
GET /api/stocks/{symbol}/quote
        │
        ▼
Cache / DB
```
- Lifecycle-aware (start/stop/cleanup), error handling with retry/backoff,
  visible connection status.

### Phase 2 — Upgrade: Laravel Reverb WebSocket
```text
Scheduler
   │  data updated
   ▼
QuoteUpdated event
   │  broadcast
   ▼
Laravel Reverb (WebSocket)
   │
   ▼
Laravel Echo (frontend)
   ▼
Vue stores (Pinia)
```
- Private/public channel per stock + a `market` channel.
- Reconnection with exponential backoff.
- **Fallback:** if socket disconnects, resume polling automatically.
  Polling is never removed.

## 7. API Design

Standardized response envelope:

```json
{
  "data": {},
  "meta": { "pagination": {} }
}
```

| Endpoint                       | Purpose                    |
|--------------------------------|----------------------------|
| `GET /api/stocks`              | List stocks (page/filter/sort/search) |
| `GET /api/stocks/{symbol}`     | Stock detail               |
| `GET /api/stocks/{symbol}/quote` | Latest quote            |
| `GET /api/stocks/{symbol}/history` | Historical close series |
| `GET /api/stocks/{symbol}/candles` | OHLCV candles (interval) |
| `GET /api/stocks/gainers`      | Top gainers                |
| `GET /api/stocks/losers`       | Top losers                 |

All endpoints rate-limited; inputs validated via Form Requests.

## 8. Deployment Architecture

```text
Docker Compose:
  postgres   → PostgreSQL 17 (host port 5433 in dev to avoid local clash)
  backend    → Laravel (PHP-FPM + nginx) / or `php artisan serve` for dev
  reverb     → Laravel Reverb (Phase 2)
  frontend   → Vue built → nginx static / CDN
```

Free-tier targets: Render/Railway (backend + PG + Reverb), Vercel/Netlify
(frontend). Keep-alive ping + polling fallback to survive server sleep.
