# app/Models

Firebase-backed Models (query-only classes — no HTML, no HTTP-request
handling). Each extends `FirebaseModel` for its shared `FirebaseClient`
dependency.

| Model | Firebase Node | Status |
|---|---|---|
| `SensorModel` | `monitoring` | Reads the same node the frontend already reads live |
| `RelayModel` | `control/relay` | **New** — no relay control existed before Step 7 |
| `TelegramModel` | `telegram/*` | **New** — no Telegram integration existed before |
| `LogModel` | `logs` | **New** — not yet wired to the dashboard's (still static) Recent Activity card |

Build a Model like this:

```php
use App\Config\FirebaseClientFactory;
use App\Models\SensorModel;

$sensor = new SensorModel(FirebaseClientFactory::make());
$latest = $sensor->latest();
```
