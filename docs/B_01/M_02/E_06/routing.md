# Routing frontendu i panelu administracyjnego

Laravel umożliwia rozdzielenie tras aplikacji na osobne pliki. W projekcie CMS wykorzystujemy ten mechanizm do oddzielenia:

- części publicznej aplikacji — `routes/web.php`,
- panelu administracyjnego — `routes/admin.php`.

## 1. Trasy panelu administracyjnego

W katalogu `routes` utwórz plik:

`routes/admin.php`

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');
```

Trasa `/` zdefiniowana w tym pliku będzie później dostępna pod adresem:

`/admin`

Jej pełna nazwa to:

`admin.dashboard`

## 2. Rejestracja pliku `admin.php`

Samo utworzenie `routes/admin.php` nie powoduje jeszcze jego automatycznego załadowania przez Laravel.

Plik należy zarejestrować w:

`bootstrap/app.php`

Na początku pliku dodaj:

```php
use Illuminate\Support\Facades\Route;
```

Następnie w konfiguracji `withRouting()` wykorzystaj parametr `then`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware('web')
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/admin.php'));
    },
)
```

## 3. Działanie konfiguracji

Konfiguracja:

```php
Route::middleware('web')
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
```

powoduje, że wszystkie trasy z `routes/admin.php`:

- korzystają z middleware `web`,
- otrzymują prefiks adresu `/admin`,
- otrzymują prefiks nazwy `admin.`.

Dlatego trasa:

```php
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');
```

jest dostępna jako:

```text
/admin
```

oraz posiada nazwę:

```text
admin.dashboard
```

Można się więc do niej odwołać w aplikacji przez:

```php
route('admin.dashboard')
```

## Powiązane

- [[project/context|Kontekst projektu]]
- [[backend/authentication|Logowanie i autoryzacja]]
- [[frontend/routing|Routing frontendu]]