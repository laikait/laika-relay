# Laika Relay

The service container and static-proxy layer of the [Laika PHP MVC Framework](https://github.com/laikait/laika-framework).

| Class | Role |
|---|---|
| `Laika\Relay\RelayRegistry` | The container: holds bindings, auto-wires constructors, caches instances |
| `Laika\Relay\Relay` | Base class for **relays**: static classes that forward every call to a bound service |
| `Laika\Relay\RelayProvider` | Base class for **providers**, which bind services into the registry |
| `Laika\Relay\ProviderRegistry` | Runs providers in two phases: every `register()`, then every `boot()` |
| `Laika\Relay\CoreProviders` | The provider that binds laika-core's services (requires laika-core) |

The package also ships the framework's own relays in `services/`, namespace `Laika\Service`: `Config`, `Request`, `Response`, `Url`, `Vault`, `Visitor` and the rest.

Requires PHP 8.1+.

---

## Table of Contents

- [How It Works](#how-it-works)
- [Inside a Laika App](#inside-a-laika-app)
- [RelayRegistry](#relayregistry)
- [Auto-Wiring](#auto-wiring)
- [RelayProvider](#relayprovider)
- [ProviderRegistry](#providerregistry)
- [Standalone Bootstrap](#standalone-bootstrap)
- [Shipping Services From a Package](#shipping-services-from-a-package)
- [Relays](#relays)
- [Testing](#testing)
- [Exceptions](#exceptions)

---

## How It Works

```
Request::input('email')                   ← relay (static call)
    │  __callStatic
    ▼
RelayRegistry::make('request')            ← container (builds once, caches)
    │
    ▼
Laika\Core\Http\Request->input('email')   ← the real instance
```

A relay keeps no state of its own. Every call asks the registry, so `swap()`, `forgetInstance()` and re-binding take effect immediately.

---

## Inside a Laika App

laika-core builds the container in its `helpers/loader.php`. You don't call any of this yourself:

1. `CoreProviders` registers the core services. `php laika relay:list` prints every key and its class.
2. Providers declared by packages register next, then the app's own providers in `lf-app/Relay/`. Binding is last-write-wins, so an app provider can override a core or package service by binding the same key.
3. `Relay::setRegistry()` connects the relays.
4. Every provider's `boot()` runs.
5. The router is given `RelayRegistry::make()` as its resolver, so controllers, pipelines and filters get constructor injection.

To add a service to an app:

```bash
php laika service:make --name=Billing --class=App\\Support\\Billing
```

This writes a provider to `lf-app/Relay/` and a relay to `lf-app/Service/`. Both are discovered automatically. See [Services & Relays](https://github.com/laikait/laika-framework/blob/main/docs/07_services-and-relay/01_basic.md) in the framework docs.

---

## RelayRegistry

```php
use Laika\Relay\RelayRegistry;

$registry = new RelayRegistry();
```

### Registering

| Method | Instances | Built |
|---|---|---|
| `singleton(string $key, Closure\|string $concrete, array $args = []): static` | One, shared | On the first `make()`, then cached |
| `bind(string $key, Closure\|string $concrete, array $args = []): static` | A new one per `make()` | Every time |
| `instance(string $key, object $instance): static` | The object you pass | Already built |

`$concrete` is either a class name, which is [auto-wired](#auto-wiring), or a closure called as `$concrete($registry, ...$args)`:

```php
// Class name, auto-wired
$registry->singleton('billing', \App\Support\Billing::class);

// Class name with values the container can't resolve (by name or position)
$registry->singleton('mailer', \App\Support\Mailer::class, ['driver' => 'smtp']);

// Closure factory
$registry->singleton('mailer', fn (RelayRegistry $r) => new \Laika\Mailman\Mailer(config('mail')));

// Interface → implementation
$registry->singleton(\App\Contracts\PaymentGateway::class, \App\Support\StripeGateway::class);

// A fresh object on every make()
$registry->bind('upload', \Laika\Core\Helper\Upload::class);

// An object you already have
$registry->instance('clock', new \App\Support\FrozenClock('2025-01-01'));
```

Prefer `singleton()` for most services. It's lazy: nothing is built until something asks for it.

> **A class without a public constructor can't be auto-wired.** Binding such a class by name fails on `make()` with "Failed to build". Bind a closure that returns the instance instead, for example `fn () => ShieldConfig::instance()`.

### Resolving

```php
$billing = $registry->make('billing');
```

`make(string $key): object` checks, in order:

1. an instance (from `instance()`, or a singleton already built);
2. a singleton binding: build it, cache it, return it;
3. a `bind()` binding: build and return a new one;
4. the key itself, if it's an existing class name: auto-wire it (not cached);
5. otherwise, throw `RelayException`.

### Other Methods

| Method | |
|---|---|
| `has(string $key): bool` | Whether the key has any binding |
| `forgetInstance(string $key): static` | Drop a cached instance; the next `make()` builds a new one |
| `bindings(): array` | Every bound key |
| `classes(): array` | Key → concrete class (`'Closure'` for factories) |

---

## Auto-Wiring

When a class name is built, each constructor parameter is filled from the first of these that applies:

1. a binding registered under the parameter's type;
2. the type itself, if it's an existing class: built recursively;
3. `$args`, by parameter name;
4. `$args`, by position;
5. the parameter's default value;
6. `null`, if the parameter is nullable;
7. otherwise `RelayException`, naming the parameter.

```php
class InvoiceService
{
    public function __construct(
        private \App\Contracts\PaymentGateway $gateway, // bound above → make()
        private \App\Support\TaxTable $taxes,           // concrete class → built
        private string $currency = 'BDT',               // default
    ) {}
}

$registry->singleton('invoices', InvoiceService::class, ['currency' => 'USD']);
```

An **interface** has to be bound. It can't be built, so an unbound interface falls through to `$args`, the default, `null` or an exception.

> **In a Laika app, core services are bound by key, not by class.** They live under keys such as `'request'` and `'response'`. Type-hinting `Laika\Core\Http\Response` therefore builds a new `Response`, not the shared one. Call the relay statically instead (`Response::setStatus(201)`), or alias the class in a provider: `$this->registry->singleton(Response::class, fn ($r) => $r->make('response'));`

---

## RelayProvider

```php
namespace App\Relay;

use Laika\Relay\RelayProvider;

class Billing extends RelayProvider
{
    public function register(): void
    {
        // Bind only.
        $this->registry->singleton('billing', \App\Support\Billing::class);
    }

    public function boot(): void
    {
        // Every provider has registered; relays work; make() is safe.
        $this->registry->make('billing')->setCurrency(config('app', 'currency'));
    }
}
```

| | `register()` | `boot()` |
|---|---|---|
| Purpose | Bind services | Use services |
| Runs | Once per provider, in registration order | After **every** provider has registered |
| `$this->registry->make()` | Risky: later providers haven't registered | Safe |
| Relay static calls (`Config::get()`) | **Throw** inside a Laika app: relays aren't connected until every `register()` has run | Work |

`$this->registry` is the `RelayRegistry`.

---

## ProviderRegistry

```php
use Laika\Relay\ProviderRegistry;

$providers = new ProviderRegistry($registry);
$providers->register(Billing::class);      // class name or instance; register() runs now
$providers->boot();                        // every boot(), in registration order
```

| Method | |
|---|---|
| `register(string\|RelayProvider $provider): static` | Instantiate (with the registry) and call `register()`. Registering the same class again is ignored. |
| `boot(): void` | Call `boot()` on every registered provider |
| `has(string $class): bool` | Whether that provider class is registered |
| `providers(): array` | The provider instances, in order |

---

## Standalone Bootstrap

Outside Laika, wire it up the same way laika-core does:

```php
use Laika\Relay\Relay;
use Laika\Relay\RelayRegistry;
use Laika\Relay\ProviderRegistry;

$registry  = new RelayRegistry();
$providers = new ProviderRegistry($registry);

$providers->register(\App\Relay\Billing::class);
$providers->register(\App\Relay\Payments::class);

Relay::setRegistry($registry);   // once; relays work from here on
$providers->boot();
```

`CoreProviders` binds laika-core classes, so only register it where laika-core is installed.

---

## Shipping Services From a Package

A package declares a directory of providers as a `relays` resource in its `composer.json`. Laika discovers it and registers every `RelayProvider` in it, after the core providers and before the app's:

```json
"extra": {
    "laika": {
        "resources": {
            "relays": {
                "path": "src/Relay",
                "namespace": "Acme\\Payment\\Relay",
                "contract": "Laika\\Relay\\RelayProvider"
            }
        }
    }
}
```

Ship a relay class next to it so users get a static API:

```php
namespace Acme\Payment\Service;

use Laika\Relay\Relay;

/**
 * @method static string charge(int $amount, string $currency)
 * @method static bool   verify(string $token)
 */
class Payment extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'acme.payment';
    }
}
```

Prefix your keys (`acme.payment`) so they can't collide with core keys or other packages.

---

## Relays

### Creating a Relay

Extend `Relay`, return the registry key from `getRelayAccessor()`, and document the forwarded methods with `@method static` tags for IDE autocomplete:

```php
namespace App\Service;

use Laika\Relay\Relay;

/**
 * @method static float total(array $items)
 * @method static \App\Support\Billing setCurrency(string $code)
 */
class Billing extends Relay
{
    protected static function getRelayAccessor(): string
    {
        return 'billing';
    }
}
```

### Using a Relay

Import the relay, not the class behind it:

```php
use App\Service\Billing;
use Laika\Service\Config;

$total = Billing::total($cart);
$name  = Config::get('app', 'name');
```

Calling a method the instance doesn't have throws `RelayException`.

**Chaining** works whenever the target returns an object: the first call goes through the relay, the rest run on the returned object.

```php
use Laika\Service\Date;

Date::now()->setTimezone('Asia/Dhaka')->modify('+7 days')->format('d M Y');
```

### Static Helpers on Every Relay

| Method | |
|---|---|
| `X::relayRoot(): object` | The real underlying instance |
| `X::swap(object $instance): void` | Bind a different instance under this relay's key |
| `X::clearResolvedInstance(): void` | Forget the cached instance; the next call rebuilds it (and undoes a `swap()`) |
| `Relay::setRegistry(RelayRegistry $registry): void` | Connect the registry. Once only. |
| `Relay::getRegistry(): RelayRegistry` | The connected registry |
| `Relay::swapRegistry(RelayRegistry $registry): void` | Replace the registry. Tests only. |
| `Relay::bindings()` / `Relay::classes()` | The registry's keys / key → class map |

---

## Testing

Swap in a fake for one test:

```php
use App\Service\Billing;

protected function setUp(): void
{
    Billing::swap(new FakeBilling());
}

protected function tearDown(): void
{
    Billing::clearResolvedInstance();
}
```

Or isolate a test completely with its own registry:

```php
use Laika\Relay\Relay;
use Laika\Relay\RelayRegistry;

protected function setUp(): void
{
    $registry = new RelayRegistry();
    $registry->instance('billing', new FakeBilling());

    Relay::swapRegistry($registry);
}
```

`swapRegistry()` is separate from `setRegistry()` on purpose: a second `setRegistry()` in application code throws, while tests can swap freely.

Singletons live for the whole process. In a long-running worker, reset request-bound services between jobs with `X::clearResolvedInstance()`.

---

## Exceptions

Everything throws `Laika\Relay\Exceptions\RelayException`:

| Situation | Message starts with |
|---|---|
| `setRegistry()` called twice | `RelayRegistry has already been set.` |
| A relay used before `setRegistry()` | `RelayRegistry has not been set.` |
| Nothing bound under the key | `No binding registered for [key].` |
| A bound class doesn't exist | `Class [ClassName] not found.` |
| A constructor can't be invoked (e.g. it isn't public) | `Failed to build [ClassName]: …` |
| A constructor parameter can't be resolved | `Cannot resolve parameter [$name] for [ClassName].` |
| The relay's instance lacks the method | `Method [method] does not exist on [ClassName].` |
| A provider class doesn't exist | `RelayProvider [ClassName] class not found.` |
| A provider doesn't extend `RelayProvider` | `[ClassName] must extend Laika\Relay\RelayProvider.` |

---

## License

MIT. See [LICENSE](LICENSE).

**Author:** Showket Ahmed · riyadhtayf@gmail.com · [laikait/laika-relay](https://github.com/laikait/laika-relay)
